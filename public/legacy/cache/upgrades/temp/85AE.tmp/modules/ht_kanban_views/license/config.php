<?php

$outfitters_config = array(
    'name' => 'HtKanbanViewv10', //The matches the id value in your manifest file. This allow the library to lookup addon version from upgrade_history, so you can see what version of addon your customers are using
    'shortname' => 'KanbanView', //The short name of the Add-on. e.g. For the url https://www.sugaroutfitters.com/addons/sugaroutfitters the shortname would be sugaroutfitters
    'public_key' => '2cde92325e6e7b5b5b7f0fde75188566', //The public key associated with the group
    'api_url' => 'https://store.suitecrm.com/api/v1',
    'validate_users' => false,
    'manage_licensed_users' => false, //Enable the user management tool to determine which users will be licensed to use the add-on. validate_users must be set to true if this is enabled. If the add-on must be licensed for all users then set this to false.
    'validation_frequency' => 'hourly', //default: weekly options: hourly, daily, weekly
    'continue_url' => 'index.php?module=ht_kanban_views&action=Configuration', 
);

