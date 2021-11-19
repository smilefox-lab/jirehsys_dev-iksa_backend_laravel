<?php

namespace Botble\RealEstate\Commands;

use Botble\Vendor\Models\Vendor;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\RealEstate\Enums\ModerationStatusEnum;
use Botble\RealEstate\Repositories\Interfaces\PropertyInterface;
use Illuminate\Console\Command;

class RenewPropertiesCommand extends Command
{
    /**
     * @var PropertyInterface
     */
    public $propertyRepository;

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'cms:properties:renew';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Renew properties';

    /**
     * RenewPropertiesCommand constructor.
     * @param PropertyInterface $propertyRepository
     */
    public function __construct(PropertyInterface $propertyRepository)
    {
        parent::__construct();
        $this->propertyRepository = $propertyRepository;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $properties = $this->propertyRepository->getModel()
            ->expired()
            ->where('re_properties.status', BaseStatusEnum::PUBLISHED)
            ->where('moderation_status', ModerationStatusEnum::APPROVED)
            ->where('author_type', Vendor::class)
            ->join('vendors', 'vendors.id', '=', 're_properties.author_id')
            ->where('vendors.credits', '>', 0)
            ->where('re_properties.auto_renew', 1)
            ->with(['author'])
            ->select('re_properties.*')
            ->get();

        foreach ($properties as $property) {
            if ($property->author->credits <= 0) {
                continue;
            }

            $property->expire_date = now()->addDays(config('plugins.real-estate.real-estate.property_expired_after_x_days'));
            $property->save();

            $property->author->credits--;
            $property->author->save();
        }

        $this->info('Renew ' . $properties->count() . ' properties successfully!');
    }
}
