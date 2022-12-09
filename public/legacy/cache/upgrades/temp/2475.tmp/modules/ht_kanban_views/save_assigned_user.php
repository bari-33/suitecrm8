<?php
$user_id = $_REQUEST['user_id'];
$id = $_REQUEST['record_id'];
$module = $_REQUEST['record_module'];
$bean = BeanFactory::getBean($module, $id);
$bean->processed = true;
$bean->assigned_user_id = $user_id;
$bean->save();die;