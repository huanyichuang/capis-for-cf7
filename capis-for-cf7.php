<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://huanyichuang.com/
 * @since             1.0.0
 * @package           Capis_For_Cf7
 *
 * @wordpress-plugin
 * Plugin Name:       Conversions API for Contact Form 7
 * Plugin URI:        https://huanyichuang.com/
 * Description:       This is an extension for Contact Form 7 to activate the integration with Meta's conversions API.
 * Version:           1.0.0
 * Author:            Huanyi Chuang
 * Author URI:        https://huanyichuang.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       capis-for-cf7
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'CAPIS_FOR_CF7_VERSION', '1.0.0' );
define( 'CAPIS_PIXEL_ID', '137567753520385' );

function cf7_facebook_pixel_settings_page() {
    ?>
    <div class="wrap">
        <h2>Facebook Pixel</h2>
        <form method="post" action="options.php">
            <?php
            settings_fields('cf7_facebook_pixel_settings');
            do_settings_sections('cf7_facebook_pixel');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

function cf7_facebook_pixel_settings_init() {
    add_settings_section(
        'cf7_facebook_pixel_section',
        '',
        '',
        'cf7_facebook_pixel'
    );

    add_settings_field(
        'cf7_facebook_pixel_api_token',
        'API Token',
        'cf7_facebook_pixel_api_token_callback',
        'cf7_facebook_pixel',
        'cf7_facebook_pixel_section'
    );

	add_settings_field(
        'cf7_facebook_pixel_id',
        'Pixel ID',
        'cf7_facebook_pixel_id_callback',
        'cf7_facebook_pixel',
        'cf7_facebook_pixel_section'
    );

	add_settings_field(
        'cf7_facebook_test_event',
        'Test event',
        'cf7_facebook_test_event_callback',
        'cf7_facebook_pixel',
        'cf7_facebook_pixel_section'
    );

    register_setting(
        'cf7_facebook_pixel_settings',
        'cf7_facebook_pixel_api_token'
    );

	register_setting(
        'cf7_facebook_pixel_settings',
        'cf7_facebook_pixel_id'
    );

	register_setting(
        'cf7_facebook_pixel_settings',
        'cf7_facebook_test_event',
    );
}

function cf7_facebook_pixel_api_token_callback() {
    $api_token = get_option('cf7_facebook_pixel_api_token');
    ?>
    <input type="text" name="cf7_facebook_pixel_api_token" value="<?php echo esc_attr($api_token); ?>" />
    <?php
}

function cf7_facebook_pixel_id_callback() {
    $pixel_id = get_option('cf7_facebook_pixel_id');
    ?>
    <input type="text" name="cf7_facebook_pixel_id" value="<?php echo esc_attr($pixel_id); ?>" />
    <?php
}

function cf7_facebook_test_event_callback() {
    $test_event = get_option('cf7_facebook_test_event');
    ?>
    <input type="text" name="cf7_facebook_test_event" value="<?php echo esc_attr($test_event); ?>" />
    <?php
}

add_action('admin_init', 'cf7_facebook_pixel_settings_init');
add_action('admin_menu', 'cf7_facebook_pixel_add_options_page');

function cf7_facebook_pixel_add_options_page() {
    add_options_page(
        'Facebook Pixel',
        'Facebook Pixel',
        'manage_options',
        'cf7_facebook_pixel',
        'cf7_facebook_pixel_settings_page'
    );
}
add_action('wpcf7_mail_sent', 'cf7_facebook_pixel_send_conversion', 10, 1);

function cf7_facebook_pixel_send_conversion($contact_form) {
    $api_token = get_option('cf7_facebook_pixel_api_token');
	$pixel_id = get_option('cf7_facebook_pixel_id');
	$test_event = get_option('cf7_facebook_test_event');
    $submission = WPCF7_Submission::get_instance();

	if ($submission) {
        $data = $submission->get_posted_data();

        // 在這裡組織要傳送到 Facebook Pixel Conversions API 的資料

        $url = 'https://graph.facebook.com/v13.0/' . $pixel_id . '/events';
        $args = array(
            'method'  => 'POST',
            'headers' => array(
                'Content-Type' => 'application/json',
				// Detect current agent
				'User-Agent' => $_SERVER['HTTP_USER_AGENT'],
            ),
            'body'    => json_encode(array(
                'data' => array(
                    array(
                        'event_name' => 'lead',
                        'event_time' => time(),
                        'user_data'  => array(
                            'em' => hash( 'sha256', $data['your-email'] ), // 根據表單欄位名稱更改
                        ),
                    ),
                ),
                'access_token' => $api_token,
            )),
        );

		if ( ! empty( $test_event ) ) {
			$args['test_event_code'] = $test_event; // 測試用，請移除
		}

        // 使用 cURL 或其他方式發送 POST 請求到 Facebook Pixel Conversions API
        $response = wp_remote_post($url, $args);

        if (!is_wp_error($response)) {
            $response_code = wp_remote_retrieve_response_code($response);
            if ($response_code === 200) {
                // 成功回應處理
				error_log( 'Facebook Pixel Conversions API Success: ' . $response_code );
            } else {
                // 錯誤回應處理
				error_log( 'Facebook Pixel Conversions API Response Error: ' . $response_code );
				error_log( 'Facebook Pixel Conversions API Response: ' . print_r( $response['body']['error']['error_user_msg'], true ) );
            }
        } else {
            // 錯誤處理
			error_log( 'Facebook Pixel Conversions API No Response: ' . $response->get_error_message() );
        }
    }
}
