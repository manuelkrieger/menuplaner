<?php

namespace AppBundle\Utils;

class NumberHelper
{
    const GROUP_DIVIDER = '.';
    const SUFFIX_LENGTH = 6;

    /**
     * @param string $prefix
     * @return string
     */
    public static function getPrefix(string $prefix)
    {
        return rtrim($prefix, '0');
    }

    /**
     * @param string $prefix
     * @return string
     */
    public static function addSuffix(string $prefix)
    {
        return str_pad(
            $prefix . self::GROUP_DIVIDER,
            strlen($prefix) + strlen(self::GROUP_DIVIDER) + self::SUFFIX_LENGTH,
            '0', STR_PAD_RIGHT);
    }
}
