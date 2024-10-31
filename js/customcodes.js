(function() {  
    tinymce.create('tinymce.plugins.prettylist', {  
        init : function(ed, url) {  
            ed.addButton('prettylist', {  
                title : 'Add a Pretty file list',  
                image : url.replace('/js','')+'/images/filelist.png',  
                onclick : function() {  
                     ed.selection.setContent('[prettyfilelist type="xls,zip,doc,ppt" filesperpage="5"]');  
  
                }  
            });  
        },  
        createControl : function(n, cm) {  
            return null;  
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