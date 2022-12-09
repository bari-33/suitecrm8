<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/login' => [[['_route' => 'app_login', '_controller' => 'App\\Authentication\\Controller\\SecurityController::login'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
        '/logout' => [[['_route' => 'app_logout', '_controller' => 'App\\Authentication\\Controller\\SecurityController::logout'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
        '/session-status' => [[['_route' => 'app_session_status', '_controller' => 'App\\Authentication\\Controller\\SecurityController::sessionStatus'], null, ['GET' => 0], null, false, false, null]],
        '/auth/login' => [[['_route' => 'native_auth_login', '_controller' => 'App\\Authentication\\Controller\\SecurityController::nativeAuthLogin'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
        '/auth/logout' => [[['_route' => 'native_auth_logout', '_controller' => 'App\\Authentication\\Controller\\SecurityController::nativeAuthLogout'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
        '/auth/session-status' => [[['_route' => 'native_auth_session_status', '_controller' => 'App\\Authentication\\Controller\\SecurityController::nativeAuthSessionStatus'], null, ['GET' => 0], null, false, false, null]],
        '/' => [[['_route' => 'index', '_controller' => 'App\\Engine\\Controller\\IndexController::index'], null, ['GET' => 0], null, false, false, null]],
        '/auth' => [[['_route' => 'nativeAuth', '_controller' => 'App\\Engine\\Controller\\IndexController::nativeAuth'], null, ['GET' => 0], null, false, false, null]],
        '/logged-out' => [[['_route' => 'logged-out', '_controller' => 'App\\Engine\\Controller\\IndexController::loggedOut'], null, ['GET' => 0], null, false, false, null]],
        '/api/graphql' => [[['_route' => 'api_graphql_entrypoint', '_controller' => 'api_platform.graphql.action.entrypoint', '_graphql' => true], null, null, null, false, false, null]],
        '/api/graphql/graphiql' => [[['_route' => 'api_graphql_graphiql', '_controller' => 'api_platform.graphql.action.graphiql', '_graphql' => true], null, null, null, false, false, null]],
        '/docs/rest' => [[['_route' => 'swagger_ui', '_controller' => 'api_platform.swagger.action.ui'], null, null, null, false, false, null]],
        '/docs/graphql' => [[['_route' => 'graphiql', '_controller' => 'api_platform.graphql.action.graphiql'], null, null, null, false, false, null]],
        '/saml/metadata' => [[['_route' => 'saml_metadata', '_controller' => 'Hslavich\\OneloginSamlBundle\\Controller\\SamlController::metadataAction'], null, null, null, false, false, null]],
        '/saml/acs' => [[['_route' => 'saml_acs', '_controller' => 'Hslavich\\OneloginSamlBundle\\Controller\\SamlController::assertionConsumerServiceAction'], null, null, null, false, false, null]],
        '/saml/login' => [[['_route' => 'saml_login', '_controller' => 'Hslavich\\OneloginSamlBundle\\Controller\\SamlController::loginAction'], null, null, null, false, false, null]],
        '/saml/logout' => [[['_route' => 'saml_logout', '_controller' => 'Hslavich\\OneloginSamlBundle\\Controller\\SamlController::singleLogoutServiceAction'], null, null, null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/api(?'
                    .'|(?:/(index)(?:\\.([^/]++))?)?(*:42)'
                    .'|/(?'
                        .'|docs(?:\\.([^/]++))?(*:72)'
                        .'|contexts/(.+)(?:\\.([^/]++))?(*:107)'
                        .'|record(?'
                            .'|/([^/]++)(*:133)'
                            .'|\\-list/([^/]++)(*:156)'
                        .')'
                        .'|vardef/field\\-definitions(?'
                            .'|(?:\\.([^/]++))?(?'
                                .'|(*:211)'
                            .')'
                            .'|/([^/\\.]++)(?:\\.([^/]++))?(*:246)'
                        .')'
                        .'|app\\-(?'
                            .'|list\\-strings/([^/\\.]++)(?:\\.([^/]++))?(*:302)'
                            .'|strings/([^/\\.]++)(?:\\.([^/]++))?(*:343)'
                            .'|metadata/([^/]++)(*:368)'
                        .')'
                        .'|m(?'
                            .'|od(?'
                                .'|\\-strings/([^/\\.]++)(?:\\.([^/]++))?(*:421)'
                                .'|ule\\-metadata/([^/]++)(*:451)'
                            .')'
                            .'|etadata/view\\-definitions(?'
                                .'|(?:\\.([^/]++))?(*:503)'
                                .'|/([^/\\.]++)(?:\\.([^/]++))?(*:537)'
                            .')'
                        .')'
                        .'|navbars/([^/\\.]++)(?:\\.([^/]++))?(*:580)'
                        .'|processes(?'
                            .'|(?:\\.([^/]++))?(*:615)'
                            .'|/([^/\\.]++)(?:\\.([^/]++))?(?'
                                .'|(*:652)'
                            .')'
                        .')'
                        .'|batched\\-statistics/([^/\\.]++)(?:\\.([^/]++))?(*:707)'
                        .'|s(?'
                            .'|tatistics/([^/\\.]++)(?:\\.([^/]++))?(*:754)'
                            .'|ystem\\-configs(?'
                                .'|(?:\\.([^/]++))?(*:794)'
                                .'|/([^/\\.]++)(?:\\.([^/]++))?(*:828)'
                            .')'
                        .')'
                        .'|theme\\-images/([^/\\.]++)(?:\\.([^/]++))?(*:877)'
                        .'|user(?'
                            .'|\\-preferences(?'
                                .'|(?:\\.([^/]++))?(*:923)'
                                .'|/([^/\\.]++)(?:\\.([^/]++))?(*:957)'
                            .')'
                            .'|s/([^/\\.]++)(?:\\.([^/]++))?(*:993)'
                        .')'
                    .')'
                .')'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        42 => [[['_route' => 'api_entrypoint', '_controller' => 'api_platform.action.entrypoint', '_format' => '', '_api_respond' => 'true', 'index' => 'index'], ['index', '_format'], null, null, false, true, null]],
        72 => [[['_route' => 'api_doc', '_controller' => 'api_platform.action.documentation', '_format' => '', '_api_respond' => 'true'], ['_format'], null, null, false, true, null]],
        107 => [[['_route' => 'api_jsonld_context', '_controller' => 'api_platform.jsonld.action.context', '_format' => 'jsonld', '_api_respond' => 'true'], ['shortName', '_format'], null, null, false, true, null]],
        133 => [[['_route' => 'api_records_get_item', '_controller' => 'api_platform.action.get_item', '_format' => null, '_stateless' => null, '_api_resource_class' => 'App\\Data\\Entity\\Record', '_api_identifiers' => ['id'], '_api_has_composite_identifier' => false, '_api_item_operation_name' => 'get'], ['id'], ['GET' => 0], null, false, true, null]],
        156 => [[['_route' => 'api_record_lists_get_item', '_controller' => 'api_platform.action.get_item', '_format' => null, '_stateless' => null, '_api_resource_class' => 'App\\Data\\Entity\\RecordList', '_api_identifiers' => ['id'], '_api_has_composite_identifier' => false, '_api_item_operation_name' => 'get'], ['id'], ['GET' => 0], null, false, true, null]],
        211 => [
            [['_route' => 'api_field_definitions_get_collection', '_controller' => 'api_platform.action.get_collection', '_format' => null, '_stateless' => null, '_api_resource_class' => 'App\\FieldDefinitions\\Entity\\FieldDefinition', '_api_identifiers' => ['id'], '_api_has_composite_identifier' => false, '_api_collection_operation_name' => 'get'], ['_format'], ['GET' => 0], null, false, true, null],
            [['_route' => 'api_field_definitions_post_collection', '_controller' => 'api_platform.action.post_collection', '_format' => null, '_stateless' => null, '_api_resource_class' => 'App\\FieldDefinitions\\Entity\\FieldDefinition', '_api_identifiers' => ['id'], '_api_has_composite_identifier' => false, '_api_collection_operation_name' => 'post'], ['_format'], ['POST' => 0], null, false, true, null],
        ],
        246 => [[['_route' => 'api_field_definitions_get_item', '_controller' => 'api_platform.action.get_item', '_format' => null, '_stateless' => null, '_api_resource_class' => 'App\\FieldDefinitions\\Entity\\FieldDefinition', '_api_identifiers' => ['id'], '_api_has_composite_identifier' => false, '_api_item_operation_name' => 'get'], ['id', '_format'], ['GET' => 0], null, false, true, null]],
        302 => [[['_route' => 'api_app_list_strings_get_item', '_controller' => 'api_platform.action.get_item', '_format' => null, '_stateless' => null, '_api_resource_class' => 'App\\Languages\\Entity\\AppListStrings', '_api_identifiers' => ['id'], '_api_has_composite_identifier' => false, '_api_item_operation_name' => 'get'], ['id', '_format'], ['GET' => 0], null, false, true, null]],
        343 => [[['_route' => 'api_app_strings_get_item', '_controller' => 'api_platform.action.get_item', '_format' => null, '_stateless' => null, '_api_resource_class' => 'App\\Languages\\Entity\\AppStrings', '_api_identifiers' => ['id'], '_api_has_composite_identifier' => false, '_api_item_operation_name' => 'get'], ['id', '_format'], ['GET' => 0], null, false, true, null]],
        368 => [[['_route' => 'api_app_metadatas_get_item', '_controller' => 'api_platform.action.get_item', '_format' => null, '_stateless' => null, '_api_resource_class' => 'App\\Metadata\\Entity\\AppMetadata', '_api_identifiers' => ['id'], '_api_has_composite_identifier' => false, '_api_item_operation_name' => 'get'], ['id'], ['GET' => 0], null, false, true, null]],
        421 => [[['_route' => 'api_mod_strings_get_item', '_controller' => 'api_platform.action.get_item', '_format' => null, '_stateless' => null, '_api_resource_class' => 'App\\Languages\\Entity\\ModStrings', '_api_identifiers' => ['id'], '_api_has_composite_identifier' => false, '_api_item_operation_name' => 'get'], ['id', '_format'], ['GET' => 0], null, false, true, null]],
        451 => [[['_route' => 'api_module_metadatas_get_item', '_controller' => 'api_platform.action.get_item', '_format' => null, '_stateless' => null, '_api_resource_class' => 'App\\Metadata\\Entity\\ModuleMetadata', '_api_identifiers' => ['id'], '_api_has_composite_identifier' => false, '_api_item_operation_name' => 'get'], ['id'], ['GET' => 0], null, false, true, null]],
        503 => [[['_route' => 'api_view_definitions_get_collection', '_controller' => 'api_platform.action.get_collection', '_format' => null, '_stateless' => null, '_api_resource_class' => 'App\\ViewDefinitions\\Entity\\ViewDefinition', '_api_identifiers' => ['id'], '_api_has_composite_identifier' => false, '_api_collection_operation_name' => 'get'], ['_format'], ['GET' => 0], null, false, true, null]],
        537 => [[['_route' => 'api_view_definitions_get_item', '_controller' => 'api_platform.action.get_item', '_format' => null, '_stateless' => null, '_api_resource_class' => 'App\\ViewDefinitions\\Entity\\ViewDefinition', '_api_identifiers' => ['id'], '_api_has_composite_identifier' => false, '_api_item_operation_name' => 'get'], ['id', '_format'], ['GET' => 0], null, false, true, null]],
        580 => [[['_route' => 'api_navbars_get_item', '_controller' => 'api_platform.action.get_item', '_format' => null, '_stateless' => null, '_api_resource_class' => 'App\\Navbar\\Entity\\Navbar', '_api_identifiers' => ['userID'], '_api_has_composite_identifier' => false, '_api_item_operation_name' => 'get'], ['userID', '_format'], ['GET' => 0], null, false, true, null]],
        615 => [[['_route' => 'api_processes_get_collection', '_controller' => 'api_platform.action.get_collection', '_format' => null, '_stateless' => null, '_api_resource_class' => 'App\\Process\\Entity\\Process', '_api_identifiers' => ['id'], '_api_has_composite_identifier' => false, '_api_collection_operation_name' => 'get'], ['_format'], ['GET' => 0], null, false, true, null]],
        652 => [
            [['_route' => 'api_processes_get_item', '_controller' => 'api_platform.action.get_item', '_format' => null, '_stateless' => null, '_api_resource_class' => 'App\\Process\\Entity\\Process', '_api_identifiers' => ['id'], '_api_has_composite_identifier' => false, '_api_item_operation_name' => 'get'], ['id', '_format'], ['GET' => 0], null, false, true, null],
            [['_route' => 'api_processes_put_item', '_controller' => 'api_platform.action.put_item', '_format' => null, '_stateless' => null, '_api_resource_class' => 'App\\Process\\Entity\\Process', '_api_identifiers' => ['id'], '_api_has_composite_identifier' => false, '_api_item_operation_name' => 'put'], ['id', '_format'], ['PUT' => 0], null, false, true, null],
        ],
        707 => [[['_route' => 'api_batched_statistics_get_item', '_controller' => 'api_platform.action.get_item', '_format' => null, '_stateless' => null, '_api_resource_class' => 'App\\Statistics\\Entity\\BatchedStatistics', '_api_identifiers' => ['id'], '_api_has_composite_identifier' => false, '_api_item_operation_name' => 'get'], ['id', '_format'], ['GET' => 0], null, false, true, null]],
        754 => [[['_route' => 'api_statistics_get_item', '_controller' => 'api_platform.action.get_item', '_format' => null, '_stateless' => null, '_api_resource_class' => 'App\\Statistics\\Entity\\Statistic', '_api_identifiers' => ['id'], '_api_has_composite_identifier' => false, '_api_item_operation_name' => 'get'], ['id', '_format'], ['GET' => 0], null, false, true, null]],
        794 => [[['_route' => 'api_system_configs_get_collection', '_controller' => 'api_platform.action.get_collection', '_format' => null, '_stateless' => null, '_api_resource_class' => 'App\\SystemConfig\\Entity\\SystemConfig', '_api_identifiers' => ['id'], '_api_has_composite_identifier' => false, '_api_collection_operation_name' => 'get'], ['_format'], ['GET' => 0], null, false, true, null]],
        828 => [[['_route' => 'api_system_configs_get_item', '_controller' => 'api_platform.action.get_item', '_format' => null, '_stateless' => null, '_api_resource_class' => 'App\\SystemConfig\\Entity\\SystemConfig', '_api_identifiers' => ['id'], '_api_has_composite_identifier' => false, '_api_item_operation_name' => 'get'], ['id', '_format'], ['GET' => 0], null, false, true, null]],
        877 => [[['_route' => 'api_theme_images_get_item', '_controller' => 'api_platform.action.get_item', '_format' => null, '_stateless' => null, '_api_resource_class' => 'App\\Themes\\Entity\\ThemeImages', '_api_identifiers' => ['id'], '_api_has_composite_identifier' => false, '_api_item_operation_name' => 'get'], ['id', '_format'], ['GET' => 0], null, false, true, null]],
        923 => [[['_route' => 'api_user_preferences_get_collection', '_controller' => 'api_platform.action.get_collection', '_format' => null, '_stateless' => null, '_api_resource_class' => 'App\\UserPreferences\\Entity\\UserPreference', '_api_identifiers' => ['id'], '_api_has_composite_identifier' => false, '_api_collection_operation_name' => 'get'], ['_format'], ['GET' => 0], null, false, true, null]],
        957 => [[['_route' => 'api_user_preferences_get_item', '_controller' => 'api_platform.action.get_item', '_format' => null, '_stateless' => null, '_api_resource_class' => 'App\\UserPreferences\\Entity\\UserPreference', '_api_identifiers' => ['id'], '_api_has_composite_identifier' => false, '_api_item_operation_name' => 'get'], ['id', '_format'], ['GET' => 0], null, false, true, null]],
        993 => [
            [['_route' => 'api_users_get_item', '_controller' => 'api_platform.action.get_item', '_format' => null, '_stateless' => null, '_api_resource_class' => 'App\\Module\\Users\\Entity\\User', '_api_identifiers' => ['id'], '_api_has_composite_identifier' => false, '_api_item_operation_name' => 'get'], ['id', '_format'], ['GET' => 0], null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
