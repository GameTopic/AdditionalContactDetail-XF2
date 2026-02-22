<?php

namespace TylerAustin\AdditionalContactDetails\Validate;

use XF\CustomField\Definition;

class Imdb
{
    public static function validate(Definition $field, $value, &$error)
    {
        if ($value === null || $value === '') {
            return true;
        }

        if (!is_string($value)) {
            $error = \XF::phrase('please_enter_valid_imdb_url');
            return false;
        }

        $value = trim($value);
        if ($value === '') {
            return true;
        }

        $pattern = '/^https?:\/\/(?:www\.)?imdb\.com\/name\/nm[0-9]{7,8}\/?(?:\?.*)?$/i';
        if (!preg_match($pattern, $value)) {
            $error = \XF::phrase('please_enter_valid_imdb_url');
            return false;
        }
        return true;
    }
}
