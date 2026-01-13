<?php
if (!defined('ABSPATH')) exit;

class AJO_Settings {
    public function __construct() {
        add_action('admin_menu', [$this, 'add_settings_page']);
        add_action('admin_init', [$this, 'register_settings']);
    }

    public function add_settings_page() {
        add_submenu_page(
            'ajo-dashboard',
            'Settings',
            'Settings',
            'manage_options',
            'ajo-settings',
            [$this, 'render_settings_page']
        );
    }

    public function register_settings() {
        register_setting('ajo_settings_group', 'ajo_job_api_url');
        register_setting('ajo_settings_group', 'ajo_cv_api_url');
        register_setting('ajo_settings_group', 'ajo_job_username');
        register_setting('ajo_settings_group', 'ajo_job_password');
    }

    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1>WP Job Opening Settings</h1>
            <form method="post" action="options.php">
                <?php settings_fields('ajo_settings_group'); ?>
                <?php do_settings_sections('ajo_settings_group'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row">Job API URL</th>
                        <td><input type="text" name="ajo_job_api_url" value="<?php echo esc_attr(get_option('ajo_job_api_url')); ?>" class="regular-text" /></td>
                    </tr>
                    <tr>
                        <th scope="row">CV Submission API URL</th>
                        <td><input type="text" name="ajo_cv_api_url" value="<?php echo esc_attr(get_option('ajo_cv_api_url')); ?>" class="regular-text" /></td>
                    </tr>
                    <tr>
                        <th scope="row">Job API Username</th>
                        <td><input type="text" name="ajo_job_username" value="<?php echo esc_attr(get_option('ajo_job_username')); ?>" class="regular-text" /></td>
                    </tr>
                    <tr>
                        <th scope="row">Job API Password</th>
                        <td><input type="text" name="ajo_job_password" value="<?php echo esc_attr(get_option('ajo_job_password')); ?>" class="regular-text" /></td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
}
new AJO_Settings();
