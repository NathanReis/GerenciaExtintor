<?php

namespace App\Exceptions;

class BaseException extends \Exception
{
    /**
     * Sobreescreve o retorno padrão quando tentar exibir diretamente o objeto.
     *
     * @return string Texto a exibir.
     */
    public function __toString(): string
    {
        return "{$this->getMessage()} on {$this->getFile()}:{$this->getLine()}";
    }
}
