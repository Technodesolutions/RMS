function reportAndRefresh(target,data){
	ajaxFormSubmissionSuccess(target,data);
	location.reload();
}
$(document).ready(function(){
	addAsterisk();
	$('li[data-critical=1] a').click(function(event){
		event.preventDefault();
		var link = $(this).attr('href');
		var action = $(this).text();
		if (confirm("are you sure you want to "+action+" item?")) {
			sendAjax(null,link,'','get',reportAndRefresh);
		}
	});
	$(document).ajaxComplete(function() {
		$('#loading').hide();
	});
	$('.table-action').mouseleave(function(event) {
		$(this).hide('fast');
	});
	$('.table-action li').click(function(event) {
		event.stopImmediatePropagation();
		$(this).parent('.table-action').hide('fast');
	});
	$('.dropdownbtn').click(function(event) {
		$(this).children('.table-action').show('fast');

	});
	$(document).ajaxStart(function() {
		$('#loading').show();
	});
	setDocumentHeight();
	sidebarNavigation()
	pageStateFromCookie();
	bindDropDown('autoload');
	$('#notification').click(function(event) {
		$(this).hide('slow');
	});


//check for number fields 
if (typeof Modernizr !=="undefined") {
	//add event for fields date picker fields 
	if (!Modernizr.inputtypes.date) {
		$("input[type=date]").datepicker({
            format: "yyyy/mm/dd"
        });
	}
	if (!Modernizr.inputtypes.number || !Modernizr.formvalidation) {
	$("input[type=number]").keypress(function(event) {
		processNumberValidation($(this),event);
	});
}
}

//add toggle event
	$(".toggle-upload-field").click(function() {
		$(".bulk-upload-block").show('fast');
	});
	// $('.print').click(function(){
	// 	$(this).hide();
	//   window.print();
	// });
	//function for paging activities
	$('.paged-item').click(function(e){
		var path = location.href;
		var start = $(this).attr('data-start');
		var len= $('#page_size').val();
		if (start!=''&& start!=undefined && len!='' & len!=undefined) {
			path =replaceOrAdd(path,'p_start',start);
			path = replaceOrAdd(path,'p_len',len);
			location.assign(path);
		}

	});
//add function for export button
	$('#export').click(function(){
		var path = location.href.indexOf('?')==-1?location.href+'?&export=yes':location.href+'&export=yes';
		path = path.replace('#','');
		window.open(path,'_blank');
	});
	if (typeof addMoreEvent ==='function') {
		addMoreEvent();
	}
	});
//function for adding asterisks to all required element
function addAsterisk(){
	var required =$('input[required],select[required],textarea[required]');
	required.each(function(ind,ele){
		var label = $(this).siblings('label');
		label.after("<i class='"+"input-required'>*</i>");
	});
}
//function to load the state of the page from cookie
function pageStateFromCookie(){
	var cookie = readCookie('openedSection');
	var indices =[];
	if (cookie==null || cookie=='') {
		indices=[0];
	}
	else{
		indices= cookie.split(' ');
	}
	expandMenu(indices);
}
function expandMenu(indices){
	for (var i = 0; i < indices.length; i++) {
		var val =parseInt(indices[i]) + 1;
		var selector = ".ed-sidemenu:nth-child("+val+")";
		var item =$(selector);
		item.children('.sidemenu-items').show();
		var fa =item.find('.ed-indicator');
		fa.removeClass('fa-angle-down');
		fa.addClass('fa-angle-up');

	}
}
function sidebarNavigation(){
	$('.menu-item-label').click(function(event) {
		var sibling =$(this).siblings('.sidemenu-items');
		var display =sibling.css('display');
		sibling.slideToggle('fast');
		var indicator =$(this).find('.ed-indicator')
		if (display!='none') {
			indicator.removeClass('fa-angle-up');
			indicator.addClass('fa-angle-down');
		}
		else{
			indicator.removeClass('fa-angle-down');
			indicator.addClass('fa-angle-up');
		}
		
		event.stopImmediatePropagation();
		saveSelectedToCookie();
	});
	
}
function saveSelectedToCookie(){
	var values ="";
	//save all the opened index
	$(".sidemenu-items:visible").each(function() {
		var display = $(this).css('display');
		if (display!='none') {
			var parent = $(this).parent();
			var index = parent.index();
			values+=' '+index;
			}
			
		});
	setCookie('openedSection',values);
}
function setDocumentHeight(){
	var topOffset = 170;
	var height = window.innerHeight-topOffset;
	//control the height of the side bar and that of th window
	$("#main").height(height);
	$("#sidebar").height(height);
	$(window).resize(function() {
		height = window.innerHeight-topOffset;
  		$("#main").height(height);
  		$("#sidebar").height(height);
	});
}


