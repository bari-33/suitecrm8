<?php

namespace ContainerFbqNV0T;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/*
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getLegacySessionLogoutHandlerService extends App_KernelProdContainer
{
    /*
     * Gets the private 'App\Security\LegacySessionLogoutHandler' shared autowired service.
     *
     * @return \App\Security\LegacySessionLogoutHandler
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 3).''.\DIRECTORY_SEPARATOR.'core'.\DIRECTORY_SEPARATOR.'backend'.\DIRECTORY_SEPARATOR.'Security'.\DIRECTORY_SEPARATOR.'LegacySessionLogoutHandler.php';

        return $container->privates['App\\Security\\LegacySessionLogoutHandler'] = new \App\Security\LegacySessionLogoutHandler(($container->privates['App\\Authentication\\LegacyHandler\\Authentication'] ?? $container->getAuthenticationService()));
    }
}
