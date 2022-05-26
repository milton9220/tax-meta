<?php
/****
Plugin Name:Tax Meta
Plugin URI:
Author: Milton
Author URI:
Description: Our 2019 default theme is designed to show off the power of the block editor. It features custom styles for all the default blocks, and is built so that what you see in the editor looks like what you'll see on your website. Twenty Nineteen is designed to be adaptable to a wide range of websites, whether you’re running a photo blog, launching a new business, or supporting a non-profit. Featuring ample whitespace and modern sans-serif headlines paired with classic serif body text, it's built to be beautiful on all screen sizes.
Version: 1.0
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain:omb-metabox 
******/

class Tax_Meta{
    public function __construct(){
        add_action('plugins_loaded',array($this,'omb_loaded_text_domain'));
        add_action('init',array($this,'tax_bootstraping'));
        add_action('category_add_form_fields',array($this,'tax_add_form_fields'));
        add_action('category_edit_form_fields',array($this,'tax_edit_form_fields'));
        add_action('create_category',array($this,'taxm_save_category_meta'));
        add_action('edit_category',array($this,'taxm_update_category_meta'));
    }
    public function omb_loaded_text_domain(){
        load_plugin_textdomain( 'omb-metabox ', false, dirname( __FILE__ ) . "/languages" );
    }
    public function tax_bootstraping(){
        $arguments=array(
            'type' =>'string',
            'sanitize_callback'  =>'sanitize_text_field',
            'single'  =>true,
            'description'  =>'Sample meta field for taxonomy',
            'show_in_rest'  =>true
        );
        register_meta( 'term', 'taxm_extra_meta', $arguments);
    }
    public function tax_add_form_fields(){
        ?>
        <div class="form-field term-description-wrap">
            <label for="tag-description"><?php _e('Extra Fields','omb-metabox'); ?></label>
            <input name="tax_extra_field" id="tax-extra-field" type='text' value=''>
            <p>Sample meta field for taxonomy.</p>
        </div>
    <?php 
    }
    public function tax_edit_form_fields($term){ 
        $extra_info=get_term_meta( $term->term_id, 'taxm_extra_meta', true );
        ?>
        <tr class="form-field form-required term-name-wrap">
			<th scope="row"><label for="name"><?php _e('Extra Fields','omb-metabox'); ?></label></th>
			<td><input name="tax_extra_field" id="tax_extra_field" type="text" value="<?php echo esc_attr($extra_info); ?>" size="40" aria-required="true">
			<p class="description">Sample meta field for taxonomy.</p></td>
		</tr>
    <?php
    }
    public function taxm_save_category_meta($term_id){
        if(wp_verify_nonce( $_POST['_wpnonce_add-tag'], 'add-tag' )){
            $extra_info=sanitize_text_field($_POST['tax_extra_field']);
            update_term_meta($term_id , 'taxm_extra_meta',$extra_info );
        }
        
    }
    public function taxm_update_category_meta($term_id){
        if(wp_verify_nonce( $_POST['_wpnonce'], "update-tag_{$term_id}" )){
            $extra_info=sanitize_text_field($_POST['tax_extra_field']);
            update_term_meta($term_id , 'taxm_extra_meta',$extra_info );
        }
        
    }
}
new Tax_Meta();