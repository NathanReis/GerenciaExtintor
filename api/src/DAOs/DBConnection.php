<?php

namespace App\DAOs;

use App\Utils\PathUtil;

class DBConnection
{
    /** @var \PDO|null Instancia de uma conexão no banco de dados. */
    private static ?\PDO $instance = null;

    /** @var array Configurações da conexão. */
    private static array $options = [
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
    ];

    /**
     * Não permite instâncias externas pois é um padrão Singleton.
     */
    private function __construct()
    {
    }

    /**
     * Retorna uma única instância da conexão no banco de dados.
     *
     * @return \PDO A instância da conexão no banco de dados.
     */
    public static function getInstance(): \PDO
    {
        if (self::$instance === null) {
            $dsn = getenv("DB_DRIVER") . ":";

            switch (getenv("DB_DRIVER")) {
                case "sqlite":
                    $dsn .= PathUtil::resolve(
                        __DIR__,
                        "..",
                        "database",
                        "database.sqlite"
                    );
                    break;

                default:
                    $dsn .= "host=" . getenv("DB_HOST") . ";";
                    $dsn .= "port=" . getenv("DB_PORT") . ";";
                    $dsn .= "dbname=" . getenv("DB_NAME");
                    break;
            }

            self::$instance = new \PDO(
                dsn: $dsn,
                username: getenv("DB_USER"),
                password: getenv("DB_PASSWORD"),
                options: self::$options
            );
        }

        return self::$instance;
    }
}
