class Nanopay_Settings {
    // ... existing code ...

    public function create_admin_page() {
        // ... existing code ...
        ?>
        <tr valign="top">
            <th scope="row"><?php _e('Allow User Editing', 'nanopay'); ?></th>
            <td>
                <input type="checkbox" name="nanopay_allow_user_editing" value="1" <?php checked(1, get_option('nanopay_allow_user_editing'), true); ?> />
                <label for="nanopay_allow_user_editing"><?php _e('Allow users to edit their own Nano address', 'nanopay'); ?></label>
            </td>
        </tr>
        <?php
        // ... existing code ...
    }

    public function page_init() {
        // ... existing code ...
        register_setting('nanopay_option_group', 'nanopay_allow_user_editing', array($this, 'sanitize'));
    }

    // ... existing code ...
}