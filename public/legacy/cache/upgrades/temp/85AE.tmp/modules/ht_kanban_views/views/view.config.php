<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class ht_kanban_viewsViewConfig extends SugarView {
   public function preDisplay(){
      if(!is_admin($GLOBALS['current_user']))
         sugar_die($GLOBALS['app_strings']['ERR_NOT_ADMIN']); 
   }
   protected function _getModuleTitleParams($browserTitle = false) {
      global $mod_strings, $app_list_strings;
       
      return array(
         "<a href='index.php?module=ht_kanban_views&action=index'>".$app_list_strings['moduleList']['ht_kanban_views']."</a>",
         $mod_strings['LBL_CONFIG']
      );
   } 
    
    public function display() {
        global $sugar_config, $moduleList, $app_list_strings;
        $enabled = array();
        $disabled = array();
        $kanban_record_limit = 50;
        $kanban_popup_limit = 2;
        $show_hide_description_in_cards = 'show';
        $sort_cards_in_asc_desc = 'DESC';
        $enable_disable_smooth_scrolling = 'disable';
        require_once "modules/ht_kanban_views/ht_kanban_views.php";
        $ht_kanban_views = new ht_kanban_views();
        $banned = $ht_kanban_views->kanabnBannedModules();
        $all_modules = $GLOBALS['beanList'];
        foreach($all_modules as $key=>$value){
            if (!in_array($key, $banned)) {
                if($key=="Users"){continue;}
                $enabled[] = array("module" => $key, 'label' => translate($key));
            }
        }
        if (!empty($sugar_config['addKanbanBannedModules'])) {
            foreach ($sugar_config['addKanbanBannedModules'] as $module) {
                if($module=="Users"){continue;}
                $disabled[] = array("module" => $module, 'label' => translate($module));
            }
        }
        if(!empty($sugar_config['kanban_record_limit']) && isset($sugar_config['kanban_record_limit'])){
            $kanban_record_limit = $sugar_config['kanban_record_limit'];
        }
        if(!empty($sugar_config['kanban_popup_limit']) && isset($sugar_config['kanban_popup_limit'])){
            $kanban_popup_limit = $sugar_config['kanban_popup_limit'];
        }
        if(!empty($sugar_config['enable_disable_smooth_scrolling']) && isset($sugar_config['enable_disable_smooth_scrolling'])){
            $enable_disable_smooth_scrolling = $sugar_config['enable_disable_smooth_scrolling'];
        }
        if(!empty($sugar_config['show_hide_description_in_cards']) && isset($sugar_config['show_hide_description_in_cards'])){
            $show_hide_description_in_cards = $sugar_config['show_hide_description_in_cards'];
        } 
        if(!empty($sugar_config['sort_cards_in_asc_desc']) && isset($sugar_config['sort_cards_in_asc_desc'])){
            $sort_cards_in_asc_desc = $sugar_config['sort_cards_in_asc_desc'];
        }
        if(!empty($sugar_config['enable_disable_aggregate_data_popup']) && isset($sugar_config['enable_disable_aggregate_data_popup'])){
            $enable_disable_aggregate_data_popup = $sugar_config['enable_disable_aggregate_data_popup'];
        }


        $enable_smooth_scrolling = '';
        $disable_smooth_scrolling = 'selected';
        if($enable_disable_smooth_scrolling=='enable'){
            $enable_smooth_scrolling = 'selected';
            $disable_smooth_scrolling = '';
        }

        $show_description_in_cards = 'selected';
        $hide_description_in_cards = '';
        if($show_hide_description_in_cards=="hide"){
            $show_description_in_cards = '';
            $hide_description_in_cards = 'selected';
        }

        $sort_cards_in_asc = 'selected';
        $sort_cards_in_desc = '';
        if($sort_cards_in_asc_desc=="DESC"){
            $sort_cards_in_asc = '';
            $sort_cards_in_desc = 'selected';
        }

        $enable_disable_aggregate_data_popup_enable='selected';
        $enable_disable_aggregate_data_popup_disable = '';
        if($enable_disable_aggregate_data_popup=="disable"){
            $enable_disable_aggregate_data_popup_enable = '';
            $enable_disable_aggregate_data_popup_disable = 'selected';
        }

        $this->ss->assign('enabled_mods', json_encode($enabled));
        $this->ss->assign('disabled_mods', json_encode($disabled));
        $this->ss->assign('kanban_record_limit', $kanban_record_limit);
        $this->ss->assign('kanban_popup_limit', $kanban_popup_limit);
        $this->ss->assign('enable_smooth_scrolling', $enable_smooth_scrolling);
        $this->ss->assign('disable_smooth_scrolling', $disable_smooth_scrolling);
        $this->ss->assign('hide_description_in_cards', $hide_description_in_cards);
        $this->ss->assign('show_description_in_cards', $show_description_in_cards);
        $this->ss->assign('sort_cards_in_asc', $sort_cards_in_asc);
        $this->ss->assign('sort_cards_in_desc', $sort_cards_in_desc);
        $this->ss->assign('enable_disable_aggregate_data_popup_disable', $enable_disable_aggregate_data_popup_disable);
        $this->ss->assign('enable_disable_aggregate_data_popup_enable', $enable_disable_aggregate_data_popup_enable);
        $this->ss->assign('title', $this->getModuleTitle(false));
        echo $this->ss->fetch('modules/ht_kanban_views/views/view.config.tpl');
    }
}