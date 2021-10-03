<?php

namespace App\Helpers;

use App\Models\Location;

class LocationHelper
{
    /**
     * Utiliza os dados do objeto dinâmico recebido para preencher o objeto correto.
     *
     * @param \stdClass $location O local sem tipagem.
     *
     * @return App\Models\Location O local com a tipagem correta.
     */
    private static function fillModel(\stdClass $location): Location
    {
        return new Location(
            id: (int)$location->id,
            description: empty($location->description)
                ? ""
                : $location->description
        );
    }

    /**
     * Converte os dados de uma requisição de local em um modelo de local.
     *
     * @param array $requestData Dados recebidos da requisição.
     *
     * @return App\Models\Location O local com os dados da requisição.
     */
    public static function getFromLocationRequest(array $requestData): Location
    {
        $location = (object)$requestData;

        if (empty($location->id)) {
            $location->id = 0;
        }

        return self::fillModel($location);
    }

    /**
     * Converte os dados de uma requisição diferente de local em um modelo de local.
     *
     * @param array $requestData Dados recebidos da requisição.
     *
     * @return App\Models\Location O local com os dados da requisição.
     */
    public static function getFromAnotherRequest(array $requestData): Location
    {
        $location = new \stdClass();

        foreach ($requestData as $index => $value) {
            if (preg_match("/.*Location$/", $index)) {
                $key = str_replace("Location", "", $index);

                $location->$key = $value;
            }
        }

        if (empty($location->id)) {
            $location->id = 0;
        }

        return self::fillModel($location);
    }

    /**
     * Converte os dados do registro da tabela de locais em um modelo de local.
     *
     * @param \stdClass $location O local sem tipagem.
     *
     * @return App\Models\Location O local com a tipagem correta.
     */
    public static function getFromOwnTable(\stdClass $location): Location
    {
        return self::fillModel($location);
    }

    /**
     * Converte os dados do registro de tabela diferente de locais em um modelo de local.
     *
     * @param \stdClass $model O local sem tipagem.
     *
     * @return App\Models\Location O local com a tipagem correta.
     */
    public static function getFromAnotherTable(\stdClass $model): Location
    {
        $location = new \stdClass();

        foreach ($model as $key => $value) {
            if (preg_match("/.*Location$/", $key)) {
                $correctKey = str_replace("Location", "", $key);

                $location->$correctKey = $value;
            }
        }

        return self::fillModel($location);
    }
}
