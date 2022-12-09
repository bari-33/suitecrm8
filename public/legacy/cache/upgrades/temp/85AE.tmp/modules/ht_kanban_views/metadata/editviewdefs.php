<?php
$module_name = 'ht_kanban_views';
$viewdefs [$module_name] = 
array (
  'EditView' => 
  array (
    'templateMeta' => 
    array (
      'form' =>
      array(
        'hidden' =>
        array(
          0 => '<input type="hidden" id="is_new_record" value="{$IS_NEW}">',
        ),
      ),
      'maxColumns' => '2',
      'widths' => 
      array (
        0 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
        1 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
      ),
      'useTabs' => false,
      'tabDefs' => 
      array (
        'DEFAULT' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_CONDITION_LINES' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
      'syncDetailEditViews' => true,
    ),
    'panels' => 
    array (
      'default' => 
      array (
        0 => 
        array (
          0 => 'name',
          1 => 
          array (
            'name' => 'status',
            'label' => 'LBL_STATUS',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'flow_module',
            'label' => 'LBL_MODULE_LIST',
          ),
          1 => 
          array (
            'name' => 'module_fields',
            'label' => 'LBL_MODULE_FIELDS',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'target_values',
            'label' => 'LBL_TARGET_VALUES',
          ), 
		  1 => 
          array (
            'name' => 'body_fields',
            'label' => 'LBL_BODY_FIELDS',
          ),
        ), 
        3 => 
        array (
          0 => 'assigned_user_name',
          1 => 'menu_display',
        ),
        5 => 
        array (
          0 => 'description',
        ),
      ),
      'LBL_CONDITION_LINES' => 
      array (
        0 => 
        array (
          0 => 'condition_lines',
        ),
      ),
      'LBL_AGGREGATED_FILEDS' => 
      array (
        0 => 
        array (
          0 => 'aggregated_fields',
        ),
      ),
    ),
  ),
);
?>
