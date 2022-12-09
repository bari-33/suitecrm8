<?php
	function getHtModuleList(){    
		global $app_list_strings, $mod_strings, $sugar_config;
		$module_option_list = array();
		$flipped = array_flip($app_list_strings['moduleList']);
		ksort($flipped);
		$app_list_strings['moduleList'] = array_flip($flipped);
		foreach($app_list_strings['moduleList'] as $module=>$label){
			if(in_array($module, $sugar_config['addKanbanBannedModules'])){
				$module_option_list[$module] = $label;
			}
		}
		return $module_option_list;
	}