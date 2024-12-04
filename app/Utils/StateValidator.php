<?php

namespace App\Utils;

use Exception;

class StateValidator
{
    protected static $validStates = [
        'AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA',
        'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN',
        'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO',
    ];

    public static function validate(string $uf): void
    {
        if (!in_array(strtoupper($uf), self::$validStates)) {
            throw new Exception("UF inválida: $uf", 400);
        }
    }

    public static function isValid(string $uf): bool
    {
        return in_array(strtoupper($uf), self::$validStates);
    }
}
