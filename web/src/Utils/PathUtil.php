<?php

namespace App\Utils;

class PathUtil
{
    /**
     * @param string[] $path
     * 
     * @return string
     */
    public static function resolve(string ...$path): string
    {
        return implode(DIRECTORY_SEPARATOR, $path);
    }
}
