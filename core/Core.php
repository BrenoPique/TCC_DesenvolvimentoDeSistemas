<?php
class Core
{
    public function run($routes)
    {
        $url = '/';
        isset($_GET['url']) ? $url .= $_GET['url'] : '';
        ($url != '/' ? $url = rtrim($url, '/') : $url);

        $routerFound = false;
        $viewData = [];

        foreach ($routes as $path => $controller) {
            $pattern = '#^' . preg_replace("/{id}/", '(\w+)', $path) . '$#';

            if (preg_match($pattern, $url, $matches)) {
                array_shift($matches);
                $routerFound = true;

                [$currentController, $action] = explode('@', $controller);
                require_once __DIR__ . "/../controllers/$currentController.php";

                $newController = new $currentController();
                $viewData = $newController->$action($matches);

                return $viewData;
            }
        }

        if (!$routerFound) {
            require_once __DIR__ . "/../controllers/NotFoundController.php";
            $controller = new NotFoundController();
            return $controller->index();
        }
    }
}
