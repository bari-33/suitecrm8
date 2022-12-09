<?php
$module_name = 'ht_kanban_views';
$viewdefs [$module_name] = 
array (
  'DetailView' => 
  array (
    'templateMeta' => 
    array (
      'form' => 
      array (
        'buttons' => 
        array (
          0 => 'EDIT',
          1 => 'DUPLICATE',
          2 => 'DELETE',
          3 => 'FIND_DUPLICATES',
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
          1 => '',
        ),
        3 => 
        array (
          0 => 'assigned_user_name',
          1 => 
          array (
            'name' => 'menu_display',
            'label' => 'LBL_MENU_DISPLAY',
          ),
        ),
        4 => 
        array (
          0 => 'description',
        ),
      ),
      'LBL_CONDITION_LINES' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'condition_lines',
            'label' => 'LBL_CONDITION_LINES',
          ),
        ),
      ),
    ),
  ),
);
?>
