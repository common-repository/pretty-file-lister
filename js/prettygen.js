(function() {  
    tinymce.create('tinymce.plugins.prettylist', {  
        init : function(ed, url) {  
            ed.addButton('prettylist', {  
                title : 'Add a Pretty file list',  
                image : url.replace('/js','')+'/images/filelist.png',  
				cmd : 'mcebutton'
            });  
			
			// Register commands
			ed.addCommand('mcebutton', function() {
				ed.windowManager.open({
					file : url.replace('/js','')+'/includes/button_popup.php', // file that contains HTML for our modal window
					width : 260 + parseInt(ed.getLang('button.delta_width', 0)), // size of our window
					height : 240 + parseInt(ed.getLang('button.delta_height', 0)), // size of our window
					inline : 1
				}, {
					plugin_url : url
				});
			});
        },  
        getInfo : function() {
            return {
                longname : 'Example',
                author : 'Paul Robinson',
                authorurl : 'http://return-true.com',
                infourl : 'http://return-true.com',
                version : tinymce.majorVersion + "." + tinymce.minorVersion
           };
       }
    });  
    tinymce.PluginManager.add('prettylist', tinymce.plugins.prettylist);  
})();
