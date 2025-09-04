<?php

declare(strict_types=1);

namespace UseCases;

use Illuminate\Log\Logger;
use Illuminate\Support\Arr;
use Task\Services\TaskService;
use Ticket\Services\TicketService;
use Illuminate\Foundation\Application;
use Task\Contracts\Services\TaskServiceInterface;
use Ticket\Contracts\Services\TicketServiceInterface;

class DomainServiceFactory
{
    protected array $bindings = [
        TicketServiceInterface::class => TicketService::class,
    ];

    /**
     * DomainServiceFactory constructor.
     *
     * @param Application $app
     * @param Logger $logger
     */
    public function __construct(
        private readonly Application $app,
        private readonly Logger $logger,
        private readonly Arr $arr,
    ) {
    }

    /**
     * @template T
     *
     * @param class-string<T> $interface
     *
     * @return T
     */
    public function create(string $interface, ?array $params = [])
    {
        $serviceClass = $this->arr->get($this->bindings, $interface);

        try {
            return $this->app->make($serviceClass, $params);
        } catch (\Throwable $throwable) {
            $this->logger->error($throwable->getMessage());

            throw new DomainServiceException($interface, $params, $throwable);
        }
    }
}