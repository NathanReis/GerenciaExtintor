<?php

namespace App\DAOs;

use App\Helpers\ExtinguisherHelper;
use App\Models\Extinguisher;

class ExtinguisherDAO extends DAO
{
    /**
     * Coloca o nome da tabela na variável $table.
     *
     * @return void
     */
    public function __construct()
    {
        $this->table = "tbExtinguishers";
    }

    /**
     * Salva um registro no banco de dados para o extintor recebido.
     *
     * @param App\Models\Extinguisher $extinguisher Extintor para salvar o registro.
     */
    public function create($extinguisher): void
    {
        $sql  = "INSERT INTO {$this->table} (idLocation, validate) ";
        $sql .= "VALUES (:idLocation, :validate);";

        DBConnection::getInstance()
            ->prepare($sql)
            ->execute([
                "idLocation" => $extinguisher->getLocation()->getId(),
                "validate" => $extinguisher->getValidate()->format("Y-m-d H:i:s")
            ]);

        $extinguisher->setId($this->getInsertedId());
    }

    /**
     * Filtra todos os extintores da tabela específica como base nos valores
     * dos campos recebidos.
     *
     * @param string[] $fields Campos da tabela para filtrar.
     * @param float[]|int[]|string[] $values Valores para filtrar.
     *
     * @return App\Models\Extinguisher[] Todos extintores encontrados.
     */
    public function filter(array $fields, array $values): array
    {
        $amountFields = count($fields);

        $sql  = "SELECT ";
        $sql .=     "fe.*, ";
        $sql .=     "l.description AS descriptionLocation ";
        $sql .= "FROM {$this->table} AS fe ";
        $sql .=     "INNER JOIN tbLocations AS l ";
        $sql .=         "ON l.id = fe.idLocation ";
        $sql .= "WHERE 1 = 1";

        $params = [];

        for ($i = 0; $i < $amountFields; $i++) {
            $sql .= " AND {$fields[$i]} = :value{$i}";
            $params[":value{$i}"] = $values[$i];
        }

        $sql .= ";";

        $stmt = DBConnection::getInstance()->prepare($sql);
        $stmt->execute($params);

        $extinguishers = $stmt->fetchAll();

        return array_map(
            function ($extinguisher) {
                return ExtinguisherHelper::getFromOwnTable($extinguisher);
            },
            $extinguishers
        );
    }

    /**
     * Busca todos os extintores da tabela específica.
     *
     * @return App\Models\Extinguisher[] Todos extintores encontrados.
     */
    public function findAll(): array
    {
        $sql  = "SELECT ";
        $sql .=     "fe.*, ";
        $sql .=     "l.description AS descriptionLocation ";
        $sql .= "FROM {$this->table} AS fe ";
        $sql .=     "INNER JOIN tbLocations AS l ";
        $sql .=         "ON l.id = fe.idLocation;";

        $extinguishers = DBConnection::getInstance()
            ->query($sql)
            ->fetchAll();

        return array_map(
            function ($extinguisher) {
                return ExtinguisherHelper::getFromOwnTable($extinguisher);
            },
            $extinguishers
        );
    }

    /**
     * Busca o primeiro extintor por um campo específico.
     *
     * Retorna o extintor encontrado ou nulo caso não tenha encontrado nada.
     *
     * @param string $field Campo da tabela para consultar.
     * @param float|int|string $value Valor para consultar.
     *
     * @return App\Models\Extinguisher|null O extintor encontrado ou nulo caso nada foi encontrado.
     */
    public function findFirst(string $field, float|int|string $value): ?Extinguisher
    {
        $sql  = "SELECT ";
        $sql .=     "fe.*, ";
        $sql .=     "l.description AS descriptionLocation ";
        $sql .= "FROM {$this->table} AS fe ";
        $sql .=     "INNER JOIN tbLocations AS l ";
        $sql .=         "ON l.id = fe.idLocation ";
        $sql .= "WHERE fe.{$field} = :{$field};";

        $stmt = DBConnection::getInstance()->prepare($sql);
        $stmt->execute([
            $field => $value
        ]);

        $extinguisher = $stmt->fetch();

        if (empty($extinguisher)) {
            return null;
        }

        return ExtinguisherHelper::getFromOwnTable($extinguisher);
    }

    /**
     * Atualiza um registro no banco de dados para o extintor recebido.
     *
     * @param App\Models\Extinguisher $extinguisher Extintor para atualizar o registro.
     */
    public function update($extinguisher): void
    {
        $sql  = "UPDATE {$this->table} SET ";
        $sql .=     "idLocation = :idLocation, ";
        $sql .=     "validate = :validate ";
        $sql .= "WHERE id = :id;";

        DBConnection::getInstance()
            ->prepare($sql)
            ->execute([
                "idLocation" => $extinguisher->getLocation()->getId(),
                "validate" => $extinguisher->getValidate()->format("Y-m-d H:i:s"),
                "id" => $extinguisher->getId()
            ]);
    }
}
