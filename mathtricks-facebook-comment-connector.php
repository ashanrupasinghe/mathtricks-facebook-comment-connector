<?php
/*
Plugin Name: MathTricks Facebook Comment Connector
Description: You have blog post/page and Facebook post/video, You need show Facebook comment under blob post/page. You can use this
Author: Ashan Rupasinghe
Version: 1.0
Author URI: https://www.facebook.com/MathTricsWebSolutions/
*/

function mathtricks_fbcc_setup_post_type()
{
  
}
add_action( 'init', 'mathtricks_fbcc_setup_post_type' );
 
function mathtricks_fbcc_install()
{
    
 
    
}
register_activation_hook( __FILE__, 'mathtricks_fbcc_install' );

function mathtricks_fbcc_deactivation()
{
    
}
register_deactivation_hook( __FILE__, 'mathtricks_fbcc_deactivation' );

//register_uninstall_hook(__FILE__, 'mathtricks_fbcc_function_to_run');

function mathtricks_fbcc_facebook_post_id_meta_box_markup($object)
{
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");

    ?>
        <div>
            <label for="mathtricks_fbcc_facebook-post-id-meta-box">Facebook Post Id</label>
            <input name="mathtricks_fbcc_facebook-post-id-meta-box" type="text" value="<?php echo get_post_meta($object->ID, "mathtricks_fbcc_facebook-post-id-meta-box", true); ?>">
        </div>
    <?php  
}

function mathtricks_fbcc_add_facebook_post_id_meta_box()
{
    add_meta_box("mathtricks_fbcc_facebook-post-id-meta-box", "Facebook Post ID", "mathtricks_fbcc_facebook_post_id_meta_box_markup", "post", "side", "low", null);
}

add_action("add_meta_boxes", "mathtricks_fbcc_add_facebook_post_id_meta_box");

function mathtricks_fbcc_save_facebook_post_id_meta_box($post_id, $post, $update)
{
    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
        return $post_id;

    if(!current_user_can("edit_post", $post_id))
        return $post_id;

    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;

    $slug = "post";
    if($slug != $post->post_type)
        return $post_id;

    $facebook_post_id_meta_box_value = "";
   

    if(isset($_POST["mathtricks_fbcc_facebook-post-id-meta-box"]))
    {
        $facebook_post_id_meta_box_value = $_POST["mathtricks_fbcc_facebook-post-id-meta-box"];
    }   
    update_post_meta($post_id, "mathtricks_fbcc_facebook-post-id-meta-box", $facebook_post_id_meta_box_value);    
}

add_action("save_post", "mathtricks_fbcc_save_facebook_post_id_meta_box", 10, 3);


function mathtricks_fbcc_get_comments(){

}
function mathtricks_fbcc_get_comment_template()
{
    if( isset( $_GET['mod']) && 'yes' == $_GET['mod'] )
        $template = plugin_dir_path( __FILE__ ) . 'comments_fb.php';

    return $template;
}