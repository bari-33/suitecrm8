<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
require_once('include/MVC/View/views/view.detail.php');
class ht_kanban_viewsViewDetail extends ViewDetail {
 	function __construct(){
 		parent::__construct();
 	}
	public function preDisplay(){
        $metadataFile = $this->getMetaDataFile();
        $this->dv = new DetailView2();
        $this->dv->ss =&  $this->ss;
        $this->dv->setup($this->module, $this->bean, $metadataFile, 'modules/ht_kanban_views/tpls/kanban_template.tpl');
        if($this->bean->status!='active'){
			SugarApplication::redirect("index.php?module=ht_kanban_views&action=inactive&record=".$this->bean->id);
        }
    }
	public function display(){
		global $db, $mod_strings, $app_list_strings, $moduleList;
		$query_array = array();
		$query_where = '';
        $query_array = $this->build_flow_query_where_ht($query_array);
        foreach ($query_array['where'] as $where){
            $query_where .=  ($query_where == '' ? ' ' : ' AND ').$where;
        }
		$current_module_label = $app_list_strings['moduleList'][$this->bean->flow_module]; 
		$fields = $this->bean->getEnumFields($this->bean->flow_module);
		$this->ss->assign('CURRENT_MODULE', $_REQUEST['module']);
		$this->ss->assign('CURRENT_MODULE_LABEL', $current_module_label);
		$this->ss->assign('KANBAN_TYPE', $heading);
		$this->ss->assign('ASSIGN_USERS', $data);
		$this->ss->assign('record_id', $_REQUEST['record']);
		$this->ss->assign('field_name', $this->bean->module_fields);
		$this->ss->assign('field_label', $fields[$this->bean->module_fields]);
		$this->ss->assign('selected_module', $this->bean->flow_module);
		$this->ss->assign('name', $this->bean->name);
        $resultss = $db->query('UPDATE ht_kanban_views SET kanban_where="'.$query_where.'" WHERE id="'.$this->bean->id.'" AND deleted=0');
		parent::display();
		echo $this->get_edit_modal();
	}
	public function build_flow_query_where_ht($query = array()){
        global $beanList;
        if(isset($beanList[$this->bean->flow_module]) && $beanList[$this->bean->flow_module]){
            $module = new $beanList[$this->bean->flow_module]();

            $sql = "SELECT id FROM aow_conditions WHERE aow_workflow_id = '".$this->bean->id."' AND deleted = 0 ORDER BY condition_order ASC";
            $result = $this->bean->db->query($sql);

            while ($row = $this->bean->db->fetchByAssoc($result)) {
                $condition = new AOW_Condition();
                $condition->retrieve($row['id']);
				require_once('modules/AOW_WorkFlow/AOW_WorkFlow.php');
				$AOW_WorkFlow = new AOW_WorkFlow();
                $query = $this->build_query_where_ht($condition,$module,$query);
                if(empty($query)){
                    return $query;
                }
            }
            $query['where'][] = $module->table_name.".deleted = 0 ";
        }

        return $query;
	}
	public function build_query_where_ht(AOW_Condition $condition, $module, $query = array()){
        global $beanList, $app_list_strings, $sugar_config, $timedate;
        $path = unserialize(base64_decode($condition->module_path));

        $condition_module = $module;
        $table_alias = $condition_module->table_name;
        if (isset($path[0]) && $path[0] != $module->module_dir) {
            foreach ($path as $rel) {
                $query = $this->build_flow_relationship_query_join_ht(
                    $rel,
                    $condition_module,
                    $query
                );
                $condition_module = new $beanList[getRelatedModule($condition_module->module_dir, $rel)];
                $table_alias = $rel;
            }
        }

        if ($this->isSQLOperator_ht($condition->operator)) {
            $where_set = false;

            $data = $condition_module->field_defs[$condition->field];

            if ($data['type'] == 'relate' && isset($data['id_name'])) {
                $condition->field = $data['id_name'];
            }
            if ((isset($data['source']) && $data['source'] == 'custom_fields')) {
                $field = $table_alias.'_cstm.'.$condition->field;
                $query = $this->build_flow_custom_query_join_ht(
                    $table_alias,
                    $table_alias.'_cstm',
                    $condition_module,
                    $query
                );
            } else {
                $field = $table_alias.'.'.$condition->field;
            }

            if ($condition->operator == 'is_null') {
                $query['where'][] = '('.$field.' '.$this->getSQLOperator_ht($condition->operator).' OR '.$field.' '.$this->getSQLOperator_ht('Equal_To')." '')";
                return $query;
            }

            switch ($condition->value_type) {
                case 'Field':

                    $data = null;
                    if (isset($module->field_defs[$condition->value])) {
                        $data = $module->field_defs[$condition->value];
                    } else {
                        LoggerManager::getLogger()->warn('Undefined field def for condition value in module: ' . get_class($module) . '::field_defs[' . $condition->value . ']');
                    }

                    if ($data['type'] == 'relate' && isset($data['id_name'])) {
                        $condition->value = $data['id_name'];
                    }
                    if ((isset($data['source']) && $data['source'] == 'custom_fields')) {
                        $value = $module->table_name.'_cstm.'.$condition->value;
                        $query = $this->build_flow_custom_query_join(
                            $module->table_name,
                            $module->table_name.'_cstm',
                            $module,
                            $query
                        );
                    } else {
                        $value = $module->table_name.'.'.$condition->value;
                    }
                    break;
                case 'Any_Change':
                    //can't detect in scheduler so return
                    return array();
                case 'Date':

                    $params = @unserialize(base64_decode($condition->value));
                    if ($params === false) {
                        LoggerManager::getLogger()->error('Unserializable data given');
                        $params = [null];
                    }

                    if ($params[0] == 'now') {
                        if ($sugar_config['dbconfig']['db_type'] == 'mssql') {
                            $value  = 'GetUTCDate()';
                        } else {
                            $value = 'UTC_TIMESTAMP()';
                        }
                    } elseif (isset($params[0]) && $params[0] == 'today') {
                        if ($sugar_config['dbconfig']['db_type'] == 'mssql') {
                            //$field =
                            $value  = 'CAST(GETDATE() AS DATE)';
                        } else {
                            $field = 'DATE('.$field.')';
                            $value = 'Curdate()';
                        }
                    } else {
                        $data = null;
                        if (isset($module->field_defs[$params[0]])) {
                            $data = $module->field_defs[$params[0]];
                        } else {
                            LoggerManager::getLogger()->warn('Filed def data is missing: ' . get_class($module) . '::$field_defs[' . $params[0] . ']');
                        }

                        if ((isset($data['source']) && $data['source'] == 'custom_fields')) {
                            $value = $module->table_name.'_cstm.'.$params[0];
                            $query = $this->build_flow_custom_query_join_ht(
                                $module->table_name,
                                $module->table_name.'_cstm',
                                $module,
                                $query
                            );
                        } else {
                            $value = $module->table_name.'.'.$params[0];
                        }
                    }

                    if ($params[1] != 'now') {
                        switch ($params[3]) {
                            case 'business_hours':
                                if (file_exists('modules/AOBH_BusinessHours/AOBH_BusinessHours.php') && $params[0] == 'now') {
                                    require_once('modules/AOBH_BusinessHours/AOBH_BusinessHours.php');

                                    $businessHours = new AOBH_BusinessHours();

                                    $amount = $params[2];

                                    if ($params[1] != "plus") {
                                        $amount = 0-$amount;
                                    }
                                    $value = $businessHours->addBusinessHours($amount);
                                    $value = "'".$timedate->asDb($value)."'";
                                    break;
                                }
                                //No business hours module found - fall through.
                                $params[3] = 'hour';
                                // no break
                            default:
                                if ($sugar_config['dbconfig']['db_type'] == 'mssql') {
                                    $value = "DATEADD(".$params[3].",  ".$app_list_strings['aow_date_operator'][$params[1]]." $params[2], $value)";
                                } else {
                                    if (!isset($params)) {
                                        LoggerManager::getLogger()->warn('Undefined variable: param');
                                        $params = [null, null, null, null];
                                    }

                                    $params1 = $params[1];
                                    $params2 = $params[2];
                                    $params3 = $params[3];

                                    $dateOp = null;
                                    if (isset($app_list_strings['aow_date_operator'][$params1])) {
                                        $dateOp = $app_list_strings['aow_date_operator'][$params1];
                                    } else {
                                        LoggerManager::getLogger()->warn('Date operator is not set in app_list_string[' . $params1 . ']');
                                    }

                                    $field = 'DATE_FORMAT('.$field.", '%Y-%m-%d %H:%i')";
                                    $value = "DATE_FORMAT(DATE_ADD($value, INTERVAL ".$dateOp." $params2 ".$params3."), '%Y-%m-%d %H:%i')";
                                }
                                break;
                        }
                    }
                    break;

                case 'Multi':
                    $sep = ' AND ';
                    if ($condition->operator == 'Equal_To') {
                        $sep = ' OR ';
                    }
                    $multi_values = unencodeMultienum($condition->value);
                    if (!empty($multi_values)) {
                        $value = '(';
                        if ($data['type'] == 'multienum') {
                            $multi_operator =  $condition->operator == 'Equal_To' ? 'LIKE' : 'NOT LIKE';
                            foreach ($multi_values as $multi_value) {
                                if ($value != '(') {
                                    $value .= $sep;
                                }
                                $value .= $field." $multi_operator '%^".$multi_value."^%'";
                            }
                        } else {
                            foreach ($multi_values as $multi_value) {
                                if ($value != '(') {
                                    $value .= $sep;
                                }
                                $value .= $field.' '.$this->getSQLOperator_ht($condition->operator)." '".$multi_value."'";
                            }
                        }
                        $value .= ')';
                        $query['where'][] = $value;
                    }
                    $where_set = true;
                    break;
                case 'SecurityGroup':
                    $sgModule = $condition_module->module_dir;
                    if (isset($data['module']) && $data['module'] !== '') {
                        $sgModule = $data['module'];
                    }
                    $sql = 'EXISTS (SELECT 1 FROM securitygroups_records WHERE record_id = ' . $field . " AND module = '" . $sgModule . "' AND securitygroup_id = '" . $condition->value . "' AND deleted=0)";
                    if ($sgModule === 'Users') {
                        $sql = 'EXISTS (SELECT 1 FROM securitygroups_users WHERE user_id = ' . $field . " AND securitygroup_id = '" . $condition->value . "' AND deleted=0)";
                    }
                    $query['where'][] = $sql;
                    $where_set = true;
                    break;
                case 'Value':
                default:
                    $value = "'".$condition->value."'";
                    break;
            }

            //handle like conditions
            switch ($condition->operator) {
                case 'Contains':
                    $value = "CONCAT('%', ".$value." ,'%')";
                    break;
                case 'Starts_With':
                    $value = "CONCAT(".$value." ,'%')";
                    break;
                case 'Ends_With':
                    $value = "CONCAT('%', ".$value.")";
                    break;
            }
            if (!$where_set) {
                if($this->check_if_field_is_of_date_type($module,$field)){
                    $query['where'][] = 'DATE('.$field.') '.$this->getSQLOperator_ht($condition->operator).' DATE('.$value.')';
                }else{
                    $query['where'][] = $field.' '.$this->getSQLOperator_ht($condition->operator).' '.$value;
                }
            }
        }

        return $query;
	}
	public function build_flow_custom_query_join_ht($name,$custom_name,SugarBean $module,$query = array()){
        if (!isset($query['join'][$custom_name])) {
            $query['join'][$custom_name] = 'LEFT JOIN '.$module->get_custom_table_name()
                    .' '.$custom_name.' ON '.$name.'.id = '. $custom_name.'.id_c ';
        }
        return $query;
    }
	public function build_flow_relationship_query_join_ht($name,SugarBean $module,$query = array()){
        if (!isset($query['join'][$name])) {
            if ($module->load_relationship($name)) {
                $params['join_type'] = 'LEFT JOIN';
                $params['join_table_alias'] = $name;
                $join = $module->$name->getJoin($params, true);

                $query['join'][$name] = $join['join'];
                $query['select'][] = $join['select']." AS '".$name."_id'";
            }
        }
        return $query;
	}
	public function getSQLOperator_ht($key){
        $sqlOperatorList['Equal_To'] = '=';
        $sqlOperatorList['Not_Equal_To'] = '!=';
        $sqlOperatorList['Greater_Than'] = '>';
        $sqlOperatorList['Less_Than'] = '<';
        $sqlOperatorList['Greater_Than_or_Equal_To'] = '>=';
        $sqlOperatorList['Less_Than_or_Equal_To'] = '<=';
        $sqlOperatorList['Contains'] = 'LIKE';
        $sqlOperatorList['Starts_With'] = 'LIKE';
        $sqlOperatorList['Ends_With'] = 'LIKE';
        $sqlOperatorList['is_null'] = 'IS NULL';
        if (!isset($sqlOperatorList[$key])) {
            return false;
        }
        return $sqlOperatorList[$key];
    }
    public function isSQLOperator_ht($key){
        return $this->getSQLOperator_ht($key) ? true : false;
	}
	public function check_if_field_is_of_date_type($module, $field){
		$field = str_replace($module->table_name.'.','',$field);
		$field = str_replace($module->table_name.'_cstm.','',$field);
		$field_defs = $module->getFieldDefinitions();
		$field_defintion = $field_defs[$field];
		if(in_array($field_defintion['type'],array('date','datetime', 'datetimecombo'))){
			return true;
		}
		return false;
	}
	public function get_edit_modal(){
		global $db;
		$options = '<option value="" selected ></option>';
		$sql = "SELECT * FROM users WHERE status='active' and deleted=0 ORDER BY first_name";
		$result = $db->query($sql,true);
		while($row = $db->fetchByAssoc($result)){
			$users[$row['id']] = $row['first_name']." ".$row['last_name'];
			$options .= "<option value='".$row['id']."'>".$row['first_name']." ".$row['last_name']."</option>";
		}
        $html='
		<div class="modal modal-custom-edit" style="display:none;top: 65%;left: 50%;transform: translate(-50%, -50%);width: fit-content;overflow-x: scroll;height: 900px;">
			<div class="modal-dialog modal-lg" style="margin: 0 auto !important; height:500px;overflow-x: auto;">
                <div class="modal-content" style="width: initial !important;    background-color: #ededed;">
                <button type="button" class="close comment-box-close-button" data-dismiss="modal" aria-label="Close" style="z-index: 15;"><span aria-hidden="true" style="color: red;">×</span></button>
                <h1 style="letter-spacing: 0px;text-align: center;margin-top: 2%;">Activities And Notes</h1>
					<div class="catch-detail-content"></div><br>
					<h4>ADD NOTES</h4>
					<div class="window-module-title window-module-title-no-divider card-detail-activity"><span class="window-module-title-icon icon-lg icon-activity"></span>
						<h3>Activity</h3>
					</div>
					<div class="new-comment js-new-comment mod-card-back is-show-controls">
						<form id="add_edit_form" name="add_edit_form" method="post" enctype="multipart/form-data">
							<input type="hidden" name="record_id_to_save" id="record_id_to_save" />
							<input type="hidden" name="module_to_save" id="module_to_save" />
							<div class="comment-frame">
								<div class="comment-box">
									<textarea id="comment_box_z"  name="comment_box_z" class="comment-box-input js-new-comment-input" placeholder="Write a comment…" dir="auto" style="overflow: hidden; overflow-wrap: break-word; height: 100px;" spellcheck="false"></textarea>
									<div class="comment-controls u-clearfix">
										<input class="primary confirm mod-no-top-bottom-margin js-add-comment" type="submit" form="add_edit_form" id="save_edit" value="Save"><span class="comment-subscribe is-clickable js-comment-subscribe is-visible" aria-label="Watch this card for updates">
										</span>
									</div>
                                    
									<div class="comment-box-options"><input class="icon-sm icon-attachment" id="note_attachement" type="file" name="note_attachement[]" onchange="showname()"></div>
									<div class="placeholder-path-file"  ></div>
                                    
								</div>
							</div>
						</form>
                        <div class = "save_button"></div>
					</div>
					<div class="js-list-actions-madam mod-card-back"  style="overflow-y: scroll;height: 200px;"></div>
                    <div class="modal-footer">
                <button class="" style = "background: #a94442;color: #f3f3f3;" data-dismiss="modal" aria-hidden="true">Close</button>
                </div>
				</div>
			</div>
        </div>';
    	return $html;
    }
}
?>