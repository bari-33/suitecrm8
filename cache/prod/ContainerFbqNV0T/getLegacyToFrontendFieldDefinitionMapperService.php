<?php

namespace ContainerFbqNV0T;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/*
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getLegacyToFrontendFieldDefinitionMapperService extends App_KernelProdContainer
{
    /*
     * Gets the private 'App\FieldDefinitions\LegacyHandler\LegacyToFrontendFieldDefinitionMapper' shared autowired service.
     *
     * @return \App\FieldDefinitions\LegacyHandler\LegacyToFrontendFieldDefinitionMapper
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 3).''.\DIRECTORY_SEPARATOR.'core'.\DIRECTORY_SEPARATOR.'backend'.\DIRECTORY_SEPARATOR.'FieldDefinitions'.\DIRECTORY_SEPARATOR.'LegacyHandler'.\DIRECTORY_SEPARATOR.'FieldDefinitionMapperInterface.php';
        include_once \dirname(__DIR__, 3).''.\DIRECTORY_SEPARATOR.'core'.\DIRECTORY_SEPARATOR.'backend'.\DIRECTORY_SEPARATOR.'FieldDefinitions'.\DIRECTORY_SEPARATOR.'LegacyHandler'.\DIRECTORY_SEPARATOR.'LegacyToFrontendFieldDefinitionMapper.php';

        return $container->privates['App\\FieldDefinitions\\LegacyHandler\\LegacyToFrontendFieldDefinitionMapper'] = new \App\FieldDefinitions\LegacyHandler\LegacyToFrontendFieldDefinitionMapper($container->parameters['record.fields.legacy_to_frontend_fields_map']);
    }
}
