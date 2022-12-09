<?php
class htKanban{
	private $module = "";
	private $kanban_type = "";

	public function __construct($module,$bean){
        $this->module = $module;
        $this->bean = $bean;
	}
	 
	function getKanbanData($where, $filters){
		require_once('modules/AOW_WorkFlow/AOW_WorkFlow.php');
		require_once('modules/AOW_WorkFlow/aow_utils.php');
		global $app_list_strings, $db, $timedate,$sugar_config;
		$display_columns = array();
		$type_options = array();
		$type_options_reverse = array();
		if($this->bean->status == 'active'){
			$module_name = $this->bean->flow_module;
			$module_field = $this->bean->module_fields;
			$bean = BeanFactory::newBean($module_name);
			$options_check = $bean->field_name_map[$module_field]['options'];
			 if (strpos($options_check, 'moduleList') !== false) {
				 $arr = array_merge(array(''=>''), $app_list_strings['moduleList']);
            	 asort($arr);
				 $display_columns = $arr;
			 }else{
				$display_columns = $app_list_strings[$bean->field_name_map[$module_field]['options']];
			 }
			 $type_options = array_flip($display_columns);
			 $type_options_reverse = $display_columns;
			if(empty($this->bean->target_values)){
				$this->bean->target_values = $this->getTypeValues($type_options);
				$this->bean->processed = true;
				$this->bean->save();
			}
			$targetValues_list = unencodeMultienum($this->bean->target_values);			
			$targetValues = implode("','", unencodeMultienum($this->bean->target_values));			
		}
		$order_by = "";
		$where = str_replace("&#039;", "'", $where);
		$type = str_replace("&#039;", "'", $filters['kanban_type']);
		$extra_field = '';	
		$where_type = '';
		$where_targetValues = '';
		if(isset($targetValues) && !empty($targetValues)){
			$display_columns = array_flip(array_intersect(array_flip($display_columns), $targetValues_list));
			$final_field = $this->detectTableOrigin($module_name,$module_field );
			$where_targetValues = "AND $final_field IN ('$targetValues')";
		}
		
		$where_filter = '';
		if($this->bean->flow_module == 'Project'  && isset($filters['kanban_project_class']) && !empty($filters['kanban_project_class']) && $filters['kanban_project_class'] != 'null' ){
			$filter1 = explode(',' , $filters['kanban_project_class']);
			$filter1 = implode("','", $filter1);
			$filter1 = " && project.project_class IN ('{$filter1}')";
		}
		if($this->bean->flow_module == 'Project'  && isset($filters['project_manager']) && !empty($filters['project_manager']) && $filters['project_manager'] != 'null'){
			$filter2 = explode(',' , $filters['project_manager']);
			$filter2 = implode("','", $filter2);
			$filter2 = " && project.assigned_user_id = ('{$filter2}')";
		}
		if($this->bean->flow_module == 'Project'  && isset($filters['project_accounts']) && !empty($filters['project_accounts']) && $filters['project_accounts'] != 'null'){
			$filter3 = explode(',' , $filters['project_accounts']);
			$filter3 = implode("','", $filter3);
			$filter3 = " && project.account_id = ('{$filter3}')";
		}
		if(isset($filters['record_ids']) && !empty($filters['record_ids']) && $filters['record_ids']!='undefined' ){
			$id = $this->detectTableOrigin($module_name,"id");
			$ids = explode(",",$filters['record_ids']);
			$ids = "'".implode("','",$ids)."'";
			$filter4 = " && {$id} NOT IN ({$ids})";
		}
		$where_filter = $filter1 . $filter2. $filter3. $filter4;
	    $where = html_entity_decode($where).' '."{$where_type}".' '."{$where_targetValues}". ' ' ."{$where_filter}";

	   $opportunity_amount_field = ($module_name == 'Opportunities') ? 'amount_usdollar'  : ' ';
	   $body_fields = unencodeMultienum($this->bean->body_fields);
		$fields = array(
			'id',
			'name',
			'description',
			'date_entered',
			'date_modified',
			'assigned_user_id',
			"{$extra_field}",
			"{$this->bean->module_fields}",
			"{$opportunity_amount_field}"
		);
		$show_hide_description_in_cards = (!empty($sugar_config['show_hide_description_in_cards']) && isset($sugar_config['show_hide_description_in_cards'])) ? $sugar_config['show_hide_description_in_cards'] : 'show';
		$enable_disable_aggregate_data_popup = (!empty($sugar_config['enable_disable_aggregate_data_popup']) && isset($sugar_config['enable_disable_aggregate_data_popup'])) ? $sugar_config['enable_disable_aggregate_data_popup'] : 'enable';
		$aggregated_data = json_decode(base64_decode($this->bean->aggregated_fields_data));
		$aggregated_fields = $this->getAggregatedFields($aggregated_data);
		$all_fields = array_merge($fields, $body_fields);
		if($show_hide_description_in_cards!="show"){
			$all_fields = array_merge(array_diff($all_fields, array('description')));
		}
		$all_fields = array_filter($all_fields);
		$all_fields_agg = array_merge( $all_fields,$aggregated_fields);
		$sql = $bean->create_new_list_query($order_by, $where, $all_fields_agg);
		$kanban_record_limit = (!empty($sugar_config['kanban_record_limit']) && isset($sugar_config['kanban_record_limit'])) ? $sugar_config['kanban_record_limit'] : 50;
		$kanban_popup_limit = (!empty($sugar_config['kanban_popup_limit']) && isset($sugar_config['kanban_popup_limit'])) ? $sugar_config['kanban_popup_limit'] : 2;
		$enable_disable_smooth_scrolling = (!empty($sugar_config['enable_disable_smooth_scrolling']) && isset($sugar_config['enable_disable_smooth_scrolling'])) ? $sugar_config['enable_disable_smooth_scrolling'] : 'disable';
		$final_sql_orginal = $this->getFinalSql($sql , $this->build_flow_query_where($this->bean->id , $this->bean->flow_module,array()),$bean);
		$ASC_DESC = (!empty($sugar_config['sort_cards_in_asc_desc']) && isset($sugar_config['sort_cards_in_asc_desc'])) ? $sugar_config['sort_cards_in_asc_desc'] : 'DESC';
		$final_sql = $final_sql_orginal.' ORDER BY '.$bean->table_name.'.date_modified  '.$ASC_DESC;
		$result = $db->query($final_sql);
		$result_count = $db->query($final_sql_orginal);
		$total_count = $result_count->num_rows;
		$result_aggregated_data=array();
		$result_data=array();
		$record_ids=array();
		$selected_field_options = $this->bean->getFields($module_name, '');
		while($row = $db->fetchByAssoc($result)){
			$record_ids[] = $row['id'];
			if($module_name == 'Documents'){
				$row['frame_heading'] = $row['document_name'];				
				$row['name'] = $row['document_name'];				
			}else{
				$row['frame_heading'] = $row['name'];				
			}
			foreach($all_fields  as $field){
				if($selected_field_options[$field] == ''){continue;}
				$data[$field] = array('label' => $selected_field_options[$field], 'value' => $row[$field]);
			}
			$data['frame_heading'] = array('label' => 'frame_heading', 'value' => $row['name']);
			$data['isfavourite'] = array('label' => 'isfavourite', 'value' => $row['isfavourite']);

			//for aggregatedfields
			$datas = array();
			foreach($aggregated_fields  as $field){
				if($selected_field_options[$field] == ''){continue;}
				$datas[$field] = array('label' => $selected_field_options[$field], 'value' => $row[$field]);
			}
			$result_aggregated_data[$row[$this->bean->module_fields]][$row['id']] = $datas;
			$result_data[$row[$this->bean->module_fields]][] = $data;
		}
		$display_columns_data = $this->getRelatedAggrecateData($where, $type);
		$kanban_data = array(
			'title_list' => $display_columns_data,
			'data' => $result_data,
			'aggregated_data' => $this->modifiedAggregatedData($aggregated_data),
			'aggregated_record_data' => $result_aggregated_data,
			'type_options' => $type_options,
			'type_options_reverse' => $type_options_reverse,
			'users_images' => $this->get_users_images(),
			'module' => $this->bean->flow_module,
			'record_ids'=>$record_ids,
			'total_count'=>$total_count,
			'kanban_popup_limit'=>$kanban_popup_limit,
			'enable_disable_smooth_scrolling'=>$enable_disable_smooth_scrolling,
			'show_hide_description_in_cards'=>$show_hide_description_in_cards,
			'enable_disable_aggregate_data_popup'=>$enable_disable_aggregate_data_popup
		);
		return $kanban_data;
	}

