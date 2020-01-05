<?php

/* 
 * Fupma
 * Arquivo que determinará configurações gerais do sistema
 * 
 */

//Tempo Padrão de um cookie sem previsão de expiração
define ('DEFAULT_COOKIE_EXPIRATION', 10000000000);

//Número de ciclos padrões para uso na função crypt.
define ('DEFAULT_CRYPTO_CICLE', '$2a$10$');

//O que é retornado por padrão caso um valor não exista na classe GlobalValue
define('DEFAULT_NO_GLOBAL_VALUE', 'UNDEFINED');