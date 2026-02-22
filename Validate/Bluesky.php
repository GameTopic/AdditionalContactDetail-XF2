<?php

namespace TylerAustin\AdditionalContactDetails\Validate;

use XF\CustomField\Definition;

class Bluesky
{
    public static function validate(Definition $field, $value, &$error)
    {
        if ($value === null || $value === '') {
            return true;
        }

        if (!is_string($value)) {
            $error = \XF::phrase('please_enter_valid_bluesky_handle');
            return false;
        }

        $value = trim($value);
        if ($value === '') {
            return true;
        }

        $pattern = '/^https?:\/\/(?:bsky\.app\/profile|bsky\.social\/profile)\/[A-Za-z0-9._-]+\/?(?:\?.*)?$/i';
        if (!preg_match($pattern, $value)) {
            $error = \XF::phrase('please_enter_valid_bluesky_handle');
            return false;
        }
        return true;
    }
}
