<?php
declare(strict_types=1);

namespace System\Middleware;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\ProblemDetails\Exception\ProblemDetailsExceptionInterface;
use Zend\ProblemDetails\ProblemDetailsMiddleware;
use Zend\ProblemDetails\ProblemDetailsResponseFactory;

class ProblemDetailsMiddlewareFactory
{
    /**
     * @param ContainerInterface $container
     * @return ProblemDetailsMiddleware
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ProblemDetailsMiddleware
    {
        $middleware = new ProblemDetailsMiddleware($container->get(ProblemDetailsResponseFactory::class));

        $middleware->attachListener($this->getErrorListener($container));

        return $middleware;
    }

    /**
     * @param ContainerInterface $container
     * @return \Closure
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function getErrorListener(ContainerInterface $container)
    {
//        /** @var Logger $logger */
//        $logger = $container->get('errorChannel');
//        $handlers = $logger->getHandlers();
//        foreach ($handlers as &$handler) {
//            if ($handler instanceof NativeMailerHandler) {
//                $handler->setContentType('text/html');
//            }
//        }
//        unset($handler);
//        $logger->setHandlers($handlers);

        return function (
            \Throwable $error,
            ServerRequestInterface $request,
            ResponseInterface $response
        ) {
            $context = [
                'error' => [
                    'code' => $error->getCode(),
                    'file' => $error->getFile(),
                    'line' => $error->getLine(),
                ],
            ];

            // если была предыдущая ошибка то ее тоже добавляем
            $previous = $error->getPrevious();
            if (!empty($previous)) {
                $context['error']['previous'] = [
                    'message' => $previous->getMessage(),
                    'code'    => $previous->getCode(),
                    'file'    => $previous->getFile(),
                    'line'    => $previous->getLine(),
                ];
            }

            // TODO сделать логирование

//            if ($error instanceof \Error) {
//                $logger->error($error->getMessage(), $context);
//            } elseif (!$error instanceof ProblemDetailsExceptionInterface) {
//                if ($error instanceof \Exception) {
//                    $logger->critical($error->getMessage(), $context);
//                } elseif ($error instanceof \Throwable) {
//                    $logger->warning($error->getMessage(), $context);
//                }
//            }
        };
    }
}
