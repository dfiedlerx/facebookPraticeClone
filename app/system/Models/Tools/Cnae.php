<?php namespace System\Models\Tools;


use Core\Engine\Model;
use System\Models\Tools\Database\DatabaseQueryFactory;


class Cnae extends Model
{


    public static function cnaeIsValid (string $cnae) : bool {

        self::getDBConn();

        $queryResult = self::$databaseConnection->run
        (
            DatabaseQueryFactory::makeSelect
            (
                [['cnae_id']],
                [['tb_cnae']],
                [],
                [
                    ['cnae_codigo', '=', ':cnae']
                ]
            ),
            [
                [':cnae', $cnae]
            ]
        );

        return self::$databaseConnection->hasResult($queryResult);

    }

    public static function getCnaeCode (string $postCnae) : string {

        return trim(explode("|", $postCnae)['1']);

    }

    /**
     * @param string $name
     * @return array
     */
    public static function getCnaeByName (string $name) : array {

        self::getDBConn();

        $queryResult = self::$databaseConnection->run
        (
            DatabaseQueryFactory::makeSelect(
                [
                    ['cnae_id'],
                    ['cnae_codigo'],
                    ['cnae_descricao']
                ],
                [['tb_cnae']],
                [],
                [
                    ['cnae_descricao', 'LIKE', ':cnae']
                ],
                [['cnae_codigo', 'ASC']]
            ),
            [
                [':cnae', '%' . $name . '%']
            ]
        );

        return
            self::$databaseConnection->hasResult($queryResult)
                ? $queryResult->fetchAll()
                : [];
    }

    /**
     * @param string $cnaeString
     * @return string
     */
    public static function getRegisterCnaeCode (string $cnaeString) : string {

        return trim(explode(' | ', $cnaeString)['1']);

    }

    public static function getCnaeIdByCode (string $cnaeCode) : int {

        self::getDBConn();

        $queryResult =
            self::$databaseConnection->run
            (
                DatabaseQueryFactory::makeSelect
                (
                    [
                        ['cnae_id']
                    ],
                    [
                        ['tb_cnae']
                    ],
                    [],
                    [
                        ['cnae_codigo', '=', ':cnaeCode']
                    ]

                ),
                [
                    [':cnaeCode', $cnaeCode]
                ]
            );

        return
            self::$databaseConnection->hasResult($queryResult)
                ? $queryResult->fetch()['cnae_id']
                : 0;


    }

}