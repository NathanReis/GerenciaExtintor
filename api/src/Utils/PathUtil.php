<?php

namespace App\Utils;

class PathUtil
{
    /**
     * Concatena o caminho recebido utilizando o separador de pastas com base no SO.
     *
     * @param string[] $path O caminho para resolver.
     *
     * @return string O caminho resolvido.
     */
    public static function resolve(string ...$path): string
    {
        return implode(DIRECTORY_SEPARATOR, $path);
    }
}
