<?php

/*
Plugin Name: Add iban field to woocommerce
Description: This plugin automatically creates a new field for (IBAN Bank Number) in the WooCommerce user account details, allowing your customers to add their Bank IBAN number for get they refund money from the store.
Author: Arash Mehrani
Author URI: https://teamara.ir/
Version: 1.0.0
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: sheba
Domain Path: /languages
*/

load_plugin_textdomain('sheba', false, dirname(plugin_basename(__FILE__)) . '/languages/');
// Add the field "user_sheba" sheba = iban
add_action('woocommerce_edit_account_form', 'aiftw_woocommerce_edit_account_form');
function aiftw_woocommerce_edit_account_form()
{
    $user = wp_get_current_user();
    ?>
    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="user_sheba"><?php _e(__('IBAN', 'sheba'), 'woocommerce'); ?></label>
        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="user_sheba"
               id="user_sheba" value="<?php echo esc_attr($user->user_sheba); ?>"/>
        <span>
            <em><?php _e(__('Bank account information (IBAN) for refund, Bank account must be in the name of the owner of the this account', 'sheba'), 'woocommerce'); ?></em>
        </span>
    </p>
    <?php
}

// Save the field 'user_sheba'
add_action('woocommerce_save_account_details', 'aiftw_woocommerce_save_user_sheba_account_details', 12, 1);
function aiftw_woocommerce_save_user_sheba_account_details($user_id)
{
    // For User sheba
    if (isset($_POST['user_sheba'])) {
        update_user_meta($user_id, 'user_sheba', sanitize_text_field($_POST['user_sheba']));
    }
    // For Billing email (added related to user_sheba field)
    if (isset($_POST['account_email'])) {
        update_user_meta($user_id, 'billing_email', sanitize_text_field($_POST['account_email']));
    }
}

function aiftw_manage_users_columns($columns)
{
    $columns['user_sheba'] = __('IBAN', 'sheba');

    return $columns;
}

add_filter('manage_users_columns', 'aiftw_manage_users_columns', 10, 1);

function aiftw_manage_users_sheba_column($output, $column_key, $user_id)
{

    switch ($column_key) {
        case 'user_sheba':
            $value = get_user_meta($user_id, 'user_sheba', true);

            return $value;
            break;
        default:
            break;
    }

    return $output;
}

add_filter('manage_users_custom_column', 'aiftw_manage_users_sheba_column', 10, 3);
?>