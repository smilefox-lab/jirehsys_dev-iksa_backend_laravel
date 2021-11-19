<?php

namespace Botble\RealEstate\Enums;

use Botble\Base\Supports\Enum;
use Html;

/**
 * @method static DefaultStatusEnum ENABLED()
 * @method static DefaultStatusEnum DISABLED()
 */
class DefaultStatusEnum extends Enum
{
    public const ENABLED = 'enabled';
    public const DISABLED = 'disabled';

    /**
     * @var string
     */
    public static $langPath = 'plugins/real-estate::real-estate.statuses';

    /**
     * @return string
     */
    public function toHtml()
    {
        switch ($this->value) {
            case self::ENABLED:
                return Html::tag('span', self::ENABLED()->label())
                    ->toHtml();
            case self::DISABLED:
                return Html::tag('span', self::DISABLED()->label())
                    ->toHtml();
            default:
                return null;
        }
    }
}
