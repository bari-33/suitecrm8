<?php
class DeleteMenu{
	
	function DeleteRecord($bean, $event, $arguments){
		global $db, $log, $sugar_config, $current_user, $app_list_strings;
		if($current_user->is_admin){
			$module = $bean->flow_module;
			$dir = "custom/Extension/modules/{$module}/Ext/Menus/{$bean->id}.php";
			if ( file_exists($dir) ) {
				unlink($dir);
			}
			require_once("modules/Administration/QuickRepairAndRebuild.php");
			$rac = new RepairAndClear();
			$rac->repairAndClearAll(array('clearAll'),array(translate('LBL_ALL_MODULES')), true,false);
		}
		
	}
	
	
	function CreateRecord($bean, $event, $arguments){
		global $db, $log, $sugar_config, $current_user, $app_list_strings;
		if($current_user->is_admin){
			$module = $bean->flow_module;
			$dir = "custom/Extension/modules/{$module}/Ext/Menus/";
			if($bean->menu_display){
				/* $dir = "custom/Extension/modules/{$module}/Ext/Menus"; */
				$data = '';
				if ( !file_exists($dir) ) {
				 mkdir ($dir, 0775, true);

				}
				$data = '<?php'.PHP_EOL;
				$file_write_data = $data. '$module_menu[]=array("index.php?module=ht_kanban_views&action=DetailView&record='.$bean->id .'", "'. $bean->name .'", "View", "'. $module .'");';
				file_put_contents ($dir.'/'.$bean->id .'.php', $file_write_data);
				
			}else{
				/* echo $dir.$bean->id .'.php';die; */
				if ( file_exists($dir.$bean->id .'.php') ) {
					unlink($dir.$bean->id .'.php');
				}
			}
			if(!empty($bean->fetched_row['id']) && $bean->fetched_row['flow_module'] != $bean->flow_module){
				$module = $bean->fetched_row['flow_module'];
				$dir = "custom/Extension/modules/{$module}/Ext/Menus/";
				// deleting the entry from menu for previous module if the module value has changed
				if ( file_exists($dir.$bean->id .'.php') ) {
					unlink($dir.$bean->id .'.php');
				}
			}
			
			require_once("modules/Administration/QuickRepairAndRebuild.php");
			$rac = new RepairAndClear();
			$rac->repairAndClearAll(array('clearAll'),array(translate('LBL_ALL_MODULES')), true,false);
		}
	}
}