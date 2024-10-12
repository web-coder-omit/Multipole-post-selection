<?php

/**
 * Plugin Name: Meta box for Multipol post
 * Plugin URI:  Plugin URL Link
 * Author:      Plugin Author Name
 * Author URI:  Plugin Author Link
 * Description: This plugin make for pratice wich is "Multipol post".
 * Version:     0.1.0
 * License:     GPL-2.0+
 * License URL: http://www.gnu.org/licenses/gpl-2.0.txt
 * text-domain: mp_p
 */

// Languages file loaded
function plugin_file_function(){
    load_plugin_textdomain('mp_p', false, dirname(__FILE__) . "/languages");
}
add_action('plugins_loaded', 'plugin_file_function');

// Add Meta Box
function add_metabox_in_post(){
    add_meta_box('select_post_metabox', __('Select Posts', 'mp_p'), 'select_metabox_function', 'page');
}
add_action('add_meta_boxes', 'add_metabox_in_post');

// Save Meta Box Data
function save_metabox($post_id){

    if(!isset($_POST['mp_p_posts_nonce']) || !wp_verify_nonce($_POST['mp_p_posts_nonce'],'mp_p_posts')){
        return;
    }
    // Verify nonce
// if (!isset($_POST['mp_p_posts_nonce']) || !wp_verify_nonce($_POST['mp_p_posts_nonce'], 'mp_p_posts')) {
    //     return;
    // }
   // array_key_exists('names', $_POST) ? update_post_meta($post_id, 'save_name_fild', $_POST['names']) : '';
    
    // Save or delete the meta field
    if (array_key_exists('selected_post_id', $_POST)) {
        update_post_meta($post_id, 'mp_p_selected_posts', $_POST['selected_post_id']);
    } else {
        delete_post_meta($post_id, 'mp_p_selected_posts');
    }
}
add_action('save_post', 'save_metabox');

// Meta Box Callback Function
function select_metabox_function($post){
    // Retrieve saved value
    $selected_post_id = get_post_meta($post->ID, 'mp_p_selected_posts', true);
    print_r($selected_post_id);
    
    // Display nonce for security
    wp_nonce_field('mp_p_posts', 'mp_p_posts_nonce');
    
    // Fetch all posts to populate the dropdown
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => -1
    );
    $all_posts = get_posts($args);
    
    // Build the dropdown list
    $dropdown_list = '';
    foreach ($all_posts as $single_post) {
        $extra = ($single_post->ID == $selected_post_id) ? 'selected' : '';
        $dropdown_list .= sprintf("<option %s value='%s'>%s</option>", $extra, $single_post->ID, $single_post->post_title);
    }
    
    // Display the dropdown and label
    echo <<<EOD
        <h1>Select any post</h1>
        <label for="selected_post_id">Choose a post:</label>
        <select multiple="multiple" id="selected_post_id" name="selected_post_id[]">
            {$dropdown_list}
        </select>
    EOD;
}
?>