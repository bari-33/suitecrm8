<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
class ht_kanban_views_logic_hooks{
    function SaveAggregatedData($bean, $event, $arguments){
        $aggregate_json = array();
        $aggregated_fileds = $_REQUEST['aggregated_fields_id'];
        $i=0;
        foreach($aggregated_fileds as $key=>$val){
            $function =  $_REQUEST['aggregated_fields_functions'][$key];
            $field = $_REQUEST['aggregated_fields_fields'][$key];
            if(($field!='' && $function!='' && !is_null($field) && !is_null($function)) || $function=='Count'){
                if($function=='Count'){
                    $aggregate_json[$i]['aggregated_fields_fields'] = 'id';
                }else{
                    $aggregate_json[$i]['aggregated_fields_fields'] = $_REQUEST['aggregated_fields_fields'][$key];
                }
                $aggregate_json[$i]['aggregated_fields_functions'] = $_REQUEST['aggregated_fields_functions'][$key];
                $i++;
            }
        }
        $bean->aggregated_fields_data = (!empty($aggregate_json)) ? base64_encode(json_encode($aggregate_json)) : '' ;
    }
    function ShowModuleFields($bean, $event, $arguments){
        global $db;
        $result = $db->query("SELECT flow_module FROM ht_kanban_views WHERE id='{$bean->id}' AND deleted=0 LIMIT 1;");
        $row = $db->fetchByAssoc($result);
        $module_name = $row['flow_module'];
        $module_bean = BeanFactory::newBean($module_name);
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
        foreach($module_bean->field_name_map as $field_name => $field_data){
            if($field_data['type'] == 'enum' && in_array($field_name, $panel)){
                $label = translate($field_data['vname'], $module_name);
                $fields[$field_name] = trim($label, ':');
            }
        }
        $bean->module_fields = $fields[$bean->module_fields];
        $bean->flow_module = translate($bean->flow_module);
    }
    function SaveColunmsData($bean, $event, $arguments){
        $target_values = $_REQUEST['target_values'];
        $final_target_values = '';
        if(!empty($target_values)){
            foreach($target_values as $key=>$target_value){
                $comma_or_not = (sizeof($target_values)-1 == $key) ? "" : ",";
                $final_target_values.="^".$target_value."^".$comma_or_not;
            }
            $bean->target_values = $final_target_values;
        }
    }
}