<?php

namespace App\Validations;

use App\DAOs\ExtinguisherDAO;
use App\DAOs\LocationDAO;
use App\Exceptions\InstanceTypeException;
use App\Models\Extinguisher;
use App\Models\Location;

class ExtinguisherValidation extends Validation
{
    /** @var App\DAOs\LocationDAO Objeto de acesso a dados dos locais. */
    private LocationDAO $locationDAO;

    /**
     * Preenche as variáveis $dao e $locationDAO.
     *
     * Variável $dao recebe um App\DAOs\ExtinguisherDAO.
     *
     * Variável $locationDAO recebe um App\DAOs\LocationDAO.
     *
     * @param App\DAOs\ExtinguisherDAO $extinguisherDAO Objeto de acesso a dados dos extintores.
     * @param App\DAOs\LocationDAO $locationDAO Objeto de acesso a dados dos locais.
     */
    public function __construct(ExtinguisherDAO $extinguisherDAO, LocationDAO $locationDAO)
    {
        $this->dao = $extinguisherDAO;
        $this->locationDAO = $locationDAO;
    }

    /**
     * Valida o extintor recebido para poder salvar.
     *
     * @param App\Models\Extinguisher $extinguisher O extintor para validar.
     *
     * @return array Os erros encontrados.
     *
     * @throws App\Exceptions\InstanceTypeException Não recebeu uma instância de
     *                                              App\Models\Extinguisher.
     */
    public function checkCreate($extinguisher): array
    {
        if (!($extinguisher instanceof Extinguisher))
            throw new InstanceTypeException(
                Extinguisher::class,
                get_class($extinguisher)
            );

        return array_merge_recursive(
            $this->getErrorsLocation($extinguisher->getLocation()),
            $this->getErrorsValidate($extinguisher->getValidate())
        );
    }

    /**
     * Retorna um array vazio pois não precisa validar nada.
     *
     * @param int $id O ID do extintor para validar.
     *
     * @return array Os erros encontrados.
     */
    public function checkDelete(int $id): array
    {
        return [];
    }

    /**
     * Valida o extintor recebido para poder atualizar.
     *
     * @param App\Models\Extinguisher $extinguisher O extintor para validar.
     *
     * @return array Os erros encontrados.
     *
     * @throws App\Exceptions\InstanceTypeException Não recebeu uma instância de
     *                                              App\Models\Extinguisher.
     */
    public function checkUpdate($extinguisher): array
    {
        if (!($extinguisher instanceof Extinguisher))
            throw new InstanceTypeException(
                Extinguisher::class,
                get_class($extinguisher)
            );

        return array_merge_recursive(
            $this->getErrorsId($extinguisher->getId()),
            $this->getErrorsLocation($extinguisher->getLocation()),
            $this->getErrorsValidateOnUpdate($extinguisher->getValidate())
        );
    }

    /**
     * Valida se foi recebido um valor diferente de vazio.
     *
     * @param \DateTime|null $validate A data de validade do extintor ou nulo.
     *
     * @return array Os erros encontrados.
     */
    private function getErrorsValidate(?\DateTime $validate): array
    {
        $errors = [];

        if (empty($validate)) {
            $errors["validate"][] = "Obrigatório";
        }

        return $errors;
    }

    /**
     * Aplica mesmas validações de getErrorsValidate e também verifica se é uma
     * data do passado.
     *
     * @param \DateTime|null $validate A data de validade do extintor ou nulo.
     *
     * @return array Os erros encontrados.
     */
    private function getErrorsValidateOnUpdate(?\DateTime $validate): array
    {
        $errors = $this->getErrorsValidate($validate);

        if (!empty($errors)) {
            return $errors;
        }

        $now = new \DateTime("today");

        if ($validate <= $now) {
            $errors["validate"][] = "Deve ser maior que hoje";
        }

        return $errors;
    }

    /**
     * Valida se existe no banco de dados o local recebido.
     *
     * @param App\Models\Location $location O local do extintor.
     *
     * @return array Os erros encontrados.
     */
    private function getErrorsLocation(Location $location): array
    {
        $locationFound = $this->locationDAO->findFirst("id", $location->getId());

        $errors = [];

        if (empty($locationFound)) {
            $errors["location"][] = "Localização não encontrada";
        }

        return $errors;
    }
}
