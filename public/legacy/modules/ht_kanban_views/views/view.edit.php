<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

/*********************************************************************************
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.

 * SuiteCRM is an extension to SugarCRM Community Edition developed by Salesagility Ltd.
 * Copyright (C) 2011 - 2014 Salesagility Ltd.
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
 ********************************************************************************/


class ht_kanban_viewsViewEdit extends ViewEdit
{
 	public function __construct()
 	{
 		parent::__construct();
 	}
	public function display(){
		global $app_list_strings, $current_user;
		
		require_once('modules/ht_kanban_views/license/htKanbanOutfittersLicense.php');
		/* $validate_license = htKanbanOutfittersLicense::isValid('ht_kanban_views'); */
		$validate_license = true;
		if($validate_license !== true) {
				SugarApplication::appendErrorMessage('Kanban view LicenseAddon is no longer active due to the following reason: '.$validate_license.' Users will have limited to no access until the issue has been addressed.');
				echo 'Kanban view License Addon is no longer active. Please renew your subscription or check your license configuration';
				SugarApplication::redirect("index.php?module=ht_kanban_views&action=license");
		}
		$is_new = '1';
		if(!empty($this->bean->id)){
			$is_new = '0';
			$app_list_strings['module_fields_dom'] = $this->bean->getEnumFields($this->bean->module_list);
		}
		$this->ss->assign('IS_NEW', $is_new);
		parent::display();
		$time = time();
		echo "
		<style>
		#aggregatedFields{
			display:block
		}
		#aggregatedFields > thead{
			display:block
		}
		#aggregatedFields > thead td{
			background-color:#bfcad3;
			padding-top:8px;
			padding-bottom:8px;
			padding-left:4px;
			padding-right:4px;
			color:#534d64;
			font-weight:bold;
			font-size:13px
		}
		
		#aggregatedFields thead td:first-of-type{
			height:34px;
			border-top-left-radius:4px
		}
		#aggregatedFields thead td:last-of-type{
			padding-left:8px;
			border-top-right-radius:4px;
			width:26%
		}
		#aggregatedFields > thead > tr{
			display:block
		}
		#aggregatedFields > tbody{
			display:block
		}
		#aggregatedFields > tbody > tr{
			display:block;
			background-color:#f5f5f5;
			border-bottom:5px #fff solid
		}
		#aggregatedFields > tbody > tr > td{
			vertical-align:top
		}
		#aggregatedFields td:first-of-type{
			display:inline-block
		}
		#aggregatedFields td:not(:first-of-type){
			display:inline-block;
			width:16.667%
		}
		#aggregatedFields tbody td{
			background-color:#f5f5f5;
			margin-bottom:4px;
			min-height:64px
		}
		#aggregatedFields tbody td:first-of-type{
			padding-left:8px;
			border-top-left-radius:4px;
			border-bottom-left-radius:4px
		}
		#aggregatedFields tbody td:last-of-type{
			padding-left:8px;
			border-top-left-radius:4px;
			border-bottom-left-radius:4px;
			border-top-right-radius:4px;
			border-bottom-right-radius:4px;
			width:26%
		}
		#aggregatedFields td:first-of-type button{
			margin-top:8px;
			line-height:30px
		}
		#aggregatedFields input{
			margin-top:8px
		}
		#aggregatedFields input.sqsEnabled{
			width:62.5%
		}
		#aggregatedFields input[type=checkbox]{
			margin-top:24px
		}
		#aggregatedFields textarea{
			margin-top:8px;
			width:95%
		}
		#aggregatedFields select{
			width:95%;
			margin-top:8px
		}
		#aggregatedFields > tbody > tr > td > table.dateTime{
			display:inline-block;
			background-color:#f5f5f5
		}
		#aggregatedFields > tbody > tr > td > table.dateTime .dateTimeComboColumn{
			display:inline-block
		}
		
		#aggregatedFields [type='button']{
			background-color:#f08377;
			padding:0;
			margin-right:4px;
			border:1px solid #f08377;
			border-radius:4px
		}
		.edit-view-row [data-label=LBL_AGGREGATED_FIELDS].label {
			display: none ;  
		}
		#body_fields {
		    width: 88% !important;
		}
		#menu_display{
		margin-top: 4%;
		}
		</style>
			<link href='https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css' rel='stylesheet' />
			<script type='text/javascript' src='modules/ht_kanban_views/js/edit.js?v={$time}'></script>
			<script src='https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js'></script>

			<script type='text/javascript'>
				//Setting titles for showing the tooltips
				$('div[data-label=LBL_BODY_FIELDS]').attr('title', 'Select only the fields you would be able to selected in the cards');
				$('div[data-label=LBL_BODY_FIELDS]').tooltip();
				$('div[data-label=LBL_MODULE_FIELDS]').attr('title', 'These  are dropdown on selected category');
				$('div[data-label=LBL_MODULE_FIELDS]').tooltip();
				
				//converting simple multiselect dropdown to select2
				$('#body_fields').select2({ dropupAuto: false});
				$('#target_values').select2({ dropupAuto: false});

				//changing the width of different filds
				$('#body_fields').css('width','88%');  
				$('#target_values').css('width','85%');    
				$('#module_fields').css('width','88%');
				$('#flow_module').css('width','68%');

				var module_field_value = '{$this->bean->module_fields}';
				var module_calculation_fields_value = '{$this->bean->module_calculation_fields}';
				var selectedRelateFieldValue = ".json_encode(unencodeMultienum($this->bean->body_fields)).";
				var SelectedFieldOptionsValue = ".json_encode(unencodeMultienum($this->bean->target_values)).";
				$(document).ready(function(){ 
					$('div[field=\"condition_lines\"]').parent().children(':first-child').hide();
					$('div[field=\"aggregated_fields\"]').parent().children(':first-child').hide();
				});
			</script>
		";
	}
}