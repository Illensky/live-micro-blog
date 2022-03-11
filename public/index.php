<?php

require __DIR__ . '/../vendor/autoload.php';
session_start();
Router::route();
exit();

/*
switch ($controller) {
    case UserController::class :
        $controller = new UserController();
        $controller->usersList();
        break;
    default:
        (new ErrorController())->error404($controller);
}


if (!file_exists(__DIR__ . '/../View/' . $controller . '.php')) {
    (new ErrorController())->error404($controller);
}
else {
    require __DIR__ . '/../View/' . $controller . '.php';
}
*/