<?php

/**
 * The code in this file runs when a plugin is uninstalled from the WordPress dashboard.
 */

/* If uninstall is not called from WordPress exit. */
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit ();
}
global $wpdb;

require_once plugin_dir_path( __FILE__ ). 'wp-amp.php';
/* Place uninstall code below here. */

$prefix = 'amphtml';

// Delete options
$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE '{$prefix}%';" );