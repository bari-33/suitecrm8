<?php
function display_condition_lines($focus, $field, $value, $view)
{
    global $locale, $app_list_strings, $mod_strings;

    $html = '';

    if (!is_file('cache/jsLanguage/AOW_Conditions/' . $GLOBALS['current_language'] . '.js')) {
        require_once('include/language/jsLanguage.php');
        jsLanguage::createModuleStringsCache('AOW_Conditions', $GLOBALS['current_language']);
    }
    $html .= '<script src="cache/jsLanguage/AOW_Conditions/'. $GLOBALS['current_language'] . '.js"></script>';

    if ($view == 'EditView') {
        $html .= '<script src="modules/ht_kanban_views/conditionLines.js"></script>';
        $html .= "<table border='0' cellspacing='4' width='100%' id='aow_conditionLines'></table>";

        $html .= "<div style='padding-top: 10px; padding-bottom:10px;'>";
        $html .= "<input type=\"button\" tabindex=\"116\" class=\"button\" value=\"".$mod_strings['LBL_ADD_CONDITION']."\" id=\"btn_ConditionLine\" onclick=\"insertConditionLine()\" disabled/>";
        $html .= "</div>";


        if (isset($focus->flow_module) && $focus->flow_module != '') {
            require_once("modules/AOW_WorkFlow/aow_utils.php");
            $html .= "<script>";
            $html .= "flow_rel_modules = \"".trim(preg_replace('/\s+/', ' ', getModuleRelationships($focus->flow_module)))."\";";
            $html .= "flow_module = \"".$focus->flow_module."\";";
            $html .= "document.getElementById('btn_ConditionLine').disabled = '';";
            if ($focus->id != '') {
                $sql = "SELECT id FROM aow_conditions WHERE aow_workflow_id = '".$focus->id."' AND deleted = 0 ORDER BY condition_order ASC";
                $result = $focus->db->query($sql);

                while ($row = $focus->db->fetchByAssoc($result)) {
                    $condition_name = new AOW_Condition();
                    $condition_name->retrieve($row['id']);
                    $condition_name->module_path = unserialize(base64_decode($condition_name->module_path));
                    if ($condition_name->module_path == '') {
                        $condition_name->module_path = $focus->flow_module;
                    }
                    $html .= "flow_fields = \"".trim(preg_replace('/\s+/', ' ', getModuleFields(getRelatedModule($focus->flow_module, $condition_name->module_path[0]))))."\";";
                    if ($condition_name->value_type == 'Date') {
                        $condition_name->value = unserialize(base64_decode($condition_name->value));
                    }
                    $condition_item = json_encode($condition_name->toArray());
                    $html .= "loadConditionLine(".$condition_item.");";
                }
            }
            $html .= "flow_fields = \"".trim(preg_replace('/\s+/', ' ', getModuleFields($focus->flow_module)))."\";";
            $html .= "</script>";
        }
    } else {
        if ($view == 'DetailView') {
            $html .= '<script src="modules/AOW_Conditions/conditionLines.js"></script>';
            $html .= "<table border='0' cellspacing='0' width='100%' id='aow_conditionLines'></table>";


            if (isset($focus->flow_module) && $focus->flow_module != '') {
                require_once("modules/AOW_WorkFlow/aow_utils.php");
                $html .= "<script>";
                $html .= "flow_rel_modules = \"".trim(preg_replace('/\s+/', ' ', getModuleRelationships($focus->flow_module)))."\";";
                $html .= "flow_module = \"".$focus->flow_module."\";";
                $sql = "SELECT id FROM aow_conditions WHERE aow_workflow_id = '".$focus->id."' AND deleted = 0 ORDER BY condition_order ASC";
                $result = $focus->db->query($sql);

                while ($row = $focus->db->fetchByAssoc($result)) {
                    $condition_name = new AOW_Condition();
                    $condition_name->retrieve($row['id']);
                    $condition_name->module_path = unserialize(base64_decode($condition_name->module_path));
                    if (empty($condition_name->module_path)) {
                        $condition_name->module_path[0] = $focus->flow_module;
                    }
                    $html .= "flow_fields = \"".trim(preg_replace('/\s+/', ' ', getModuleFields(getRelatedModule($focus->flow_module, $condition_name->module_path[0]))))."\";";
                    if ($condition_name->value_type == 'Date') {
                        $condition_name->value = unserialize(base64_decode($condition_name->value));
                    }
                    $condition_item = json_encode($condition_name->toArray());
                    $html .= "loadConditionLine(".$condition_item.");";
                }
                $html .= "</script>";
            }
        }
    }
    return $html;
}
