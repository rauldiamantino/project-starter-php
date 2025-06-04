<?php

namespace Core\Library;

use DI\Container as DIContainer;
use DI\ContainerBuilder;

use function DI\autowire;

class Container
{
    public readonly DIContainer $container;
    private array $services;

    public function build(array $services = [])
    {
        $this->load($services);
        $this->services[] = dirname(__FILE__, 2) . '/Services/services.php';
        $container = new ContainerBuilder();
        $container->addDefinitions(...$this->services);

        return $container->build();
    }

    public function bind(string $interface, string $class)
    {
        $this->services[] = [$interface => autowire($class)];
    }

    private function load(array $services)
    {
        $default = dirname(__FILE__, 2) . '/Services/services.php';
        $this->services[] = $default;

        if ($services) {
            foreach ($services as $service) {
                $this->services[] = dirname(__FILE__, 3) . '/App/Services/' . $service . '.php';
            }
        }
    }
}