	function getTypeValues($type_options){
		$mult_enum_string = '';
		$counter = 0;
		foreach($type_options as $type_option){
			$mult_enum_string .= '^'.$type_option.'^';
			$counter++;
			if($counter!=sizeof($type_options)){
				$mult_enum_string .=',';
			}
		}
		return $mult_enum_string;
	}
	function get_users_images(){
		global $db;
		$sql = "SELECT * FROM users WHERE deleted=0";
		$result = $db->query($sql);
		$users = array();
		while($row = $db->fetchByAssoc($result)){
			if($row['photo']!='' && !is_null($row['photo'])){
				$users[$row['id']]['photo'] = "index.php?entryPoint=download&id=".$row['id']."_photo&type=Users&preview=yes";
			}else{
				$users[$row['id']]['photo'] = "https://www.w3schools.com/howto/img_avatar.png";
			}
			$users[$row['id']]['tooltip'] = $row['first_name'].' '.$row['last_name'];
			$users[$row['id']]['user_name'] = $row['user_name'];
		}
		return $users;
	}
	function getAggregatedFields($data){
		$fields = array();
		foreach($data as $d){
			$fields[] = $d->aggregated_fields_fields;
		}
		 $fields[] = 'id';
		return array_unique($fields);
	}
	function modifiedAggregatedData($data){
		$function_fields = array();
		$function_fields_2 = array();
		foreach($data as $d){
			$function_fields[$d->aggregated_fields_fields][] = $d->aggregated_fields_functions ; 
		}
		foreach($function_fields as $key=>$val){
			$unique_with_indexes =  array_unique($val);
			$unique = array();
			foreach($unique_with_indexes as $element){
				$unique[] = $element; 
			}
			$function_fields_2[$key] = $unique;
		}
		return $function_fields_2;
	}
	function detectTableOrigin($module,$field){
		$final_field = '';
		$bean = BeanFactory::getBean($module);
		$field_defs = $bean->getFieldDefinitions();
		if(isset($field_defs[$field]['source']) && $field_defs[$field]['source']=='custom_fields'){
			$final_field = $bean->table_name.'_cstm.'.$field;
		}else{
			$final_field = $bean->table_name.".".$field;
		}
		return $final_field;
	}
	function getRelatedAggrecateData($where, $type){
		global $app_list_strings, $db, $timedate,$sugar_config;
		$display_columns = array();
		if($this->bean->status == 'active'){
			$module_name = $this->bean->flow_module;
			$module_field = $this->bean->module_fields;
			$bean = BeanFactory::newBean($module_name);
		
			$options_check = $bean->field_name_map[$module_field]['options'];
			 if (strpos($options_check, 'moduleList') !== false) {
				 $arr = array_merge(array(''=>''), $app_list_strings['moduleList']);
            	 asort($arr);
				 $display_columns = $arr;
			 }else{
				$display_columns = $app_list_strings[$bean->field_name_map[$module_field]['options']];
			 }
			$targetValues_list = unencodeMultienum($this->bean->target_values);			
			$targetValues = implode("','", unencodeMultienum($this->bean->target_values));			
		}
		$order_by = '';
		$where = str_replace("&#039;", "'", $where);
		$type = str_replace("&#039;", "'", $type);
		$extra_field = '';	
		$where_type = '';
		$where_targetValues = '';
		if(isset($targetValues) && !empty($targetValues)){
			$display_columns = array_flip(array_intersect(array_flip($display_columns), $targetValues_list));
			$where_targetValues = "AND $module_field IN ('$targetValues')";
		}
		//  if ($bean instanceof Company) {
        //     $where_type = !empty($type)? "&& name LIKE '%{$type}%'" : ' ';
        //  }else if($bean instanceof Person){
		// 	 $where_type = !empty($type)? "&& CONCAT_WS('', first_name, last_name) LIKE '%{$type}%'" : ' ';
		//  }else if($bean instanceof File){
		// 	 $extra_field = 'document_name';
		// 	 $where_type = !empty($type)? "&& document_name LIKE '%{$type}%'" : ' ';
		//  }
		//  else if($bean instanceof SugarBean){
		// 	  $where_type = !empty($type)? "&& name LIKE '%{$type}%'" : ' ';
		//  }
	   $where = $where.' '."{$where_type}".' '."{$where_targetValues}";
		$fields = array(
			'id',
			'name',
			"{$extra_field}",
			"{$this->bean->module_fields}",
			"{$this->bean->module_calculation_fields}",
		);
		$sql = $bean->create_new_list_query($order_by, $where, $fields);
		$final_sql = $this->getFinalSql($sql , $this->build_flow_query_where($this->bean->id , $this->bean->flow_module,array()), $bean);
		$result = $db->query($final_sql, true);
		$result_data=array();
		$selected_field_options = $this->bean->getFields($module_name, '');
		while($row = $db->fetchByAssoc($result)){
			if($module_name == 'Documents'){
				$row['frame_heading'] = $row['document_name'];				
				$row['name'] = $row['document_name'];				
			}else{
				$row['frame_heading'] = $row['name'];				
			}
			foreach($fields  as $field){
				if($selected_field_options[$field] == ''){continue;}
				$data[$field] = array('label' => $selected_field_options[$field], 'value' => $row[$field]);
			}
				$data['frame_heading'] = array('label' => 'frame_heading', 'value' => $row['name']);
				$data['isfavourite'] = array('label' => 'isfavourite', 'value' => $row['isfavourite']);
			
			$result_data[$row[$this->bean->module_fields]][] = $data;
		}
		if($this->bean->aggregate_function == 'Avg'){
			foreach($display_columns as $col_val => $col_name){
				$total = 0;
				$total_entries = 0;
				$total_entries = count($result_data[$col_val]);
				foreach($result_data[$col_val] as $no => $field){
				  $total += $field[$this->bean->module_calculation_fields]['value'];
				}
				if($total_entries != 0){
					$average = $total/$total_entries;					
				}else{
					$average = 0;
				}
				$display_columns_data[$col_val] = array('col_name'=> $col_name, 'Total Cards' => count($result_data[$col_val]), 'Average' => round($average, 2));
			}
			
		}else if($this->bean->aggregate_function == 'Min'){
			foreach($display_columns as $col_val => $col_name){
				foreach($result_data[$col_val] as $no => $field){
				  $type_array[$col_val][] = $field[$this->bean->module_calculation_fields]['value'];
				}
				$min = min($type_array[$col_val]);
				if(empty($min)){
					$min = 0;
				}
				$display_columns_data[$col_val] = array('col_name'=> $col_name, 'Total Cards' => count($result_data[$col_val]), 'Min' => $min);
			}
			
		}else if($this->bean->aggregate_function == 'Max'){
			foreach($display_columns as $col_val => $col_name){
				foreach($result_data[$col_val] as $no => $field){
				  $type_array[$col_val][] = $field[$this->bean->module_calculation_fields]['value'];
				}
				$max = max($type_array[$col_val]);
				if(empty($max)){
					$max = 0;
				}
				$display_columns_data[$col_val] = array('col_name'=> $col_name, 'Total Cards' => count($result_data[$col_val]), 'Max' => $max);
			}
			
		}else if($this->bean->aggregate_function == 'Sum'){
			foreach($display_columns as $col_val => $col_name){
				$total = 0;
				foreach($result_data[$col_val] as $no => $field){
				  $total += $field[$this->bean->module_calculation_fields]['value'];
				}
				$display_columns_data[$col_val] = array('col_name'=> $col_name, 'Total Cards' => count($result_data[$col_val]), 'Sum' => $total);
			}
			
		}else{
			foreach($display_columns as $col_val => $col_name){
				$total = 0;
				$total_entries = 0;
				$total_entries = count($result_data[$col_val]);
				$display_columns_data[$col_val] = array('col_name'=> $col_name, 'Total Cards' => count($result_data[$col_val]));
			}
		}
		return $display_columns_data;
	}
	
