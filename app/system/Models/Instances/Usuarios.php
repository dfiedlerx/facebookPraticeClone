<?php


namespace System\Models\Instances;


use Core\Engine\Model;
use System\Models\Tools\Basic\ArrayC;
use System\Models\Tools\Basic\Crypto;
use System\Models\Tools\Basic\GlobalValue;
use System\Models\Tools\Basic\Session;
use System\Models\Tools\Database\DatabaseQueryFactory;

class Usuarios extends Model
{

    /**
     * @param string $email
     * @param string $password
     * @return bool
     */
    public static function login (string $email, string $password) : bool {

        $usuario = self::getByEmail($email);

        if (is_bool($usuario) || ArrayC::empty($usuario) || !Crypto::hashComparer($password, $usuario['senha'])) {

            return false;

        }

        unset($usuario['senha']);

        self::setUserSession($usuario);

        return true;

    }

    /**
     * @param string $email
     * @return mixed
     */
    public static function getByEmail (string $email) {

        return
            self::$DB->run(
                DatabaseQueryFactory::makeSelect
                (
                    [
                        ['id'],
                        ['email'],
                        ['senha'],
                        ['nome'],
                        ['sexo'],
                        ['bio']
                    ],
                    [
                        ['usuarios']
                    ],
                    [],
                    [
                        ['email', '=', ':email']
                    ]
                ),
                [
                    'email' => $email
                ]
            )->fetch();

    }

    /**
     * @param string $nome
     * @param string $email
     * @param string $senha
     * @param string $sexo
     * @return string
     */
    public static function register (string $nome, string $email, string $senha, string $sexo) : string {

        $usuario = self::getByEmail($email);

        if (!is_bool($usuario) && !ArrayC::empty($usuario)) {

            GlobalValue::set('Email já usado por outra conta', 'view->message');

        } else if (self::insert($nome, $email, $senha, $sexo)) {

            self::setUserSession
            (
                [
                    'id' => self::$DB->getLastInsertId(),
                    'nome' => $nome,
                    'email' => $email,
                    'sexo' => $sexo
                ]
            );

            return true;

        } else {

            GlobalValue::set('Houve um problema. Tente novamente.', 'view->message');

        }

        return false;

    }

    /**
     * @param string $nome
     * @param string $email
     * @param string $senha
     * @param string $sexo
     * @return bool
     */
    private static function insert (string $nome, string $email, string $senha, string $sexo) : bool {


        $result =
            self::$DB->run
            (
                DatabaseQueryFactory::makeInsert
                (
                    'usuarios',
                    [
                        'nome',
                        'email',
                        'senha',
                        'sexo'
                    ],
                    [
                        [
                            ':nome',
                            ':email',
                            ':senha',
                            ':sexo'
                        ]
                    ]
                ),
                [

                    ':nome' => $nome,
                    ':email' => $email,
                    ':senha' => $senha,
                    ':sexo' => $sexo

                ]
            );

        return self::$DB->hasResult($result);

    }

    /**
     * @param $usuario
     */
    private static function setUserSession ($usuario) {

        Session::set(
            [
                'lgsocial' => $usuario
            ]
        );

    }

    public static function logout () {

        Session::unset
        (
            [
                'lgsocial'
            ]
        );

    }

}