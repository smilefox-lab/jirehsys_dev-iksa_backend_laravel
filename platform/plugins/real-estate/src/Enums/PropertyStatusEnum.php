<?php

namespace Botble\RealEstate\Enums;

use Botble\Base\Supports\Enum;
use Html;

/**
 * @method static PropertyStatusEnum AVAILABLE()
 * @method static PropertyStatusEnum RENTED()
 */
class PropertyStatusEnum extends Enum
{
    public const AVAILABLE = 'available';
    public const RENTED = 'rented';

    /**
     * @var string
     */
    public static $langPath = 'plugins/real-estate::property.statuses';

    /**
     * @return string
     */
    public function toHtml()
    {

        switch ($this->value) {
            case self::AVAILABLE:
                return Html::tag('span', self::AVAILABLE()->label())
                    ->toHtml();
            case self::RENTED:
                return Html::tag('span', self::RENTED()->label())
                    ->toHtml();
            default:
                return null;
        }
    }
}
