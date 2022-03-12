<?php

class ErrorController
{
    public static function error404(string $askedPage)
    {
        require_once __DIR__ . '/../View/error/404.php';
        exit();
    }
}