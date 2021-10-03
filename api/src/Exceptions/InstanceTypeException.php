<?php

namespace App\Exceptions;

class InstanceTypeException extends BaseException
{
    /**
     * Customiza a mensagem da exceção.
     *
     * @param string $neededClassName A classe que deveria ter recebido.
     * @param string $receivedClassName A classe que recebeu.
     */
    public function __construct(string $neededClassName, string $receivedClassName)
    {
        parent::__construct("You received {$receivedClassName} instead of {$neededClassName}");
    }
}
