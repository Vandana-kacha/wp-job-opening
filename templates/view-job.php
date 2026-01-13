<?php
if (!defined('ABSPATH')) exit;

global $wpdb;
$table_name = $wpdb->prefix . 'wp_jobs';

$job_id = intval(get_query_var('ajo_job_id'));
$job = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $job_id), ARRAY_A);

get_header();

function wp_override_breadcrumbs( $links ) {
    if ( is_page_template( 'view-job.php' ) && isset($_GET['apply_id']) ) {
        $job_title = sanitize_text_field( $_GET['post'] ?? 'Job Detail' );

        // Clear default last crumb
        array_pop( $links );

        // Add Jobs archive link
        $links[] = [
            'url'  => site_url('/jobs'),
            'text' => 'Jobs',
        ];

        // Add current job title
        $links[] = [
            'url'  => '',
            'text' => $job_title,
        ];
    }

    return $links;
}
add_filter( 'wpseo_breadcrumb_links', 'wp_override_breadcrumbs' );

?>

<div class="container" style="padding-top:25px;">
<?php
if ($job): ?>

    <div class="single-job">

        <h1 class="job-title"><?php echo esc_html($job['jobTitle']); ?></h1>
        <ul class="job-meta">
            <li><strong>Location:</strong> <?php echo esc_html($job['jobLocation']); ?></li>
            <li><strong>Industry:</strong> <?php echo esc_html($job['jobIndustry']); ?></li>
            <li><strong>Designation:</strong> <?php echo esc_html($job['designation']); ?></li>
            <li><strong>Experience:</strong> <?php echo esc_html($job['minExperience']); ?></li>
            <li><strong>Required Qualification:</strong> <?php echo esc_html($job['minQualification']); ?></li>
        </ul>
        <div class="job-desc">
            <h6>Required Skills</h6>
            <?php echo wpautop(esc_html($job['requiredSkills'])); ?>
        </div>
        <div class="job-desc">
            <h6>Description</h6>
            <?php echo wpautop(esc_html($job['jobDescription'])); ?>
        </div>
        
    </div>
<?php else: ?>
    <p>No job found.</p>
<?php endif;
?>
<style>
.site-content {
    background:#fff;
}
    </style>
</div>
<?php
get_footer();
