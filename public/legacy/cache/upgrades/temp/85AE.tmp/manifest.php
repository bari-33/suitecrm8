<?php
$manifest = array(
    array(
        'acceptable_sugar_versions' => array(),
    ),
    array(
        'acceptable_sugar_flavors' => array(
            'CE',
            'PRO',
            'ENT',
        ),
    ),
    'readme' => '',
    'key' => '',
    'author' => 'Helfertech',
    'description' => 'Kanban View for all modules',
    'icon' => '',
    'is_uninstallable' => true,
    'name' => 'HT_KanbanProOneTime',
    'published_date' => '2021-10-29 00:00:00',
    'type' => 'module',
    'version' => '4.0',
    'remove_tables' => 'prompt',
);

$installdefs = array(
	'id' => 'HtKanbanViewv',
    'copy' => array(
        array(
            'from' => '<basepath>/custom/Extension/application/Ext/Include',
            'to' => 'custom/Extension/application/Ext/Include',
        ),
		array(
            'from' => '<basepath>/custom/Extension/application/Ext/Language/en_us.Kanban_Views.php',
            'to' => 'custom/Extension/application/Ext/Language/en_us.Kanban_Views.php',
        ),
		array(
            'from' => '<basepath>/custom/Extension/application/Ext/Utils/custom_get_module_list.php',
            'to' => 'custom/Extension/application/Ext/Utils/custom_get_module_list.php',
        ),
		array(
            'from' => '<basepath>/custom/Extension/modules/Administration/Ext/Administration/kanban_view_setup.php',
            'to' => 'custom/Extension/modules/Administration/Ext/Administration/kanban_view_setup.php',
        ),
		array(
            'from' => '<basepath>/custom/Extension/modules/Administration/Ext/Language/en_us.kanban_view_setup.php',
            'to' => 'custom/Extension/modules/Administration/Ext/Language/en_us.kanban_view_setup.php',
        ),
		array(
            'from' => '<basepath>/custom/modules/ht_kanban_views/metadata/listviewdefs.php',
            'to' => 'custom/modules/ht_kanban_views/metadata/listviewdefs.php',
        ),
		array(
            'from' => '<basepath>/custom/modules/ht_kanban_views/logic_hooks.php',
            'to' => 'custom/modules/ht_kanban_views/logic_hooks.php',
        ),
		array(
            'from' => '<basepath>/custom/themes/default/images',
            'to' => 'custom/themes/default/images',
        ),
		array(
            'from' => '<basepath>/modules/ht_kanban_views',
            'to' => 'modules/ht_kanban_views',
        ),
    ),
);