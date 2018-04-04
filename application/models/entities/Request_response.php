<?php
		require_once('application/models/Crud.php');
		/**
		* This class  is automatically generated based on the structure of the table. And it represent the model of the request_response table.
		*/
		class Request_response extends Crud
		{
protected static $tablename='Request_response';
/* this array contains the field that can be null*/
static $nullArray=array('date' );
static $compositePrimaryKey=array();
static $uploadDependency = array();
/*this array contains the fields that are unique*/
static $uniqueArray=array();
/*this is an associative array containing the fieldname and the type of the field*/
static $typeArray = array('request_id'=>'int','response'=>'text','date'=>'timestamp','user_id'=>'int');
/*this is a dictionary that map a field name with the label name that will be shown in a form*/
static $labelArray=array('ID'=>'','request_id'=>'','response'=>'','date'=>'','user_id'=>'');
/*associative array of fields that have default value*/
static $defaultArray = array('date'=>'current_timestamp()');
//populate this array with fields that are meant to be displayed as document in the format array('fieldname'=>array('filetype','maxsize',foldertosave','preservefilename'))
//the folder to save must represent a path from the basepath. it should be a relative path,preserve filename will be either true or false. when true,the file will be uploaded with it default filename else the system will pick the current user id in the session as the name of the file.
static $documentField = array();//array containing an associative array of field that should be regareded as document field. it will contain the setting for max size and data type.
		
static $relation=array('request'=>array( 'request_id', 'ID')
,'user'=>array( 'user_id', 'ID')
);
static $tableAction=array('enable'=>'getEnabled','delete'=>'delete/request_response','update'=>'edit/request_response');
function __construct($array=array())
{
	parent::__construct($array);
}
	 function getRequest_idFormField($value=''){
	$fk=null;//change the value of this variable to array('table'=>'request','display'=>'request_name'); if you want to preload the value from the database where the display key is the name of the field to use for display in the table.

	if (is_null($fk)) {
		return $result="<input type='hidden' value='$value' name='request_id' id='request_id' class='form-control' />
			";
	}
	if (is_array($fk)) {
		$result ="<div class='form-group'>
		<label for='request_id'>Request Id</label>";
		$option = $this->loadOption($fk,$value);
		//load the value from the given table given the name of the table to load and the display field
		$result.="<select name='request_id' id='request_id' class='form-control'>
			$option
		</select>";
	}
	$result.="</div>";
	return  $result;

}
function getResponseFormField($value=''){
	return "<div class='form-group'>
	<label for='response' >Response</label>
<textarea id='response' name='response' class='form-control' required>$value</textarea>
</div> ";

}
function getDateFormField($value=''){
	return " ";

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


		
protected function getRequest(){
	$query ='SELECT * FROM request WHERE id=?';
	if (!isset($this->array['ID'])) {
		return null;
	}
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Request.php');
	$resultObject = new Request($result[0]);
	return $resultObject;
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
		}
		?>