//function to test if an item is an array
function isArray(array) {
    return Object.prototype.toString.call(array) == '[object Array]';
}

function isObject(array) {
    return Object.prototype.toString.call(array) == '[object Object]';
}

function showNotification(status,data){//a boolean value for success or failure
	//work on this code to show toast
	if (typeof data!=='string') {
		status = data.status;
		data = data.message;
	}
	var notification =$("#notification");
	if (status) {
		if (!notification.hasClass('success')) {
			notification.removeClass('error');
			notification.addClass('success');
		}		
	}
	else{
		if (!notification.hasClass('error')) {
			notification.removeClass('success');
			notification.addClass('error');
		}
	}
	notification.html(data);
	animateTop(notification);
	//notification.show('slow');
}

function animateTop(element){
	element.show();
	element.animate({
		top: 0,
		opacity: 1},
		"slow", function() {
		fadeTimer(element);
	});
}
function fadeTimer(element){
	setTimeout(function() {reverseAnimateTop(element)}, 3000);
	
}
function reverseAnimateTop(element){
	element.animate({
		top: -50,
		opacity: 0
	},
		"slow",function() {
		element.hide();
	});
}
function clearNotification() {
    var notification = $("#notification");
    notification.text("");
}
//function for ajax form submission success
function ajaxFormSubmissionSuccess(target,data) {
	try{
		data = data.trim();
		data = $.parseJSON(data);
		if (typeof(ajaxFormSuccess) ==='function') {
			ajaxFormSuccess(target.attr("name"),data);return;
		}
		else{
			showNotification(data.status,data.message);
			if (data.status && target!==null ) {
				//if the insert is an insert command just clear the form else leave the form u
				if (target.attr('action').indexOf('add')!==-1) {
					clearForm(target);
				}
				 
			}
			
		}
	}
	catch(err){
		showNotification(false,data);
	}
}
function moveToNextPage(){
	var loc =$('.continue-btn a').attr('href');
	location.assign(loc);
}
function ajaxFormSubmissionFailure(target,xhr,data,exception){
	data = data==null?"an error occur while processing the request":data.toString() + ": an error occur while processing the request";
	if (typeof ajaxFormFail =='function') {
		ajaxFormFail(form.attr("name"),data,Exception);
	}
	else{
		showNotification(false,data);
		// clearForm(form);
	}
}
// this function helps send ajax request to the server.
// the first parameter is the target. not needed alway can therefore be null, then the link , the data(already encode) , the function to call on success and the function to call on failure.
function sendAjax(target,url,data,type,success, failure){
    $.ajax({
        url: url,
        type: type,
        processData:typeof data==='string'?true:false,
        data: data,
        contentType:typeof data==='string'?'application/x-www-form-urlencoded':false,
        success: function(data){
        	if (typeof(success)==='undefined') {
        		ajaxFormSubmissionSuccess(target,data);return;
        	}
        	var len=success.length;
        	if (len ==1) {
        		success(data);
        	}else{
            success(target,data);
        	}
        },
        error:function(xhr,data,exception){
        	if (failure==undefined) {
        		ajaxFormSubmissionFailure(target,xhr,data,exception);return;
        	}
        	var param = failure.length;
        	if (param ==1) {
        		failure(exception);
        	}
        	else if (param=2) {
        		failure(exception,data);
        	}
        	else if (param==3) {
        		failure(exception,data,target)
        	}
        	else{
          	 	failure(target,xhr,data,exception);
       		}
        }
    }
    );
}
//function to submit ajax call
//let the data be added using formdata when it is supported by the browser
function submitAjaxForm(form){
	//the submitted form is passed
	clearNotification();
	var message = "";
	if (typeof (message =validateFormData(form)!="string")) {
		var data = loadFormData(form);
		var url = form.attr('action');
		sendAjax(form,url,data,'post',ajaxFormSubmissionSuccess,ajaxFormSubmissionFailure);
	}
	else{
		showNotification(false,message);
	}
}
/**
 * @param  {form}  the form whose data is to be processed
 * @return {[mixed]} form data object or a serialised string format.
 */
