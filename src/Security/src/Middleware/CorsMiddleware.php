<?php
declare(strict_types=1);

namespace Security\Middleware;

use Illuminate\Support\Str;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CorsMiddleware implements MiddlewareInterface
{
    protected $origins = [
        'null',
    ];

    public function process(ServerRequestInterface $request, RequestHandlerInterface $delegate): ResponseInterface
    {
        $response = $delegate->handle($request);
        $origin = $request->getServerParams()['HTTP_ORIGIN'] ?? null;

        if ($origin && Str::endsWith($origin, $this->origins)) {
            $response = $response->withAddedHeader(
                'Access-Control-Allow-Origin',
                $origin
            )->withAddedHeader(
                'Access-Control-Allow-Credentials',
                'true'
            )->withAddedHeader(
                'Access-Control-Allow-Headers',
                'Content-Type, Accept, Authorization, Widget-Auth-Token'
            )->withAddedHeader(
                'Access-Control-Allow-Methods',
                'POST, GET, PATCH, DELETE, OPTION',
            );
        }

        return $response;
    }
}
