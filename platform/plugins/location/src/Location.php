<?php

namespace Botble\Location;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Location\Enums\DefaultStatusEnum;
use Botble\Location\Repositories\Interfaces\CityInterface;
use Botble\Location\Repositories\Interfaces\CommuneInterface;
use Botble\Location\Repositories\Interfaces\RegionInterface;
use Botble\Location\Repositories\Interfaces\StateInterface;

class Location
{
    /**
     * @var StateInterface
     */
    public $stateRepository;
    /**
     * @var CityInterface
     */
    public $cityRepository;
    /**
     * @var RegionInterface
     */
    public $regionRepository;
    /**
     * @var CommuneInterface
     */
    public $communeRepository;

    /**
     * Location constructor.
     * @param StateInterface $stateRepository
     * @param CityInterface $cityRepository
     * @param RegionInterface $regionRepository
     * @param CommuneInterface $communeRepository
     */
    public function __construct(
        StateInterface $stateRepository,
        CityInterface $cityRepository,
        RegionInterface $regionRepository,
        CommuneInterface $communeRepository
    )
    {
        $this->stateRepository = $stateRepository;
        $this->cityRepository = $cityRepository;
        $this->regionRepository = $regionRepository;
        $this->communeRepository = $communeRepository;
    }

    /**
     * @return \Illuminate\Config\Repository|mixed
     */
    public function getStates()
    {
        $states = $this->stateRepository->advancedGet([
            'condition' => [
                'status' => BaseStatusEnum::PUBLISHED,
            ],
            'order_by'   => ['order' => 'DESC'],
        ]);

        return $states->pluck('name', 'id')->all();
    }

    /**
     * @return \Illuminate\Config\Repository|mixed
     */
    public function getCommunes()
    {
        $communes = $this->communeRepository->advancedGet([
            'condition' => [
                'status' => DefaultStatusEnum::ENABLED,
            ],
            'order_by'   => ['order' => 'DESC'],
        ]);

        return $communes->pluck('name', 'id')->all();
    }

    /**
     * @return \Illuminate\Config\Repository|mixed
     */
    public function getRegions()
    {
        $regions = $this->regionRepository->advancedGet([
            'condition' => [
                'status' => DefaultStatusEnum::ENABLED,
            ],
            'order_by'   => ['order' => 'DESC'],
        ]);

        return $regions->pluck('name', 'id')->all();
    }

    /**
     * @param $stateId
     * @return \Illuminate\Config\Repository|mixed
     */
    public function getCitiesByState($stateId)
    {
        $cities = $this->cityRepository->advancedGet([
            'condition' => [
                'status'   => BaseStatusEnum::PUBLISHED,
                'state_id' => $stateId,
            ],
            'order_by'   => ['order' => 'DESC'],
        ]);

        return $cities->pluck('name', 'id')->all();
    }

    /**
     * @param $cityId
     * @return string
     */
    public function getCityNameById($cityId)
    {
        $city = $this->cityRepository->findById($cityId);

        return $city ? $city->name : null;
    }

    /**
     * @param $stateId
     * @return string
     */
    public function getStateNameById($stateId)
    {
        $state = $this->stateRepository->findById($stateId);

        return $state ? $state->name : null;
    }
}
