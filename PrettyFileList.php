<?php
/*
   Plugin Name: Pretty file list
   Plugin URI: http://www.smartredfox.com/prettylist
   Description: A plugin that lists files attached to the current post/page.
   Version: 0.4
   Author: James Botham
   Author URI: http://www.smartredfox.com
   License: GPL2
   */
class PrettyFileListPlugin_Class{  
  
    /** 
     * Class Constructor 
     */  
    public function __construct() {  
  
        $this->plugin_defines();
        $this->setup_actions();
		
		if(!is_admin())
		{
			add_shortcode('prettyfilelist', array($this, 'prettyfilelist_shortcode'));
		}
    }
	
	 /** 
     * Defines to be used anywhere in WordPress after the plugin has been initiated. 
     */  
    function plugin_defines()
	{
        define( 'PRETTY_FILE_LIST_PATH', trailingslashit( WP_PLUGIN_DIR.'/'.str_replace(basename( __FILE__ ),"",plugin_basename( __FILE__ ) ) ) );  
        define( 'PRETTY_FILE_LIST_URL' , trailingslashit( WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__ ),"",plugin_basename( __FILE__ ) ) ) );    		
    }  
  
    /** 
     * Setup the actions to hook the plugin into WordPress at the appropriate places 
     */  
    function setup_actions(){
		if(!is_admin())
		{
			//Add stylesheets
			add_action('init', array($this,'prettyfilelist_stylesheets'));
			//Add javascript
			add_action('init', array($this,'srf_prettylist_frontend_scripts'));
		}
		else
		{			
			//Attach the settings menu
			add_action('admin_menu', array($this,'srf_prettylist_admin_menu'));
			//Attach save event for Settings page
			add_filter( 'attachment_fields_to_save', array($this,'srf_attachment_field_prettylist_save'), 10, 2 );				
			//Add shortcode button
			add_action('init', array($this,'add_button'));
		}
	}  
	  
	//Output shortcode
	public function prettyfilelist_shortcode($atts, $content = null){
		//Get attributes from shortcode
		extract(shortcode_atts(array(  
			"type" => "excel,pdf,doc,zip,ppt",
			"filesperpage" => "7"
		), $atts)); 
		
		$html ='';
		
		//Set paging numbers
		$params = array('pageAt' => $filesperpage);  
		wp_localize_script('prettylistjs', 'prettylistScriptParams', $params );
		wp_enqueue_script("jquery");
		wp_enqueue_script('prettylistjs' );	
		
		//Get a string of mime types we want
		$mimeTypesToGet = $this->TypeToMime($type);
		//Check to see if we want all types	
		//If all types add filters
		//Get all attachments of the right type
		//TODO:Add option for 'orderby' => 'title'
		$args = array( 'post_type' => 'attachment', 'numberposts' => -1, 'post_status' => null, 'post_parent' => get_the_id(), 'post_mime_type' => $mimeTypesToGet); 
		$attachments = get_children( $args);
		if ($attachments) {
			$html .= '<div class="prettyFileList">';
			foreach ( $attachments as $attachment ) {
				$html .= $this->srf_get_formatted_link($attachment);
			}
			$html .= '</div>';
		}
		
		return $html;
	}

	//Add Stylesheet
	public function prettyfilelist_stylesheets()
	{
		//Get user selected stylesheet if any
		$options['stylesheet_to_use'] = get_option('stylesheet_to_use'); 
		$option = $options['stylesheet_to_use'];
		$stylesheet_url = PRETTY_FILE_LIST_URL . 'styles/prettylinks.css';

		//Add our prettylist stylesheet
		if($options['stylesheet_to_use'] != ""){
			//See if the selected style has a hash in it (this means it's an alt style)
			if(strpos($options['stylesheet_to_use'],'#') > 0){
				//See if the file exists in the template directory first
				//if(!file_exists($stylesheet_url)){
				$cleanOption = str_replace("#", "", $option);
				$stylesheet_url = (get_bloginfo('template_url') . '/prettystyles/' . $cleanOption);
				//}
			}
			else{
				//If not, fallback to plugin version
				$stylesheet_url = (PRETTY_FILE_LIST_URL . 'styles/' . $options['stylesheet_to_use']);
			}
		}
		
		//echo $stylesheet_url;
		
		wp_register_style('srfprettylistStyleSheets', $stylesheet_url);
		wp_enqueue_style( 'srfprettylistStyleSheets');
	}  
	
	private function TypeToMime($typesToConvert){
		$unconvertedTypes = explode(",", $typesToConvert);
		$typeString = "";
		$i = 0;
		foreach ($unconvertedTypes as $type) {
		//Check to see if a comma is needed
		if ($i > 0){
			$typeString	.= ",";
		}
		
		  switch ($type) {
		  case "pdf":
			$typeString .= "application/pdf,application/x-pdf,application/acrobat,applications/vnd.pdf,text/pdf,text/x-pdf";
			break;
		  case "excel":
		  case "xls":
			$typeString .= "application/vnd.ms-excel";  
			break;
		  case "doc":
			$typeString .= "doc";
			break;
		  case "ppt":
			$typeString .= "application/mspowerpnt,application/vnd-mspowerpoint,application/powerpoint,application/x-powerpoint,application/vnd.ms-powerpoint,application/mspowerpoint,application/ms-powerpoint";
			break;      
		  case "zip":
			$typeString .= "application/zip,application/x-zip,application/x-zip-compressed,application/x-compress,application/x-compressed,multipart/x-zip";
			break;
			}
			$i++;
		}
		return $typeString;
	}
	
	public function srf_get_formatted_link($attachment){
		//Get the mime type and title
		$mime_type = $attachment->post_mime_type; //Get the mime-type
		$title_temp = $attachment->post_title; //Get the title
		
		//check mime-type against our list of types we style links for
		$type="";    
		switch ($mime_type) {
		  case "application/pdf":
		  case "application/x-pdf": 
		  case "application/acrobat": 
		  case "applications/vnd.pdf":
		  case "text/pdf":
		  case "text/x-pdf":
			$type = "pdf";
			break;
		  case "application/vnd.ms-excel":
			$type = "xls";  
			break;
		  case "application/doc":
		  case "'application/vnd.msword": 
		  case "application/vnd.ms-word":
		  case "application/winword":
		  case "application/word":
		  case "application/x-msw6":
		  case "application/x-msword":
		  case "application/msword":
			$type = "doc";
			break;
		  case "application/mspowerpnt":
		  case "application/vnd-mspowerpoint":
		  case "application/powerpoint":
		  case "application/x-powerpoint":
		  case "application/vnd.ms-powerpoint":
		  case "application/mspowerpoint":
		  case "application/ms-powerpoint":
			$type = "ppt";
			break;      
		  case "application/zip":
		  case "application/x-zip":
		  case "application/x-zip-compressed":
		  case "application/x-compress":
		  case "application/x-compressed":
		  case "multipart/x-zip":
			$type = "zip";
			break;		 
		}
		 $html = "";
		 $src = "";
		//If we matched a type create our shortcode
		if($type != "")
		{
		  $src = wp_get_attachment_url( $attachment->ID );
		  $html = '<a class="prettylink '. $type . '" href="' . $src .'">'. $title_temp . '</a>';
		}    
		return $html; // return new $html	 
	}
	
	/****************************************************************************************
	ADMIN STUFF
	****************************************************************************************/
	
	
	//QUEUE ADMIN MENU
	public function srf_prettylist_admin_menu()
	{  
	  // this is where we add our plugin to the admin menu  
	  $page = add_options_page('prettylist', 'Pretty file list', 'manage_options', dirname(__FILE__), array($this,'srf_prettylist_settings'));  
	  //Add admin preview script only to our pages
	  add_action( 'admin_print_styles-' . $page, array($this,'srf_prettylist_admin_scripts'));
	} 

	/**********************
	LOAD ADMIN MENU SCRIPTS
	**********************/
	function srf_prettylist_admin_scripts()
	{
	  $params = array('pluginUrl' => PRETTY_FILE_LIST_URL,'altPluginUrl' => get_bloginfo('template_directory') . '/prettystyles/');
	  wp_register_script('prettylistpreviewer', PRETTY_FILE_LIST_URL . '/js/style_previewer.js');
	  wp_localize_script('prettylistpreviewer', 'prettylistScriptParams', $params );
	  wp_enqueue_script('prettylistpreviewer' );	 
	}

	function srf_prettylist_frontend_scripts(){
	 //Localize and script enqueue done in shortcode so we can get attributes
	  wp_register_script('prettylistjs', PRETTY_FILE_LIST_URL . '/js/PrettyFileList.js');
	}
	
	/**********************
	CONSTRUCT ADMIN MENU
	**********************/
	function srf_prettylist_settings()  
	{    
	  //Blank message created
	  $message = "";
		
	  //The @ suppresses an error if post[action] is not set
	  if (@$_POST['action'] == 'update')  
	  {  
		//Set the option to the form variable
		update_option('stylesheet_to_use', $_POST['stylesheet_name']);  
		//Send a message to the user to let them know it was updated
		$message = '<div id="message" class="updated fade"><p><strong>Options Saved</strong></p></div>';  
	  }

	  //Path to directories to scan
	  $directory = PRETTY_FILE_LIST_PATH . '/styles/';
	  $altDirectory = get_template_directory() . '/prettystyles/';
	  
	  //get all css files with a .css extension.
	  $styles = glob($directory . "*.css");
	  $altStyles = glob($altDirectory . "*.css");  
	  
	  //Get our options
	  $options['stylesheet_to_use'] = get_option('stylesheet_to_use');

	//Display options form
	echo '<div style="background-color:#eee;border:solid 1px #ccc;border-radius:3px;float:right;margin:20px;padding:10px;width:300px;">
			<div style="background-color:#fff;border:solid 1px #ccc;float:right;">
				<img src="http://www.smartredfox.com/wp-content/uploads/2012/02/All_styles-150x150.png" style="margin:5px;" />
			</div>
			<h2>Want more styles?</h2>
			<p>Buy the Styles Pack (15 extra styles) for just $3.</p>
			<p><a class="button-secondary" href="http://www.smartredfox.com/pretty-file-links-wordpress-plugin/style-pack-for-pretty-file-links/">Buy the pack now</a></p>
			</div>
			<div class="wrap">'.$message.
		'<div id="icon-options-general" class="icon32"><br /></div>  
		<h2>Pretty file list Settings</h2>  
	  
		<form method="post" action="">  
		<input type="hidden" name="action" value="update" />  
	  
		<h3>Which stylesheet would you like to use</h3>      
		<style id="Previewer"></style>
		<p><select name="stylesheet_name" id="show_pages">';
		
	  //print each available css file
	  foreach($styles as $style)
	  {
		echo '<option value="' . basename($style) .'"' . (basename($style) == $options['stylesheet_to_use'] ? 'selected="selected"' : '')  . '>' . basename($style) . '</option>';
	  }
	  foreach($altStyles as $style)
	  {
		echo '<option value="' . basename($style) .'#"' . (basename($style) == $options['stylesheet_to_use'] ? 'selected="selected"' : '')  . '>' . basename($style) . ' (Custom)</option>';
	  }
	  
	  echo '</select></p><p><input type="submit" class="button-primary" value="Save Changes" /></p>
		<h3 style="clear:both;width:100%;">Current style example:</h3>
		<a href="#" class="prettylink pdf">A pdf example pretty file link</a>
		<a href="#" class="prettylink xls">An Excel spreadsheet example pretty file link</a>
		<a href="#" class="prettylink ppt">A PowerPoint example pretty file link</a>
		<a href="#" class="prettylink doc">A Word document example pretty file link</a>
		<a href="#" class="prettylink zip">A Zip file example pretty file link</a>
		</form>    
	  </div>'; 
	} 
	  
	/**********************
	SAVE ADMIN MENU SETTINGS
	**********************/

	/*Save value of "srf_prettylist" selection in media uploader */
	 function srf_attachment_field_prettylist_save( $post, $attachment ) {
	  //if( isset( $attachment['srf_prettylist-include'] ) ) 
	  //update_post_meta( $post['ID'], 'srf_prettylist-include', $attachment['srf_prettylist-include'] );  
	  return $post;
	}
	
	/*********************
	ADD SHORTCODE BUTTON
	**********************/	
	
    function add_button() {  
       if ( current_user_can('edit_posts') &&  current_user_can('edit_pages') )  
       {  
         add_filter('mce_external_plugins', array($this,'add_plugin'));
         add_filter('mce_buttons', array($this,'register_button'));
       }  
    }  
	
    function register_button($buttons) {  
       array_push($buttons, "prettylist");  
       return $buttons;  
    }  
	
	function add_plugin($plugin_array) {  
	   $plugin_array['prettylist'] = PRETTY_FILE_LIST_URL.'js/prettygen.js';  
	   return $plugin_array;  
	} 
	
	
}  
  
//Engage.  
$PrettyFileListPlugin_Class = new PrettyFileListPlugin_Class();  
?>