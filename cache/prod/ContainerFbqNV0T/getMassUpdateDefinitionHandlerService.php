<?php

namespace ContainerFbqNV0T;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/*
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getMassUpdateDefinitionHandlerService extends App_KernelProdContainer
{
    /*
     * Gets the private 'App\ViewDefinitions\LegacyHandler\MassUpdateDefinitionHandler' shared autowired service.
     *
     * @return \App\ViewDefinitions\LegacyHandler\MassUpdateDefinitionHandler
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 3).''.\DIRECTORY_SEPARATOR.'core'.\DIRECTORY_SEPARATOR.'backend'.\DIRECTORY_SEPARATOR.'ViewDefinitions'.\DIRECTORY_SEPARATOR.'Service'.\DIRECTORY_SEPARATOR.'MassUpdateDefinitionProviderInterface.php';
        include_once \dirname(__DIR__, 3).''.\DIRECTORY_SEPARATOR.'core'.\DIRECTORY_SEPARATOR.'backend'.\DIRECTORY_SEPARATOR.'ViewDefinitions'.\DIRECTORY_SEPARATOR.'LegacyHandler'.\DIRECTORY_SEPARATOR.'FieldDefinitionsInjectorTrait.php';
        include_once \dirname(__DIR__, 3).''.\DIRECTORY_SEPARATOR.'core'.\DIRECTORY_SEPARATOR.'backend'.\DIRECTORY_SEPARATOR.'ViewDefinitions'.\DIRECTORY_SEPARATOR.'Service'.\DIRECTORY_SEPARATOR.'MassUpdate'.\DIRECTORY_SEPARATOR.'MassUpdateFieldDefinitionsInjectorTrait.php';
        include_once \dirname(__DIR__, 3).''.\DIRECTORY_SEPARATOR.'core'.\DIRECTORY_SEPARATOR.'backend'.\DIRECTORY_SEPARATOR.'ViewDefinitions'.\DIRECTORY_SEPARATOR.'LegacyHandler'.\DIRECTORY_SEPARATOR.'MassUpdateDefinitionHandler.php';
        include_once \dirname(__DIR__, 3).''.\DIRECTORY_SEPARATOR.'core'.\DIRECTORY_SEPARATOR.'backend'.\DIRECTORY_SEPARATOR.'Module'.\DIRECTORY_SEPARATOR.'Service'.\DIRECTORY_SEPARATOR.'ModuleAwareRegistry.php';
        include_once \dirname(__DIR__, 3).''.\DIRECTORY_SEPARATOR.'core'.\DIRECTORY_SEPARATOR.'backend'.\DIRECTORY_SEPARATOR.'ViewDefinitions'.\DIRECTORY_SEPARATOR.'Service'.\DIRECTORY_SEPARATOR.'MassUpdateDefinitionMappers.php';

        return $container->privates['App\\ViewDefinitions\\LegacyHandler\\MassUpdateDefinitionHandler'] = new \App\ViewDefinitions\LegacyHandler\MassUpdateDefinitionHandler(($container->privates['App\\FieldDefinitions\\LegacyHandler\\FieldDefinitionsHandler'] ?? $container->load('getFieldDefinitionsHandlerService')), new \App\ViewDefinitions\Service\MassUpdateDefinitionMappers(new RewindableGenerator(function () use ($container) {
            yield 0 => ($container->privates['App\\Module\\Accounts\\Service\\MassUpdate\\AccountsEmailOptoutMapper'] ?? $container->load('getAccountsEmailOptoutMapperService'));
            yield 1 => ($container->privates['App\\Module\\Contacts\\Service\\MassUpdate\\ContactSyncMapper'] ?? $container->load('getContactSyncMapperService'));
            yield 2 => ($container->privates['App\\Module\\Contacts\\Service\\MassUpdate\\ContactsEmailOptoutMapper'] ?? $container->load('getContactsEmailOptoutMapperService'));
            yield 3 => ($container->privates['App\\Module\\Leads\\Service\\MassUpdate\\LeadsEmailOptoutMapper'] ?? $container->load('getLeadsEmailOptoutMapperService'));
            yield 4 => ($container->privates['App\\Module\\Prospects\\Service\\MassUpdate\\ProspectsEmailOptoutMapper'] ?? $container->load('getProspectsEmailOptoutMapperService'));
        }, 5)), ($container->privates['App\\ViewDefinitions\\Service\\FieldAliasMapper'] ?? $container->load('getFieldAliasMapperService')), $container->parameters['massupdate']);
    }
}