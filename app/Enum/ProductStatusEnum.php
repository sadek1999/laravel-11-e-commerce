<?php

namespace App\Enum;

enum ProductStatusEnum: string
{
    case Draft = 'draft';
    case Published = 'published';

    public static function labels()
    {
        return [
            self::Draft->value => __('Draft'),
            self::Published->value => __('Published')
        ];
    }

    public static function colors()
    {
        return [
             self::Draft->value=>'warning',
           
            self::Published->value =>'success',
        ];
    }
}
