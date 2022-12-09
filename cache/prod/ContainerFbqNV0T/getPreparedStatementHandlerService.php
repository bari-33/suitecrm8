<?php

namespace ContainerFbqNV0T;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/*
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getPreparedStatementHandlerService extends App_KernelProdContainer
{
    /*
     * Gets the private 'App\Data\LegacyHandler\PreparedStatementHandler' shared autowired service.
     *
     * @return \App\Data\LegacyHandler\PreparedStatementHandler
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 3).''.\DIRECTORY_SEPARATOR.'core'.\DIRECTORY_SEPARATOR.'backend'.\DIRECTORY_SEPARATOR.'Data'.\DIRECTORY_SEPARATOR.'LegacyHandler'.\DIRECTORY_SEPARATOR.'PreparedStatementHandler.php';

        return $container->privates['App\\Data\\LegacyHandler\\PreparedStatementHandler'] = new \App\Data\LegacyHandler\PreparedStatementHandler(($container->services['doctrine.orm.default_entity_manager'] ?? $container->getDoctrine_Orm_DefaultEntityManagerService()));
    }
}