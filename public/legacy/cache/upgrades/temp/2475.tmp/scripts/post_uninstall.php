<?php
global $db,$log;
$db->dropTableName('ht_kanban_views');
$db->dropTableName('ht_kanban_views_audit');

$delete_table = "DELETE FROM config WHERE config.name='KanbanView'";
$result = $db->query($delete_table);

require_once("modules/Administration/QuickRepairAndRebuild.php");
$rac = new RepairAndClear();
$rac->repairAndClearAll(array('clearAll'),array(translate('LBL_ALL_MODULES')), true,false);