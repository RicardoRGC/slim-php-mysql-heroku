<?php


class LoginControllers extends Usuario
{

    public function Verificar($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $usuario = $parametros['nombre'];
        $clave = $parametros['clave'];


        $usuario = Usuario::obtenerUsuario($usuario);

        if (password_verify($clave, $usuario->clave)) {
            $mensaje = 'Password is valid!';
        } else {
            $mensaje = 'Invalid password.';
        }

        $payload = json_encode($mensaje);

        $response->getBody()->write($payload);

        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}
