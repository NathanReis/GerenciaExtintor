<?php

namespace App\DAOs;

use App\DAOs\DBConnection;
use App\Exceptions\NotImplementedException;

abstract class DAO
{
    /** @var string Nome da tabela no banco de dados. */
    protected string $table;

    /**
     * Busca o último ID criado na tabela específica.
     *
     * @return int O último ID criado.
     */
    protected function getInsertedId(): int
    {
        $sql = "SELECT LAST_INSERT_ROWID() AS id FROM {$this->table};";

        return DBConnection::getInstance()
            ->query($sql)
            ->fetch()
            ->id;
    }

    /**
     * Salva um registro no banco de dados para o modelo recebido.
     *
     * @param mixed $model Modelo para salvar o registro.
     *
     * @throws App\Exceptions\NotImplementedException Método não foi sobreescrito
     *                                                nos herdeiros.
     */
    public function create($model): void
    {
        throw new NotImplementedException(__METHOD__);
    }

    /**
     * Apaga um registro no banco de dados com o ID recebido.
     *
     * @param int $id ID do registro para apagar.
     */
    public function delete(int $id): void
    {
        $sql = "DELETE FROM {$this->table} WHERE id = {$id};";

        DBConnection::getInstance()
            ->prepare($sql)
            ->execute();
    }

    /**
     * Busca todos os registros da tabela específica.
     *
     * @return array Todos registro encontrados.
     */
    public function findAll(): array
    {
        $sql = "SELECT * FROM {$this->table};";

        return DBConnection::getInstance()
            ->query($sql)
            ->fetchAll();
    }

    /**
     * Busca o primeiro registro por um campo específico.
     *
     * Retorna o registro encontrado ou nulo caso não tenha encontrado nada.
     *
     * @param string $field Campo da tabela para consultar.
     * @param float|int|string $value Valor para consultar.
     *
     * @return mixed O registro encontrado ou nulo caso nada foi encontrado.
     */
    public function findFirst(string $field, float|int|string $value): mixed
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$field} = :{$field};";

        $stmt = DBConnection::getInstance()->prepare($sql);
        $stmt->execute([
            $field => $value
        ]);

        return $stmt->fetch() ?: null;
    }

    /**
     * Atualiza um registro no banco de dados para o modelo recebido.
     *
     * @param mixed $model Modelo para atualizar o registro.
     *
     * @throws App\Exceptions\NotImplementedException Método não foi sobreescrito
     *                                                nos herdeiros.
     */
    public function update($model): void
    {
        throw new NotImplementedException(__METHOD__);
    }
}
