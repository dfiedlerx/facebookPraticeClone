<?php namespace System\Models\Tools;


use Core\Engine\Model;
use System\Models\Tools\Basic\StringC;
use System\Models\Tools\Database\DatabaseQueryFactory;


class Cnpj extends Model
{
    /**
     * Retorna true/false sobre a validade de um CNPJ
     *
     * @param string $cnpj
     * @return bool
     */
    public static function isValid (string $cnpj) {

        //Remove todos os caracteres nao numericos do cnpj
        $cnpj = StringC::removeAllNotNumeric($cnpj);

        return
            strlen($cnpj) == 14
            && self::isNotABlacklistCNPJ($cnpj)
            && self::validaPrimeiroDigitoVerificador($cnpj)
            && self::validaSegundoDigitoVerificador($cnpj);

    }

    /**
     * Impede que algumas exceções invalidas sejam consideradas corretas
     *
     * @param string $cnpj
     * @return bool
     */
    private static function isNotABlacklistCNPJ (string $cnpj) {

        $invalidCNPJs =
            [
                '00000000000000',
                '11111111111111',
                '22222222222222',
                '33333333333333',
                '44444444444444',
                '55555555555555',
                '66666666666666',
                '77777777777777',
                '88888888888888',
                '99999999999999'
            ];

        return !in_array($cnpj, $invalidCNPJs);

    }

    /**
     * @param $cnpj
     * @return bool
     */
    private static function validaPrimeiroDigitoVerificador (string $cnpj) : bool {

        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {

            $soma += $cnpj{$i} * $j;
            $j = ($j == 2) ? 9 : $j - 1;

        }

        $resto = $soma % 11;

        return !($cnpj{12} != ($resto < 2 ? 0 : 11 - $resto));

    }

    /**
     * @param $cnpj
     * @return bool
     */
    private static function validaSegundoDigitoVerificador (string $cnpj) : bool {

        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {

            $soma += $cnpj{$i} * $j;
            $j = ($j == 2) ? 9 : $j - 1;

        }

        $resto = $soma % 11;

        return $cnpj{13} == ($resto < 2 ? 0 : 11 - $resto);

    }

    /**
     * Verifica se o cnpj informado ainda nao existe no banco de dados
     *
     * @param string $cnpj
     * @return bool
     */
    public static function cnpjNoAlreadyInUse (string $cnpj) : bool {

        self::getDBConn();

        $queryResult = self::$databaseConnection->run
        (

            DatabaseQueryFactory::makeSelect
            (
                [['emp_id']],
                [['tb_empresa']],
                [],
                [['emp_cnpj', '=', ':cnpj']]
            ),
            [
                [':cnpj', $cnpj]
            ]

        );

        return !self::$databaseConnection->hasResult($queryResult);

    }

}