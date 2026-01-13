<?php
// Exit if accessed directly
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Remove plugin options
delete_option('ajo_job_api_url');
delete_option('ajo_cv_api_url');

// Drop custom table if exists
global $wpdb;
$table_name = $wpdb->prefix . "ajo_applicants";
$wpdb->query("DROP TABLE IF EXISTS $table_name");

$table_name1 = $wpdb->prefix . "wp_jobs";
$wpdb->query("DROP TABLE IF EXISTS $table_name1");
