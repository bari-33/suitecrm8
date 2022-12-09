<?php

namespace ContainerFbqNV0T;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/*
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getLeadsByStatusCountService extends App_KernelProdContainer
{
    /*
     * Gets the private 'App\Module\Leads\Statistics\Series\LeadsByStatusCount' shared autowired service.
     *
     * @return \App\Module\Leads\Statistics\Series\LeadsByStatusCount
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 3).''.\DIRECTORY_SEPARATOR.'core'.\DIRECTORY_SEPARATOR.'backend'.\DIRECTORY_SEPARATOR.'Statistics'.\DIRECTORY_SEPARATOR.'Service'.\DIRECTORY_SEPARATOR.'StatisticsProviderInterface.php';
        include_once \dirname(__DIR__, 3).''.\DIRECTORY_SEPARATOR.'core'.\DIRECTORY_SEPARATOR.'backend'.\DIRECTORY_SEPARATOR.'Statistics'.\DIRECTORY_SEPARATOR.'StatisticsHandlingTrait.php';
        include_once \dirname(__DIR__, 3).''.\DIRECTORY_SEPARATOR.'core'.\DIRECTORY_SEPARATOR.'modules'.\DIRECTORY_SEPARATOR.'Leads'.\DIRECTORY_SEPARATOR.'Statistics'.\DIRECTORY_SEPARATOR.'Series'.\DIRECTORY_SEPARATOR.'LeadsByStatusCount.php';

        return $container->privates['App\\Module\\Leads\\Statistics\\Series\\LeadsByStatusCount'] = new \App\Module\Leads\Statistics\Series\LeadsByStatusCount(\dirname(__DIR__, 3), (\dirname(__DIR__, 3).'/public/legacy'), 'LEGACYSESSID', 'PHPSESSID', ($container->privates['App\\Engine\\LegacyHandler\\LegacyScopeState'] ?? ($container->privates['App\\Engine\\LegacyHandler\\LegacyScopeState'] = new \App\Engine\LegacyHandler\LegacyScopeState())), ($container->privates['App\\Data\\LegacyHandler\\ListDataQueryHandler'] ?? $container->load('getListDataQueryHandlerService')), ($container->privates['App\\Module\\LegacyHandler\\ModuleNameMapperHandler'] ?? $container->getModuleNameMapperHandlerService()), ($container->services['session'] ?? $container->getSessionService()));
    }
}
