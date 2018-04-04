<?php
		require_once('application/models/Crud.php');
		/**
		* This class  is automatically generated based on the structure of the table. And it represent the model of the min_max_unit table.
		*/
		class Min_max_unit extends Crud
		{
protected static $tablename='Min_max_unit';
/* this array contains the field that can be null*/
static $nullArray=array();
static $compositePrimaryKey=array();
static $uploadDependency = array();
/*this array contains the fields that are unique*/
static $uniqueArray=array();
/*this is an associative array containing the fieldname and the type of the field*/
static $typeArray = array('min_unit'=>'float','max_unit'=>'float','session_id'=>'int');
/*this is a dictionary that map a field name with the label name that will be shown in a form*/
static $labelArray=array('ID'=>'','min_unit'=>'','max_unit'=>'','session_id'=>'');
/*associative array of fields that have default value*/
static $defaultArray = array();
//populate this array with fields that are meant to be displayed as document in the format array('fieldname'=>array('filetype','maxsize',foldertosave','preservefilename'))
//the folder to save must represent a path from the basepath. it should be a relative path,preserve filename will be either true or false. when true,the file will be uploaded with it default filename else the system will pick the current user id in the session as the name of the file.
static $documentField = array();//array containing an associative array of field that should be regareded as document field. it will contain the setting for max size and data type.
		
static $relation=array('session'=>array( 'session_id', 'ID')
);
static $tableAction=array('enable'=>'getEnabled','delete'=>'delete/min_max_unit','update'=>'edit/min_max_unit');
function __construct($array=array())
{
	parent::__construct($array);
}
function getMin_unitFormField($value=''){
	return "<div class='form-group'>
	<label for='min_unit' >Min Unit</label>
		<input type='text' name='min_unit' id='min_unit' value='$value' class='form-control' required />
</div> ";

}
function getMax_unitFormField($value=''){
	return "<div class='form-group'>
	<label for='max_unit' >Max Unit</label>
		<input type='text' name='max_unit' id='max_unit' value='$value' class='form-control' required />
</div> ";

}
	 function getSession_idFormField($value=''){
	$fk=null;//change the value of this variable to array('table'=>'session','display'=>'session_name'); if you want to preload the value from the database where the display key is the name of the field to use for display in the table.

	if (is_null($fk)) {
		return $result="<input type='hidden' value='$value' name='session_id' id='session_id' class='form-control' />
			";
	}
	if (is_array($fk)) {
		$result ="<div class='form-group'>
		<label for='session_id'>Session Id</label>";
		$option = $this->loadOption($fk,$value);
		//load the value from the given table given the name of the table to load and the display field
		$result.="<select name='session_id' id='session_id' class='form-control'>
			$option
		</select>";
	}
	$result.="</div>";
	return  $result;

}


		
protected function getSession(){
	$query ='SELECT * FROM session WHERE id=?';
	if (!isset($this->array['ID'])) {
		return null;
	}
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Session.php');
	$resultObject = new Session($result[0]);
	return $resultObject;
}
		}
		?>