<?php
require_once("modules/AOW_WorkFlow/aow_utils.php");
class ht_kanban_viewsController extends SugarController
{
    public function action_getKanbaAggregatedValues()
    {
       
        global $db;
        $response = array('status' => false, 'data' => array(), 'msg' => '');
        $functions_mapping = array(
            'Count' => 'COUNT',
            'Sum' => 'SUM',
            'Avg' => 'AVG',
            'Max' => 'MAX',
            'Min' => 'MIN'
        );
        $kanban_record_id = $_REQUEST['kanban_record_id'];
        $aggregate_records_ids = $_REQUEST['aggregate_records_ids'];
        $aggregate_data = $_REQUEST['aggregate_data'];
        $module = $_REQUEST['selected_module'];
        $bean_custom = BeanFactory::getBean($module);
        $field_defs = $bean_custom->getFieldDefinitions();
        $table = $bean_custom->table_name;
        $table_cstm = $table . '_cstm';

        if (empty($aggregate_records_ids)) {
            $response['msg'] = 'There are no records for this colunm.';
            $response['status'] = false;
            echo json_encode($response);
            die;
        }

        if (empty($aggregate_data)) {
            $response['msg'] = 'There is not aggregated field method pairs.';
            $response['status'] = false;
            echo json_encode($response);
            die;
        }

        $SELECT = 'SELECT ';
        $FROM = ' FROM ' . $table . ' A ';
        $JOIN = ($db->tableExists($table_cstm)) ? ' INNER JOIN  ' . $table_cstm . ' B ON A.id=B.id_c ' : '';
        $WHERE = ' WHERE A.id IN ( ' . "'" . implode("','", $aggregate_records_ids) . "'" . ' )';
        $GROUPBY = ' ; ';

        foreach ($aggregate_data as $field => $functions) {
            $final_field = '';
            if (isset($field_defs[$field]['source']) && $field_defs[$field]['source'] == 'custom_fields') {
                $final_field = 'B.' . $field;
            } else {
                $final_field = "A." . $field;
            }
            foreach ($functions as $function) {
                $final_function = $functions_mapping[$function];
                $comma = ',';
                $placholder = "COUNT";
                if ($final_function != "COUNT") {
                    $placholder = $final_function . '___' . translate($field_defs[$field]['vname'], $bean_custom->module_name, '');
                }
                $SELECT .= $final_function . '(' . $final_field . ') AS "' . $placholder . '"' . $comma;
            }
        }
        $SQL = rtrim($SELECT, ',') . $FROM . $JOIN . $WHERE . $GROUPBY;
        $response['msg'] = 'data retrieved successfully';
        $response['status'] = true;
        $result = $db->query($SQL, true);
        $row = $db->fetchByAssoc($result);
        foreach ($row as $key => $value) {
            $value = (is_null($value)) ? 0 : $value;
            if ($key == "COUNT") {
                $response['data']['COUNT'] = $value;
            } else {
                $value = number_format($value, 2);
                $lable_function = explode("___", $key);
                $response['data'][$lable_function[1]][$lable_function[0]] = $value;
            }
        }
        echo json_encode($response);
        die;
    }
    public function action_modules_list()
    {
        global $current_user;
        require_once('modules/ht_kanban_views/license/htKanbanOutfittersLicense.php');
        /* $validate_license = htKanbanOutfittersLicense::isValid('ht_kanban_views'); */
        $validate_license = true;
        if ($validate_license !== true) {
            if (is_admin($current_user)) {
                SugarApplication::appendErrorMessage('Kanban view LicenseAddon is no longer active due to the following reason: ' . $validate_license . ' Users will have limited to no access until the issue has been addressed.');
            }
            echo 'Kanban view License Addon is no longer active. Please renew your subscription or check your license configuration';
        } else {
            $this->view = 'license';
        }
    }

