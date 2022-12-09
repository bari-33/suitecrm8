<?php

namespace ContainerFbqNV0T;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/*
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getRecordListResolverService extends App_KernelProdContainer
{
    /*
     * Gets the private 'App\Data\Resolver\RecordListResolver' shared autowired service.
     *
     * @return \App\Data\Resolver\RecordListResolver
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 3).''.\DIRECTORY_SEPARATOR.'vendor'.\DIRECTORY_SEPARATOR.'api-platform'.\DIRECTORY_SEPARATOR.'core'.\DIRECTORY_SEPARATOR.'src'.\DIRECTORY_SEPARATOR.'GraphQl'.\DIRECTORY_SEPARATOR.'Resolver'.\DIRECTORY_SEPARATOR.'QueryItemResolverInterface.php';
        include_once \dirname(__DIR__, 3).''.\DIRECTORY_SEPARATOR.'core'.\DIRECTORY_SEPARATOR.'backend'.\DIRECTORY_SEPARATOR.'Data'.\DIRECTORY_SEPARATOR.'Resolver'.\DIRECTORY_SEPARATOR.'RecordListResolver.php';

        return $container->privates['App\\Data\\Resolver\\RecordListResolver'] = new \App\Data\Resolver\RecordListResolver(($container->privates['App\\Data\\LegacyHandler\\RecordListHandler'] ?? $container->load('getRecordListHandlerService')));
    }
}