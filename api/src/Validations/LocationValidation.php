<?php

namespace App\Validations;

use App\DAOs\ExtinguisherDAO;
use App\DAOs\LocationDAO;
use App\Exceptions\InstanceTypeException;
use App\Models\Location;

class LocationValidation extends Validation
{
    /** @var int Quantidade máxima de caracteres na descrição. */
    private const MAX_LENGTH_DESCRIPTION = 20;

    /** @var App\DAOs\ExtinguisherDAO Objeto de acesso a dados dos extintores. */
    private ExtinguisherDAO $extinguisherDAO;

    /**
     * Preenche as variáveis $dao e $extinguisherDAO.
     *
     * Variável $dao recebe um App\DAOs\LocationDAO.
     *
     * Variável $extinguisherDAO recebe um App\DAOs\ExtinguisherDAO.
     *
     * @param App\DAOs\LocationDAO $locationDAO Objeto de acesso a dados dos locais.
     * @param App\DAOs\ExtinguisherDAO $extinguisherDAO Objeto de acesso a dados dos extintores.
     */
    public function __construct(LocationDAO $locationDAO, ExtinguisherDAO $extinguisherDAO)
    {
        $this->dao = $locationDAO;
        $this->extinguisherDAO = $extinguisherDAO;
    }

    /**
     * Valida o local recebido para poder salvar.
     *
     * @param App\Models\Location $location O local para validar.
     *
     * @return array Os erros encontrados.
     *
     * @throws App\Exceptions\InstanceTypeException Não recebeu uma instância de
     *                                              App\Models\Location.
     */
    public function checkCreate($location): array
    {
        if (!($location instanceof Location))
            throw new InstanceTypeException(
                Location::class,
                get_class($location)
            );

        return $this->getErrorsDescription(
            $location->getId(),
            $location->getDescription()
        );
    }

    /**
     * Valida um local que tenha o ID recebido para poder apagar.
     *
     * @param int $id O ID do local para validar.
     *
     * @return array Os erros encontrados.
     */
    public function checkDelete(int $id): array
    {
        return array_merge_recursive(
            $this->getErrorsId($id),
            $this->getErrorsIsInUse($id)
        );
    }

    /**
     * Valida o local recebido para poder atualizar.
     *
     * @param App\Models\Location $location O local para validar.
     *
     * @return array Os erros encontrados.
     *
     * @throws App\Exceptions\InstanceTypeException Não recebeu uma instância de
     *                                              App\Models\Location.
     */
    public function checkUpdate($location): array
    {
        if (!($location instanceof Location))
            throw new InstanceTypeException(
                Location::class,
                get_class($location)
            );

        return array_merge_recursive(
            $this->getErrorsId($location->getId()),
            $this->getErrorsDescription($location->getId(), $location->getDescription())
        );
    }

    /**
     * Valida se foi recebido uma descrição não vazia, que não tenha
     * ultrapassado o tamanho máximo e que não seja repetida.
     *
     * @param int $id O ID do local.
     * @param string $description A descrição do local.
     *
     * @return array Os erros encontrados.
     */
    private function getErrorsDescription(int $id, string $description): array
    {
        $errors = [];

        if (empty($description)) {
            $errors["description"][] = "Não pode ser vazio";
        } elseif (mb_strlen($description) > self::MAX_LENGTH_DESCRIPTION) {
            $errors["description"][] = "Não pode ter mais de " . self::MAX_LENGTH_DESCRIPTION . " caracteres";
        } else {
            $locationByDescription = $this->dao->findFirst("description", $description);

            if (
                !empty($locationByDescription)
                && $locationByDescription->getId() != $id
            ) {
                $errors["description"][] = "Valor já existente";
            }
        }

        return $errors;
    }

    /**
     * Valida se existe algum extintor com o local do ID recebido.
     *
     * @param int $id O ID do local.
     *
     * @return array Os erros encontrados.
     */
    private function getErrorsIsInUse(int $id): array
    {
        $errors = [];

        $extinguisherByIdLocation = $this->extinguisherDAO->findFirst("idLocation", $id);

        if (!empty($extinguisherByIdLocation)) {
            $errors["id"][] = "Existe (m) extintor (es) associado (s) a esse registro";
        }

        return $errors;
    }
}
