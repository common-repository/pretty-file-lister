=== Plugin Name ===
Contributors: smartredfox
Tags: download link, file listing, pdf, doc, xls, zip, file links
Requires at least: 3.0.0
Tested up to: 3.3.1
Stable tag: 0.4

The Pretty file list plugin lists files attached to a post, displays them, and pages them in an attractive format without you having to do any work. It’s designed to be very easy to use, and even comes with built in styles for different set ups.

== Description ==

The Pretty File Lister plugin lists files attached to the current post of page. All you need to do is add the shortcode to your page or post, specify the types of files you want displayed, and pick the number of files you want per page. You can purchase [additional styles](http://www.smartredfox.com/pretty-file-links-wordpress-plugin/style-pack-for-pretty-file-links/), and see examples at [SmartRedFox.com](http://www.smartredfox.com/pretty-file-lister/).

Shortcode format is [prettyfilelist type="xls,pdf" filesperpage="5"]. Options for the file type are excel,pdf,doc,zip,ppt.

== Installation ==

1. Upload the PrettyFileLister folder to the /wp-content/plugins/ directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Configure the plugin styles by going to the Pretty File Lister option under settings in your admin menu.

== Usage ==

1. Go to the the post/page you want to display the Pretty file list on.
2. Upload the files you want to list using the Media Uploader (the same way you would with images).
3. Make sure to give the uploaded files sensible titles as these will be used by the list.
4. Close the media uploader modal.
5. Click the Pretty File List button and Choose your options - this will insert a Shortcode into your content.
6. Save your post/page.

That's it. You should now have a paged list of all of the attached files sorted by creation date.

== Screenshots ==

1. The Pretty file list plugin showing attached Excel files.
2. An example of the shiny Pretty File List style.
3. An example of the split Pretty File List style.

== Changelog ==

= 0.1 =
* Initial release

= 0.2 =
* Fixed initial style not set (BUG)
* Added editor button to insert shortcode (FEATURE)
* Added shortcode wizard to insert shortcode (FEATURE)
* Now reads custom css files from a folder in your theme directory (stops files getting wiped after upgrade).

= 0.3 =
* Various small bugs fixed
* Changed jquery calls to work with older versions

= 0.4 =
* Various small bugs fixed
* Removed console.log() calls from admin

== Upgrade Notice ==

= 0.2 =
If you are using the Style Pack you will need to [redownload it (Style Pack v0.3)](http://www.smartredfox.com/style-pack-download/) and install it into your theme directory.
= 0.3 =
If you are using the Style Pack you will need to [redownload it (Style Pack v0.3)](http://www.smartredfox.com/style-pack-download/) and install it into your theme directory.