<?php

namespace App\Models;

class Response
{
    /**
     * Utiliza a promoção de construtor para definir as propriedades da resposta.
     *
     * @param array|object|string $data Dados que serão enviados.
     * @param int $statusCode O código da resposta.
     * @param bool $success Se o objetivo da requisição foi atingido.
     */
    public function __construct(
        private array|object|string $data,
        private int $statusCode,
        private bool $success
    ) {
    }

    /** @return array|object|string Dados que serão enviados. */
    public function getData(): array|object|string
    {
        return $this->data;
    }

    /**
     * @param array|object|string $data Dados que serão enviados.
     *
     * @return App\Models\Response A própria resposta.
     */
    public function setData(array|object|string $data): self
    {
        $this->data = $data;

        return $this;
    }

    /** @return int O código da resposta. */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode O código da resposta.
     *
     * @return App\Models\Response A própria resposta.
     */
    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /** @return bool Se o objetivo da requisição foi atingido. */
    public function getSuccess(): bool
    {
        return $this->success;
    }

    /**
     * @param bool $success Se o objetivo da requisição foi atingido.
     *
     * @return App\Models\Response A própria resposta.
     */
    public function setSuccess(bool $success): self
    {
        $this->success = $success;

        return $this;
    }

    /**
     * Cria um array associativo com todas propriedades da resposta.
     *
     * @return array As propriedades da resposta.
     */
    public function toArray(): array
    {
        return [
            "data" => $this->data,
            "statusCode" => $this->statusCode,
            "success" => $this->success
        ];
    }

    /**
     * Cria uma string JSON com base no array das propriedades.
     *
     * @return string A string JSON.
     */
    public function toJSON(): string
    {
        return json_encode($this->toArray());
    }
}
