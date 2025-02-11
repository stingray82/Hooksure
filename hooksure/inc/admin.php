<?php
// Exit if accessed directly.
defined('ABSPATH') || exit;

// Add a submenu under Tools to manage Hooksure settings
add_action('admin_menu', 'hooksure_admin_menu', 20);

function hooksure_admin_menu() {
    add_submenu_page(
        'tools.php',                        // Parent slug (Tools menu)
        __('Hooksure Settings', 'hooksure'), // Page title
        __('Hooksure', 'hooksure'),         // Menu title
        'manage_options',                   // Capability
        'hooksure_settings',                // Menu slug
        'render_hooksure_admin_page'        // Callback function to render the page
    );
}

// Render the admin page for managing form-webhook pairs
function render_hooksure_admin_page() {
    // Check if SureForms is installed
    if (!defined('SRFM_FORMS_POST_TYPE')) {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html__('Hooksure Settings', 'hooksure'); ?></h1>
            <div class="notice notice-error">
                <p><strong><?php echo esc_html__('Error:', 'hooksure'); ?></strong> <?php echo esc_html__('Hooksure requires SureForms to be installed and activated for it to function. Please install SureForms.', 'hooksure'); ?> </p>
            </div>
        </div>
        <?php
        return; // Exit early to prevent errors
    }


    // Display deletion success message
    if (isset($_GET['deleted']) && $_GET['deleted'] == 1) {
        ?>
        <div class="notice notice-success is-dismissible">
            <p><?php echo esc_html__('Form Webhook deleted successfully.', 'hooksure'); ?></p>
        </div>
        <?php
    }

    // Handle form submission for saving new form-webhook pairs
    if (isset($_POST['hooksure_nonce'])) {
        $nonce = sanitize_text_field(wp_unslash($_POST['hooksure_nonce']));
        if (wp_verify_nonce($nonce, 'save_hooksure_settings')) {
            // Get and sanitize form data
            $form_id = isset($_POST['form_id']) ? sanitize_text_field(wp_unslash($_POST['form_id'])) : '';
            $webhook_url = isset($_POST['webhook_url']) ? esc_url_raw(wp_unslash($_POST['webhook_url'])) : '';
            $form_webhooks = get_option('hooksure_webhooks', []);

            // Save new form ID and webhook URL pairs
            if (!empty($form_id) && !empty($webhook_url)) {
                $form_webhooks[$form_id] = $webhook_url;
                update_option('hooksure_webhooks', $form_webhooks);
                echo '<div class="updated"><p>' . esc_html__('Webhook settings saved successfully.', 'hooksure') . '</p></div>';
            }
        }
    }

    // Retrieve saved form-webhook pairs
    $form_webhooks = get_option('hooksure_webhooks', []);

    // Fetch all forms of type SRFM_FORMS_POST_TYPE
    $args = [
        'post_type'   => SRFM_FORMS_POST_TYPE,
        'post_status' => 'publish',
        'posts_per_page' => -1,
    ];
    $forms = get_posts($args);
    $form_names = [];
    foreach ($forms as $form) {
        $form_names[$form->ID] = $form->post_title; // Map form ID to name
    }

    // Output the admin page HTML
    ?>
    <div class="wrap">
        <h1><?php echo esc_html__('Hooksure Settings', 'hooksure'); ?></h1>
        
        <!-- Form to add new webhook mappings -->
        <form method="post">
            <?php wp_nonce_field('save_hooksure_settings', 'hooksure_nonce'); ?>
            <h2><?php echo esc_html__('Add New Form Webhook', 'hooksure'); ?></h2>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="form_id"><?php echo esc_html__('Form', 'hooksure'); ?></label></th>
                    <td>
                        <select id="form_id" name="form_id">
                            <?php if (!empty($forms)): ?>
                                <?php foreach ($forms as $form): ?>
                                    <option value="<?php echo esc_attr($form->ID); ?>">
                                        <?php echo esc_html($form->post_title); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value=""><?php echo esc_html__('No forms available', 'hooksure'); ?></option>
                            <?php endif; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="webhook_url"><?php echo esc_html__('Webhook URL', 'hooksure'); ?></label></th>
                    <td><input type="url" id="webhook_url" name="webhook_url" /></td>
                </tr>
            </table>

            <p class="submit"><button type="submit" class="button button-primary"><?php echo esc_html__('Save Settings', 'hooksure'); ?></button></p>
        </form>

        <!-- List existing form-webhook mappings -->
        <h2><?php echo esc_html__('Existing Form Webhooks', 'hooksure'); ?></h2>
        <table class="widefat">
            <thead>
                <tr>
                    <th><?php echo esc_html__('Form ID', 'hooksure'); ?></th>
                    <th><?php echo esc_html__('Form Name', 'hooksure'); ?></th>
                    <th><?php echo esc_html__('Webhook URL', 'hooksure'); ?></th>
                    <th><?php echo esc_html__('Actions', 'hooksure'); ?></th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($form_webhooks)): ?>
                <?php foreach ($form_webhooks as $form_id => $webhook_url): ?>
                    <tr>
                        <td><?php echo esc_html($form_id); ?></td>
                        <td><?php echo esc_html($form_names[$form_id] ?? esc_html__('Unknown Form', 'hooksure')); ?></td>
                        <td><?php echo esc_url($webhook_url); ?></td>
                        <td>
                            <a href="<?php echo esc_url(wp_nonce_url(admin_url('tools.php?page=hooksure_settings&delete_form_id=' . $form_id), 'hooksure_delete_webhook')); ?>" class="button">
                            <?php echo esc_html__('Delete', 'hooksure'); ?>
                            </a>

                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4"><?php echo esc_html__('No webhook mappings found.', 'hooksure'); ?></td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php
}

function hooksure_handle_deletion() {
    if (isset($_GET['delete_form_id']) && isset($_GET['_wpnonce'])) {
        //Securely process nonce
        $nonce = isset($_GET['_wpnonce']) ? sanitize_text_field(wp_unslash($_GET['_wpnonce'])) : '';
        if (!wp_verify_nonce($nonce, 'hooksure_delete_webhook')) {
            wp_die(esc_html__('Security check failed. Try again.', 'hooksure'));
        }

        // Securely process delete_form_id
        $form_webhooks = get_option('hooksure_webhooks', []);
        $delete_form_id = isset($_GET['delete_form_id']) ? sanitize_text_field(wp_unslash($_GET['delete_form_id'])) : '';

        if (!empty($delete_form_id) && isset($form_webhooks[$delete_form_id])) {
            unset($form_webhooks[$delete_form_id]);
            update_option('hooksure_webhooks', $form_webhooks);
        }

        //Redirect with success message
        wp_safe_redirect(admin_url('tools.php?page=hooksure_settings&deleted=1'));
        exit;
    }
}
add_action('admin_init', 'hooksure_handle_deletion');
