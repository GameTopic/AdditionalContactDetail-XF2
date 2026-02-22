<?php

namespace TylerAustin\AdditionalContactDetails\Validate;

use XF\CustomField\Definition;

class Steam
{
    public static function validate(Definition $field, $value, &$error)
    {
        if ($value === null || $value === '') {
            return true;
        }

        if (!is_string($value)) {
            $error = \XF::phrase('please_enter_valid_steam_url');
            return false;
        }

        $value = trim($value);
        if ($value === '') {
            return true;
        }

        $pattern = '/^https?:\/\/steamcommunity\.com\/(id\/[A-Za-z0-9_-]+|profiles\/[0-9]{17})\/?(?:\?.*)?$/i';
        if (!preg_match($pattern, $value)) {
            $error = \XF::phrase('please_enter_valid_steam_url');
            return false;
        }
        return true;
    }
}
