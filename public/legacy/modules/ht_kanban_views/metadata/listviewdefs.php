<?php
$module_name = 'ht_kanban_views';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'FLOW_MODULE' => 
  array (
    'type' => 'enum',
    'label' => 'LBL_MODULE_LIST',
    'width' => '10%',
    /* 'default' => true, */
  ),
  'MODULE_FIELDS' => 
  array (
    'type' => 'enum',
    'default' => true,
    'label' => 'LBL_MODULE_FIELDS',
    'width' => '10%',
  ),
  'STATUS' => 
  array (
    'type' => 'enum',
    'default' => true,
    'label' => 'LBL_STATUS',
    'width' => '10%',
  ),
  'MENU_DISPLAY' => 
  array (
    'type' => 'bool',
    /* 'default' => true, */
    'label' => 'LBL_MENU_DISPLAY',
    'width' => '10%',
  ),
  'ASSIGNED_USER_NAME' => 
  array (
    'width' => '9%',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => true,
  ),
);
;
?>