	public function getFinalSql($old_sql, $data, $bean){
		$fav = "(SELECT count(*) FROM favorites WHERE favorites.parent_type='{$bean->module_name}' AND favorites.parent_id={$bean->table_name}.id AND favorites.deleted=0) AS isfavourite";
		$table_array = array($bean->table_name , $bean->table_name.'_cstm');
		$query_parts = explode('where' , $old_sql);
		$join = '';
		foreach($data['join'] as $key=>$value){
			if(!in_array($key , $table_array)){
				$join.=" ".$value." ";
			}
		}
		$query_parts_select = explode('FROM' , $query_parts[0]);
		$final_select = $query_parts_select[0].','.$fav;
		return  $final_select.' FROM '.$query_parts_select[1].' '.$join.' WHERE '.$query_parts[1];
	}
	// From here on out there are those functions which helps in getting Joins for other tabels
	public function build_flow_query_where($kanban_id,$flow_module,$query = array()){
        global $beanList,$db;
            $module = new $beanList[$flow_module]();
			$sql = "SELECT id FROM aow_conditions WHERE aow_workflow_id = '".$kanban_id."' AND deleted = 0 ORDER BY condition_order ASC";
            $result = $db->query($sql);
            while ($row = $db->fetchByAssoc($result)) {
                $condition = new AOW_Condition();
                $condition->retrieve($row['id']);
                $query = $this->build_query_where($condition, $module, $query);
                if (empty($query)) {
                    return $query;
                }
			}
        $query['where'][] = $module->table_name.".deleted = 0 ";
        return $query;
	}
	public function build_query_where(AOW_Condition $condition, $module, $query = array()){
        global $beanList, $app_list_strings, $sugar_config, $timedate;
        $path = unserialize(base64_decode($condition->module_path));

        $condition_module = $module;
        $table_alias = $condition_module->table_name;
        if (isset($path[0]) && $path[0] != $module->module_dir) {
            foreach ($path as $rel) {
                $query = $this->build_flow_relationship_query_join(
                    $rel,
                        $condition_module,
                    $query
                );
                $condition_module = new $beanList[getRelatedModule($condition_module->module_dir, $rel)];
                $table_alias = $rel;
            }
        }

        if ($this->isSQLOperator($condition->operator)) {
            $where_set = false;

            $data = $condition_module->field_defs[$condition->field];

            if ($data['type'] == 'relate' && isset($data['id_name'])) {
                $condition->field = $data['id_name'];
            }
            if ((isset($data['source']) && $data['source'] == 'custom_fields')) {
                $field = $table_alias.'_cstm.'.$condition->field;
                $query = $this->build_flow_custom_query_join(
                    $table_alias,
                    $table_alias.'_cstm',
                    $condition_module,
                    $query
                );
            } else {
                $field = $table_alias.'.'.$condition->field;
            }

            if ($condition->operator == 'is_null') {
                $query['where'][] = '('.$field.' '.$this->getSQLOperator($condition->operator).' OR '.$field.' '.$this->getSQLOperator('Equal_To')." '')";
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
                            $query = $this->build_flow_custom_query_join(
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

                                    $value = "DATE_ADD($value, INTERVAL ".$dateOp." $params2 ".$params3.")";
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
                                $value .= $field.' '.$this->getSQLOperator($condition->operator)." '".$multi_value."'";
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
                $query['where'][] = $field.' '.$this->getSQLOperator($condition->operator).' '.$value;
            }
        }

        return $query;
	}
	public function getSQLOperator($key){
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
	public function build_flow_relationship_query_join($name,SugarBean $module,$query = array()) {
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
	public function build_flow_custom_query_join($name,$custom_name,SugarBean $module,$query = array()) {
        if (!isset($query['join'][$custom_name])) {
            $query['join'][$custom_name] = 'LEFT JOIN '.$module->get_custom_table_name()
                    .' '.$custom_name.' ON '.$name.'.id = '. $custom_name.'.id_c ';
        }
        return $query;
	}
	public function isSQLOperator($key){
        return $this->getSQLOperator($key) ? true : false;
    }
}