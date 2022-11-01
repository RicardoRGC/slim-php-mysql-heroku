<?php


use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class VerificarMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = $handler->handle($request);
        $parametros = $request->getParsedBody();
        var_dump($parametros);

        $existingContent = (string) $response->getBody();
        $response = new Response();

        $response->getBody()->write('antes!' . $existingContent);

        return $response;
    }
}