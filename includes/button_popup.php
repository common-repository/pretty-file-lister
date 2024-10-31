<?php
// this file contains the contents of the popup window
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Insert File List</title>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="../../../../wp-includes/js/tinymce/tiny_mce_popup.js"></script>
<style type="text/css" src="../../../../wp-includes/js/tinymce/themes/advanced/skins/wp_theme/dialog.css"></style>
<style type="text/css">
#button-dialog h2{color:#069;padding-top:0;}
#button-dialog .fieldContainer{margin-top:5px;}
#button-dialog ul{margin-top:5px;}
#button-dialog ul,
#button-dialog li{list-style-type:none;margin-left:0;padding-left:0;}
#button-dialog select{padding:5px;width:70px;}
#button-dialog .textfield{padding:5px;}
#button-dialog #ButtonBox{border-top:solid 1px #ccc;margin-top:10px;padding:5px;text-align:right;}
#button-dialog .button{display:block;line-height:24px;float:right;text-align:center;text-decoration:none;}
</style>

<script type="text/javascript">
 
var ButtonDialog = {
	local_ed : 'ed',
	init : function(ed) {
		ButtonDialog.local_ed = ed;
		tinyMCEPopup.resizeToInnerSize();
	},
	insert : function insertButton(ed) {
		// Try and remove existing style / blockquote
		//tinyMCEPopup.execCommand('mceRemoveNode', false, null);
		 
		//Get all checked items
		var types = jQuery(':checked','#TypeList');

		var typeString = "";
		//Make them into a string
		types.each(function(){
			if(typeString != ""){
				typeString += ',';
			}
			typeString += jQuery(this).val();
		});
		
		var filesPerPage = jQuery('#FilesPerPage').val();
		 
		var output = '';
		//[prettyfilelist type="xls,zip,doc,ppt" filesperpage="2"]
		// setup the output of our shortcode
		output = '[prettyfilelist ';
		output += 'type="' + typeString + '" ';
		output += 'filesPerPage="' + filesPerPage + '"]';
			
		// inserts the shortcode into the active editor
		tinyMCE.activeEditor.execCommand('mceInsertContent', 0, output);
		
		// closes Thickbox
		tinyMCEPopup.close();
	}
};
tinyMCEPopup.onInit.add(ButtonDialog.init, ButtonDialog);
 
</script>

</head>
<body>
	<div id="button-dialog">
		<form action="/" method="get" accept-charset="utf-8">
			<h2>Insert a pretty file list</h2>
			<div class="fieldContainer">
				<label>File types to show:</label>
				<ul id="TypeList">
					<li><label><input type="checkbox" name="pdfcheck" value="pdf">PDF</label></li>
					<li><label><input type="checkbox" name="xlscheck" value="xls">Excel</label></li>
					<li><label><input type="checkbox" name="doccheck" value="doc">Word Doc</label></li>
					<li><label><input type="checkbox" name="pptcheck" value="ppt">Powerpoint</label></li>
					<li><label><input type="checkbox" name="zipcheck" value="zip">Zip file</label></li>
				<ul>
			</div>
		
			<div class="fieldContainer">
				<label for="button-url">Files per page</label>
				<select name="FilesPerPage" class="" id="FilesPerPage">
				  <option value="3">3</option>
				  <option value="5">5</option>
				  <option value="10">10</option>
				  <option value="15">15</option>
				</select> 
			</div>
			
			<div id="ButtonBox">	
				<a href="javascript:ButtonDialog.insert(ButtonDialog.local_ed)" id="insert" class="button">Insert</a>
			</div>
		</form>
	</div>
</body>
</html>