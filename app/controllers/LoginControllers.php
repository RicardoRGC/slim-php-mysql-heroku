<?php


class LoginControllers extends Usuario
{

    public function Verificar($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        if ($parametros != null) {
            $usuario = $parametros['nombre'];
            $clave = $parametros['clave'];


            $usuario = Usuario::obtenerUsuario($usuario);

            if (password_verify($clave, $usuario->clave)) {
                $mensaje = 'Password is valid!';

                $datos = array('usuario' => $parametros['nombre'], 'clave' => $parametros['clave'], 'tipo_perfil' => $usuario->tipo_perfil);

                $token = AutentificadorJWT::CrearToken($datos);
                $payload = json_encode(array('jwt' => $token));

                $response->getBody()->write($payload);
                return $response
                    ->withHeader('Content-Type', 'application/json');
            } else {
                $mensaje = 'Invalid password.';
            }
        } else {
            $mensaje = "Nada q mostrar";
        }

        $payload = json_encode($mensaje);

        $response->getBody()->write($payload);

        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}
