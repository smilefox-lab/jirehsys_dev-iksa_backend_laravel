<?php

namespace Botble\RealEstate;

use Exception;
use File;
use Illuminate\Http\UploadedFile;
use Normalizer;
use Storage;
use Validator;

// lo primero esto es un array, por consiguiente debo
// manejarlo como tal, con esto quiero decir,
// que debo retonar un array con los nombre de los
// documentos o con el nombre de las imagenes
// creo que debo manejar cada cosa por separado,
// y si instanciar la clase para el resto de las cosas

class IksaMedia
{
    protected static $uploadedFiles;

    public function __construct(
        UploadedFile $uploadedFiles
    ) {
        $this->uploadedFiles = $uploadedFiles;
    }

    protected static function isArray($uploadedFiles)
    {
        return is_array($uploadedFiles) && count($uploadedFiles) > 0;
    }

    protected static function validateInstance($uploadedFiles)
    {
        if (self::isArray($uploadedFiles)) {
            foreach ($uploadedFiles as $file) {
                if (!$file instanceof UploadedFile) return false;
            }
            return true;
        }
        return false;
    }


    /**
     * @param \Illuminate\Http\UploadedFile $fileUpload
     * @param int $folderId
     * @param string $folderSlug
     * @return string
     */
    public function handleUpload($fileUpload, $folderId = '')
    {

        if (!$fileUpload) {
            return [
                'error'   => true,
                'message' => trans('core/media::media.can_not_detect_file_type'),
            ];
        }

        if (!config('core.media.media.chunk.enabled')) {

            request()->merge(['uploaded_file' => $fileUpload]);

            // $validator = Validator::make(request()->all(), [
            //     'uploaded_file' => 'required|mimes:' . config('core.media.media.allowed_mime_types'),
            // ]);

            // if ($validator->fails()) {
            //     return [
            //         'error'   => true,
            //         'message' => $validator->getMessageBag()->first(),
            //     ];
            // }
            $maxSize = self::getServerConfigMaxUploadFileSize();

            // dd($fileUpload[1]);

            if ($fileUpload[1]->getSize() / 1024 > (int)$maxSize) {
                return [
                    'error'   => true,
                    'message' => trans('core/media::media.file_too_big', ['size' => human_file_size($maxSize)]),
                ];
            }
        }

        $fileExtension = $fileUpload[1]->getClientOriginalExtension();

        if (in_array(strtolower($fileExtension), explode(',', config('plugins.real-estate.media.allowed_mime_document')))) {

            return self::handleFile($fileUpload, $folderId);

        } elseif (in_array(strtolower($fileExtension), explode(',', config('plugins.real-estate.media.allowed_mime_image')))) {

            return self::handleImage($fileUpload[1], $folderId);

        }
    }

    /**
     * Returns a file size limit in bytes based on the PHP upload_max_filesize and post_max_size
     * @return float|int
     */
    public static function getServerConfigMaxUploadFileSize()
    {
        // Start with post_max_size.
        $maxSize = self::parseSize(ini_get('post_max_size'));

        // If upload_max_size is less, then reduce. Except if upload_max_size is
        // zero, which indicates no limit.
        $uploadMax = self::parseSize(ini_get('upload_max_filesize'));
        if ($uploadMax > 0 && $uploadMax < $maxSize) {
            $maxSize = $uploadMax;
        }

        return $maxSize;
    }

    /**
     * @param int $size
     * @return float - bytes
     */
    public static function parseSize($size)
    {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
        $size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
        if ($unit) {
            // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        }

        return round($size);
    }

    public static function handleImage($uploadedFiles, $folderId)
    {
        if (!self::isArray($uploadedFiles)) {
            throw new Exception('No es un array o el array esta vacio.');
        }

        if (!self::validateInstance($uploadedFiles)) {
            throw new Exception('Uno o más elementos del array no son instancia de UploadedFile');
        }

        $images = [];

        try {
            $path = "images/{$folderId}";


            foreach ($uploadedFiles as $file) {
                $name = self::createName($file);

                if (!Storage::exists($path)) {
                    Storage::makeDirectory($path);
                }

                array_push($images, $file->storeAs($path, $name));
            }

            return $images;

        } catch (Exception $exception) {
            return [
                'error'   => true,
                'message' => $exception->getMessage(),
            ];
        }
    }

    public static function handleFile($uploadedFiles, $path, $folderId)
    {
        if (!self::isArray($uploadedFiles)) {
            throw new Exception('No es un array o el array esta vacio.');
        }

        if (!self::validateInstance($uploadedFiles)) {
            throw new Exception('Uno o más elementos del array no son instancia de UploadedFile');
        }

        $files = [];

        try {
            $path = "$path/{$folderId}";


            foreach ($uploadedFiles as $file) {
                $name = self::createName($file);

                if (!Storage::exists($path)) {
                    Storage::makeDirectory($path);
                }
                array_push($files, $file->storeAs($path, $name));
            }
            return $files;

        } catch (Exception $exception) {
            return [
                'error'   => true,
                'message' => $exception->getMessage(),
            ];
        }
    }

    public static function handleFileDownload($fileName, $path, $folderId)
    {
        try {
            $fileName = str_replace('%23','#', $fileName);

            $path = "$path/{$folderId}/{$fileName}";

            if (!Storage::exists($path)) {
                throw new \Exception('No existe el archivo o directorio.');
            }

            return Storage::download($path);
        } catch (Exception $exception) {
            return [
                'error'   => true,
                'message' => $exception->getMessage(),
            ];
        }
    }

    private static function createName(UploadedFile $fileUpload): string
    {
        // Se cambian los carecteres especiales (por ejemplo: Ñ -> N)
        // para hacer uso de la clase Normalizer se debe instalar la
        // dependencia intl de php
        $name = rtrim(File::name($fileUpload->getClientOriginalName()));
        $name = preg_replace('/[\x{0300}-\x{036f}]/u', "", Normalizer::normalize($name, Normalizer::FORM_D));
        return strtoupper(str_replace(" ", "_", $name)).".".$fileUpload->getClientOriginalExtension();
    }


}
