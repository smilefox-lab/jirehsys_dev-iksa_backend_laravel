<?php

namespace App\Imports;

use Botble\ACL\Models\Company;
use Botble\Location\Models\Commune;
use Botble\RealEstate\Models\Contract;
use Botble\RealEstate\Models\Lessee;
use Botble\RealEstate\Models\Payment;
use Botble\RealEstate\Models\Property;
use Botble\RealEstate\Models\Type;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Validators\Failure;

class BulkImport implements
    ToCollection,
    WithHeadingRow,
    SkipsOnError,
    SkipsOnFailure,
    WithChunkReading,
    WithBatchInserts,
    ShouldQueue,
    WithEvents,
    WithCalculatedFormulas
{
    use Importable, SkipsErrors, SkipsFailures, RegistersEventListeners;

    /**
     * @return array
     */
    public function sheets(): array
    {
        return [
            //0 => new FirstSheetImport(),
            0 => $this,
        ];
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function onFailure(Failure ...$failure)
    {
    }

    public function dateGetFormat($date, $format = 'Y-m-d')
    {
        $strDate = '';

        if (!is_numeric($date)) {
            $strDate = str_replace('\'', '', str_replace('/', '-', $date));
        } else {
            $UNIX_DATE = ($date - 25569) * 86400;
            $strDate = gmdate("d-m-Y H:i:s", $UNIX_DATE);
        }

        return date($format, strtotime($strDate));
    }

    public function dateAddOneYear($date)
    {
        $strDate = str_replace('\'', '', str_replace('/', '-', $date));
        return date('Y-m-d', strtotime(date('Y-m-d', strtotime($strDate)) . " + 365 day"));
    }

    public function createFormat($y, $m, $d) {
        $day = $d ?? "01"; 
        $strDate = "$y-$m-$day";
        return $this->dateGetFormat($strDate);
    }

    /**
    * @param Collection $rows
    */
    public function collection(Collection $rows)
    {

        $ids = $rows->pluck('CC');
        $properties = Property::find($ids);

        foreach ($rows as $row) {

            $ingreso_real = (!is_numeric($row["INGRESO REAL"])) ? 0 : floatval($row["INGRESO REAL"]);
            $renta_costo = ((!is_numeric($row["% (RENTA/COSTO)"])) ? 0 : floatval($row["% (RENTA/COSTO)"])) * 100;
            $quota = (!is_numeric($row["ARRIENDO"])) ? 0 : floatval($row["ARRIENDO"]);
            $ingreso_real = floatval(number_format($ingreso_real, 2, '.', ''));
            $renta_costo = number_format($renta_costo, 2, '.', '');
            $contribucion_cuota = number_format($row["CONTRIBUCIÓN CUOTA 3"], 2, '.', '');
            $contribucion = number_format($row["CONTRIBUCIÓN"], 2, '.', '');
            $pesos = (!is_numeric($row["PESOS"])) ? 0 : floatval($row["PESOS"]);

            $companyRut = str_replace(',', '.', $row["EMPRESA RUT"]);

            if (empty($companyRut)) continue;

            $company = Company::where('rut', $companyRut)->first();

            if (is_null($company)) {
                $company = Company::updateOrCreate([
                    'rut' => $row["EMPRESA RUT"],
                ],
                [
                    'name'    => ucwords(strtolower($row["EMPRESA NOMBRE"] ?? $row["EMPRESA RUT"])),
                    'status'  => 'activated',
                    'rut'     => $row["EMPRESA RUT"],
                    'address' => $row["EMPRESA DIRECCIÓN"],
                    'phone'   => $row["EMPRESA TELÉFONO"],
                    'files'   => '[]'
                ]);
            }

            if (!is_numeric($row["CC"])) continue;

            $commune = Commune::query()->where('name', 'like', "{$row['COMUNA']}")->first();

            if (!empty($row['DESTINO'])) {

                $type = Type::query()->where('name', 'like', "{$row['DESTINO']}")->first();

                if (is_null($type)) {
                    $type = Type::updateOrCreate([
                        'name' => ucwords(strtolower($row['DESTINO']))
                    ]);
                }
            }

            $oldProperty = $properties->find($row["CC"]);

            $property = Property::updateOrCreate([
                'id' => $row["CC"],
            ],
            [
                'id'              => $row["CC"],
                'name'            => trim("{$row["NOMBRE"]} {$row["CC"]}"),
                'description'     => '',
                'location'        => empty($row["DIRECCION"]) && isset($oldProperty)? $oldProperty->location : $row["DIRECCION"],
                'coordinates'     => isset($oldProperty)? $oldProperty->coordinates : '{}',
                'images'          => isset($oldProperty) ? $oldProperty->images : '[]',
                'number_bedroom'  => isset($oldProperty) ? $oldProperty->number_bedroom : '0',
                'number_bathroom' => isset($oldProperty) ? $oldProperty->number_bathroom : '0',
                'number_floor'    => isset($oldProperty) ? $oldProperty->number_floor : '0',
                'square'          => !is_numeric($row["M2 TERRENO"]) && isset($oldProperty) ? $oldProperty->square : $row["M2 TERRENO"],
                'square_build'    => !is_numeric($row["M2 CONSTRUIDOS"]) && isset($oldProperty) ? $oldProperty->square_build : $row["M2 CONSTRUIDOS"],
                'price'           => isset($oldProperty) ? $oldProperty->price : 0,
                'is_featured'     => isset($oldProperty) ? $oldProperty->is_featured : 0,
                'status'          => strtoupper($row['STATUS']) == 'ARRENDADA' ? 'rented' : 'available',
                'commune_id'      => $commune->id ?? 1,
                'company_id'      => $company->id,
                'role'            => empty($row["ROL"]) && isset($oldProperty) ? $oldProperty->role : $row['ROL'],
                'leaves'          => empty($row["FOJAS"]) && isset($oldProperty) ? $oldProperty->leaves : $row["FOJAS"],
                'number'          => empty($row["N°"]) && isset($oldProperty) ? $oldProperty->number : $row["N°"],
                'year'            => empty($row["AÑO"]) && isset($oldProperty) ? $oldProperty->year : $row["AÑO"],
                'buy'             => empty($row["$ COMPRA"]) && isset($oldProperty) ? $oldProperty->buy : $row["$ COMPRA"],
                'date_deed'       => !is_null($row["FECHA DE COMPRA"]) ? $this->dateGetFormat($row['FECHA DE COMPRA']) : date('Y-m-d'),
                'appraisal'       => empty($row["AVALUO"]) && isset($oldProperty) ? $oldProperty->appraisal : $row["AVALUO"],
                'uf'              => empty($row["UF"]) && isset($oldProperty) ? $oldProperty->uf : $row["UF"],
                'pesos'           => $pesos,
                'profitability'   => $renta_costo,
                'type_id'         => $type->id ?? 1
            ]);


            $rut = str_replace(',', '.', $row['ARRENDATARIO RUT']);

            if (empty($rut)) continue;

            $lessee = Lessee::updateOrCreate([
                'rut' => $rut
            ],
            [
                'name'   => ucwords(strtolower($row['ARRENDATARIO'])),
                'rut'    => $rut,
                'phone'  => $row['ARRENDATARIO TELÉFONO'],
                'email'  => !is_null($row['ARRENDATARIO CORREO']) ? strtoupper($row['ARRENDATARIO CORREO']) : null,
                'type'   => strtoupper($row['ARRENDATARIO TIPO']) == 'NATURAL' ? 'natural' : 'legal',
                'status' => 'enabled',
                'contact_name'  => $row['ARRENDATARIO NOMBRE DEL CONTACTO']
            ]);

            $startDate  = $this->dateGetFormat($row['INICIO DEL CONTRATO']);
            $endDate    = !is_null($row['CULMINACIÓN DEL CONTRATO']) ? $this->dateGetFormat($row['CULMINACIÓN DEL CONTRATO']) : $this->dateAddOneYear($row['INICIO DEL CONTRATO']);
            $cutoffDate = $this->createFormat($this->dateGetFormat($startDate, 'Y'), $this->dateGetFormat($startDate, 'm'), $row['DÍA DE PAGO']);
            $contract = Contract::updateOrCreate([
                'property_id'        => $property->id,
                'lessee_id'          => $lessee->id,
                'start_date'         => $startDate,
                'end_date'           => $endDate,
            ],
            [
                'property_id'        => $property->id,
                'lessee_id'          => $lessee->id,
                'start_date'         => $startDate,
                'end_date'           => $endDate,
                'cutoff_date'        => $cutoffDate,
                'name'               => $row['ARRENDATARIO'],
                'quota'              => $quota,
                'contribution_quota' => $contribucion_cuota,
                'contribution'       => $contribucion,
                'income'             => $ingreso_real,
            ]);

            $ENERO      = (!is_numeric($row["ENERO"])) ? 0 : floatval($row["ENERO"]);
            $FEBRERO    = (!is_numeric($row["FEBRERO"])) ? 0 : floatval($row["FEBRERO"]);
            $MARZO      = (!is_numeric($row["MARZO"])) ? 0 : floatval($row["MARZO"]);
            $ABRIL      = (!is_numeric($row["ABRIL"])) ? 0 : floatval($row["ABRIL"]);
            $MAYO       = (!is_numeric($row["MAYO"])) ? 0 : floatval($row["MAYO"]);
            $JUNIO      = (!is_numeric($row["JUNIO"])) ? 0 : floatval($row["JUNIO"]);
            $JULIO      = (!is_numeric($row["JULIO"])) ? 0 : floatval($row["JULIO"]);
            $AGOSTO     = (!is_numeric($row["AGOSTO"])) ? 0 : floatval($row["AGOSTO"]);
            $SEPTIEMBRE = (!is_numeric($row["SEPTIEMBRE"])) ? 0 : floatval($row["SEPTIEMBRE"]);
            $OCTUBRE    = (!is_numeric($row["OCTUBRE"])) ? 0 : floatval($row["OCTUBRE"]);
            $NOVIEMBRE  = (!is_numeric($row["NOVIEMBRE"])) ? 0 : floatval($row["NOVIEMBRE"]);
            $DICIEMBRE  = (!is_numeric($row["DICIEMBRE"])) ? 0 : floatval($row["DICIEMBRE"]);

            if ($ENERO > 0) {
                Payment::updateOrCreate([
                    'contract_id' => $contract->id,
                    'date' => "{$this->dateGetFormat($startDate, 'Y')}-01-{$this->dateGetFormat($cutoffDate, 'd')}",
                ], [
                    'date' => "{$this->dateGetFormat($startDate, 'Y')}-01-{$this->dateGetFormat($cutoffDate, 'd')}",
                    'amount' => $ENERO,
                ]);
            }
            if ($FEBRERO > 0) {
                Payment::updateOrCreate([
                    'contract_id' => $contract->id,
                    'date'        => "{$this->dateGetFormat($startDate, 'Y')}-02-{$this->dateGetFormat($cutoffDate, 'd')}",
                ], [
                    'date'   => "{$this->dateGetFormat($startDate, 'Y')}-02-{$this->dateGetFormat($cutoffDate, 'd')}",
                    'amount' => $FEBRERO,
                ]);
            }
            if ($MARZO > 0) {
                Payment::updateOrCreate([
                    'contract_id' => $contract->id,
                    'date'        => "{$this->dateGetFormat($startDate, 'Y')}-03-{$this->dateGetFormat($cutoffDate, 'd')}",
                ], [
                    'date'   => "{$this->dateGetFormat($startDate, 'Y')}-03-{$this->dateGetFormat($cutoffDate, 'd')}",
                    'amount' => $MARZO,
                ]);
            }
            if ($ABRIL > 0) {
                Payment::updateOrCreate([
                    'contract_id' => $contract->id,
                    'date'        => "{$this->dateGetFormat($startDate, 'Y')}-04-{$this->dateGetFormat($cutoffDate, 'd')}",
                ], [
                    'date'   => "{$this->dateGetFormat($startDate, 'Y')}-04-{$this->dateGetFormat($cutoffDate, 'd')}",
                    'amount' => $ABRIL,
                ]);
            }
            if ($MAYO > 0) {
                Payment::updateOrCreate([
                    'contract_id' => $contract->id,
                    'date'        => "{$this->dateGetFormat($startDate, 'Y')}-05-{$this->dateGetFormat($cutoffDate, 'd')}",
                ], [
                    'date'   => "{$this->dateGetFormat($startDate, 'Y')}-05-{$this->dateGetFormat($cutoffDate, 'd')}",
                    'amount' => $MAYO,
                ]);
            }
            if ($JUNIO > 0) {
                Payment::updateOrCreate([
                    'contract_id' => $contract->id,
                    'date'        => "{$this->dateGetFormat($startDate, 'Y')}-06-{$this->dateGetFormat($cutoffDate, 'd')}",
                ], [
                    'date'   => "{$this->dateGetFormat($startDate, 'Y')}-06-{$this->dateGetFormat($cutoffDate, 'd')}",
                    'amount' => $JUNIO,
                ]);
            }
            if ($JULIO > 0) {
                Payment::updateOrCreate([
                    'contract_id' => $contract->id,
                    'date'        => "{$this->dateGetFormat($startDate, 'Y')}-07-{$this->dateGetFormat($cutoffDate, 'd')}",
                ], [
                    'date'   => "{$this->dateGetFormat($startDate, 'Y')}-07-{$this->dateGetFormat($cutoffDate, 'd')}",
                    'amount' => $JULIO,
                ]);
            }
            if ($AGOSTO > 0) {
                Payment::updateOrCreate([
                    'contract_id' => $contract->id,
                    'date'        => "{$this->dateGetFormat($startDate, 'Y')}-08-{$this->dateGetFormat($cutoffDate, 'd')}",
                ], [
                    'date'   => "{$this->dateGetFormat($startDate, 'Y')}-08-{$this->dateGetFormat($cutoffDate, 'd')}",
                    'amount' => $AGOSTO,
                ]);
            }
            if ($SEPTIEMBRE > 0) {
                Payment::updateOrCreate([
                    'contract_id' => $contract->id,
                    'date'        => "{$this->dateGetFormat($startDate, 'Y')}-09-{$this->dateGetFormat($cutoffDate, 'd')}",
                ], [
                    'date'   => "{$this->dateGetFormat($startDate, 'Y')}-09-{$this->dateGetFormat($cutoffDate, 'd')}",
                    'amount' => $SEPTIEMBRE,
                ]);
            }
            if ($OCTUBRE > 0) {
                Payment::updateOrCreate([
                    'contract_id' => $contract->id,
                    'date'        => "{$this->dateGetFormat($startDate, 'Y')}-10-{$this->dateGetFormat($cutoffDate, 'd')}",
                ], [
                    'date'   => "{$this->dateGetFormat($startDate, 'Y')}-10-{$this->dateGetFormat($cutoffDate, 'd')}",
                    'amount' => $OCTUBRE,
                ]);
            }
            if ($NOVIEMBRE > 0) {
                Payment::updateOrCreate([
                    'contract_id' => $contract->id,
                    'date'        => "{$this->dateGetFormat($startDate, 'Y')}-11-{$this->dateGetFormat($cutoffDate, 'd')}",
                ], [
                    'date'   => "{$this->dateGetFormat($startDate, 'Y')}-11-{$this->dateGetFormat($cutoffDate, 'd')}",
                    'amount' => $NOVIEMBRE,
                ]);
            }
            if ($DICIEMBRE > 0) {
                Payment::updateOrCreate([
                    'contract_id' => $contract->id,
                    'date'        => "{$this->dateGetFormat($startDate, 'Y')}-12-{$this->dateGetFormat($cutoffDate, 'd')}",
                ], [
                    'date'   => "{$this->dateGetFormat($startDate, 'Y')}-12-{$this->dateGetFormat($cutoffDate, 'd')}",
                    'amount' => $DICIEMBRE,
                ]);
            }
        }
    }
}
