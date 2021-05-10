
function addPagination(data, recordsPerPage, pages){
	var div = document.getElementById("tableContainer");
	
	var ul = document.createElement('ul');
	ul.id = "userListPaginate";
	ul.setAttribute('class', 'pagination margin-zero');
	
	var li = document.createElement('li');
	var a = document.createElement('a');
	a.href = "index_using_rest_third.php?page=1";
	a.title = "Go to the first page.";
	a.innerHTML = "First Page";
	li.appendChild(a);
	ul.appendChild(li);
	
	var totalPages = Math.ceil(data/recordsPerPage);
	var range = 2;
	var initialNum = pages - range;
	var conditionLimitNum = (pages + range)  + 1;
	console.log(totalPages);
	
	for (var x = initialNum; x < conditionLimitNum; x++) {
	    if ((x > 0) && (x <= totalPages)) {
	        if (x == pages) {
	        	var li = document.createElement('li');
	        	li.setAttribute('class', 'active');
	        	var a = document.createElement('a');
	        	a.href = "#";
	        	a.innerHTML = x + " <span class=\"sr-only\">(current)</span>";
	        	li.appendChild(a);
	        	ul.appendChild(li);
	        } 
	        else {
	        	var li = document.createElement('li');
	        	var a = document.createElement('a');
	        	a.href = "index_using_rest_third.php?page=" + x;
	        	a.innerHTML = x; 
	        	li.appendChild(a);
	        	ul.appendChild(li);	        	
	        }
	    }		
	}

	if(pages < totalPages){
		var li = document.createElement('li');
		var a = document.createElement('a');
		a.href = "index_using_rest_third.php?page=" + totalPages;
		a.title = "Last page is " + totalPages;
		a.innerHTML = "Last Page";
		li.appendChild(a);
		ul.appendChild(li);
	}
	div.appendChild(ul);	
}