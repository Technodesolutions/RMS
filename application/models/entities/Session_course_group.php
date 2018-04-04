<?php
		require_once('application/models/Crud.php');
		/**
		* This class  is automatically generated based on the structure of the table. And it represent the model of the session_course_group table.
		*/
		class Session_course_group extends Crud
		{
protected static $tablename='Session_course_group';
/* this array contains the field that can be null*/
static $nullArray=array();
static $compositePrimaryKey=array();
static $uploadDependency = array();
/*this array contains the fields that are unique*/
static $uniqueArray=array();
/*this is an associative array containing the fieldname and the type of the field*/
static $typeArray = array('session_semester_course_id'=>'int','course_group_id'=>'int');
/*this is a dictionary that map a field name with the label name that will be shown in a form*/
static $labelArray=array('ID'=>'','session_semester_course_id'=>'','course_group_id'=>'');
/*associative array of fields that have default value*/
static $defaultArray = array();
//populate this array with fields that are meant to be displayed as document in the format array('fieldname'=>array('filetype','maxsize',foldertosave','preservefilename'))
//the folder to save must represent a path from the basepath. it should be a relative path,preserve filename will be either true or false. when true,the file will be uploaded with it default filename else the system will pick the current user id in the session as the name of the file.
static $documentField = array();//array containing an associative array of field that should be regareded as document field. it will contain the setting for max size and data type.
		
static $relation=array('session_semester_course'=>array( 'session_semester_course_id', 'ID')
,'course_group'=>array( 'course_group_id', 'ID')
);
static $tableAction=array('enable'=>'getEnabled','delete'=>'delete/session_course_group','update'=>'edit/session_course_group');
function __construct($array=array())
{
	parent::__construct($array);
}
	 function getSession_semester_course_idFormField($value=''){
	$fk=null;//change the value of this variable to array('table'=>'session_semester_course','display'=>'session_semester_course_name'); if you want to preload the value from the database where the display key is the name of the field to use for display in the table.

	if (is_null($fk)) {
		return $result="<input type='hidden' value='$value' name='session_semester_course_id' id='session_semester_course_id' class='form-control' />
			";
	}
	if (is_array($fk)) {
		$result ="<div class='form-group'>
		<label for='session_semester_course_id'>Session Semester Course Id</label>";
		$option = $this->loadOption($fk,$value);
		//load the value from the given table given the name of the table to load and the display field
		$result.="<select name='session_semester_course_id' id='session_semester_course_id' class='form-control'>
			$option
		</select>";
	}
	$result.="</div>";
	return  $result;

}
	 function getCourse_group_idFormField($value=''){
	$fk=null;//change the value of this variable to array('table'=>'course_group','display'=>'course_group_name'); if you want to preload the value from the database where the display key is the name of the field to use for display in the table.

	if (is_null($fk)) {
		return $result="<input type='hidden' value='$value' name='course_group_id' id='course_group_id' class='form-control' />
			";
	}
	if (is_array($fk)) {
		$result ="<div class='form-group'>
		<label for='course_group_id'>Course Group Id</label>";
		$option = $this->loadOption($fk,$value);
		//load the value from the given table given the name of the table to load and the display field
		$result.="<select name='course_group_id' id='course_group_id' class='form-control'>
			$option
		</select>";
	}
	$result.="</div>";
	return  $result;

}


		
protected function getSession_semester_course(){
	$query ='SELECT * FROM session_semester_course WHERE id=?';
	if (!isset($this->array['ID'])) {
		return null;
	}
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Session_semester_course.php');
	$resultObject = new Session_semester_course($result[0]);
	return $resultObject;
}
		
protected function getCourse_group(){
	$query ='SELECT * FROM course_group WHERE id=?';
	if (!isset($this->array['ID'])) {
		return null;
	}
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Course_group.php');
	$resultObject = new Course_group($result[0]);
	return $resultObject;
}
		}
		?>