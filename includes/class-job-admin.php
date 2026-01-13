<?php
if (!defined('ABSPATH')) exit;

class AJO_Job_Admin {
    public function __construct() {
        add_action('admin_menu', [$this, 'add_job_page']);
    }

    public function add_job_page() {
        add_submenu_page(
            'ajo-dashboard',
            'Job Openings',
            'Job Openings',
            'manage_options',
            'ajo-jobs',
            [$this, 'wp_render_job_admin_page']
        );
    }

    public function wp_sync_jobs_from_api() {
        global $wpdb;
        $table = $wpdb->prefix . 'wp_jobs';

        $api_url  = get_option('ajo_job_api_url');
        $username = get_option('ajo_job_username');
        $password = get_option('ajo_job_password');

        $response = wp_remote_get($api_url, [
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode("$username:$password"),
                'Accept'        => 'application/json',
            ],
            'timeout' => 20,
        ]);

        if (is_wp_error($response)) return false;

        $data = json_decode(wp_remote_retrieve_body($response), true);
        if (!$data || !is_array($data)) return false;
        update_option( 'wp_total_job_count', count($data) );
        foreach ($data as $job) {
            $wpdb->replace($table, [
                'jobPostingID'    => $job['jobPostingID'],
                'jobTitle'        => $job['jobTitle'],
                'jobDescription'  => $job['jobDescription'],
                'requiredSkills'  => $job['requiredSkills'],
                'minQualification'=> $job['minQualification'],
                'jobLocation'     => $job['jobLocation'],
                'department'      => $job['department'],
                'designation'     => $job['designation'],
                'minExperience'   => $job['minExperience'],
                'jobDuration'     => $job['jobDuration'],
                'jobIndustry'     => $job['jobIndustry'],
                'monthlySalary'   => $job['monthlySalary'],
            ]);
        }
        return true;
    }

    public function wp_render_job_admin_page() {
        global $wpdb;
        $table = $wpdb->prefix . 'wp_jobs';

        if (isset($_POST['wp_sync_jobs'])) {
            $this->wp_sync_jobs_from_api();
            echo '<div class="updated"><p>Jobs synced successfully.</p></div>';
        }

        $jobs = $wpdb->get_results("SELECT * FROM $table ORDER BY id DESC");

        echo "<div class='wrap'><h1>Job Openings</h1>";
        echo "<form method='post'><button type='submit' name='wp_sync_jobs' class='button button-primary'>Sync Now</button></form><br>";

        if (!$jobs) {
            echo "<p>No jobs found.</p>";
        } else {
            echo "<table class='wp-list-table widefat fixed striped posts'>
                    <thead>
                        <tr>
                            <th>Sr.</th>
                            <th>Job Post Id</th>
                            <th>Job Title</th>
                            <th>Location</th>
                            <th>Industry</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>";
            $i = 1;
            foreach ($jobs as $job) {
                echo "<tr>
                        <td>{$i}</td>
                        <td>{$job->jobPostingID}</td>
                        <td>{$job->jobTitle}</td>
                        <td>{$job->jobLocation}</td>
                        <td>{$job->jobIndustry}</td>
                        <td><a href='" . admin_url('admin.php?page=ajo-jobs&view=' . $job->id) . "' class='button'>View</a></td>
                    </tr>";
                $i++;
            }
            echo "</tbody></table>";
        }

        if (isset($_GET['view'])) {
            $job_id = intval($_GET['view']);
            $job = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE id=%d", $job_id));
            if ($job) {
                echo "<div style='margin-top:20px;padding:15px;border:1px solid #ddd;background:#fff;'>
                <h2><a href='?page=ajo-jobs/'>Back</a></h2>
                        <h2>{$job->jobTitle}</h2>
                        <p><b>Location:</b> {$job->jobLocation}</p>
                        <p><b>Industry:</b> {$job->jobIndustry}</p>
                        <p><b>Designation:</b> {$job->designation}</p>
                        <p><b>Experience:</b> {$job->minExperience}</p>
                        <p><b>Salary:</b> {$job->monthlySalary}</p>
                        <h3>Description</h3>
                        <p>{$job->jobDescription}</p>
                        <h3>Required Skills</h3>
                        <p>{$job->requiredSkills}</p>
                    </div>";
            }
        }

        echo "</div>";
    }
}
new AJO_Job_Admin();

