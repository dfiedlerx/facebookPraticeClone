<?php namespace System\Models\Tools\Basic;


class Session
{
    /**
     * Seta determinados valores na sessão.
     *
     * @param array $sessionParams
     */
    public static function set (array $sessionParams) {

        foreach ($sessionParams as $sessionKey => $sessionValue) {

            $_SESSION[$sessionKey] = $sessionValue;

        }

    }

    public static function unset ($sessionParams) {

        foreach ($sessionParams as $sessionValue) {

            unset($_SESSION[$sessionValue]);

        }

    }

    /**
     * @return bool
     */
    public static function hasSession () : bool {

        return !empty($_SESSION);

    }

    /**
     * @return bool
     */
    public static function noHasSession () : bool {

        return !isset($_SESSION) || empty($_SESSION);

    }

    public static function sessionDestroy () {

        session_unset();

    }

    /**
     * @param string $key
     * @param bool $allowEmpty
     * @return bool
     */
    public static function sessionKeyExists (string $key, $allowEmpty = false) : bool {

        return (!$allowEmpty && !empty($_SESSION[$key])) || ($allowEmpty && isset($_SESSION[$key]));

    }

    /**
     * @param string $sessionKey
     * @return bool
     */
    public static function isLogged ($sessionKey = '') {

        return
            $sessionKey === ''
                ? self::hasSession()
                : self::sessionKeyExists('lgsocial');

    }

    /**
     * @param string $sessionKey
     * @param bool $obgLogin
     */
    public static  function controlLoginSessionPage ($sessionKey = '', $obgLogin = true) {

        $isLogged = self::isLogged($sessionKey);

        if ($isLogged && !$obgLogin) {

            Url::redirect(LOGGED_ROUTE);

        } else if (!$isLogged && $obgLogin) {

            Url::redirect(NOT_LOGGED_ROUTE);

        }

    }


}