<?php

namespace Botble\ACL\Enums;

use Botble\Base\Supports\Enum;
use Html;

/**
 * @method static CompanyStatusEnum ACTIVATED()
 * @method static CompanyStatusEnum DEACTIVATED()
 */
class CompanyStatusEnum extends Enum
{
    public const ACTIVATED = 'activated';
    public const DEACTIVATED = 'deactivated';

    /**
     * @var string
     */
    public static $langPath = 'core/acl::company.statuses';

    /**
     * @return string
     */
    public function toHtml()
    {
        switch ($this->value) {
            case self::ACTIVATED:
                return Html::tag('span', self::ACTIVATED()->label())
                    ->toHtml();
            case self::DEACTIVATED:
                return Html::tag('span', self::DEACTIVATED()->label())
                    ->toHtml();
            default:
                return parent::toHtml();
        }
    }
}
