<?php
// Exit if accessed directly.
defined('ABSPATH') || exit;

// Add a submenu under Tools to manage Hooksure settings
add_action('admin_menu', 'hooksure_admin_menu', 20);

function hooksure_admin_menu() {
    add_submenu_page(
        'tools.php',                        // Parent slug (Tools menu)
        'Hooksure Settings',                // Page title
        'Hooksure',                         // Menu title
        'manage_options',                   // Capability
        'hooksure_settings',                // Menu slug
        'render_hooksure_admin_page'        // Callback function to render the page
    );
}

// Render the admin page for managing form-webhook pairs
function render_hooksure_admin_page() {
    // Handle form submission for saving new form-webhook pairs
    if (isset($_POST['hooksure_nonce']) && wp_verify_nonce($_POST['hooksure_nonce'], 'save_hooksure_settings')) {
        
        // Get and sanitize form data
        $form_id = sanitize_text_field($_POST['form_id']);
        $webhook_url = esc_url_raw($_POST['webhook_url']);
        $form_webhooks = get_option('hooksure_webhooks', []);

        // Save new form ID and webhook URL pairs
        if (!empty($form_id) && !empty($webhook_url)) {
            $form_webhooks[$form_id] = $webhook_url;
            update_option('hooksure_webhooks', $form_webhooks);
            echo '<div class="updated"><p>Webhook settings saved successfully.</p></div>';
        }
    }

    // Handle deletion of form-webhook pairs
    if (isset($_GET['delete_form_id'])) {
        $form_webhooks = get_option('hooksure_webhooks', []);
        $delete_form_id = sanitize_text_field($_GET['delete_form_id']);
        unset($form_webhooks[$delete_form_id]);
        update_option('hooksure_webhooks', $form_webhooks);
        echo '<div class="updated"><p>Form Webhook deleted successfully.</p></div>';
    }

    // Retrieve saved form-webhook pairs
    $form_webhooks = get_option('hooksure_webhooks', []);

    // Output the admin page HTML
    ?>
    <div class="wrap">
        <h1>Hooksure Settings</h1>
        
        <!-- Form to add new webhook mappings -->
        <form method="post">
            <?php wp_nonce_field('save_hooksure_settings', 'hooksure_nonce'); ?>
            <h2>Add New Form Webhook</h2>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="form_id">Form ID</label></th>
                    <td><input type="text" id="form_id" name="form_id" /></td>
                </tr>
                <tr>
                    <th scope="row"><label for="webhook_url">Webhook URL</label></th>
                    <td><input type="url" id="webhook_url" name="webhook_url" /></td>
                </tr>
            </table>

            <p class="submit"><button type="submit" class="button button-primary">Save Settings</button></p>
        </form>

        <!-- List existing form-webhook mappings -->
        <h2>Existing Form Webhooks</h2>
        <table class="widefat">
            <thead>
                <tr>
                    <th>Form ID</th>
                    <th>Webhook URL</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($form_webhooks)): ?>
                    <?php foreach ($form_webhooks as $form_id => $webhook_url): ?>
                        <tr>
                            <td><?php echo esc_html($form_id); ?></td>
                            <td><?php echo esc_url($webhook_url); ?></td>
                            <td>
                                <a href="<?php echo add_query_arg('delete_form_id', $form_id); ?>" class="button">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="3">No webhook mappings found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php
}
