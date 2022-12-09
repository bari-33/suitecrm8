<?php

require_once('include/MVC/View/views/view.list.php');
class ht_kanban_viewsViewList extends ViewList
{
    /**
     * @see ViewList::preDisplay()
     */
    public function preDisplay(){
        require_once('modules/AOS_PDF_Templates/formLetter.php');
        formLetter::LVPopupHtml('ht_kankan_views');
        parent::preDisplay();
    }
	function display(){
		global $app_list_strings, $mod_strings,$current_user;
		require_once('modules/ht_kanban_views/license/htKanbanOutfittersLicense.php');
		$validate_license = htKanbanOutfittersLicense::isValid('ht_kanban_views');
		if($validate_license !== true) {
				SugarApplication::appendErrorMessage('Kanban view LicenseAddon is no longer active due to the following reason: '.$validate_license.' Users will have limited to no access until the issue has been addressed.');
				echo 'Kanban view License Addon is no longer active. Please renew your subscription or check your license configuration';
				SugarApplication::redirect("index.php?module=ht_kanban_views&action=license");
		}

		require_once('include/utils.php');
		$field_options = array();
		foreach($this->lv->data['data'] as $label => $field_data){
			 $bean = BeanFactory::newBean($field_data['MODULE_LIST']);
			$label = translate($bean->field_name_map[$field_data['MODULE_FIELDS']]['vname'], $field_data['MODULE_LIST']);
			$field_data['MODULE_FIELDS'] = trim($label, ':');
		}
		parent::display();
	}
	
}
