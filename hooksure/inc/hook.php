<?php
// Exit if accessed directly.
defined('ABSPATH') || exit;

function hooksure_trigger_webhook_on_submission($result, $server, $request) {
    if ($request->get_route() === '/sureforms/v1/submit-form' && $request->get_method() === 'POST') {
        
        $form_data = $request->get_params();
        $submitted_form_id = $form_data['form_id'] ?? $form_data['_form_id'] ?? $form_data['form-id'] ?? null;

        if ($submitted_form_id) {
            $form_webhooks = get_option('hooksure_webhooks', []);
            $webhook_url = $form_webhooks[$submitted_form_id] ?? null;

            if ($webhook_url) {
                

                // Send data to the matched webhook URL
                $response = hooksure_send_data_to_webhook($webhook_url, $form_data);

                // Additional detailed logging for response
                if (is_wp_error($response)) {
                    
                } else {
                    $response_code = wp_remote_retrieve_response_code($response);
                    $response_body = wp_remote_retrieve_body($response);
                    
                }
            } else {
                
            }
        } else {            
        }
    }
    return $result;
}

function hooksure_send_data_to_webhook($url, $data) {
    $response = wp_remote_post($url, [
        'body'    => wp_json_encode($data), // Updated to use wp_json_encode()
        'headers' => ['Content-Type' => 'application/json'],
    ]);

    return $response;
}

// Register webhook trigger
add_action('rest_api_init', function() {
    add_filter('rest_pre_dispatch', 'hooksure_trigger_webhook_on_submission', 10, 3);
});
