<?php

namespace Botble\RealEstate\Enums;

use Botble\Base\Supports\Enum;
use Html;

/**
 * @method static LesseeTypeEnum NATURAL()
 * @method static LesseeTypeEnum LEGAL()
 */
class LesseeTypeEnum extends Enum
{
    public const NATURAL = 'natural';
    public const LEGAL = 'legal';

    /**
     * @var string
     */
    public static $langPath = 'plugins/real-estate::lessee.type';

    /**
     * @return string
     */
    public function toHtml()
    {
        switch ($this->value) {
            case self::NATURAL:
                return Html::tag('span', self::NATURAL()->label())
                    ->toHtml();
            case self::LEGAL:
                return Html::tag('span', self::LEGAL()->label())
                    ->toHtml();
            default:
                return null;
        }
    }
}
