jQuery(function(jQuery) {  
	var pageAt = prettylistScriptParams.pageAt - 1;
	var currentPage = 1;
	//Get the file container
	var allFilesContainer = jQuery('.prettyFileList');
	//Get all the filelists on the page	
	var allFiles = jQuery('.prettylink',allFilesContainer);	
	//Get the number of files
	var totalFiles = allFiles.length;
	//Total pages	
	var totalPages = Math.ceil(allFiles.length / (pageAt + 1));
	
	var i = 0;//First array counter
	var x = 0;//Second array counter
	var pages = [];
	pages[0] = new Array(2); //Add a second dimension to the array
	//Chunk the files into sets
	allFiles.each(function(){
		if(x >= pageAt){	
			pages[i][x] = jQuery(this);//Add this file to this page			

			x = 0;//Reset counter
			i++;//Move page counter on
			pages[i] = new Array(2); //add an array to the next set

		}
		else{
			pages[i][x] = jQuery(this); //Add file to this page
			x++;//Move counter on
		}		
	});	
	
	ShowHidePages();//Set up pages
	
	//Add next and prev buttons if needed
	if(pageAt < totalFiles){
		allFilesContainer.append('<div class="prettyPagination"><a href="#" class="next">Next &raquo;</a><span class="pagingInfo">Page <span class="currentPage">1</span> of <span class="totalPages">' + totalPages + '</span></span></div>');
		jQuery('.prettyPagination').append('<a href="#" class="prev disabled">&laquo; Prev</a>');
		
		//Next click
		jQuery('.next',allFilesContainer).click(function()
		{
			if(currentPage < totalPages){
				currentPage++;
				ShowHidePages();
				//Make sure next is not disabled
				jQuery('.prev',allFilesContainer).removeClass('disabled');
				//Check to see if we hit the last page	
				if(currentPage == totalPages){
					//Disable the button
					jQuery(this).addClass('disabled');
				}
			}

			return false;
		});
		
		//Prev click
		jQuery('.prev',allFilesContainer).click(function()
		{
			//Check to see if on first page
			if(currentPage != 1){
				currentPage--;
				ShowHidePages();
				//Make sure next is not disabled
				jQuery('.next',allFilesContainer).removeClass('disabled');
				//Check to see if we hit the first page					
				if(currentPage == 1){
					//Disable the button
					jQuery(this).addClass('disabled');
				}
			}
			
			return false;
		});
	}
	
	function ShowHidePages(){
		//Hide all pages
		allFiles.hide();
		//Show the current page
		for(var i=0; i<pages[currentPage - 1].length; i++) {
			if(pages[currentPage - 1][i] != undefined){
				pages[currentPage - 1][i].show();
			}			
		}
		
		//Set paging info
		jQuery('.currentPage').text(currentPage);
	}
});  