<?php
declare(strict_types=1);

namespace App\Application\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Exception\HttpBadRequestException;

class AppJsonMiddleware implements Middleware {

    /**
     * {@inheritdoc}
     */
    public function process(Request $request, RequestHandler $handler): Response
    {
        $content_type = $request->getHeaderLine('Content-Type');

        if (empty($content_type) || $content_type !== "application/json") {
            throw new HttpBadRequestException($request, "Contenty-Type must be application/json");
        }

        return $handler->handle($request);
    } 

}

?>