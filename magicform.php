<?php
/*
Plugin Name: Magicform
Plugin URI: https://medienwerke.de
Description: Ein kleines Test-Plugin für Formulare
Version: 1.0
Author: Daniel Koch
Author URI: https://medienwerke.de
License: GPL2
*/

// Enqueue scripts and styles
function magicform_enqueue_scripts() {
    // Enqueue CSS
    wp_enqueue_style('magicform-css', plugin_dir_url(__FILE__) . 'css/magicform.css');
}
add_action('wp_enqueue_scripts', 'magicform_enqueue_scripts');

function magicform_enqueue_admin_scripts() {
    if (is_admin()) {
        wp_enqueue_script('magicform-admin-script', plugin_dir_url(__FILE__) . 'js/magicform_admin.js', array('jquery'), '1.0', true);
        wp_enqueue_style('magicform-admin-css', plugin_dir_url(__FILE__) . 'css/magicform_admin.css');
    }
}
add_action('admin_enqueue_scripts', 'magicform_enqueue_admin_scripts');

// Define Plugin Settings
function magicform_settings() {
    add_options_page('Magicform Settings', 'Magicform', 'manage_options', 'magicform-settings', 'magicform_settings_page');
    register_setting('magicform-settings-group', 'mf_first_name_label');
    register_setting('magicform-settings-group', 'mf_last_name_label');
    register_setting('magicform-settings-group', 'mf_email_label');
    register_setting('magicform-settings-group', 'mf_subject_label');
    register_setting('magicform-settings-group', 'mf_message_label');
    register_setting('magicform-settings-group', 'mf_button_label');
    register_setting('magicform-settings-group', 'mf_success_message');
    register_setting('magicform-settings-group', 'mf_failure_message');
}
add_action('admin_menu', 'magicform_settings');

