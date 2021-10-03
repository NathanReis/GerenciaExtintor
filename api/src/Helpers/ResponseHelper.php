<?php

namespace App\Helpers;

use App\Models\Response as ResponseModel;
use Psr\Http\Message\ResponseInterface as IResponse;

class ResponseHelper
{
    /**
     * Retorna a resposta preenchida com os dados no formato JSON assim como o
     * cabeçalho para o mesmo formato.
     *
     * @param Psr\Http\Message\ResponseInterface $response A resposta que será preenchida.
     * @param array|object|string $data Dados que serão enviados.
     * @param int $statusCode O código da resposta.
     * @param bool $success Se o objetivo da requisição foi atingido.
     *
     * @return Psr\Http\Message\ResponseInterface A resposta preenchida.
     */
    public static function getNewResponseWithJSON(
        IResponse $response,
        array|object|string $data,
        int $statusCode,
        bool $success
    ): IResponse {
        $responseModel = new ResponseModel(
            data: $data,
            statusCode: $statusCode,
            success: $success
        );

        $response
            ->getBody()
            ->write($responseModel->toJSON());

        return $response
            ->withHeader("Content-Type", "application/json; charset=UTF-8")
            ->withStatus($statusCode);
    }

    /**
     * Retorna a resposta preenchida utilizando JSON e sempre considerando que
     * não houve sucesso.
     *
     * @param Psr\Http\Message\ResponseInterface $response A resposta que será preenchida.
     * @param array|object|string $data Dados que serão enviados.
     * @param int $statusCode O código da resposta.
     *
     * @return Psr\Http\Message\ResponseInterface A resposta preenchida.
     */
    public static function getNewFailResponseWithJSON(
        IResponse $response,
        array|object|string $data,
        int $statusCode = 400
    ): IResponse {
        return self::getNewResponseWithJSON(
            response: $response,
            data: $data,
            statusCode: $statusCode,
            success: false
        );
    }

    /**
     * Retorna a resposta preenchida utilizando JSON e sempre considerando que
     * houve sucesso.
     *
     * @param Psr\Http\Message\ResponseInterface $response A resposta que será preenchida.
     * @param array|object|string $data Dados que serão enviados.
     * @param int $statusCode O código da resposta.
     *
     * @return Psr\Http\Message\ResponseInterface A resposta preenchida.
     */
    public static function getNewSuccessResponseWithJSON(
        IResponse $response,
        array|object|string $data,
        int $statusCode = 200
    ): IResponse {
        return self::getNewResponseWithJSON(
            response: $response,
            data: $data,
            statusCode: $statusCode,
            success: true
        );
    }
}
