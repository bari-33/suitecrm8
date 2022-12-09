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

$dictionary['ht_kanban_views'] = array(
    'table' => 'ht_kanban_views',
    'audited' => true,
    'inline_edit' => true,
    'duplicate_merge' => false,
    'fields' => array (
		'flow_module' => 
				array (
				'required' => true,
				'name' => 'flow_module',
				'vname' => 'LBL_MODULE_LIST',
				'type' => 'enum',
				'massupdate' => true,
				//'no_default' => false,
				'comments' => '',
				'help' => '',
				'importable' => 'true',
				'duplicate_merge' => 'enabled',
				'duplicate_merge_dom_value' => '1',
				'audited' => true,
				'reportable' => true,
				'unified_search' => false,
				'merge_filter' => 'disabled',
				'calculated' => false,
				'len' => 100,
				'size' => '20',
				'function' => 'getHtModuleList',
				'dependency' => false,
				// 'default' => 'Accounts',
				// 'options' => 'module_options_dom',
				),
		'module_fields' => 
				array (
				'required' => true,
				'name' => 'module_fields',
				'vname' => 'LBL_MODULE_FIELDS',
				'type' => 'enum',
				'massupdate' => true,
				'no_default' => false,
				'comments' => '',
				'help' => '',
				'importable' => 'true',
				'duplicate_merge' => 'enabled',
				'duplicate_merge_dom_value' => '1',
				'audited' => true,
				'reportable' => true,
				'unified_search' => false,
				'merge_filter' => 'disabled',
				'calculated' => false,
				'len' => 100,
				'size' => '20',
				'default' => 'Select',
				'options' => 'module_fields_dom',
				'dependency' => false,
				),	
			'module_calculation_fields' => 
			array (
				'required' => false,
				'name' => 'module_calculation_fields',
				'vname' => 'LBL_MODULE_CALCULATION_FIELDS',
				'type' => 'enum',
				'massupdate' => true,
				'no_default' => false,
				'comments' => '',
				'help' => '',
				'importable' => 'true',
				'duplicate_merge' => 'enabled',
				'duplicate_merge_dom_value' => '1',
				'audited' => true,
				'reportable' => true,
				'unified_search' => false,
				'merge_filter' => 'disabled',
				'calculated' => false,
				'len' => 100,
				'size' => '20',
				'default' => 'Select',
				'function' => 'get_module_calculation_fields',
				'options' => 'module_calculation_fields_dom',
				'dependency' => false,
			),	
			'aggregate_function' => 
			array (
				'required' => false,
				'name' => 'aggregate_function',
				'vname' => 'LBL_AGGREGATE_FUNCTION',
				'type' => 'enum',
				'massupdate' => true,
				'comments' => '',
				'help' => '',
				'importable' => 'true',
				'duplicate_merge' => 'enabled',
				'duplicate_merge_dom_value' => '1',
				'audited' => true,
				'reportable' => true,
				'unified_search' => false,
				'merge_filter' => 'disabled',
				'calculated' => false,
				'len' => 100,
				'size' => '20',
				'default' => 'Count',
				'options' => 'aggregate_function_dom',
				'dependency' => false,
			),
		'body_fields' => 
				array(
					'name' => 'body_fields',
					'vname' => 'LBL_BODY_FIELDS',
					'type' => 'multienum',
					'options' => 'selected_module_fields_dom',
					'audited' => true,
					'merge_filter' => 'enabled',
				),
		'status' => 
				array (
				'required' => true,
				'name' => 'status',
				'vname' => 'LBL_STATUS',
				'type' => 'enum',
				'massupdate' => true,
				'no_default' => false,
				'comments' => '',
				'help' => '',
				'importable' => 'true',
				'duplicate_merge' => 'enabled',
				'duplicate_merge_dom_value' => '1',
				'audited' => true,
				'reportable' => true,
				'unified_search' => false,
				'merge_filter' => 'disabled',
				'calculated' => false,
				'len' => 100,
				'size' => '20',
				'default' => 'active',
				'options' => 'status_dom',
				'dependency' => false,
				),
		'menu_display' => 
				array (
				'required' => false,
				'name' => 'menu_display',
				'vname' => 'LBL_MENU_DISPLAY',
				'type' => 'bool',
				),
		'target_values' => 
				array(
				'name' => 'target_values',
				'vname' => 'LBL_TARGET_VALUES',
				'type' => 'multienum',
				'options' => 'selected_field_options_dom',
				'audited' => true,
				'merge_filter' => 'enabled',
				),
		'condition_lines' =>
            array(
                'required' => false,
                'name' => 'condition_lines',
                'vname' => 'LBL_CONDITION_LINES',
                'type' => 'function',
                'source' => 'non-db',
                'massupdate' => 0,
                'importable' => 'false',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => 0,
                'audited' => false,
                'reportable' => false,
                'inline_edit' => false,
                'function' =>
                    array(
                        'name' => 'display_condition_lines',
                        'returns' => 'html',
                        'include' => 'modules/ht_kanban_views/conditionLines.php'
                    ),
			),
			'aggregated_fields' =>
            array(
                'required' => false,
                'name' => 'aggregated_fields',
                'vname' => 'LBL_AGGREGATED_FIELDS',
                'type' => 'function',
                'source' => 'non-db',
                'massupdate' => 0,
                'importable' => 'false',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => 0,
                'audited' => false,
                'reportable' => false,
                'inline_edit' => false,
                'function' =>
                    array(
                        'name' => 'display_aggregated_fields',
                        'returns' => 'html',
                        'include' => 'modules/ht_kanban_views/aggregatedFields.php'
                    ),
            ),
			'aggregated_fields_data' =>
            array(
                'name' => 'aggregated_fields_data',
                'vname' => 'LBL_AGGREGATED_FIELDS_DATA',
                'type' => 'text',
			),
			'kanban_where' =>
            array(
                'name' => 'kanban_where',
                'vname' => 'LBL_KANBAN_WHERE',
                'type' => 'text',
            ),
),
    'relationships' => array (
),
    'optimistic_locking' => true,
    'unified_search' => true,
);
if (!class_exists('VardefManager')) {
        require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('ht_kanban_views', 'ht_kanban_views', array('basic','assignable','security_groups'));