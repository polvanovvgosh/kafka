<?php

declare(strict_types=1);

namespace Api\Test\Feature;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Stream;
use Laminas\Diactoros\Uri;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;

class WebTestCase extends TestCase
{
    protected function get(string $uri): ResponseInterface
    {
        return $this->method($uri, 'GET');
    }

    protected function post(string $uri, array $params = []): ResponseInterface
    {
        return $this->method($uri, 'POST', $params);
    }

    protected function method(string $uri, $method, array $params = []): ResponseInterface
    {
        $body = new Stream('php://temp', 'r+');
        $body->write(json_encode($params));
        $body->rewind();

        return $this->request(
            (new ServerRequest())
                ->withHeader('Content-Type', 'application/json')
                ->withHeader('Accept', 'application/json')
                ->withUri(new Uri('http://test' . $uri))
                ->withMethod($method)
                ->withBody($body)
        );
    }

    protected function request(ServerRequestInterface $request): ResponseInterface
    {
        $response = $this->app()->process($request, new Response());
        $response->getBody()->rewind();
        return $response;
    }

    private function app(): App
    {
        $container = $this->container();
        $app = new App($container);
        (require 'config/routes.php')($app, $container);
        return $app;
    }

    protected function loadFixtures(array $fixtures): void
    {
        $container = $this->container();
        $em = $container->get(EntityManagerInterface::class);
        $loader = new Loader();

        foreach ($fixtures as $class) {
            if ($container->has($class)) {
                $fixture = $container->get($class);
            } else {
                $fixture = new $class;
            }
            $loader->addFixture($fixture);
        }

        $executor = new ORMExecutor($em, new ORMPurger($em));
        $executor->execute($loader->getFixtures());
    }

    private function container(): ContainerInterface
    {
        return require 'config/container.php';
    }
}