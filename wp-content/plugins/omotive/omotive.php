<?php
/* Plugin Name: oMotive */
use oMotive\Plugin;

// $wpdb is an object that can be used to access data form default tables and custom tables.
global $wpdb;

// includes composer's autoload.
require __DIR__ . '/vendor/autoload.php';
// includes the file user.php for the WP functions ('wp_delete_user', wp_current_user')
require_once ABSPATH."wp-admin/includes/user.php";

// conserves the path to this file in the 'constant' value for the hooks of the activation/deactivation 
define('OMOTIVE_PLUGIN_FILE', __FILE__);

// we stock the name of the custom array for the commuication between user and resolution
define('OMOTIVE_RESOLUTION_USER_TABLE_NAME',$wpdb->prefix .'user_resolution');
define('OMOTIVE_REWARD_USER_TABLE_NAME', $wpdb->prefix .'user_reward');

// starts the plugin thanks to the clss Plugin
Plugin::run();

     

