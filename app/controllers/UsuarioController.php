<?php
require_once './models/Usuario.php';
require_once './interfaces/IApiUsable.php';

class UsuarioController extends Usuario implements IApiUsable
{
  public function CargarUno($request, $response, $args)
  {

    $parametros = $request->getParsedBody();

    $usuario = $parametros['nombre'];
    $clave = $parametros['clave'];
    var_dump($usuario);
    // Creamos el usuario
    $usr = new Usuario();
    $usr->usuario = $usuario;
    $usr->clave = $clave;

    $usr->crearUsuario();

    $payload = json_encode(array("mensaje" => "Usuario creado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerUno($request, $response, $args)
  {
    // Buscamos usuario por nombre
    $usr = $args['usuario'];
    $usuario = Usuario::obtenerUsuario($usr);
    $payload = json_encode($usuario);

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerTodos($request, $response, $args)
  {
    $lista = Usuario::obtenerTodos();
    $payload = json_encode(array("listaUsuario" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
  ///MODIFICAR----------------------------------------------------------------------------------
  public function ModificarUno($request, $response, $args)
  {

    $header = $request->getHeaderLine('Authorization');
    $token = trim(explode("Bearer", $header)[1]);
    $esValido = false;

    try {
      AutentificadorJWT::verificarToken($token);
      $esValido = true;
    } catch (Exception $e) {
      $payload = json_encode(array('error' => $e->getMessage()));
    }

    if ($esValido) {
      $parametros = $request->getParsedBody();
      if ($parametros != null) {

        var_dump($parametros);

        $nombre = $parametros['nombre'];
        $clave = $parametros['clave'];
        $id = $parametros['id'];

        $usr = new Usuario();
        $usr->usuario = $nombre;
        $usr->clave = $clave;
        $usr->id = $id;

        $usr->modificarUsuario();

        $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));
      } else {
        $payload = json_encode("error de datos");
      }
    }
    //-----------------------------------------------------------


    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function BorrarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();


    $usuarioId = $parametros['usuarioId'];
    Usuario::borrarUsuario($usuarioId);

    $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
}
