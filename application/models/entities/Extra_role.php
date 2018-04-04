<?php
		require_once('application/models/Crud.php');
		/**
		* This class  is automatically generated based on the structure of the table. And it represent the model of the extra_role table.
		*/
		class Extra_role extends Crud
		{
protected static $tablename='Extra_role';
/* this array contains the field that can be null*/
static $nullArray=array('status' );
static $compositePrimaryKey=array();
static $uploadDependency = array();
/*this array contains the fields that are unique*/
static $uniqueArray=array();
/*this is an associative array containing the fieldname and the type of the field*/
static $typeArray = array('user_id'=>'int','role_id'=>'int','status'=>'tinyint','user_type'=>'varchar');
/*this is a dictionary that map a field name with the label name that will be shown in a form*/
static $labelArray=array('ID'=>'','user_id'=>'','role_id'=>'','status'=>'','user_type'=>'');
/*associative array of fields that have default value*/
static $defaultArray = array('status'=>'1');
//populate this array with fields that are meant to be displayed as document in the format array('fieldname'=>array('filetype','maxsize',foldertosave','preservefilename'))
//the folder to save must represent a path from the basepath. it should be a relative path,preserve filename will be either true or false. when true,the file will be uploaded with it default filename else the system will pick the current user id in the session as the name of the file.
static $documentField = array();//array containing an associative array of field that should be regareded as document field. it will contain the setting for max size and data type.
		
static $relation=array('user'=>array( 'user_id', 'ID')
,'role'=>array( 'role_id', 'ID')
);
static $tableAction=array('enable'=>'getEnabled','delete'=>'delete/extra_role','update'=>'edit/extra_role');
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
	 function getRole_idFormField($value=''){
	$fk=null;//change the value of this variable to array('table'=>'role','display'=>'role_name'); if you want to preload the value from the database where the display key is the name of the field to use for display in the table.

	if (is_null($fk)) {
		return $result="<input type='hidden' value='$value' name='role_id' id='role_id' class='form-control' />
			";
	}
	if (is_array($fk)) {
		$result ="<div class='form-group'>
		<label for='role_id'>Role Id</label>";
		$option = $this->loadOption($fk,$value);
		//load the value from the given table given the name of the table to load and the display field
		$result.="<select name='role_id' id='role_id' class='form-control'>
			$option
		</select>";
	}
	$result.="</div>";
	return  $result;

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
function getUser_typeFormField($value=''){
	return "<div class='form-group'>
	<label for='user_type' >User Type</label>
		<input type='text' name='user_type' id='user_type' value='$value' class='form-control' required />
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
		
protected function getRole(){
	$query ='SELECT * FROM role WHERE id=?';
	if (!isset($this->array['ID'])) {
		return null;
	}
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Role.php');
	$resultObject = new Role($result[0]);
	return $resultObject;
}
		}
		?>