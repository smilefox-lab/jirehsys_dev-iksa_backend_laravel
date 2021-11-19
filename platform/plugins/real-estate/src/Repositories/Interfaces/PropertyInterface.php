<?php

namespace Botble\RealEstate\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;

interface PropertyInterface extends RepositoryInterface
{
    /**
     * @param int $propertyId
     * @param int $limit
     * @return array
     */
    public function getRelatedProperties(int $propertyId, $limit = 4);

    /**
     * @param array $filters
     * @param array $params
     * @return array
     */
    public function getProperties($filters = [], $params = []);

    /**
     * @param int $propertyId
     * @param array $with
     * @return array
     */
    public function getProperty(int $propertyId, array $with = []);

    /**
     * @param array $condition
     * @param int $limit
     * @param array $with
     * @return array
     */
    public function getPropertiesByConditions(array $condition, $limit, array $with = []);

    /**
     * @return array
     */
    public function getPropertiesByCompany(array $condition);
}
