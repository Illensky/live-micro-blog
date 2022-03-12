<?php

class Router
{
    public static function route(): void
    {
        $controllerStr = self::getParam('c', 'home');
        $method = self::getParam('m');
        $controller = self::guessController($controllerStr);

// Display a 404 message if controller cannot be guessed.

        if ($controller === ErrorController::class) {
            $controller::error404($controllerStr);
            exit();
        }

// Here we are sure to have a controller so we guess the method, if there is no method we call the index one

        $method = self::guessMethod($controller, $method);

        if (null === $method) {
            $controller::index();
            exit();
        }

// Here we are sure to have a method so we guess params if the methods have

        $parameters = self::guessParams($controller, $method);

// here we execute the method without param if the method don't take param

        if (count($parameters) === 0) {
            $controller::$method();
            exit();
        }

// here we are sure we are using a method wath need param so we get them from the URL and we execute the method with this params

        $params = [];
        foreach ($parameters as $p) {
            $var = $_GET[$p['param']];
            settype($var, $p['type']);
            $params[] = $var;
        }
        $controller::$method(...$params);
        exit();
    }

    /**
     * @param string $key
     * @param null $default
     * @return string|null
     */
    private static function getParam(string $key, $default = null): ?string
    {
        return isset($_GET[$key]) ? filter_var($_GET[$key], FILTER_SANITIZE_STRING) : $default;
    }

    /**
     * @param string $controller
     * @return ErrorController|mixed
     */
    private static function guessController(string $controller)
    {
        $controller = ucfirst($controller) . "Controller";
        return class_exists($controller) ? $controller : "ErrorController";
    }

    /**
     * @param object $controller
     * @param string $method
     * @return string|null
     */
    private static function guessMethod(string $controller, ?string $method)
    {
        $method = lcfirst(str_replace(' ', '', ucwords(str_replace('-', ' ', $method))));
        return method_exists($controller, $method) ? $method : null;
    }

    /**
     * @param AbstractController $controller
     * @param string $method
     * @return array
     * @throws ReflectionException
     */
    private static function guessParams(string $controller, string $method): array
    {
        $paramsArray = [];
        $reflexion = new ReflectionMethod($controller, $method);
        foreach ($reflexion->getParameters() as $param) {
            $paramsArray[] = [
                'param' => $param->name,
                'type' => $param->getType(),
            ];
        }
        return $paramsArray;
    }
}