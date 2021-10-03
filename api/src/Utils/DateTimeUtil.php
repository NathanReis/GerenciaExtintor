<?php

namespace App\Utils;

class DateTimeUtil
{
    /** @var string A regex que representa um formato de data. */
    public const DATE_AS_REGEXP = "\d{4}-\d{2}-\d{2}";

    /** @var string A regex que representa um formato de horário. */
    public const TIME_AS_REGEXP = "\d{2}:\d{2}:\d{2}";

    /** @var string A regex que representa um formato de data com horário. */
    public const DATE_TIME_AS_REGEXP = self::DATE_AS_REGEXP . "\D" . self::TIME_AS_REGEXP;

    /**
     * Valida o formato da data com base na regex.
     *
     * @param string $date A data para validar.
     *
     * @return bool Se é valida ou não.
     */
    public static function isValidStringDate(string $date): bool
    {
        return preg_match("/^" . self::DATE_AS_REGEXP . "$/", $date);
    }

    /**
     * Valida o formato do horário com base na regex.
     *
     * @param string $time O horário para validar.
     *
     * @return bool Se é valido ou não.
     */
    public static function isValidStringTime(string $time): bool
    {
        return preg_match("/^" . self::TIME_AS_REGEXP . "$/", $time);
    }

    /**
     * Valida o formato da data com horário com base na regex.
     *
     * @param string $dateTime A data com horário para validar.
     *
     * @return bool Se é valido ou não.
     */
    public static function isValidStringDateTime(string $dateTime): bool
    {
        return preg_match("/^" . self::DATE_TIME_AS_REGEXP . "$/", $dateTime);
    }
}
