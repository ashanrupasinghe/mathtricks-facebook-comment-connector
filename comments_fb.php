<?php
//Required password to comment
if ( post_password_required() ) { ?>
	<p><?php esc_html_e('This post is password protected. Enter the password to view comments.', 'grandphotography' ); ?></p>
<?php
	return;
}
?>
<h6 class="subtitle"><span><?php echo esc_html_e('Leave a reply', 'grandphotography' ); ?></span></h6><hr class="title_break"/><br class="clear"/>
<?php 
//Display Comments
$blog_post_id=$post->ID;//blog postid $post->ID
$fb_post_id=get_post_meta($blog_post_id, 'facebook-post-id-meta-box', true);//fb post id 1604432556509494,
$fb_access_token=get_constant_val('FB_LIFETIME_TOKEN');
if($fb_post_id!=null){
$json=file_get_contents("https://graph.facebook.com/".$fb_post_id."/comments?access_token=".$fb_access_token."&filter=stream&fields=parent.fields(id),message,from,created_time,message_tags,object");

$obj = json_decode($json);
$comments=[];
foreach($obj->data as $coment){
	if(isset($coment->parent)){
		$comments[$coment->parent->id]['child'][]=$coment;
	}else{
		$comments[$coment->id]['parent']=$coment;
	}
}
$have_fb_comments=false;
if(sizeof($comments)>0){
$have_fb_comments=true;
}

if( $have_fb_comments ) { ?> 

<div>
<a name="comments"></a>

<?php
foreach($comments as $comment){
?>
<!--parent-->
<div class="comment" id="comment-<?php echo $comment['parent']->id; ?>">
		<div class="gravatar">
		<?php $picture= 'http://graph.facebook.com/'.$comment['parent']->from->id.'/picture?height=60' ?>
         	<img alt="" src="<?php echo $picture;?>" srcset="<?php echo $picture;?>" class="avatar avatar-60 photo" height="60" width="60">      	</div>
      
      	<div class="right">
						
								<h7><?php echo $comment['parent']->from->name;?></h7>
						
			<div class="comment_date"><?php echo date_i18n(THEMEDATEFORMAT, strtotime($comment['parent']->created_time)); ?> <?php echo esc_html_e('at', 'grandphotography') ?> <?php echo date_i18n(THEMETIMEFORMAT, strtotime($comment['parent']->created_time)); ?></div>
			      									<br class="clear">
      		<p><?php echo $comment['parent']->message; ?></p>

      	</div>
    </div>
        <br class="clear"><hr><div style="height:20px"></div>
<!--/..parent-->

<?php
if(isset($comment['child'])){
	foreach($comment['child'] as $child_comment){
?>	

<!--child-->
<ul class="children">
   
	<div class="comment" id="comment-<?php echo $child_comment->id;?>">
		<div class="gravatar">
		<?php $picture= 'http://graph.facebook.com/'.$child_comment->from->id.'/picture?height=60' ?>
         	<img alt="" src="<?php echo $picture;?>" srcset="<?php echo $picture;?>" class="avatar avatar-60 photo" height="60" width="60">      	</div>
      
      	<div class="right">
						
								<h7><?php echo $child_comment->from->name; ?></h7>
						
			<div class="comment_date"><?php echo date_i18n(THEMEDATEFORMAT, strtotime($child_comment->created_time)); ?> <?php echo esc_html_e('at', 'grandphotography') ?> <?php echo date_i18n(THEMETIMEFORMAT, strtotime($child_comment->created_time)); ?></div>
			      			<br class="clear">
      		<p><?php echo $child_comment->message;?></p>

      	</div>
    </div>
    <!-- #comment-## -->  
	
</ul>
<!--/..child-->
<?php		
	}
}
?>

	


<!-- End of thread -->  
<div style="height:10px"></div>

<?php 
} 

}?>  
</div>
<?php }?>


