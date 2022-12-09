<?php

namespace ContainerFbqNV0T;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/*
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getApiPlatform_Graphql_Action_EntrypointService extends App_KernelProdContainer
{
    /*
     * Gets the public 'api_platform.graphql.action.entrypoint' shared service.
     *
     * @return \ApiPlatform\Core\GraphQl\Action\EntrypointAction
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 3).''.\DIRECTORY_SEPARATOR.'vendor'.\DIRECTORY_SEPARATOR.'api-platform'.\DIRECTORY_SEPARATOR.'core'.\DIRECTORY_SEPARATOR.'src'.\DIRECTORY_SEPARATOR.'GraphQl'.\DIRECTORY_SEPARATOR.'Action'.\DIRECTORY_SEPARATOR.'EntrypointAction.php';
        include_once \dirname(__DIR__, 3).''.\DIRECTORY_SEPARATOR.'vendor'.\DIRECTORY_SEPARATOR.'api-platform'.\DIRECTORY_SEPARATOR.'core'.\DIRECTORY_SEPARATOR.'src'.\DIRECTORY_SEPARATOR.'GraphQl'.\DIRECTORY_SEPARATOR.'ExecutorInterface.php';
        include_once \dirname(__DIR__, 3).''.\DIRECTORY_SEPARATOR.'vendor'.\DIRECTORY_SEPARATOR.'api-platform'.\DIRECTORY_SEPARATOR.'core'.\DIRECTORY_SEPARATOR.'src'.\DIRECTORY_SEPARATOR.'GraphQl'.\DIRECTORY_SEPARATOR.'Executor.php';
        include_once \dirname(__DIR__, 3).''.\DIRECTORY_SEPARATOR.'vendor'.\DIRECTORY_SEPARATOR.'api-platform'.\DIRECTORY_SEPARATOR.'core'.\DIRECTORY_SEPARATOR.'src'.\DIRECTORY_SEPARATOR.'GraphQl'.\DIRECTORY_SEPARATOR.'Error'.\DIRECTORY_SEPARATOR.'ErrorHandlerInterface.php';
        include_once \dirname(__DIR__, 3).''.\DIRECTORY_SEPARATOR.'vendor'.\DIRECTORY_SEPARATOR.'api-platform'.\DIRECTORY_SEPARATOR.'core'.\DIRECTORY_SEPARATOR.'src'.\DIRECTORY_SEPARATOR.'GraphQl'.\DIRECTORY_SEPARATOR.'Error'.\DIRECTORY_SEPARATOR.'ErrorHandler.php';

        return $container->services['api_platform.graphql.action.entrypoint'] = new \ApiPlatform\Core\GraphQl\Action\EntrypointAction(($container->privates['api_platform.graphql.schema_builder'] ?? $container->load('getApiPlatform_Graphql_SchemaBuilderService')), new \ApiPlatform\Core\GraphQl\Executor(), ($container->services['api_platform.graphql.action.graphiql'] ?? $container->load('getApiPlatform_Graphql_Action_GraphiqlService')), ($container->services['api_platform.graphql.action.graphql_playground'] ?? $container->load('getApiPlatform_Graphql_Action_GraphqlPlaygroundService')), ($container->services['.container.private.serializer'] ?? $container->get_Container_Private_SerializerService()), new \ApiPlatform\Core\GraphQl\Error\ErrorHandler(), false, true, false, 'graphql-playground');
    }
}
