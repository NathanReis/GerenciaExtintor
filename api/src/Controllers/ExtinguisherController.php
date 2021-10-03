<?php

namespace App\Controllers;

use App\DAOs\ExtinguisherDAO;
use App\DAOs\LocationDAO;
use App\Helpers\ExtinguisherHelper;
use App\Models\Extinguisher;
use App\Validations\ExtinguisherValidation;

class ExtinguisherController extends Controller
{
    /**
     * Preenche as variáveis $dao e $validation.
     *
     * Variável $dao recebe um App\DAOs\ExtinguisherDAO.
     *
     * Variável $validation recebe um App\Validations\ExtinguisherValidation.
     */
    public function __construct()
    {
        $this->dao = new ExtinguisherDAO();
        $this->validation = new ExtinguisherValidation(
            extinguisherDAO: $this->dao,
            locationDAO: new LocationDAO()
        );
    }

    /**
     * Recebe os dados da requisição e retorna um objeto modelo de extintor preenchido.
     *
     * @param array $requestData Dados recebidos da requisição.
     *
     * @return App\Models\Extinguisher O extintor preenchido.
     */
    protected function fillModel(array $requestData): Extinguisher
    {
        return ExtinguisherHelper::getFromExtinguisherRequest($requestData);
    }
}
