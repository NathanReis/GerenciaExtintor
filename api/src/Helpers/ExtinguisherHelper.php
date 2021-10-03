<?php

namespace App\Helpers;

use App\Models\Extinguisher;
use App\Utils\DateTimeUtil;

class ExtinguisherHelper
{
    /**
     * Utiliza os dados do objeto dinâmico recebido para preencher o objeto correto.
     *
     * @param \stdClass $extinguisher O extintor sem tipagem.
     *
     * @return App\Models\Extinguisher O extintor com a tipagem correta.
     */
    private static function fillModel(\stdClass $extinguisher): Extinguisher
    {
        return new Extinguisher(
            id: (int)$extinguisher->id,
            location: $extinguisher->location,
            validate: empty($extinguisher->validate)
                ? null
                : new \DateTime($extinguisher->validate, new \DateTimeZone("UTC"))
        );
    }

    /**
     * Converte os dados de uma requisição de extintor em um modelo de extintor.
     *
     * @param array $requestData Dados recebidos da requisição.
     *
     * @return App\Models\Extinguisher O extintor com os dados da requisição.
     */
    public static function getFromExtinguisherRequest(array $requestData): Extinguisher
    {
        $extinguisher = (object)$requestData;

        if (empty($extinguisher->id)) {
            $extinguisher->id = 0;
        }

        $extinguisher->location = LocationHelper::getFromAnotherRequest($requestData);

        if (!DateTimeUtil::isValidStringDate($extinguisher->validate)) {
            $extinguisher->validate = null;
        }

        return self::fillModel($extinguisher);
    }

    /**
     * Converte os dados do registro da tabela de extintores em um modelo de extintor.
     *
     * @param \stdClass $extinguisher O extintor sem tipagem.
     *
     * @return App\Models\Extinguisher O extintor com a tipagem correta.
     */
    public static function getFromOwnTable(\stdClass $extinguisher): Extinguisher
    {
        $extinguisher->location = LocationHelper::getFromAnotherTable($extinguisher);

        return self::fillModel($extinguisher);
    }
}