function loadFormData(form){
	var submit = form.find("input[type='submit']");
	var subName = submit.attr('name');
	var subValue = submit.val();
	if (window.FormData === undefined ) {
		var data = form.serialize();
		data+= "&"+encodeURIComponent(subName)+"="+encodeURIComponent(subValue);
		return data;
	}
	var data = new FormData(form[0]);
	data.append(subName,subValue);
	return data;
}
//function to clear the content of a form
function clearForm(form){
	formItems = form.find("input, select, textarea");
	formItems.each(function() {
		var  attribute =$(this).attr("type");
		if (!(attribute=="hidden" || attribute=="submit" || attribute=="reset")) {
			$(this).val("");
		}

	});
}
//function to validate the form submitted
function validateFormData(form){
	form.find('input[required], select[required], textarea[required]').each(function() {
		if ($(this).val().trim()=="") {
 			var name = $(this).attr('name');
 			return name+" is required";
		}
	});;
	return true;//means the form is validated.
}

//set of functions for working with cookie
function setCookie(name, value){
	document.cookie  = name+'='+value;
}
function readCookie(name){
	var cookie = document.cookie;
	var values = cookie.split(';');
	for (var i = values.length - 1; i >= 0; i--) {
		if (values[i]=='') {
			continue;
		}
		var temp = values[i].split('=');
		if (temp.length !=2) {
			return;
		}
		if(temp[0]==name){
			return temp[1];
		}
	}
	return null;
}

//a utility function for loading  select option

/**
 * This function load data form url and pass a function to be called to afer the work is done
 * @param  {[type]}   url      [description]
 * @param  {Function} callback [description]
 * @return {[type]}            [description]
 */
function loadSelectFromUrl(url,select){
	$.get(url, function(data) {
		/*optional stuff to do after success */
		var obj = '';
		if (data.trim()!='') {
			var obj = jQuery.parseJSON(data);
		} 
		
		loadSelect(select,obj);
	});
}
/**
 * This method help load data to any select element specified
 * @param  html select element [description]
 * @param  array[object] data   the object must have an id and value as the field
 * @return none.
 */
function loadSelect(select,data){
	var options = buildOption(data);
	select.html(options);
}
function buildOption(data){
	var result = "<option value=''>..choose..</option>";
	if (data==null) {
		return result;
	}
	for (var i = 0; i < data.length; i++) {
		var current =data[i];
		result+="<option value='"+current.id+"'>"+current.value+"</option>";
	}
	return result;
}
//function for building ajax link based on a relative address
function buildLink(link){
	return $("#base_link").val()+link;
}

//function to show comfirm dialog for delete operation
function processDelete(event,target){
	
}
//function to convert a tabele into a csv format
function convertTableToCsv(table){
	var content = '';
	//check if the table heade paramete is present.
	//just get all the row item on the table and process it into csv
	var rows = table[0].rows;
	for (var i = 0; i < rows.length; i++) {
		content+=extractRow(rows[i]);
	}

	return content;
}
function extractRow(element){
	//load all the data
	var result='';
	var columns=element.cells;
	for(var i=0;i < columns.length; i++) {
		var separator= ',';
		if (i==0) {
			separator='';
		}
		if (columns[i].innerHTML.indexOf('ul')!==-1) {continue;}
		result+=separator+columns[i].textContent;
	}
	result+="\n";
	return result;
}

