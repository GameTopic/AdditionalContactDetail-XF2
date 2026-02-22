<?php

namespace TylerAustin\AdditionalContactDetails\Validate;

use XF\CustomField\Definition;

class Cameo
{
    public static function validate(Definition $field, $value, &$error)
    {
        if ($value === null || $value === '') {
            return true;
        }

        if (!is_string($value)) {
            $error = \XF::phrase('please_enter_valid_cameo_url');
            return false;
        }

        $value = trim($value);
        if ($value === '') {
            return true;
        }

        $pattern = '/^https?:\/\/(?:www\.)?cameo\.com\/[A-Za-z0-9_-]{1,40}\/?(?:\?.*)?$/i';
        if (!preg_match($pattern, $value)) {
            $error = \XF::phrase('please_enter_valid_cameo_url');
            return false;
        }
        return true;
    }
}
