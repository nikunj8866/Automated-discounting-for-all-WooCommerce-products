<?php 
$wadap_date_range = get_option('wadap_date_range', true);
$wadap_discount = get_option('wadap_discount', true);
$wadap_header_message = get_option('wadap_header_message', true);
$wadap_background = get_option('wadap_background', true);
$wadap_text_color = get_option('wadap_text_color', true);
?>
<div class="wrap">
    <h1><?php _e('Discount Settings', 'wadap'); ?></h1>
    <form method="post" id="save-wadap">
        <table class="form-table" role="presentation">
            <tbody>
                <tr class="form-field">
                    <th ><?php _e('Discount Header Content', 'wadap'); ?></th>
                    <td>
                        <?php
                        $settings = array( 'media_buttons' => false,'quicktags' => false,  'editor_height' => 200, 'textarea_rows' => 10, 'editor_class' => 'field-required' );
                        $content = $wadap_header_message;                     
                        $editor_id = 'wadap_header_message';
                        wp_editor( $content, $editor_id,$settings );
                        ?>
                        <p>Note: You can use [wadap-date] shortcode for display date and [wadap-discount] for display discount.</p>
                    </td>
                    <tr class="form-field">
                        <th>Background Color</th>
                        <td>
                            <input type="text" name="wadap_background" id="wadap_background" class="field-required" value="<?php echo (!empty($wadap_background)) ? $wadap_background : '#000'; ?>">
                            <p>Background color of discount messsage</p>
                        </td>
                    </tr>
                    <tr class="form-field">
                        <th>Text Color</th>
                        <td>
                            <input type="text" name="wadap_text_color" id="wadap_text_color" class="field-required" value="<?php echo (!empty($wadap_text_color)) ? $wadap_text_color : '#ffffff'; ?>">
                            <p>Text color of discount messsage</p>
                        </td>
                    </tr>
                    <tr class="form-field">
                        <th ><?php _e('Date', 'wadap'); ?></th>
                        <td>
                            <input type="text" name="wadap_date_range" id="wadap_date_range" class="field-required" value="<?php echo $wadap_date_range; ?>">
                        </td>
                    </tr>
                    <tr class="form-field">
                        <th ><?php _e('Discount(%)', 'wadap'); ?></th>
                        <td>
                            <input type="number" name="wadap_discount" min="0" class="field-required" value="<?php echo $wadap_discount; ?>">
                        </td>
                    </tr>
                    <tr class="form-field">
                        <?php wp_nonce_field( 'wadap-settings-save', 'wadap-settings-save' ); ?>
                        <input type="hidden" name="action" value="wadap_save_setting">
                        <td colspan="2"><?php submit_button(__('Save', 'wadap'), 'primary', 'save_changes'); ?></td>
                    </tr>
                </tr>
            <tbody>
        </table>
    </form>
</div>