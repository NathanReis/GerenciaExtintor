<?php

namespace App\Controllers;

use App\DAOs\ExtinguisherDAO;
use App\DAOs\LocationDAO;
use App\Helpers\LocationHelper;
use App\Models\Location;
use App\Validations\LocationValidation;

class LocationController extends Controller
{
    /**
     * Preenche as variáveis $dao e $validation.
     *
     * Variável $dao recebe um App\DAOs\LocationDAO.
     *
     * Variável $validation recebe um App\Validations\LocationValidation.
     */
    public function __construct()
    {
        $this->dao = new LocationDAO();
        $this->validation = new LocationValidation(
            locationDAO: $this->dao,
            extinguisherDAO: new ExtinguisherDAO()
        );
    }

    /**
     * Recebe os dados da requisição e retorna um objeto modelo de local preenchido.
     *
     * @param array $requestData Dados recebidos da requisição.
     *
     * @return App\Models\Location O local preenchido.
     */
    protected function fillModel(array $requestData): Location
    {
        return LocationHelper::getFromLocationRequest($requestData);
    }
}
