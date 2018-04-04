<?php
		require_once('application/models/Crud.php');
		/**
		* This class  is automatically generated based on the structure of the table. And it represent the model of the admin_log table.
		*/
		class Admin_log extends Crud
		{
protected static $tablename='Admin_log';
/* this array contains the field that can be null*/
static $nullArray=array('admin_id' ,'path' ,'date_visited' );
static $compositePrimaryKey=array();
static $uploadDependency = array();
/*this array contains the fields that are unique*/
static $uniqueArray=array();
/*this is an associative array containing the fieldname and the type of the field*/
static $typeArray = array('admin_id'=>'int','path'=>'varchar','date_visited'=>'timestamp');
/*this is a dictionary that map a field name with the label name that will be shown in a form*/
static $labelArray=array('ID'=>'','admin_id'=>'','path'=>'','date_visited'=>'');
/*associative array of fields that have default value*/
static $defaultArray = array('date_visited'=>'current_timestamp()');
//populate this array with fields that are meant to be displayed as document in the format array('fieldname'=>array('filetype','maxsize',foldertosave','preservefilename'))
//the folder to save must represent a path from the basepath. it should be a relative path,preserve filename will be either true or false. when true,the file will be uploaded with it default filename else the system will pick the current user id in the session as the name of the file.
static $documentField = array();//array containing an associative array of field that should be regareded as document field. it will contain the setting for max size and data type.
		
static $relation=array('admin'=>array( 'admin_id', 'ID')
);
static $tableAction=array('enable'=>'getEnabled','delete'=>'delete/admin_log','update'=>'edit/admin_log');
function __construct($array=array())
{
	parent::__construct($array);
}
	 function getAdmin_idFormField($value=''){
	$fk=null;//change the value of this variable to array('table'=>'admin','display'=>'admin_name'); if you want to preload the value from the database where the display key is the name of the field to use for display in the table.

	if (is_null($fk)) {
		return $result="<input type='hidden' value='$value' name='admin_id' id='admin_id' class='form-control' />
			";
	}
	if (is_array($fk)) {
		$result ="<div class='form-group'>
		<label for='admin_id'>Admin Id</label>";
		$option = $this->loadOption($fk,$value);
		//load the value from the given table given the name of the table to load and the display field
		$result.="<select name='admin_id' id='admin_id' class='form-control'>
			$option
		</select>";
	}
	$result.="</div>";
	return  $result;

}
function getPathFormField($value=''){
	return "<div class='form-group'>
	<label for='path' >Path</label>
		<input type='text' name='path' id='path' value='$value' class='form-control'  />
</div> ";

}
function getDate_visitedFormField($value=''){
	return " ";

}


		
protected function getAdmin(){
	$query ='SELECT * FROM admin WHERE id=?';
	if (!isset($this->array['ID'])) {
		return null;
	}
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Admin.php');
	$resultObject = new Admin($result[0]);
	return $resultObject;
}
		}
		?>