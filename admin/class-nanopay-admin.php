class Nanopay_Admin {
    // ... existing code ...

    public function add_nano_address_field($user) {
        $nano_address = get_user_meta($user->ID, 'nano_address', true);
        ?>
        <h3><?php _e('Nano Address', 'nanopay'); ?></h3>
        <table class="form-table">
            <tr>
                <th><label for="nano_address"><?php _e('Nano Address', 'nanopay'); ?></label></th>
                <td>
                    <input type="text" name="nano_address" id="nano_address" value="<?php echo esc_attr($nano_address); ?>" class="regular-text" />
                    <p class="description"><?php _e('Enter the user\'s Nano address for receiving tips.', 'nanopay'); ?></p>
                </td>
            </tr>
        </table>
        <?php
    }

    public function save_nano_address_field($user_id) {
        if (current_user_can('edit_user', $user_id)) {
            update_user_meta($user_id, 'nano_address', sanitize_text_field($_POST['nano_address']));
        }
    }

    // ... existing code ...

    public function __construct() {
        // ... existing code ...
        add_action('show_user_profile', array($this, 'add_nano_address_field'));
        add_action('edit_user_profile', array($this, 'add_nano_address_field'));
        add_action('personal_options_update', array($this, 'save_nano_address_field'));
        add_action('edit_user_profile_update', array($this, 'save_nano_address_field'));
    }
}