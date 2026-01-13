<?php
/*
Plugin Name: WP Job Opening
Description: Job listing
Version: 1.0.0
Author: Vandana
*/

if (!defined('ABSPATH')) exit;

require_once plugin_dir_path(__FILE__) . 'includes/class-admin.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-settings.php';
require plugin_dir_path(__FILE__) . 'includes/shortcode.php';
require plugin_dir_path(__FILE__) . 'includes/ajax-handler.php';

add_action('wp_enqueue_scripts', function() {
    wp_enqueue_script(
        'wp-job-ajax',
        plugin_dir_url(__FILE__) . 'assets/js/wp-job-ajax.js',
        ['jquery'],
        '1.0',
        true
    );

    wp_localize_script('wp-job-ajax', 'wpAjax', [
        'ajaxurl'  => admin_url('admin-ajax.php'),
        'security' => wp_create_nonce('wp_ajax_nonce'),
    ]);
});

add_action('admin_enqueue_scripts', function($hook) {
    if ($hook !== 'wp-job-opening_page_ajo-applicants') {
        return;
    }

    wp_enqueue_script(
        'wp-job-ajax-admin',
        plugin_dir_url(__FILE__) . 'assets/js/wp-job-ajax-admin.js',
        ['jquery'],
        '1.0',
        true
    );

    wp_enqueue_style(
        'wp-job-ajax-admin-css',
        plugin_dir_url(__FILE__) . 'assets/css/wp-job-ajax.css',
        [],
        '1.0.0',
        'all'
    );

    wp_localize_script('wp-job-ajax-admin', 'ajo_ajax_obj', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('wp_ajax_nonce'),
    ]);
});

// Register custom rewrite rule
add_action('init', function() {
    add_rewrite_rule('^career/current-opening/jobs/([0-9]+)/?', 'index.php?ajo_job_id=$matches[1]', 'top');
});

// Register query var
add_filter('query_vars', function($vars) {
    $vars[] = 'ajo_job_id';
    return $vars;
});

// Template loader
add_action('template_redirect', function() {
    $job_id = get_query_var('ajo_job_id');
    if ($job_id) {
        include plugin_dir_path(__FILE__) . 'templates/view-job.php';
        exit;
    }
});

// Add "Settings" link on Plugins page
add_filter('plugin_action_links_' . plugin_basename(__FILE__), function($links) {
    $settings_link = '<a href="' . admin_url('admin.php?page=ajo-settings') . '">Settings</a>';
    array_unshift($links, $settings_link);
    return $links;
});

register_activation_hook(__FILE__, function() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . "ajo_applicants";
    $table1 = $wpdb->prefix . 'wp_jobs';

    $charset_collate = $wpdb->get_charset_collate();

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        job_id INT(11) NOT NULL,
        job_post varchar(255) NOT NULL,
        candidate_type varchar(100) NOT NULL,
        full_name varchar(100) NOT NULL,
        email varchar(100) NOT NULL,
        phone varchar(100) NOT NULL,
        age varchar(100) NOT NULL,
        job_location varchar(100) NOT NULL,
        pincode varchar(100) NOT NULL,
        gender varchar(100) NOT NULL,
        marital_status varchar(100) NOT NULL,
        qualification varchar(100) NOT NULL,
        job_preference varchar(100) NOT NULL,
        current_designation varchar(100) NOT NULL,
        years_of_experience varchar(100) NOT NULL,
        current_ctc varchar(100) NOT NULL,
        comment varchar(100) NOT NULL,
        cv text NOT NULL,
        submitted_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    $sql1 = "CREATE TABLE IF NOT EXISTS $table1 (
        id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        jobPostingID BIGINT(20),
        jobTitle VARCHAR(255),
        jobDescription LONGTEXT,
        requiredSkills TEXT,
        minQualification VARCHAR(255),
        jobLocation VARCHAR(255),
        department VARCHAR(255),
        designation VARCHAR(255),
        minExperience VARCHAR(255),
        jobDuration VARCHAR(255),
        jobIndustry VARCHAR(255),
        monthlySalary VARCHAR(255),
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) $charset_collate;";

    dbDelta($sql);
    dbDelta($sql1);
});


