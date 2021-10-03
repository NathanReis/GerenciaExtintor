<?php

namespace App\DAOs;

use App\Helpers\LocationHelper;
use App\Models\Location;

class LocationDAO extends DAO
{
    /**
     * Coloca o nome da tabela na variável $table.
     *
     * @return void
     */
    public function __construct()
    {
        $this->table = "tbLocations";
    }

    /**
     * Salva um registro no banco de dados para o local recebido.
     *
     * @param App\Models\Location $location Local para salvar o registro.
     */
    public function create($location): void
    {
        $sql  = "INSERT INTO {$this->table} (description) ";
        $sql .= "VALUES (:description);";

        DBConnection::getInstance()
            ->prepare($sql)
            ->execute([
                "description" => $location->getDescription()
            ]);

        $location->setId($this->getInsertedId());
    }

    /**
     * Busca todos os locais da tabela específica.
     *
     * @return App\Models\Location[] Todos locais encontrados.
     */
    public function findAll(): array
    {
        $extinguisherDAO = new ExtinguisherDAO();

        return array_map(
            function ($locationFromDb) use ($extinguisherDAO) {
                $location = LocationHelper::getFromOwnTable($locationFromDb);
                $location->setExtinguishers(
                    $extinguisherDAO->filter(
                        ["idLocation"],
                        [$location->getId()]
                    )
                );

                return $location;
            },
            parent::findAll()
        );
    }

    /**
     * Busca o primeiro local por um campo específico.
     *
     * Retorna o local encontrado ou nulo caso não tenha encontrado nada.
     *
     * @param string $field Campo da tabela para consultar.
     * @param float|int|string $value Valor para consultar.
     *
     * @return App\Models\Location|null O local encontrado ou nulo caso nada foi encontrado.
     */
    public function findFirst(string $field, float|int|string $value): ?Location
    {
        $location = parent::findFirst($field, $value);

        if (empty($location)) {
            return null;
        }

        return LocationHelper::getFromOwnTable($location);
    }

    /**
     * Atualiza um registro no banco de dados para o local recebido.
     *
     * @param App\Models\Location $location Local para atualizar o registro.
     */
    public function update($location): void
    {
        $sql  = "UPDATE {$this->table} SET ";
        $sql .=     "description = :description ";
        $sql .= "WHERE id = :id;";

        DBConnection::getInstance()
            ->prepare($sql)
            ->execute([
                "description" => $location->getDescription(),
                "id" => $location->getId()
            ]);
    }
}
