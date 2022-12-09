<?php

namespace ContainerFbqNV0T;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/*
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getCsvExportBulkActionService extends App_KernelProdContainer
{
    /*
     * Gets the private 'App\Process\Service\BulkActions\CsvExportBulkAction' shared autowired service.
     *
     * @return \App\Process\Service\BulkActions\CsvExportBulkAction
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 3).''.\DIRECTORY_SEPARATOR.'core'.\DIRECTORY_SEPARATOR.'backend'.\DIRECTORY_SEPARATOR.'Process'.\DIRECTORY_SEPARATOR.'Service'.\DIRECTORY_SEPARATOR.'ProcessHandlerInterface.php';
        include_once \dirname(__DIR__, 3).''.\DIRECTORY_SEPARATOR.'core'.\DIRECTORY_SEPARATOR.'backend'.\DIRECTORY_SEPARATOR.'Process'.\DIRECTORY_SEPARATOR.'Service'.\DIRECTORY_SEPARATOR.'BulkActions'.\DIRECTORY_SEPARATOR.'CsvExportBulkAction.php';

        return $container->privates['App\\Process\\Service\\BulkActions\\CsvExportBulkAction'] = new \App\Process\Service\BulkActions\CsvExportBulkAction(($container->privates['App\\Module\\LegacyHandler\\ModuleNameMapperHandler'] ?? $container->getModuleNameMapperHandlerService()), ($container->privates['App\\Data\\LegacyHandler\\FilterMapper\\LegacyFilterMapper'] ?? $container->load('getLegacyFilterMapperService')));
    }
}
