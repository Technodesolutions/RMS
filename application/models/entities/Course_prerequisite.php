<?php
		require_once('application/models/Crud.php');
		/**
		* This class  is automatically generated based on the structure of the table. And it represent the model of the course_prerequisite table.
		*/
		class Course_prerequisite extends Crud
		{
protected static $tablename='Course_prerequisite';
/* this array contains the field that can be null*/
static $nullArray=array('status' );
static $compositePrimaryKey=array();
static $uploadDependency = array();
/*this array contains the fields that are unique*/
static $uniqueArray=array();
/*this is an associative array containing the fieldname and the type of the field*/
static $typeArray = array('session_semester_id'=>'int','course_id'=>'int','prerequisite_course_id'=>'int','status'=>'tinyint');
/*this is a dictionary that map a field name with the label name that will be shown in a form*/
static $labelArray=array('ID'=>'','session_semester_id'=>'','course_id'=>'','prerequisite_course_id'=>'','status'=>'');
/*associative array of fields that have default value*/
static $defaultArray = array('status'=>'1');
//populate this array with fields that are meant to be displayed as document in the format array('fieldname'=>array('filetype','maxsize',foldertosave','preservefilename'))
//the folder to save must represent a path from the basepath. it should be a relative path,preserve filename will be either true or false. when true,the file will be uploaded with it default filename else the system will pick the current user id in the session as the name of the file.
static $documentField = array();//array containing an associative array of field that should be regareded as document field. it will contain the setting for max size and data type.
		
static $relation=array('session_semester'=>array( 'session_semester_id', 'ID')
,'course'=>array( 'course_id', 'ID')
);
static $tableAction=array('enable'=>'getEnabled','delete'=>'delete/course_prerequisite','update'=>'edit/course_prerequisite');
function __construct($array=array())
{
	parent::__construct($array);
}
	 function getSession_semester_idFormField($value=''){
	$fk=null;//change the value of this variable to array('table'=>'session_semester','display'=>'session_semester_name'); if you want to preload the value from the database where the display key is the name of the field to use for display in the table.

	if (is_null($fk)) {
		return $result="<input type='hidden' value='$value' name='session_semester_id' id='session_semester_id' class='form-control' />
			";
	}
	if (is_array($fk)) {
		$result ="<div class='form-group'>
		<label for='session_semester_id'>Session Semester Id</label>";
		$option = $this->loadOption($fk,$value);
		//load the value from the given table given the name of the table to load and the display field
		$result.="<select name='session_semester_id' id='session_semester_id' class='form-control'>
			$option
		</select>";
	}
	$result.="</div>";
	return  $result;

}
	 function getCourse_idFormField($value=''){
	$fk=null;//change the value of this variable to array('table'=>'course','display'=>'course_name'); if you want to preload the value from the database where the display key is the name of the field to use for display in the table.

	if (is_null($fk)) {
		return $result="<input type='hidden' value='$value' name='course_id' id='course_id' class='form-control' />
			";
	}
	if (is_array($fk)) {
		$result ="<div class='form-group'>
		<label for='course_id'>Course Id</label>";
		$option = $this->loadOption($fk,$value);
		//load the value from the given table given the name of the table to load and the display field
		$result.="<select name='course_id' id='course_id' class='form-control'>
			$option
		</select>";
	}
	$result.="</div>";
	return  $result;

}
	 function getPrerequisite_course_idFormField($value=''){
	$fk=null;//change the value of this variable to array('table'=>'prerequisite_course','display'=>'prerequisite_course_name'); if you want to preload the value from the database where the display key is the name of the field to use for display in the table.

	if (is_null($fk)) {
		return $result="<input type='hidden' value='$value' name='prerequisite_course_id' id='prerequisite_course_id' class='form-control' />
			";
	}
	if (is_array($fk)) {
		$result ="<div class='form-group'>
		<label for='prerequisite_course_id'>Prerequisite Course Id</label>";
		$option = $this->loadOption($fk,$value);
		//load the value from the given table given the name of the table to load and the display field
		$result.="<select name='prerequisite_course_id' id='prerequisite_course_id' class='form-control'>
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


		
protected function getSession_semester(){
	$query ='SELECT * FROM session_semester WHERE id=?';
	if (!isset($this->array['ID'])) {
		return null;
	}
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Session_semester.php');
	$resultObject = new Session_semester($result[0]);
	return $resultObject;
}
		
protected function getCourse(){
	$query ='SELECT * FROM course WHERE id=?';
	if (!isset($this->array['ID'])) {
		return null;
	}
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Course.php');
	$resultObject = new Course($result[0]);
	return $resultObject;
}
		}
		?>