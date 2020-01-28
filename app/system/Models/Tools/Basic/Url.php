<?php


namespace System\Models\Tools\Basic;


class Url
{

    public static function redirect ($location) {

        if (!headers_sent()) {

            header('Location: ' . $location);

        } else {

            echo '<script>window.location = "'.$location.'"</script>';

        }

        exit;

    }

}