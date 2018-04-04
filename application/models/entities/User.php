<?php
		require_once('application/models/Crud.php');
		/**
		* This class  is automatically generated based on the structure of the table. And it represent the model of the user table.
		*/
		class User extends Crud
		{
protected static $tablename='User';
/* this array contains the field that can be null*/
static $nullArray=array('token' ,'last_login' ,'last_logout' ,'date_created' ,'status' );
static $compositePrimaryKey=array();
static $uploadDependency = array();
/*this array contains the fields that are unique*/
static $uniqueArray=array('username' );
/*this is an associative array containing the fieldname and the type of the field*/
static $typeArray = array('username'=>'varchar','password'=>'varchar','user_type'=>'varchar','user_table_id'=>'int','token'=>'text','last_login'=>'timestamp','last_logout'=>'timestamp','date_created'=>'timestamp','status'=>'tinyint');
/*this is a dictionary that map a field name with the label name that will be shown in a form*/
static $labelArray=array('ID'=>'','username'=>'','password'=>'','user_type'=>'','user_table_id'=>'','token'=>'','last_login'=>'','last_logout'=>'','date_created'=>'','status'=>'');
/*associative array of fields that have default value*/
static $defaultArray = array('last_login'=>'current_timestamp()','date_created'=>'current_timestamp()','status'=>'1');
//populate this array with fields that are meant to be displayed as document in the format array('fieldname'=>array('filetype','maxsize',foldertosave','preservefilename'))
//the folder to save must represent a path from the basepath. it should be a relative path,preserve filename will be either true or false. when true,the file will be uploaded with it default filename else the system will pick the current user id in the session as the name of the file.
static $documentField = array();//array containing an associative array of field that should be regareded as document field. it will contain the setting for max size and data type.
		
static $relation=array('complaint_response'=>array(array( 'ID', 'user_id', 1))
,'extra_role'=>array(array( 'ID', 'user_id', 1))
,'request'=>array(array( 'ID', 'user_id', 1))
,'request_response'=>array(array( 'ID', 'user_id', 1))
,'upload_history'=>array(array( 'ID', 'user_id', 1))
);
static $tableAction=array('enable'=>'getEnabled','delete'=>'delete/user','update'=>'edit/user');
function __construct($array=array())
{
	parent::__construct($array);
}
function getUsernameFormField($value=''){
	return "<div class='form-group'>
	<label for='username' >Username</label>
		<input type='text' name='username' id='username' value='$value' class='form-control' required />
</div> ";

}
function getPasswordFormField($value=''){
	return "<div class='form-group'>
	<label for='password' >Password</label>
	<input type='password' name='password' id='password' value='$value' class='form-control' required />
</div> ";

}
function getUser_typeFormField($value=''){
	return "<div class='form-group'>
	<label for='user_type' >User Type</label>
		<input type='text' name='user_type' id='user_type' value='$value' class='form-control' required />
</div> ";

}
	 function getUser_table_idFormField($value=''){
	$fk=null;//change the value of this variable to array('table'=>'user_table','display'=>'user_table_name'); if you want to preload the value from the database where the display key is the name of the field to use for display in the table.

	if (is_null($fk)) {
		return $result="<input type='hidden' value='$value' name='user_table_id' id='user_table_id' class='form-control' />
			";
	}
	if (is_array($fk)) {
		$result ="<div class='form-group'>
		<label for='user_table_id'>User Table Id</label>";
		$option = $this->loadOption($fk,$value);
		//load the value from the given table given the name of the table to load and the display field
		$result.="<select name='user_table_id' id='user_table_id' class='form-control'>
			$option
		</select>";
	}
	$result.="</div>";
	return  $result;

}
function getTokenFormField($value=''){
	return "<div class='form-group'>
	<label for='token' >Token</label>
<textarea id='token' name='token' class='form-control' >$value</textarea>
</div> ";

}
function getLast_loginFormField($value=''){
	return " ";

}
function getLast_logoutFormField($value=''){
	return " ";

}
function getDate_createdFormField($value=''){
	return " ";

}
function getStatusFormField($value=''){
	return "<div class='form-group'>
	<label class='form-checkbox'>Status</label>
	<select class='form-control' id='status' name='status' >
		<option value='1'>Yes</option>
		<option value='0' selected='selected'>No</option>
	</select>
	</div> ";

}


		
protected function getComplaint_response(){
	$query ='SELECT * FROM complaint_response WHERE user_id=?';
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Complaint_response.php');
	$resultobjects = array();
	foreach ($result as  $value) {
		$resultObjects[] = new Complaint_response($value);
	}

	return $resultObjects;
}
		
protected function getExtra_role(){
	$query ='SELECT * FROM extra_role WHERE user_id=?';
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Extra_role.php');
	$resultobjects = array();
	foreach ($result as  $value) {
		$resultObjects[] = new Extra_role($value);
	}

	return $resultObjects;
}
		
protected function getRequest(){
	$query ='SELECT * FROM request WHERE user_id=?';
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Request.php');
	$resultobjects = array();
	foreach ($result as  $value) {
		$resultObjects[] = new Request($value);
	}

	return $resultObjects;
}
		
protected function getRequest_response(){
	$query ='SELECT * FROM request_response WHERE user_id=?';
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
		
protected function getUpload_history(){
	$query ='SELECT * FROM upload_history WHERE user_id=?';
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Upload_history.php');
	$resultobjects = array();
	foreach ($result as  $value) {
		$resultObjects[] = new Upload_history($value);
	}

	return $resultObjects;
}
		}
		?>