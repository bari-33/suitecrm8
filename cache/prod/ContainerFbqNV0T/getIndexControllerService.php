<?php

namespace ContainerFbqNV0T;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/*
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getIndexControllerService extends App_KernelProdContainer
{
    /*
     * Gets the public 'App\Engine\Controller\IndexController' shared autowired service.
     *
     * @return \App\Engine\Controller\IndexController
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 3).''.\DIRECTORY_SEPARATOR.'vendor'.\DIRECTORY_SEPARATOR.'symfony'.\DIRECTORY_SEPARATOR.'framework-bundle'.\DIRECTORY_SEPARATOR.'Controller'.\DIRECTORY_SEPARATOR.'AbstractController.php';
        include_once \dirname(__DIR__, 3).''.\DIRECTORY_SEPARATOR.'core'.\DIRECTORY_SEPARATOR.'backend'.\DIRECTORY_SEPARATOR.'Engine'.\DIRECTORY_SEPARATOR.'Controller'.\DIRECTORY_SEPARATOR.'IndexController.php';

        $container->services['App\\Engine\\Controller\\IndexController'] = $instance = new \App\Engine\Controller\IndexController(\dirname(__DIR__, 3), ($container->privates['App\\Authentication\\LegacyHandler\\UserHandler'] ?? $container->getUserHandlerService()), ($container->privates['App\\Authentication\\LegacyHandler\\Authentication'] ?? $container->getAuthenticationService()));

        $instance->setContainer(($container->privates['.service_locator.v1dRr5f'] ?? $container->load('get_ServiceLocator_V1dRr5fService'))->withContext('App\\Engine\\Controller\\IndexController', $container));

        return $instance;
    }
}
