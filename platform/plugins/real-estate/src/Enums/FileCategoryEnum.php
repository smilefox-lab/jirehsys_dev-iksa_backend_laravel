<?php

namespace Botble\RealEstate\Enums;

use Botble\Base\Supports\Enum;
use Html;

/**
 * @method static FileCategoryEnum FIRST()
 * @method static FileCategoryEnum SECOND()
 * @method static FileCategoryEnum THREETY()
 * @method static FileCategoryEnum FOURTY()
 */
class FileCategoryEnum extends Enum
{
    public const TECHNICAL = 'technical';
    public const LEGAL = 'legal';
    public const PLAN = 'plan';

    /**
     * @var string
     */
    public static $langPath = 'plugins/real-estate::file.categories';

    /**
     * @return string
     */
    public function toHtml()
    {
        switch ($this->value) {
            case self::TECHNICAL:
                return Html::tag('span', self::TECHNICAL()->label())
                    ->toHtml();
            case self::LEGAL:
                return Html::tag('span', self::LEGAL()->label())
                    ->toHtml();
            case self::PLAN:
                return Html::tag('span', self::PLAN()->label())
                    ->toHtml();
            default:
                return null;
        }
    }
}
