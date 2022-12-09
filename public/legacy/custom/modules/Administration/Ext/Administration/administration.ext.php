<?php 
 //WARNING: The contents of this file are auto-generated

 
$admin_option_defs = array();
$admin_option_defs['Administration']['KanbanView'] = array(
    'Administration',
    'LBL_KANBANVIEW',
    'LBL_KANBANVIEW_DETAIL',
    './index.php?module=ht_kanban_views&action=index',
    'display-modules-and-subpanels'
);
$admin_option_defs['Administration']['KanbanView_Module_ENABLE_DISABLE'] = array(
    'Administration',
    'LBL_KANBANVIEW_MODULE_CONFIGURATION',
    'LBL_KANBANVIEW_MODULE_CONFIGURATION_DETAIL',
    './index.php?module=ht_kanban_views&action=Configuration',
    'system-settings'
);
$admin_option_defs['Administration']['ht_kanban_viewsLicense'] = array(
    'ht_kanban_views',
    'LBL_RECYCLE_BIN_LICENSE_TITLE',
    'LBL_RECYCLE_BIN_LICENSE_DESC',
    './index.php?module=ht_kanban_views&action=license',
    'oauth-keys'
);
$admin_group_header[]= array('LBL_KANBANVIEW','',false,$admin_option_defs, '');

?>