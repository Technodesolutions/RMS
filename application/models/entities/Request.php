<?php
		require_once('application/models/Crud.php');
		/**
		* This class  is automatically generated based on the structure of the table. And it represent the model of the request table.
		*/
		class Request extends Crud
		{
protected static $tablename='Request';
/* this array contains the field that can be null*/
static $nullArray=array('request' ,'date' ,'resolved' ,'date_resolved' );
static $compositePrimaryKey=array();
static $uploadDependency = array();
/*this array contains the fields that are unique*/
static $uniqueArray=array();
/*this is an associative array containing the fieldname and the type of the field*/
static $typeArray = array('user_id'=>'int','request_type_id'=>'int','request'=>'text','date'=>'timestamp','resolved'=>'tinyint','date_resolved'=>'date');
/*this is a dictionary that map a field name with the label name that will be shown in a form*/
static $labelArray=array('ID'=>'','user_id'=>'','request_type_id'=>'','request'=>'','date'=>'','resolved'=>'','date_resolved'=>'');
/*associative array of fields that have default value*/
static $defaultArray = array('date'=>'current_timestamp()');
//populate this array with fields that are meant to be displayed as document in the format array('fieldname'=>array('filetype','maxsize',foldertosave','preservefilename'))
//the folder to save must represent a path from the basepath. it should be a relative path,preserve filename will be either true or false. when true,the file will be uploaded with it default filename else the system will pick the current user id in the session as the name of the file.
static $documentField = array();//array containing an associative array of field that should be regareded as document field. it will contain the setting for max size and data type.
		
static $relation=array('user'=>array( 'user_id', 'ID')
,'request_type'=>array( 'request_type_id', 'ID')
,'request_response'=>array(array( 'ID', 'request_id', 1))
);
static $tableAction=array('enable'=>'getEnabled','delete'=>'delete/request','update'=>'edit/request');
function __construct($array=array())
{
	parent::__construct($array);
}
	 function getUser_idFormField($value=''){
	$fk=null;//change the value of this variable to array('table'=>'user','display'=>'user_name'); if you want to preload the value from the database where the display key is the name of the field to use for display in the table.

	if (is_null($fk)) {
		return $result="<input type='hidden' value='$value' name='user_id' id='user_id' class='form-control' />
			";
	}
	if (is_array($fk)) {
		$result ="<div class='form-group'>
		<label for='user_id'>User Id</label>";
		$option = $this->loadOption($fk,$value);
		//load the value from the given table given the name of the table to load and the display field
		$result.="<select name='user_id' id='user_id' class='form-control'>
			$option
		</select>";
	}
	$result.="</div>";
	return  $result;

}
	 function getRequest_type_idFormField($value=''){
	$fk=null;//change the value of this variable to array('table'=>'request_type','display'=>'request_type_name'); if you want to preload the value from the database where the display key is the name of the field to use for display in the table.

	if (is_null($fk)) {
		return $result="<input type='hidden' value='$value' name='request_type_id' id='request_type_id' class='form-control' />
			";
	}
	if (is_array($fk)) {
		$result ="<div class='form-group'>
		<label for='request_type_id'>Request Type Id</label>";
		$option = $this->loadOption($fk,$value);
		//load the value from the given table given the name of the table to load and the display field
		$result.="<select name='request_type_id' id='request_type_id' class='form-control'>
			$option
		</select>";
	}
	$result.="</div>";
	return  $result;

}
function getRequestFormField($value=''){
	return "<div class='form-group'>
	<label for='request' >Request</label>
<textarea id='request' name='request' class='form-control' >$value</textarea>
</div> ";

}
function getDateFormField($value=''){
	return " ";

}
function getResolvedFormField($value=''){
	return "<div class='form-group'>
	<label class='form-checkbox'>Resolved</label>
	<select class='form-control' id='resolved' name='resolved' >
		<option value='1'>Yes</option>
		<option value='0' selected='selected'>No</option>
	</select>
	</div> ";

}
function getDate_resolvedFormField($value=''){
	return "<div class='form-group'>
	<label for='date_resolved' >Date Resolved</label>
	<input type='date' name='date_resolved' id='date_resolved' value='$value' class='form-control'  />
</div> ";

}


		
protected function getUser(){
	$query ='SELECT * FROM user WHERE id=?';
	if (!isset($this->array['ID'])) {
		return null;
	}
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('User.php');
	$resultObject = new User($result[0]);
	return $resultObject;
}
		
protected function getRequest_type(){
	$query ='SELECT * FROM request_type WHERE id=?';
	if (!isset($this->array['ID'])) {
		return null;
	}
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Request_type.php');
	$resultObject = new Request_type($result[0]);
	return $resultObject;
}
		
protected function getRequest_response(){
	$query ='SELECT * FROM request_response WHERE request_id=?';
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Request_response.php');
	$resultobjects = array();
	foreach ($result as  $value) {
		$resultObjects[] = new Request_response($value);
	}

	return $resultObjects;
}
		}
		?>