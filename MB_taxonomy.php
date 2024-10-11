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
// add CSS file
// function my_plugin_design()
// {
//     wp_enqueue_style('my_css_style_file', plugin_dir_url(__FILE__) . 'asset/admin/css/style.css', null, time());
// }
// add_action('admin_enqueue_scripts', 'my_plugin_design');


function add_metabox_in_post(){
    add_meta_box('select_post_metabox', __('Select Posts', 'mp_p'), 'select_metabox_function', 'page');
}
// add_action('admin_menu','add_metabox_in_post');
add_action('add_meta_boxes','add_metabox_in_post');
function save_metabox(){
 if(! mp_p_is_secured('mp_p_posts_nonce','mp_p_posts',$post_id)){
    return $post_id;
 }
 $selected_post_id = $_POST['selected_post_id'];
 if($selected_post_id > 0){
    update_post_meta($post_id, 'mp_p_selected_posts', $selected_post_id);
 }
return $post_id;


}
add_action('save_post','save_metabox');

function select_metabox_function($post){
   // $selected_post_id = get_post_meta($post->ID,'mp_p_selected_posts',true);
    $selected_post_id = get_post_meta($post->ID,'mp_p_selected_posts',true);
    echo $selected_post_id;
    wp_nonce_field('mp_p_posts', 'mp_p_posts_nonce');
    $args = array(
        'post_type' => 'post',
        'posts_per_page'=>-1
        );
    $all_post = get_posts($args);
    $dropdown_list ='';
    foreach($all_post as $post){
        $extra = '';
        if(get_the_ID()==$selected_post_id){
            $extra = 'selected';
        }
        // $post_id = $post->ID;
        // $post_title = $post->post_title; 
       // echo '<h2>' . esc_html($post_title).'</h2>';
       $dropdown_list .= sprintf("<option %s value='%s'>%s</option>",$extra,$post->ID,$post->post_title);
    }
        // $dropdown_list = '';
        // $_posts = new wp_query($args);
        // while($_posts->have_posts()){
        // $_posts->the_post();
        // $dropdown_list .=sprintf("<option value='%s'>%s</option>",get_the_ID(),get_the_title());
        // }
        // wp_reset_query();






 
    $mata_imge = <<<EOD
     

    <h1>Select any post</h1>
    <label for="selected_post_id">Choose a post:</label>
    <select id="selected_post_id" name="selected_post_id">
        {$dropdown_list}
    </select>
  
EOD;
    echo $mata_imge;

}




// function save_selected_post_meta($post_id) {
//     // Check if nonce is set
//     if (!isset($_POST['selected_post_meta_nonce'])) {
//         return;
//     }

//     // Verify that the nonce is valid
//     if (!wp_verify_nonce($_POST['selected_post_meta_nonce'], 'save_selected_post_meta_nonce')) {
//         return;
//     }

//     // Check if it's an autosave
//     if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
//         return;
//     }

//     // Check user permissions
//     if (!current_user_can('edit_post', $post_id)) {
//         return;
//     }

//     // Check if the selected_post_id is set
//     if (isset($_POST['selected_post_id'])) {
//         // Sanitize the user input
//         $selected_post_id = sanitize_text_field($_POST['selected_post_id']);

//         // Update the meta field in the database
//         update_post_meta($post_id, '_selected_post_id', $selected_post_id);
//     }
// }
// add_action('save_post', 'save_selected_post_meta');
if(!function_exists('mp_p_is_secured')){
    function mp_p_is_secured($nonce_field,$action,$post_id){
        $nonce = isset($_POST[$nonce_field]) ? $_POST[$nonce_field] :'';
        if($nonce == ''){
            return false;
        }
        if(!wp_verify_nonce($nonce, $action)){
            return false;
        }
        if( ! current_user_can('edit_post',$post_id)){
            return false;
        }
        if(wp_is_post_autosave($post_id)){
            return false;
        }
        if(wp_is_post_revision($post_id)){
            return false;
        }
    }
}

?>