<?php
if (!defined('ABSPATH')) exit;

class AJO_Admin {

    public function __construct() {
        add_action('admin_menu', [$this, 'add_menu']);
        add_action('admin_post_ajo_export_json', [$this, 'export_json']);
        add_action('wp_ajax_ajo_get_applicants', [$this, 'ajo_get_applicants']);
    }

    public function add_menu() {
        add_menu_page(
            'WP Job Opening',
            'WP Job Opening',
            'manage_options',
            'ajo-dashboard',
            [$this, 'render_dashboard'],
            'dashicons-id',
            26
        );

        add_submenu_page(
            'ajo-dashboard',
            'Applicants',
            'Applicants',
            'manage_options',
            'ajo-applicants',
            [$this, 'render_admin_page']
        );
    }

    public function render_dashboard() {
        global $wpdb;

        $table = $wpdb->prefix . "ajo_applicants";
        $applicants_count = $wpdb->get_var("SELECT COUNT(*) FROM $table");

        $jobs_count = get_option('wp_total_job_count');
        
        echo "<div class='wrap'><h1>WP Job Opening Dashboard</h1>";
        echo "<div style='display:flex;gap:20px;margin-top:20px;'>";

        echo "<div style='flex:1;background:#fff;padding:20px;border:1px solid #ddd;border-radius:8px;text-align:center;'>
                <h2>{$jobs_count}</h2>
                Job Opening
              </div>";

        echo "<div style='flex:1;background:#fff;padding:20px;border:1px solid #ddd;border-radius:8px;text-align:center;'>
                <h2>{$applicants_count}</h2><p>Applicants</p>
                <a href='" . admin_url("admin.php?page=ajo-applicants") . "' class='button button-primary'>View Applicants</a>
              </div>";

        echo "<div style='flex:1;background:#fff;padding:20px;border:1px solid #ddd;border-radius:8px;text-align:center;'>
                <h2>⚙️</h2><p>Settings</p>
                <a href='" . admin_url("admin.php?page=ajo-settings") . "' class='button'>Configure</a>
              </div>";

        echo "</div></div>";
    }

    public function ajo_get_applicants() {
        check_ajax_referer('wp_ajax_nonce', 'security');

        global $wpdb;
        $table = $wpdb->prefix . "ajo_applicants";

        // Sanitize input
        $paged = isset($_POST['paged']) ? max(1, intval($_POST['paged'])) : 1;
        $items_per_page = 10;
        $offset = ($paged - 1) * $items_per_page;

        $search = sanitize_text_field($_POST['search'] ?? '');
        $from = sanitize_text_field($_POST['from_date'] ?? '');
        $to = sanitize_text_field($_POST['to_date'] ?? '');

        $where = "1=1";
        if ($search) {
            $where .= $wpdb->prepare(" AND (full_name LIKE %s OR email LIKE %s)", "%$search%", "%$search%");
        }
        if ($from && $to) {
            $where .= $wpdb->prepare(" AND submitted_at BETWEEN %s AND %s", $from . " 00:00:00", $to . " 23:59:59");
        }

        $total_items = $wpdb->get_var("SELECT COUNT(*) FROM $table WHERE $where");

        $rows = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $table WHERE $where ORDER BY submitted_at DESC LIMIT %d OFFSET %d",
                $items_per_page,
                $offset
            )
        );

        ob_start();

        if ($rows) {
            foreach ($rows as $row) {
                $cv = esc_url($row->cv);
                echo "<tr>
                        <td>{$row->job_id}</td>
                        <td>{$row->job_post}</td>
                        <td>{$row->full_name}<br>
                        <b>Current CTC :</b> {$row->current_ctc} <br>
                        <b>Marital Status :</b> {$row->marital_status} <br>
                        <b>Qualification :</b> {$row->qualification} <br>
                        </td>
                        <td>{$row->email}</td>
                        <td>{$row->phone}</td>
                        <td style='padding-left:35px;'><a href='$cv' target='_blank'><span class='dashicons dashicons-download'></span> </a></td>
                        <td>{$row->submitted_at}</td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='7' align='center'>No applicants found.</td></tr>";
        }

        $table_html = ob_get_clean();

        wp_send_json_success([
            'table_rows' => $table_html,
            'total_pages' => ceil($total_items / $items_per_page),
        ]);
    }

    public function render_admin_page() {
        echo "<div class='wrap'><h1>Applicants</h1>";

        echo "<form id='ajo-filter-form'>";
        echo "<input type='hidden' name='page' value='ajo-applicants' />";
        echo "<input type='text' id='search' name='search' placeholder='Search name/email' value='" . esc_attr($_GET['search'] ?? '') . "' /> ";
        echo "From: <input type='date' id='from_date' name='from_date' value='" . esc_attr($_GET['from_date'] ?? '') . "' /> ";
        echo "To: <input type='date' id='to_date' name='to_date' value='" . esc_attr($_GET['to_date'] ?? '') . "' /> ";
        echo "<button type='submit' class='button button-secondary'>Filter</button>";
        echo "</form><br>";

        echo "<table class='widefat fixed striped'>";
        echo "<thead><tr><th>jobPostingID</th><th>jobTitle</th><th>Name</th><th>Email</th><th>Phone</th><th align='center'>Download CV</th><th>Date</th></tr></thead>";
        echo "<tbody id='ajo-applicants-table-body'>";
        echo "</tbody>";
        echo "</table>";

        echo "<div id='ajo-pagination' class='tablenav'><div class='tablenav-pages'></div></div>";

        echo "</div>";
    }
}
new AJO_Admin();

function fjp_trim_description($text, $word_limit = 50) {
    $text = str_replace(["\r\n", "\r"], "\n", $text);

    // Replace line breaks with <br> so they're preserved visually
    $text = str_replace("\n", '', $text);

    // Strip all tags except <br>
    $text = strip_tags($text, '<br>');

    // Split into words (including <br> as separate word due to spacing above)
    $words = preg_split('/\s+/', $text);

    if (count($words) > $word_limit) {
        $words = array_slice($words, 0, $word_limit);
        return implode(' ', $words) . '...';
    }

    return implode(' ', $words);
}

function getIndustry()
{
    $api_url  = get_option('ajo_job_api_url');
    $username = get_option('ajo_job_username');
    $password = get_option('ajo_job_password');
    $in = [];
    $auth = base64_encode("$username:$password");
    $args = [
        'headers' => [
            'Authorization' => 'Basic ' . $auth,
        ],
        'timeout' => 15,
    ];
    $response = wp_remote_get($api_url, $args);

    if (is_wp_error($response)) {
        wp_send_json_error(['message' => 'API request failed']);
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
    $industry_list = $data['data'] ?? (isset($data[0]) ? $data : []);

    foreach ($industry_list as $industry) {
        $in[] = $industry['jobIndustry'];
    }
    return is_array($in) ? array_unique($in) : $in;
}
