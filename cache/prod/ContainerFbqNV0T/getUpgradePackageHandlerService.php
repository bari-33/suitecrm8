<?php

namespace ContainerFbqNV0T;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/*
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getUpgradePackageHandlerService extends App_KernelProdContainer
{
    /*
     * Gets the private 'App\Install\Service\Upgrade\UpgradePackageHandler' shared autowired service.
     *
     * @return \App\Install\Service\Upgrade\UpgradePackageHandler
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 3).''.\DIRECTORY_SEPARATOR.'core'.\DIRECTORY_SEPARATOR.'backend'.\DIRECTORY_SEPARATOR.'Install'.\DIRECTORY_SEPARATOR.'Service'.\DIRECTORY_SEPARATOR.'Package'.\DIRECTORY_SEPARATOR.'PackageHandler.php';
        include_once \dirname(__DIR__, 3).''.\DIRECTORY_SEPARATOR.'core'.\DIRECTORY_SEPARATOR.'backend'.\DIRECTORY_SEPARATOR.'Install'.\DIRECTORY_SEPARATOR.'Service'.\DIRECTORY_SEPARATOR.'Upgrade'.\DIRECTORY_SEPARATOR.'UpgradePackageHandler.php';
        include_once \dirname(__DIR__, 3).''.\DIRECTORY_SEPARATOR.'core'.\DIRECTORY_SEPARATOR.'backend'.\DIRECTORY_SEPARATOR.'Engine'.\DIRECTORY_SEPARATOR.'Service'.\DIRECTORY_SEPARATOR.'FolderSync'.\DIRECTORY_SEPARATOR.'FolderSync.php';
        include_once \dirname(__DIR__, 3).''.\DIRECTORY_SEPARATOR.'core'.\DIRECTORY_SEPARATOR.'backend'.\DIRECTORY_SEPARATOR.'Engine'.\DIRECTORY_SEPARATOR.'Service'.\DIRECTORY_SEPARATOR.'FolderSync'.\DIRECTORY_SEPARATOR.'FolderComparatorInterface.php';
        include_once \dirname(__DIR__, 3).''.\DIRECTORY_SEPARATOR.'core'.\DIRECTORY_SEPARATOR.'backend'.\DIRECTORY_SEPARATOR.'Engine'.\DIRECTORY_SEPARATOR.'Service'.\DIRECTORY_SEPARATOR.'FolderSync'.\DIRECTORY_SEPARATOR.'FolderComparator.php';

        return $container->privates['App\\Install\\Service\\Upgrade\\UpgradePackageHandler'] = new \App\Install\Service\Upgrade\UpgradePackageHandler(\dirname(__DIR__, 3), (\dirname(__DIR__, 3).'/tmp/package/upgrade'), (\dirname(__DIR__, 3).'/public/legacy'), new \App\Engine\Service\FolderSync\FolderSync(), new \App\Engine\Service\FolderSync\FolderComparator(), ($container->services['monolog.logger.upgrade'] ?? $container->load('getMonolog_Logger_UpgradeService')), $container->parameters['upgrades']);
    }
}
