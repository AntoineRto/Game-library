<?php
namespace App;

class Router {
    private $routes = []; // Propriété pour stocker les routes

    // Ajouter une route
    public function add($path, $controller, $method) {
        $this->routes[$path] = [
            'controller' => $controller,
            'method' => $method
        ];
    }

    // Dispatcher la requête vers le bon contrôleur et méthode
    public function dispatch($path) {
        // Vérifier si la route demandée existe
        if (isset($this->routes[$path])) {
            $controllerName = "\\App\\Controllers\\" . $this->routes[$path]['controller'];
            $methodName = $this->routes[$path]['method'];

            // Vérifier que la classe du contrôleur existe
            if (class_exists($controllerName)) {
                $controller = new $controllerName();

                // Vérifier que la méthode existe dans le contrôleur
                if (method_exists($controller, $methodName)) {
                    $controller->$methodName(); // Appeler la méthode
                } else {
                    echo "Erreur : La méthode '$methodName' n'existe pas dans le contrôleur '$controllerName'.";
                }
            } else {
                echo "Erreur : Le contrôleur '$controllerName' n'existe pas.";
            }
        } else {
            // Afficher une page 404 si la route n'existe pas
            http_response_code(404);
            include '../templates/404.php';
        }
    }
}
