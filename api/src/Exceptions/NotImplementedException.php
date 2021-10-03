<?php

namespace App\Exceptions;

class NotImplementedException extends BaseException
{
    /**
     * Customiza a mensagem da exceção.
     *
     * @param string $methodName O método que não foi implementado.
     */
    public function __construct(string $methodName)
    {
        parent::__construct("The method {$methodName} hasn't been implemented yet");
    }
}
