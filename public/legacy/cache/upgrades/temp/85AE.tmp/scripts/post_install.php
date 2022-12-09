<?php
if (! defined('sugarEntry') || ! sugarEntry) die('Not A Valid Entry Point');

function post_install() {
    global $db;
    if (!$db->tableExists('so_users')) {
        $fieldDefs = array(
            'id' => array (
              'name' => 'id',
              'vname' => 'LBL_ID',
              'type' => 'id',
              'required' => true,
              'reportable' => true,
            ),
            'deleted' => array (
                'name' => 'deleted',
                'vname' => 'LBL_DELETED',
                'type' => 'bool',
                'default' => '0',
                'reportable' => false,
                'comment' => 'Record deletion indicator',
            ),
            'shortname' => array (
                'name' => 'shortname',
                'vname' => 'LBL_SHORTNAME',
                'type' => 'varchar',
                'len' => 255,
            ),
            'user_id' => array (
                'name' => 'user_id',
                'rname' => 'user_name',
                'module' => 'Users',
                'id_name' => 'user_id',
                'vname' => 'LBL_USER_ID',
                'type' => 'relate',
                'isnull' => 'false',
                'dbType' => 'id',
                'reportable' => true,
                'massupdate' => false,
            )
        );
        
        $indices = array(
            'id' => array (
                'name' => 'so_userspk',
                'type' => 'primary',
                'fields' => array (
                    0 => 'id',
                ),
            ),
            'shortname' => array (
                'name' => 'shortname',
                'type' => 'index',
                'fields' => array (
                    0 => 'shortname',
                ),
            )
        );
        $db->createTableParams('so_users',$fieldDefs,$indices);
    }
	// Adding Custom module to the All Menu
	global $db, $sugar_version, $current_user,$beanList, $beanFiles, $mod_strings;
	$sql = "SELECT * FROM config WHERE category = 'MySettings' AND name = 'tab'";
	$result = $db->query($sql, true);
	$row = $db->fetchByAssoc($result);
	$tabs = unserialize(base64_decode($row['value']));
	if(!in_array('ht_kanban_views', $tabs)){
		$tabs[] = 'ht_kanban_views';
	}

	$decode = base64_encode(serialize($tabs));
	$update = "UPDATE config SET value =  '{$decode}' WHERE name = 'tab' AND category = 'MySettings'";
	$result = $db->query($update, true);
    
     //Repair
     require_once 'modules/ACL/install_actions.php';
     $module = array('All Modules');
     $selected_actions = array('clearAll');
     require_once 'modules/Administration/QuickRepairAndRebuild.php';
     $randc = new RepairAndClear();
     $randc->repairAndClearAll($selected_actions, $module, false, false);
    
    /** Your setting to save in config_override.php */
	require_once('modules/Configurator/Configurator.php');
    $cfg = new Configurator();
    $cfg->config['addAjaxBannedModules'][100] = 'ht_kanban_views';
    $cfg->config['addKanbanBannedModules'][0] = 'Contacts';
    $cfg->config['addKanbanBannedModules'][1] = 'Accounts';
    $cfg->config['addKanbanBannedModules'][2] = 'Leads';   
    $cfg->handleOverride(); 
	

	$installed_classes = array();
	$ACLbeanList=$beanList;

    foreach ($ACLbeanList as $module=>$class) {
        if (empty($installed_classes[$class]) && isset($beanFiles[$class]) && file_exists($beanFiles[$class])) {
            if ($class == 'Tracker') {
            } else {
                require_once($beanFiles[$class]);
                $mod = new $class();
                if ($mod->bean_implements('ACL') && empty($mod->acl_display_only)) {
                    if (!empty($mod->acltype)) {
                        ACLAction::addActions($mod->getACLCategory(), $mod->acltype);
                    } else {
                        ACLAction::addActions($mod->getACLCategory());
                    }

                    $installed_classes[$class] = true;
                }
            }
        }
    }
    // kanban_where
    $sql_kanban_where= "SHOW COLUMNS FROM ht_kanban_views LIKE 'kanban_where'";
    $sql_check_kanban_where = $db->query($sql_kanban_where);
    if(!$sql_check_kanban_where->num_rows){
        $sql ="ALTER TABLE ht_kanban_views ADD COLUMN `kanban_where` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL";
        $db->query($sql);
    }
	if(preg_match( "/^6.*/", $sugar_version)) {
        echo "
            <script>
            document.location = 'index.php?module=ht_kanban_views&action=license';
            </script>"
        ;
    } else {
        echo "
            <script>
            var app = window.parent.SUGAR.App;
            window.parent.SUGAR.App.sync({callback: function(){
                app.router.navigate('#bwc/index.php?module=ht_kanban_views&action=license', {trigger:true});
            }});
            </script>"
        ;
    }
}