// Plugin Settings Page
function magicform_settings_page() {
    ?>
    <div class="wrap">
        <h2>Magicform Settings</h2>
        <form method="post" action="options.php">
            <?php settings_fields('magicform-settings-group'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row">First Name Label</th>
                    <td><input type="text" name="mf_first_name_label" value="<?php echo esc_attr(get_option('mf_first_name_label')); ?>"></td>
                </tr>
                <tr>
                    <th scope="row">Last Name Label</th>
                    <td><input type="text" name="mf_last_name_label" value="<?php echo esc_attr(get_option('mf_last_name_label')); ?>"></td>
                </tr>
                <tr>
                    <th scope="row">Email Label</th>
                    <td><input type="text" name="mf_email_label" value="<?php echo esc_attr(get_option('mf_email_label')); ?>"></td>
                </tr>
                <tr>
                    <th scope="row"> Subject Label</th>
                    <td><input type="text" name="mf_subject_label" value="<?php echo esc_attr(get_option('mf_subject_label')); ?>"></td>
                </tr>
                <tr>
                    <th scope="row">Message Label</th>
                    <td><input type="text" name="mf_message_label" value="<?php echo esc_attr(get_option('mf_message_label')); ?>"></td>
                </tr>
                <tr>
                    <th scope="row">Button Label</th>
                    <td><input type="text" name="mf_button_label" value="<?php echo esc_attr(get_option('mf_button_label')); ?>"></td>
                </tr>
                <tr>
                    <th scope="row">Success Message (Data stored and email sent)</th>
                    <td><textarea name="mf_success_message"><?php echo esc_attr(get_option('mf_success_message')); ?></textarea></td>
                </tr>
                <tr>
                    <th scope="row">Failure Message (Data could not be stored and/or email could not be sent)</th>
                    <td><textarea name="mf_failure_message"><?php echo esc_attr(get_option('mf_failure_message')); ?></textarea></td>
                </tr>
                <!-- Add similar rows for other labels -->
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Define the shortcode function
function magicform_shortcode_func() {
    $mf_first_name_label = get_option('mf_first_name_label', 'First Name');
    $mf_last_name_label = get_option('mf_last_name_label', 'Last Name');
    $mf_email_label = get_option('mf_email_label', 'Email');
    $mf_subject_label = get_option('mf_subject_label', 'Subject');
    $mf_message_label = get_option('mf_message_label', 'Message');
    $mf_button_label = get_option('mf_button_label', 'Submit');
    ob_start(); // Start output buffering
    ?>

    <div class="mf-form">
        <form id="magicform-body" method="post">
            <div class="mf-form-group">
                <input type="text" id="first-name" name="first_name" class="mf-form-control" required placeholder="<?php echo esc_html($mf_first_name_label); ?>" onfocus="this.placeholder=''" onblur="this.placeholder='<?php echo esc_html($mf_first_name_label); ?>'">
            </div>
            <div class="mf-form-group">
                <input type="text" id="last-name" name="last_name" class="mf-form-control" placeholder="<?php echo esc_html($mf_last_name_label); ?>" onfocus="this.placeholder=''" onblur="this.placeholder='<?php echo esc_html($mf_last_name_label); ?>'">
            </div>
            <div class="mf-form-group">
                <input type="email" id="email" name="email" class="mf-form-control" placeholder="<?php echo esc_html($mf_email_label); ?>" onfocus="this.placeholder=''" onblur="this.placeholder='<?php echo esc_html($mf_email_label); ?>'">
            </div>
            <div class="mf-form-group">
                <input type="text" id="subject" name="subject" class="mf-form-control" placeholder="<?php echo esc_html($mf_subject_label); ?>" onfocus="this.placeholder=''" onblur="this.placeholder='<?php echo esc_html($mf_subject_label); ?>'">
            </div>
            <div class="mf-form-group">
                <textarea id="message" name="message" class="mf-form-control" rows="5" placeholder="<?php echo esc_html($mf_message_label); ?>" onfocus="this.placeholder=''" onblur="this.placeholder='<?php echo esc_html($mf_message_label); ?>'"></textarea>
            </div>
            <div class="mf-form-group">
                <button type="submit" name="submit_form" class="mf-submit-btn btn btn-primary mf-form-control"><?php echo esc_html($mf_button_label); ?></button>
            </div>
        </form>
    </div>

    <?php
    if (isset($_POST['submit_form'])) {
        handle_form_submission();
    }
    
    return ob_get_clean(); // Return the buffered content
}
add_shortcode('magicform_shortcode', 'magicform_shortcode_func');

// Function to handle form submission
function handle_form_submission() {
    global $wpdb;

    $mf_success_message = get_option('mf_success_message', 'Form submitted successfully. Thank you!');
    $mf_failure_message = get_option('mf_failure_message', 'Error submitting form. Please try again later.');

    // Get form data
    $first_name = sanitize_text_field($_POST['first_name']);
    $last_name = sanitize_text_field($_POST['last_name']);
    $email = sanitize_email($_POST['email']);
    $subject = sanitize_text_field($_POST['subject']);
    $message = sanitize_textarea_field($_POST['message']);

    // Insert data into magicform database table
    $table_name = $wpdb->prefix . 'magicform_submissions';
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            first_name varchar(255) NOT NULL,
            last_name varchar(255) NOT NULL,
            email varchar(255) NOT NULL,
            subject varchar(255) NOT NULL,
            message text NOT NULL,
            submission_date datetime NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    
    $insert_result = $wpdb->insert(
        $table_name,
        array(
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'subject' => $subject,
            'message' => $message,
            'submission_date' => current_time('mysql')
        ),
        array(
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s'
        )
    );

    if ($insert_result === false) {
        // Error occurred during data insertion
        echo '<div class="notice notice-error"><p>' . $mf_failure_message . '</p></div>';
    } else {
        // Data insertion successful
        // Send email to administrator
        $to = get_option('admin_email');
        $subject = 'New form submission from ' . $first_name . " " . $last_name;
        $body = "First Name: $first_name\nLast Name: $last_name\nEmail: $email\nSubject: $subject\nMessage: $message";
        $headers = array('Content-Type: text/html; charset=UTF-8');
        $email_result = wp_mail($to, $subject, $body, $headers);
        
        if ($email_result) {
            // Email sent successfully
            echo '<div class="notice notice-success"><p>' . $mf_success_message . '</p></div>';
        } else {
            // Error occurred while sending email
            echo '<div class="notice notice-error"><p>' . $mf_failure_message . '</p></div>';
        }
    }
}
// Add an administration page
function magicform_admin_page() {
    add_menu_page(
        'Magicform', // Page title
        'Magicform', // Menu title
        'manage_options', // Capability
        'magicform-submissions', // Menu slug
        'magicform_admin_page_content', // Callback function,
        'dashicons-forms',
        30
    );
}
add_action('admin_menu', 'magicform_admin_page');

// Display the administration page content
function magicform_admin_page_content() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'magicform_submissions';
    
    if (isset($_GET['delete_submission']) && is_numeric($_GET['delete_submission'])) {
        // Delete submission if delete_submission parameter is set in URL
        $submission_id = intval($_GET['delete_submission']);
        $wpdb->delete($table_name, array('id' => $submission_id));
        echo '<div class="notice notice-success"><p>Submission deleted successfully.</p></div>';
    }

    $submissions = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);

    ?>
    <div class="wrap">
        <h2>Magicform Submissions</h2>
        <?php
        if ($submissions) {
            echo '<table class="wp-list-table widefat fixed striped">';
            echo '<thead><tr><th>Name</th><th>Email</th><th>Subject</th><th>Action</th><th></th></tr></thead>';
            echo '<tbody>';
            foreach ($submissions as $submission) {
                echo '<tr>';
                echo '<td>' . esc_html($submission['first_name'] . ' ' . $submission['last_name']) . '</td>';
                echo '<td>' . esc_html($submission['email']) . '</td>';
                echo '<td>' . esc_html($submission['subject']) . '</td>';
                echo '<td><a href="?page=magicform-submissions&delete_submission=' . $submission['id'] . '" class="mf-button-destructive">Delete</a></td>';
                echo '<td class="right-aligned"><a href="#" class="button button-small mf-expand-btn">Expand</a></td>';
                echo '</tr>';
                echo '<tr>';
                echo '<td class="mf-message-col" colspan="5"><div class="mf-admin-message-wrapper">';
                echo '<div class="mf-admin-message-area">';
                echo '<div class="mf-admin-message-header"><h4>Message</h4></div>';
                echo '<div class="mf-admin-message-text">' . esc_html($submission['message']) . '</div>';
                echo '</div></td>';
                echo '</tr>';

            }
            echo '</tbody></table>';
        } else {
            echo '<p>No submissions found.</p>';
        }
        ?>
    </div>
    <?php
}