    public function action_inactive()
    {
        $this->view = 'inactive';
    }
    function action_getRelatedFields()
    {
        $kanban = BeanFactory::newBean('ht_kanban_views');
        $fields = $kanban->getEnumFields($_POST['module_name']);
        echo json_encode($fields);
        die;
    }
    function action_getRelatedModuleFields()
    {
        $kanban = BeanFactory::newBean('ht_kanban_views');
        $fields = $kanban->getFields($_POST['module_name'], '');
        echo json_encode($fields);
        die;
    }
    function action_get_module_calculation_fields()
    {
        $kanban = BeanFactory::newBean('ht_kanban_views');
        $fields = $kanban->getCalculationFields($_POST['module_name'], '');
        echo json_encode($fields);
        die;
    }
    function action_getSelectedFieldOptions()
    {
        $kanban = BeanFactory::newBean('ht_kanban_views');
        $fields = $kanban->getValues($_POST['module_name'], $_POST['module_fields']);
        echo json_encode($fields);
        die;
    }
    function action_getKanbanData()
    {
        global $db;
        require_once('modules/ht_kanban_views/htkanban/htKanban.php');
        $module = $_REQUEST['for_module'];
        $record_id = $_REQUEST['record_id'];
        $result = $db->query("SELECT kanban_where FROM ht_kanban_views WHERE id='{$record_id}'");
        $row = $db->fetchByAssoc($result);
        $where = ($row['kanban_where']) ? $row['kanban_where'] : '';
        $bean = BeanFactory::getBean('ht_kanban_views', $record_id);
        $kanban = new htKanban($module, $bean);
        $kanban_project_class = ($_REQUEST['kanban_project_class'] != 'undefined') ? $_REQUEST['kanban_project_class'] : '';
        $project_accounts = ($_REQUEST['project_accounts'] != 'undefined') ? $_REQUEST['project_accounts'] : '';
        $project_manager = ($_REQUEST['project_manager'] != 'undefined') ? $_REQUEST['project_manager'] : '';
        $record_ids = ($_REQUEST['record_ids'] != 'undefined') ? $_REQUEST['record_ids'] : '';
        $filters = array('kanban_type' => $_REQUEST['kanban_type'], 'kanban_project_class' => $kanban_project_class, 'project_manager' => $project_manager, 'project_accounts' => $project_accounts, 'record_ids' => $record_ids);
        $data = $kanban->getKanbanData($where, $filters);
        echo json_encode($data);
        die;
    }
    function action_update_wo_status()
    {
        global $app_list_strings, $db, $timedate, $sugar_config;
        require_once('modules/ht_kanban_views/htkanban/htKanban.php');
        $module = $_REQUEST['selected_module'];
        $update_id = $_REQUEST['frame_id'];
        $update_val = $_REQUEST['column_val'];
        $field_name = $_REQUEST['field_name'];
        $result = array("Updated Successfully");
        $bean = BeanFactory::newBean($module);
        $bean->retrieve($update_id);
        $bean->$field_name = $update_val;
        $bean->save();
        echo json_encode($result);
        die;
    }
    function action_Configuration()
    {
        if (is_admin($GLOBALS['current_user'])) {
            $this->view = 'config';
            $GLOBALS['view'] = $this->view;
        } else {
            sugar_die($GLOBALS['app_strings']['ERR_NOT_ADMIN']);
        }
        return true;
    }

    public function action_UpdateKanbanUI()
    {
        require_once('modules/Configurator/Configurator.php');
        $disabled = json_decode(html_entity_decode($_REQUEST['disabled_modules'], ENT_QUOTES));
        $cfg = new Configurator();
        if (!array_key_exists('addKanbanBannedModules', $cfg->config)) {
            $cfg->config['addKanbanBannedModules'] = '';
        }
        if (!array_key_exists('kanban_record_limit', $cfg->config)) {
            $cfg->config['kanban_record_limit'] = '';
        }
        if (!array_key_exists('kanban_popup_limit', $cfg->config)) {
            $cfg->config['kanban_popup_limit'] = '';
        }
        if (!array_key_exists('enable_disable_smooth_scrolling', $cfg->config)) {
            $cfg->config['enable_disable_smooth_scrolling'] = '';
        }
        if (!array_key_exists('show_hide_description_in_cards', $cfg->config)) {
            $cfg->config['show_hide_description_in_cards'] = '';
        }
        if (!array_key_exists('sort_cards_in_asc_desc', $cfg->config)) {
            $cfg->config['sort_cards_in_asc_desc'] = '';
        }
        if (!array_key_exists('enable_disable_aggregate_data_popup', $cfg->config)) {
            $cfg->config['enable_disable_aggregate_data_popup'] = '';
        }
        $cfg->config['enable_disable_smooth_scrolling'] = $_REQUEST['enable_disable_smooth_scrolling'];
        $cfg->config['kanban_record_limit'] = $_REQUEST['kanban_record_limit'];
        $cfg->config['kanban_popup_limit'] = $_REQUEST['kanban_popup_limit'];
        $cfg->config['show_hide_description_in_cards'] = $_REQUEST['show_hide_description_in_cards'];
        $cfg->config['sort_cards_in_asc_desc'] = $_REQUEST['sort_cards_in_asc_desc'];
        $cfg->config['enable_disable_aggregate_data_popup'] = $_REQUEST['enable_disable_aggregate_data_popup'];
        $cfg->config['addKanbanBannedModules'] = empty($disabled) ? false : $disabled;
        $cfg->saveConfig();
        $this->view = 'config';
    }

