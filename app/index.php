<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

require_once './db/AccesoDatos.php';
// require_once './middlewares/Logger.php';
require_once './middlewares/SalidaMiddlewares.php';
require_once './middlewares/EntradaMiddlewares.php';

require_once './controllers/UsuarioController.php';
require_once './controllers/LoginMiddlewares.php';

// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();
/*
 Hacer un login para nuestra aplicacion;
 para esto vamos a necesitar añadir una columna mas a la tabla usuarios que ya tenemos en nuestro localhost
 tipo_perfil
 [empleado, cliente, admin]
 vamos a utilizar un middleware para poder chequear los perfiles de usuario en los request necesarios
 '/login' ese no deberia tener un middleware de proteccion
 por POST usuario y clave
 es usuario franco con perfil admin ingresó en la aplicacion
 */

// Routes
$app->group('/usuarios', function (RouteCollectorProxy $group) {
  $group->get('[/]', \UsuarioController::class . ':TraerTodos');
  $group->get('/{usuario}', \UsuarioController::class . ':TraerUno');
  $group->post('[/]', \UsuarioController::class . ':CargarUno');
  $group->post('/login', \LoginControllers::class . ':Verificar');
  $group->put('[/]', \UsuarioController::class . ':ModificarUno');
  $group->delete('[/]', \UsuarioController::class . ':BorrarUno');
}); /*->add(new SalidaMiddleware())->add(new EntradaMiddlewares());*/

$app->get('[/]', function (Request $request, Response $response) {
  $response->getBody()->write("Pagina RGraf");
  return $response;
});

$app->run();