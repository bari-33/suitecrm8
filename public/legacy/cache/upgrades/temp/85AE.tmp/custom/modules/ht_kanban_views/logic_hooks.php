<?php
// Do not store anything in this file that is not part of the array or the hook version.  This file will	
// be automatically rebuilt in the future. 
 $hook_version = 1; 
$hook_array = Array(); 
// position, file, function 
$hook_array['after_delete'] = Array(); 
$hook_array['after_delete'][] = Array(78, 'Deletes the Entry from the menu of this deleted record', 'modules/ht_kanban_views/MenuEntry.php','DeleteMenu', 'DeleteRecord'); 
$hook_array['before_save'] = Array(); 
$hook_array['before_save'][] = Array(77, 'Creates the Entry in the menu if displayed is checked', 'modules/ht_kanban_views/MenuEntry.php','DeleteMenu', 'CreateRecord'); 
$hook_array['before_save'][] = Array(79, 'Saving aggregated data', 'modules/ht_kanban_views/ht_kanban_views_logic_hooks.php','ht_kanban_views_logic_hooks', 'SaveAggregatedData');
$hook_array['before_save'][] = Array(80, 'Saving colunms data', 'modules/ht_kanban_views/ht_kanban_views_logic_hooks.php','ht_kanban_views_logic_hooks', 'SaveColunmsData');
$hook_array['process_record'] = Array(); 
$hook_array['process_record'][] = Array(1, 'Show module fields labels.', 'modules/ht_kanban_views/ht_kanban_views_logic_hooks.php','ht_kanban_views_logic_hooks', 'ShowModuleFields');

?>