function bindDropDown(className){
	$('.'+className).change(function(event) {
		var path = $(this).attr('data-load');
		var child =  $(this).attr('data-child');
		var val = $(this).val();
		if (val=="" || val=="..choose..") {
			$('#'+child).html("<option value='"+"'>..choose..</option>");
			return;
		}
		var data='';
		var target =$('#'+child);
		url = $('#baseurl').val()+'ajaxData/'+path+'/'+val;
		sendAjax(target,url,data,'get',childLoad);
	});
}

function childLoad(target,data){
	if (target[0].tagName.toLowerCase()=='select') {
		if (data.trim()=="") {target.html('');return;}
		var fromServer = jQuery.parseJSON(data);
		loadSelect(target,fromServer);
		return;
	}
	// if not select just
	target.html(data);
}
function toggleCheckBox(element){
	element[0].checked=!element[0].checked;
}

function successFunction(target,data){
	var message = jQuery.parseJSON(data.trim());
	// message.message = message.status?'Operation Succesful':'Operation failed';
	if (!message.status) {
		toggleCheckBox(target);
	}
	showNotification(message.status,message.message);
}
function failedFunction(exception,data,target){
	target[0].checked =true;
	showNotification(false,'Operation failed');
	toggleCheckBox(target);
}

function saveJsFile(table,anchor){
	var csv = convertTableToCsv(table);
	var outputFile = window.prompt("What do you want to name your output file (Note: This won't have any effect on Safari)") || 'export';
    outputFile = outputFile.replace('.csv','') + '.csv'
    var csvLink = 'data:application/csv;charset=UTF-8,' + encodeURIComponent(csv);
	if (window.navigator.msSaveOrOpenBlob) {
        var blob = new Blob([decodeURIComponent(encodeURI(csv))], {
            type: "text/csv;charset=utf-8;"
        });
        navigator.msSaveBlob(blob, outputFile);
    } else {
        anchor
            .attr({
                'download': outputFile
                ,'href': csvLink
        });
    }
// location.assign(csvLink);
}

//function to add check box to the begining of every an table rows
function addCheckBox(table){
	var rows = table.find('tbody tr');
	for (var i = 0; i < rows.length; i++) {
		var current = rows[i];
		var temp = "<td><input type='checkbox' class='selection' /></td>";
		var html =  current.innerHTML+temp;
		rows[i].innerHTML = html;
	}
}

//function to get the index of a string after a particular position
function getPosition(str,start,needle){
	var index = str.substr(start).indexOf(needle);
	if (index==-1) {
		return index;
	}
	return index+start;
}
function replaceOrAdd(str,variable,value){
	value = encodeURIComponent(value);
	var path = str;
	var ind = path.indexOf(variable+'=');
	if (ind==-1) {
		path += path.indexOf('?')==-1?'?'+variable+'='+value:'&'+variable+'='+value;
		return path;
	}
	else{
		var next = getPosition(path,ind,'&');
		path =next==-1?replaceSubstr(path,ind,next,variable+'='+value):
		path= replaceSubstr(path,ind,next,variable+'='+value);
		return path;
		}
}
function replaceSubstr(str,start,end,replace){
	var temp = end==-1?str.substr(start):str.substr(start,end-start);
	return str.replace(temp,replace);
}

//function to get base url
function getBase(){
	return $('#baseurl').val();
}

//function to presces date dispaly
function processDateDisplay(item,event) {
	//embed the timer loader here
	item.datepicker(
		{
            format: "yyyy/mm/dd"
        });
}

function processNumberValidation(item,event){
	// alert(event.keyCode);
	// if (event.keyCode) {}
}