    function action_get_aggreate_functions_list()
    {
        echo json_encode($GLOBALS['app_list_strings']['aggregate_function_dom']);
        die;
    }
    function action_getFieldTypeOptions()
    {
        global $app_list_strings, $beanFiles, $beanList;

        if (isset($_REQUEST['rel_field']) &&  $_REQUEST['rel_field'] != '') {
            $module = getRelatedModule($_REQUEST['aow_module'], $_REQUEST['rel_field']);
        } else {
            $module = $_REQUEST['aow_module'];
        }
        $fieldname = $_REQUEST['aow_fieldname'];
        $aow_field = $_REQUEST['aow_newfieldname'];

        if (isset($_REQUEST['view'])) {
            $view = $_REQUEST['view'];
        } else {
            $view = 'EditView';
        }

        if (isset($_REQUEST['aow_value'])) {
            $value = $_REQUEST['aow_value'];
        } else {
            $value = '';
        }


        require_once($beanFiles[$beanList[$module]]);
        $focus = new $beanList[$module];
        $vardef = $focus->getFieldDefinition($fieldname);

        switch ($vardef['type']) {
            case 'double':
            case 'decimal':
            case 'float':
            case 'currency':
                $valid_opp = array('Value');
                break;
            case 'uint':
            case 'ulong':
            case 'long':
            case 'short':
            case 'tinyint':
            case 'int':
                $valid_opp = array('Value');
                break;
            case 'date':
            case 'datetime':
            case 'datetimecombo':
                $valid_opp = array('Value', 'Date');
                break;
            case 'enum':
            case 'dynamicenum':
            case 'multienum':
                $valid_opp = array('Value', 'Multi');
                break;
            case 'relate':
            case 'id':
                $valid_opp = array('Value');
                break;
            default:
                $valid_opp = array('Value');
                break;
        }
        if (!file_exists('modules/SecurityGroups/SecurityGroup.php')) {
            unset($app_list_strings['aow_condition_type_list']['SecurityGroup']);
        }
        foreach ($app_list_strings['aow_condition_type_list'] as $key => $keyValue) {
            if (!in_array($key, $valid_opp)) {
                unset($app_list_strings['aow_condition_type_list'][$key]);
            }
        }
        if ($view == 'EditView') {
            echo "<select type='text'  name='$aow_field' id='$aow_field' title='' tabindex='116'>" . get_select_options_with_id($app_list_strings['aow_condition_type_list'], $value) . "</select>";
        } else {
            echo $app_list_strings['aow_condition_type_list'][$value];
        }
        die;
    }

