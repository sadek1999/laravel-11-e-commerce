<?php

namespace App\Enum;

enum ProductVariationTypeEnum:string
{

    case select = "Select";
    case radio = 'Radio';
    case image = "Image";

    public static function labels()
    {
        return [
            self::select->value => __('Select'),
            self::radio->value => __('Radio'),
            self::image->value => __('Image'),
        ];
    }
}
