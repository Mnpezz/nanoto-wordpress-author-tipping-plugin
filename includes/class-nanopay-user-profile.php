<?php
class Nanopay_User_Profile {
    public function __construct() {
        // WooCommerce account page
        add_action('woocommerce_edit_account_form', array($this, 'add_nano_address_field'));
        add_action('woocommerce_save_account_details', array($this, 'save_nano_address_field'));

        // WordPress user profile
        add_action('show_user_profile', array($this, 'add_nano_fields'));
        add_action('edit_user_profile', array($this, 'add_nano_fields'));
        add_action('personal_options_update', array($this, 'save_nano_fields'));
        add_action('edit_user_profile_update', array($this, 'save_nano_fields'));
    }

    public function add_nano_address_field() {
        $user_id = get_current_user_id();
        $nano_address = get_user_meta($user_id, 'nano_address', true);
        $nano_default_tip = get_user_meta($user_id, 'nano_default_tip', true);
        ?>
        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label for="nano_address"><?php _e('Nano Address', 'nanopay'); ?></label>
            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="nano_address" id="nano_address" value="<?php echo esc_attr($nano_address); ?>" />
            <span><em><?php _e('Enter your Nano address to receive tips.', 'nanopay'); ?></em></span>
        </p>
        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label for="nano_default_tip"><?php _e('Default Tip Amount', 'nanopay'); ?></label>
            <input type="number" class="woocommerce-Input woocommerce-Input--text input-text" name="nano_default_tip" id="nano_default_tip" value="<?php echo esc_attr($nano_default_tip); ?>" step="0.001" min="0" />
            <span><em><?php _e('Enter the default tip amount in Nano.', 'nanopay'); ?></em></span>
        </p>
        <?php
    }

    public function save_nano_address_field($user_id) {
        if (isset($_POST['nano_address'])) {
            update_user_meta($user_id, 'nano_address', sanitize_text_field($_POST['nano_address']));
        }
        if (isset($_POST['nano_default_tip'])) {
            update_user_meta($user_id, 'nano_default_tip', sanitize_text_field($_POST['nano_default_tip']));
        }
    }

    public function add_nano_fields($user) {
        ?>
        <h3>Nano Tipping Settings</h3>
        <table class="form-table">
            <tr>
                <th><label for="nano_address">Nano Address</label></th>
                <td>
                    <input type="text" name="nano_address" id="nano_address" value="<?php echo esc_attr(get_user_meta($user->ID, 'nano_address', true)); ?>" class="regular-text" />
                    <p class="description">Enter your Nano address to receive tips.</p>
                </td>
            </tr>
            <tr>
                <th><label for="nano_default_tip">Default Tip Amount</label></th>
                <td>
                    <input type="number" name="nano_default_tip" id="nano_default_tip" value="<?php echo esc_attr(get_user_meta($user->ID, 'nano_default_tip', true)); ?>" class="regular-text" step="0.001" min="0" />
                    <p class="description">Enter the default tip amount in Nano.</p>
                </td>
            </tr>
        </table>
        <?php
    }

    public function save_nano_fields($user_id) {
        if (current_user_can('edit_user', $user_id)) {
            update_user_meta($user_id, 'nano_address', sanitize_text_field($_POST['nano_address']));
            update_user_meta($user_id, 'nano_default_tip', sanitize_text_field($_POST['nano_default_tip']));
        }
    }
}