<?php
/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2017 SalesAgility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for  technical reasons, the Appropriate Legal Notices must
 * display the words  "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 */


class ht_kanban_views extends Basic{
    public $new_schema = true;
    public $module_dir = 'ht_kanban_views';
    public $object_name = 'ht_kanban_views';
    public $table_name = 'ht_kanban_views';
    public $importable = false;

    public $id;
    public $name;
    public $date_entered;
    public $date_modified;
    public $modified_user_id;
    public $modified_by_name;
    public $created_by;
    public $created_by_name;
    public $description;
    public $deleted;
    public $created_by_link;
    public $modified_user_link;
    public $assigned_user_id;
    public $assigned_user_name;
    public $assigned_user_link;
    public $SecurityGroups;
	
    public function bean_implements($interface){
        switch($interface)
        {
            case 'ACL':
                return true;
        }

        return false;
    }
	function getEnumFields($module_name){
		if(!empty($module_name)){
			$bean = BeanFactory::newBean($module_name);
			$fields = array();
            $file = "custom/modules/".$module_name."/metadata/editviewdefs.php";
            $file_main = "modules/".$module_name."/metadata/editviewdefs.php";

			if(file_exists($file)){
				require_once $file;
			}elseif(file_exists($file_main)){
                require_once $file_main;
            }
			foreach ($viewdefs [$module_name]['EditView']['panels'] as $key => $p) {
				foreach ($p as $row => $rowDef) {

					foreach ($rowDef as $col => $colDef) {
						$panel[] = is_array($colDef)
							? $colDef['name']
							: $colDef;
					}
				}
			}
			foreach($bean->field_name_map as $field_name => $field_data){
				if($field_data['type'] == 'enum' && in_array($field_name, $panel) && ($field_data['source']!='non-db')){
					$label = translate($field_data['vname'], $module_name);
					$fields[$field_name] = trim($label, ':');
				}
			}
			return $fields;
		}
	}
	function save($check_notify = false){
        if (empty($this->id) || (isset($_POST['duplicateSave']) && $_POST['duplicateSave'] == 'true')) {
            unset($_POST['aow_conditions_id']);
        }

        parent::save($check_notify);

        require_once('modules/AOW_Conditions/AOW_Condition.php');
        $condition = new AOW_Condition();
        $temp_post = $_POST;
        unset($temp_post['aggregated_fields_deleted']);
        unset($temp_post['aggregated_fields_id']);
        unset($temp_post['aggregated_fields_functions']);
        unset($temp_post['aggregated_fields_fields']);
        $condition->save_lines($temp_post, $this, 'aow_conditions_');
    }
	
 function getValues($module, $selected_field){
        global $app_list_strings;
        $targetBean = BeanFactory::getBean($module);

        if (!$targetBean) {
            return array();
        }

        $field = $targetBean->getFieldDefinition($selected_field);

        $options = array(
            '' => '',
        );
        
        if (empty($field['options']) || empty($module)) {
            return array();
        }
         if (strpos($field['options'], 'moduleList') !== false) {
             $arr = $app_list_strings['moduleList'];
             asort($arr);
             return $arr;;
          }
        foreach (translate($field['options'], $module) as $key => $label) {
            $options[$key] = $label;
        }

        return array_unique($options);
    }
    function getFields($module, $fieldName){
		global $app_list_strings, $mod_strings, $sugar_config;
        $enabledTypeForOrder = array(
            'name',
            'fullname',
            'relate',
        );
        $targetBean = BeanFactory::getBean($module);
        if (!$targetBean) {
            return array();
        }

        $fields = $targetBean->getFieldDefinitions();

        $options = $fieldName != 'order_fields' ? array() : array('' => '');

        foreach ($fields as $field) {
            if (empty($field['vname'])) {
                continue;
            }

            if ($field['type'] == 'link' || $field['type'] == 'email') {
                continue;
            }

            if ($fieldName == 'order_fields' && !empty($field['source'])) {
                if ($field['source'] == 'non-db' && !in_array($field['type'], $enabledTypeForOrder)) {
                    continue;
                }
            }

            $label = rtrim(translate($field['vname'], $targetBean->module_name), ': ');

            if ($field['vname'] == $label) {
                continue;
            }

            if (($field['type'] == 'id' || (isset($field['dbType']) && $field['dbType'] == 'id')) && stripos($label, 'ID') === false) {
                $label .= ' (ID)';
            }
			$app_list_strings['selected_module_fields_dom'][$field['name']] = $label;
            $options[$field['name']] = $label;
        }

        asort($options);
        return $options;
    }
	function getCalculationFields($module, $fieldName){
		global $app_list_strings, $mod_strings, $sugar_config;
        $targetBean = BeanFactory::getBean($module);
        if (!$targetBean) {
            return array();
        }
        $fields = $targetBean->getFieldDefinitions();
        $options = $fieldName != 'order_fields' ? array() : array('' => '');
        foreach ($fields as $field) {
            if ($field['vname'] == $label) {
                continue;
            }
            $label = rtrim(translate($field['vname'], $targetBean->module_name), ': ');
            if ((in_array($field['type'], array('int', 'float','number', 'currency')) || in_array($field['dbType'], array('int', 'float','number', 'currency'))) && ($field['source']!='non-db')){
                $app_list_strings['get_module_calculation_fields'][$field['name']] = $label;
                $options[$field['name']] = $label;
            } 
        }
        asort($options);
        return $options;
    }
	function kanabnBannedModules(){
        $bannedModules=array();
        if (!empty($GLOBALS['sugar_config']['addKanbanBannedModules'])) {
            $bannedModules = array_merge($bannedModules, $GLOBALS['sugar_config']['addKanbanBannedModules']);
        }
        if (!empty($GLOBALS['sugar_config']['overrideAjaxKanbanModules'])) {
            $bannedModules = $GLOBALS['sugar_config']['overrideAjaxKanbanModules'];
        }
        return $bannedModules;
    }
}
