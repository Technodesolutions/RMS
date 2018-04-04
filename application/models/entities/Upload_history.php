<?php
		require_once('application/models/Crud.php');
		/**
		* This class  is automatically generated based on the structure of the table. And it represent the model of the upload_history table.
		*/
		class Upload_history extends Crud
		{
protected static $tablename='Upload_history';
/* this array contains the field that can be null*/
static $nullArray=array('date' ,'lecturer_id' ,'approval_status' ,'user_id' );
static $compositePrimaryKey=array();
static $uploadDependency = array();
/*this array contains the fields that are unique*/
static $uniqueArray=array();
/*this is an associative array containing the fieldname and the type of the field*/
static $typeArray = array('date'=>'timestamp','lecturer_id'=>'int','approval_status'=>'tinyint','user_id'=>'int');
/*this is a dictionary that map a field name with the label name that will be shown in a form*/
static $labelArray=array('ID'=>'','date'=>'','lecturer_id'=>'','approval_status'=>'','user_id'=>'');
/*associative array of fields that have default value*/
static $defaultArray = array('date'=>'current_timestamp()','approval_status'=>'1');
//populate this array with fields that are meant to be displayed as document in the format array('fieldname'=>array('filetype','maxsize',foldertosave','preservefilename'))
//the folder to save must represent a path from the basepath. it should be a relative path,preserve filename will be either true or false. when true,the file will be uploaded with it default filename else the system will pick the current user id in the session as the name of the file.
static $documentField = array();//array containing an associative array of field that should be regareded as document field. it will contain the setting for max size and data type.
		
static $relation=array('lecturer'=>array( 'lecturer_id', 'ID')
,'user'=>array( 'user_id', 'ID')
);
static $tableAction=array('enable'=>'getEnabled','delete'=>'delete/upload_history','update'=>'edit/upload_history');
function __construct($array=array())
{
	parent::__construct($array);
}
function getDateFormField($value=''){
	return " ";

}
	 function getLecturer_idFormField($value=''){
	$fk=null;//change the value of this variable to array('table'=>'lecturer','display'=>'lecturer_name'); if you want to preload the value from the database where the display key is the name of the field to use for display in the table.

	if (is_null($fk)) {
		return $result="<input type='hidden' value='$value' name='lecturer_id' id='lecturer_id' class='form-control' />
			";
	}
	if (is_array($fk)) {
		$result ="<div class='form-group'>
		<label for='lecturer_id'>Lecturer Id</label>";
		$option = $this->loadOption($fk,$value);
		//load the value from the given table given the name of the table to load and the display field
		$result.="<select name='lecturer_id' id='lecturer_id' class='form-control'>
			$option
		</select>";
	}
	$result.="</div>";
	return  $result;

}
function getApproval_statusFormField($value=''){
	return "<div class='form-group'>
	<label class='form-checkbox'>Approval Status</label>
	<select class='form-control' id='approval_status' name='approval_status' >
		<option value='1'>Yes</option>
		<option value='0' selected='selected'>No</option>
	</select>
	</div> ";

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


		
protected function getLecturer(){
	$query ='SELECT * FROM lecturer WHERE id=?';
	if (!isset($this->array['ID'])) {
		return null;
	}
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Lecturer.php');
	$resultObject = new Lecturer($result[0]);
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