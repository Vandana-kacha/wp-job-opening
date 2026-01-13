<?php
defined('ABSPATH') || exit;

add_action('wp_ajax_wp_filter_jobs', 'wpfilter_jobs');
add_action('wp_ajax_nopriv_wp_filter_jobs', 'wp_filter_jobs');

function wp_filter_jobs() {
    check_ajax_referer('wp_ajax_nonce', 'security');
    $keyword  = sanitize_text_field($_POST['keyword'] ?? '');
    $industry = sanitize_text_field($_POST['industry'] ?? '');
    $location = sanitize_text_field($_POST['location'] ?? '');
    $zipcode  = sanitize_text_field($_POST['zipcode'] ?? '');
    $page     = intval($_POST['page'] ?? 1);
    $per_page = 10; // jobs per page

    $api_url  = get_option('ajo_job_api_url');
    $username = get_option('ajo_job_username');
    $password = get_option('ajo_job_password');

    $params = [
        'jobTitle'    => $keyword,
        'requiredSkills' => $keyword,
        'jobIndustry' => $industry,
        'jobLocation' => $location,
        'designation' => $zipcode,
        'page'        => $page,
        'limit'       => $per_page,
    ];

    $response = wp_remote_get(
        add_query_arg($params, $api_url),
        [
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode($username . ':' . $password),
                'Accept'        => 'application/json',
            ],
            'timeout' => 20,
        ]
    );

    if (is_wp_error($response)) {
        wp_send_json_error(['message' => 'API request failed']);
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
    $jobs = $data['data'] ?? (isset($data[0]) ? $data : []);
    $total_jobs = count($jobs);

    if (!empty($keyword)) {
        $jobs = array_filter($jobs, function($job) use ($keyword) {
            return stripos($job['jobTitle'] ?? '', $keyword) !== false
                || stripos($job['requiredSkills'] ?? '', $keyword) !== false;
        });
    }
    if (!empty($industry)) {
        $jobs = array_filter($jobs, function($job) use ($industry) {
            return stripos($job['jobIndustry'] ?? '', $industry) !== false;
        });
    }
    if (!empty($location)) {
        $jobs = array_filter($jobs, function($job) use ($location) {
            return stripos($job['jobLocation'] ?? '', $location) !== false;
        });
    }
    if (!empty($zipcode)) {
        $jobs = array_filter($jobs, function($job) use ($zipcode) {
            return stripos($job['designation'] ?? '', $zipcode) !== false;
        });
    }

    $total_jobs = count($jobs);
    $offset = ($page - 1) * $per_page;
    $jobs = array_slice($jobs, $offset, $per_page);

    ob_start();
    echo "<div class='row'>";
    echo '<div class="job-count col-lg-3">' . $total_jobs . ' Current Openings</div>';

    $total_pages = ceil($total_jobs / $per_page);
    if ($total_pages > 1) {
        echo '<div class="job-pagination col-lg-9">';
        if ($page > 1) {
            echo '<a href="#" class="page-link" data-page="1">&laquo;</a>';
            echo '<a href="#" class="page-link" data-page="' . ($page - 1) . '">&lt;</a>';
        } else {
            echo '<span class="page-link disabled">&laquo;</span>';
            echo '<span class="page-link disabled">&lt;</span>';
        }
        for ($i = 1; $i <= $total_pages; $i++) {
            $active = ($i == $page) ? 'active' : '';
            echo '<a href="#" class="page-link ' . $active . '" data-page="' . $i . '">' . $i . '</a>';
        }
        if ($page < $total_pages) {
            echo '<a href="#" class="page-link" data-page="' . ($page + 1) . '">&gt;</a>';
            echo '<a href="#" class="page-link" data-page="' . $total_pages . '">&raquo;</a>';
        } else {
            echo '<span class="page-link disabled">&gt;</span>';
            echo '<span class="page-link disabled">&raquo;</span>';
        }
        echo '</div>';
    }
    echo '<div style="margin-bottom:25px;"></div></div>';

    if (!empty($jobs)) {
        foreach ($jobs as $job) {
            $page_url = site_url( '/career/post-resume/' );
            ?>
            <div class="clear margin10">&nbsp;</div>
            <div class="job-container">
                <h3 class="job-title"><?php echo esc_html($job['jobTitle'] ?? 'Untitled'); ?> <hr> </h3>
                <!-- Job Details -->
                <div class="section-header">Job Details</div>
                <div class="job-details row">
                    <div class="col-md-3 text-center"><strong>Posted Date</strong><br><?php echo date("d-m-Y", strtotime($job['postedDate']));?></div>
                    <div class="col-md-3 text-center"><strong>Designation</strong><br><?php echo esc_html($job['designation'] ?? ''); ?></div>
                    <div class="col-md-3 text-center"><strong>Department</strong><br><?php echo esc_html($job['department'] ?? ''); ?></div>
                    <div class="highlight col-md-3 text-center"><strong>Industry</strong><br><?php echo esc_html($job['jobIndustry'] ?? ''); ?></div>
                </div>

                <!-- Job Description -->
                <div class="section-header">Job Description</div>
                <div class="job-meta row">
                    <div class="col-md-3 text-center"><strong>Salary Range</strong><br><?php echo esc_html($job['monthlySalary'] ?? ''); ?> <br> Per Month</div>
                    <div class="col-md-3 text-center"><strong>Location</strong><br><?php echo esc_html($job['jobLocation'] ?? ''); ?></div>
                    <div class="col-md-3 text-center"><strong>Qualification</strong><br><?php echo esc_html($job['minQualification'] ?? ''); ?></div>
                    <div class="col-md-3 text-center"><strong>Minimum Experience</strong><br><?php echo esc_html($job['minExperience'] ?? ''); ?></div>
                </div>

                <!-- Job Responsibilities -->
                <div class="description">
                    <div><strong>Job Description / Job Responsibilities</strong></div>
                    <div class="pt-3">
                        <?php
                        $desc = str_replace('*', '', $job['jobDescription']);
                        echo $desc;
                        ?>
                    </div>
                </div>

                <!-- Skills & Competencies -->
                <div class="skills">
                    <div><strong>Skills & Competencies</strong></div>
                    <div class="pt-3">
                        <?php
                        $rawDescription = esc_html($job['requiredSkills']);
                        $items = explode("\r\n", $rawDescription);

                        echo '<ol>';
                        foreach ($items as $item) {
                            $clean = trim($item, "* ");
                            $clean = trim($clean, "o ");
                            $clean = trim($clean, "• ");
                            if (!empty($clean)) {
                                echo "<li>$clean</li>";
                            }
                        }
                        echo '</ol>';
                        ?>
                    </div>
                </div>
                
                <div class="margin10">&nbsp;</div>
                <a href="<?php echo $page_url;?>?apply_id=<?php echo $job['jobPostingID'];?>&post=<?php echo urlencode($job['jobTitle']);?>" class="btn career-btn">Apply Now</a>

            </div>
            <?php
        }
        ?>
        <?php
    } else {
        echo '<p class="no-jobs">No jobs found.</p>';
    }

    $html = ob_get_clean();
    wp_send_json_success(['html' => $html]);
}
