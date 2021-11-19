<?php

namespace Botble\RealEstate\Enums;

use Botble\Base\Supports\Enum;
use Html;

/**
 * @method static PropertyTypeEnum SALE()
 * @method static PropertyTypeEnum RENT()
 */
class PropertyTypeEnum extends Enum
{
    public const SALE = 'sale';
    public const RENT = 'rent';

    /**
     * @var string
     */
    public static $langPath = 'plugins/real-estate::property.types';

    /**
     * @return string
     */
    public function toHtml()
    {
        switch ($this->value) {
            case self::SALE:
                return Html::tag('span', self::SALE()->label())
                    ->toHtml();
            case self::RENT:
                return Html::tag('span', self::RENT()->label())
                    ->toHtml();
            default:
                return null;
        }
    }
}
