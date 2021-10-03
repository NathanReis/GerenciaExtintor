<?php

namespace App\Validations;

abstract class Validation
{
    /** @var mixed Objeto de acesso a dados. */
    protected $dao;

    /**
     * Valida o modelo recebido para poder salvar.
     *
     * @param mixed $model O modelo para validar.
     *
     * @return array Os erros encontrados.
     */
    public function checkCreate($model): array
    {
        return ["This method didn't implemented"];
    }

    /**
     * Valida um modelo que tenha o ID recebido para poder apagar.
     *
     * @param int $id O ID do modelo para validar.
     *
     * @return array Os erros encontrados.
     */
    public function checkDelete(int $id): array
    {
        return ["This method didn't implemented"];
    }

    /**
     * Valida o modelo recebido para poder atualizar.
     *
     * @param mixed $model O modelo para validar.
     *
     * @return array Os erros encontrados.
     */
    public function checkUpdate($model): array
    {
        return ["This method didn't implemented"];
    }

    /**
     * Valida a existencia de um registro com o ID recebido.
     *
     * @param int $id O ID do modelo para validar.
     *
     * @return array Os erros encontrados.
     */
    protected function getErrorsId(int $id): array
    {
        $errors = [];

        $modelById = $this->dao->findFirst("id", $id);

        if (empty($modelById)) {
            $errors["id"][] = "ID não encontrado";
        }

        return $errors;
    }
}
