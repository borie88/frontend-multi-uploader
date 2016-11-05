<?php
/*
Plugin Name: Frontend Multi Uploader
Plugin URI: https://github.com/borie88/frontend-multi-uploader/
Description: Creates a form that lets users create a new 'gallery' post in Wordpress, which also lets them upload multiple images at once into a repeating image field created by Wordpress Types. 
Form optimized for Bootstrap 3. Contribute to the plugin's Github!
Version: 0.1
Author: George Borrelli
Author URI: https://github.com/borie88/
*/

function insert_attachment($file_handler,$post_id,$setthumb='false') {
 global $wpdb;
  // check to make sure its a successful upload
  if ($_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK) __return_false();

  require_once(ABSPATH . "wp-admin" . '/includes/image.php');
  require_once(ABSPATH . "wp-admin" . '/includes/file.php');
  require_once(ABSPATH . "wp-admin" . '/includes/media.php');

  $attach_id = media_handle_upload( $file_handler, $post_id );


$image_url = wp_get_attachment_image_src(  $attach_id,'full' ); 
if ($setthumb){ 

  $wpdb->insert(
  $wpdb->prefix . 'postmeta', array(
                'post_id' => $post_id,
                'meta_key' => 'wpcf-gallery-image',
                'meta_value' => $image_url[0] ));



  }
  return $attach_id;
}

function post_gallery_form() {
	if(!isset($_POST['gallery-submit'])){
	echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post" id="gallery-form" enctype="multipart/form-data">';
	echo '<div class="form-group">';
	echo '<label for="post-title">Gallery Title</label>';
	echo '<input type="text" id="post-title" class="form-control" name="post_title" value="' . ( isset( $_POST["post_title"] ) ? esc_attr( $_POST["post_title"] ) : '' ) . '" size="40" />';
	echo '</div>';
	echo '<div class="form-group">';
	echo '<label for="post-content">Description</label>';
	echo '<input type="text" id="post-content" class="form-control" name="post_content" value="' . ( isset( $_POST["post_content"] ) ? esc_attr( $_POST["post_content"] ) : '' ) . '" size="200" />';
	echo '</div>';
	echo '<div class="form-group">';
	echo '<label for="photographer">Photographer Name</label>';
	echo '<input type="text" id="photographer" class="form-control" name="wcpf-photographer" value="' . ( isset( $_POST["wcpf-photographer"] ) ? esc_attr( $_POST["wcpf-photographer"] ) : '' ) . '" size="30" />';
	echo '</div>';
	echo '<div class="form-group">';
	echo '<label for="gallery-images">Upload Images</label>';
	echo '<input type="file" id="gallery-images" name="wpcf-gallery-image[]" multiple="multiple" accept="image/*" />';
	echo '<div class="preview-area col-sm-12"></div>';
	?>
<script type="text/javascript">
(function($) {
	var inputLocalFont = document.getElementById("gallery-images");
	inputLocalFont.addEventListener("change",previewImages,false); //bind the function to the input
	function previewImages(){
		var fileList = this.files;
		var anyWindow = window.URL || window.webkitURL;
			for(var i = 0; i < fileList.length; i++){
			  //get a blob to play with
			  var objectUrl = anyWindow.createObjectURL(fileList[i]);
			  $('.preview-area').append('<div class="col-sm-4"><div class="thumbnail"><img src="' + objectUrl + '" /></div></div>');
			  // get rid of the blob
			  window.URL.revokeObjectURL(fileList[i]);
			}
	}
})( jQuery );
</script>
	<?php
	echo '</div>';
	echo '<input type="submit" class="btn btn-default" name="gallery-submit" id="submit-gallery" value="Submit" />';
	echo '</form>';
	}
}

function upload_gallery_post() {

	// if the submit button is clicked, insert the post
	if ( isset( $_POST['gallery-submit'] ) && isset( $_POST['post_title'] ) && isset( $_FILES['wpcf-gallery-image'] ) ) {
		// sanitize form values
		$title        = sanitize_text_field( $_POST["post_title"] );
		$description  = sanitize_text_field( $_POST["post_content"] );
		$photographer = sanitize_text_field( $_POST["wcpf-photographer"] );
		$my_post = array(
			'post_title'    => $title,
			'post_content'  => $description,
			'post_status'   => 'publish',
			'post_type'     => 'gallery'
		);
		// Insert the post into the database.
		$post_id = wp_insert_post( $my_post );
		if ( $post_id && ! is_wp_error( $post_id ) ) {
			if ( $_FILES ) 
			{
				$files = $_FILES['wpcf-gallery-image'];
				 $count= count($files['name']);

				foreach ($files['name'] as $key => $value) {
					if ($files['name'][$key]) {
						$file = array(
							'name'     => $files['name'][$key],
							'type'     => $files['type'][$key],
							'tmp_name' => $files['tmp_name'][$key],
							'error'    => $files['error'][$key],
							'size'     => $files['size'][$key]
						);

						$_FILES = array("wpcf-gallery-image" => $file);

						foreach ($_FILES as $file => $array) {

							$newupload = insert_attachment($file,$post_id);
						}
					}
				}
			}
			update_post_meta( $post_id, 'wpcf-photographer', $photographer );
			$post_url = get_permalink($post_id);
			echo '<div>New gallery successfully created, you can click here to view it - <a href="'.$post_url.'">'.$title.'</a></div>';
		}
	}
}
function gallery_form_shortcode() {
	ob_start();
	post_gallery_form();
	upload_gallery_post();
	return ob_get_clean();
}

add_shortcode( 'gallery_post_form', 'gallery_form_shortcode' );
?>
