<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
require_once('include/MVC/View/SugarView.php');
class Viewinactive extends SugarView{
    public function display(){
        require_once('include/Sugar_Smarty.php');
        $inactive = new Sugar_Smarty();
        $kanaban_record = BeanFactory::getBean('ht_kanban_views', $_REQUEST['record']);
        $name = $kanaban_record->name;
        $inactive->assign('name', $name);
        echo $inactive->fetch('modules/ht_kanban_views/tpls/kanban_template_inactive.tpl');
    }
}