    public function action_delete_comment()
    {
        global $db, $log, $sugar_config, $timedate;

        $value = $_REQUEST['record_id'];
        $paraent_id = $_REQUEST['paraent_id'];
        $module = $_REQUEST['selected_module'];
        $bean = BeanFactory::getBean($module, $paraent_id);
        $data = array();
        $sql = "UPDATE notes SET deleted=1 WHERE id = '" . $value . "'";
        $query_success = $db->query($sql, true);

        if ($bean->load_relationship('notes')) {
            $notes = $bean->get_linked_beans(
                'notes',
                'Notes',
                'date_entered DESC',
                0,
                '',
                0,
                ""
            );

            foreach ($notes as $key => $note) {
                $data[$key]['subject'] = $note->name;
                $data[$key]['id'] = $note->id;
                $data[$key]['parent_id'] = $note->parent_id;
                $data[$key]['description'] = $note->description;
                $data[$key]['ago'] = $this->timeago($note->date_entered);
                $data[$key]['filename'] = $note->filename;
                $user = BeanFactory::getBean('Users', $note->created_by);
                $data[$key]['user_name'] = $user->first_name . ' ' . $user->last_name;
                $data[$key]['user_name_initials'] = $this->get_intials($user);
                $data[$key]['user'] = $user->user_name;
                $data[$key]['link'] = $sugar_config['site_url'] . "/index.php?entryPoint=download&id=" . $note->id . "&type=Notes&preview=yes";
            }
        }
        echo json_encode($data);
        die;
    }
    function timeago($date)
    {
        $timestamp = strtotime($date);
        $strTime = array("second", "minute", "hour", "day", "month", "year");
        $length = array("60", "60", "24", "30", "12", "10");
        $currentTime = time();
        if ($currentTime >= $timestamp) {
            $diff     = time() - $timestamp;
            for ($i = 0; $diff >= $length[$i] && $i < count($length) - 1; $i++) {
                $diff = $diff / $length[$i];
            }
            $diff = round($diff);
            if ($strTime[$i] == 'second') {
                return " moments ago ";
            }
            return $diff . " " . $strTime[$i] . "(s) ago ";
        }
    }
    function get_intials($user){
        $first = strtoupper(mb_substr($user->first_name, 0, 1, "UTF-8"));
        $last = strtoupper(mb_substr($user->last_name, 0, 1, "UTF-8"));
        return $first.''.$last;
     }


     public function action_edit_comment()
     {
        global $db, $log, $sugar_config, $timedate;
        $value = $_REQUEST['record_id'];
        $sql = $db->query("SELECT * from  notes WHERE id = '" . $value . "'");
        $result = $db->fetchByAssoc($sql);
        echo json_encode($result);
        die;
     }


     public function action_update_comment()
     {
        global $db, $log, $sugar_config, $timedate;
        $value = $_REQUEST['record_id'];
        $paraent_id = $_REQUEST['paraent_id'];
        $module = $_REQUEST['selected_module'];
        $filesss = $_REQUEST['filesss'];
         if($filesss!="undefined"){
         $is_saved = $this->save_file($_FILES,$value);
         }
        $bean = BeanFactory::getBean($module, $paraent_id);
        $data = array();
        $desc = $_REQUEST['text_comments'];
        $sql = "UPDATE notes SET description ='".  $desc ."' WHERE id = '" . $value . "'";
        $query_success = $db->query($sql, true);
        if ($bean->load_relationship('notes')) {
            $notes = $bean->get_linked_beans(
                'notes',
                'Notes',
                'date_entered DESC',
                0,
                '',
                0,
                ""
            );
            foreach ($notes as $key => $note) {
                $data[$key]['subject'] = $note->name;
                $data[$key]['id'] = $note->id;
                $data[$key]['parent_id'] = $note->parent_id;
                $data[$key]['description'] = $note->description;
                $data[$key]['ago'] = $this->timeago($note->date_entered);
                $data[$key]['filename'] = $note->filename;
                $user = BeanFactory::getBean('Users', $note->created_by);
                $data[$key]['user_name'] = $user->first_name . ' ' . $user->last_name;
                $data[$key]['user_name_initials'] = $this->get_intials($user);
                $data[$key]['user'] = $user->user_name;
                $data[$key]['link'] = $sugar_config['site_url'] . "/index.php?entryPoint=download&id=" . $note->id . "&type=Notes&preview=yes";
            }
        }
        echo json_encode($data);
        die;
     }
     function save_file($files,$id){
        global $db;
        if ($files['file']){
            require_once('include/upload_file.php');
            // $note = BeanFactory::getBean('Notes', $id);
            $mime_type = $files['file']['type'];
            $filename= $files['file']['name'];
            $contents = file_get_contents($files['file']['tmp_name']);
            $sql = "UPDATE notes SET filename ='".  $filename ."' WHERE id = '" . $id . "'";
            $query_success = $db->query($sql, true);
            $uploadfile = 'upload://'.$id;
            $res = move_uploaded_file($files['file']['tmp_name'],$uploadfile);
            $destination = ('upload/'.$id);
            $fp = fopen($destination, 'w+');
            if (!fwrite($fp,  $contents)) {
                die("ERROR: can't save file to $destination");
            }
            return true;
        }
        return false;
    }
}
