<?php
		require_once('application/models/Crud.php');
		/**
		* This class  is automatically generated based on the structure of the table. And it represent the model of the course table.
		*/
		class Course extends Crud
		{
protected static $tablename='Course';
/* this array contains the field that can be null*/
static $nullArray=array();
static $compositePrimaryKey=array();
static $uploadDependency = array();
/*this array contains the fields that are unique*/
static $uniqueArray=array();
/*this is an associative array containing the fieldname and the type of the field*/
static $typeArray = array('course_title'=>'varchar','course_code'=>'varchar','department_id'=>'int');
/*this is a dictionary that map a field name with the label name that will be shown in a form*/
static $labelArray=array('ID'=>'','course_title'=>'','course_code'=>'','department_id'=>'');
/*associative array of fields that have default value*/
static $defaultArray = array();
//populate this array with fields that are meant to be displayed as document in the format array('fieldname'=>array('filetype','maxsize',foldertosave','preservefilename'))
//the folder to save must represent a path from the basepath. it should be a relative path,preserve filename will be either true or false. when true,the file will be uploaded with it default filename else the system will pick the current user id in the session as the name of the file.
static $documentField = array();//array containing an associative array of field that should be regareded as document field. it will contain the setting for max size and data type.
		
static $relation=array('department'=>array( 'department_id', 'ID')
,'course_prerequisite'=>array(array( 'ID', 'course_id', 1))
,'session_semester_course'=>array(array( 'ID', 'course_id', 1))
);
static $tableAction=array('enable'=>'getEnabled','delete'=>'delete/course','update'=>'edit/course');
function __construct($array=array())
{
	parent::__construct($array);
}
function getCourse_titleFormField($value=''){
	return "<div class='form-group'>
	<label for='course_title' >Course Title</label>
		<input type='text' name='course_title' id='course_title' value='$value' class='form-control' required />
</div> ";

}
function getCourse_codeFormField($value=''){
	return "<div class='form-group'>
	<label for='course_code' >Course Code</label>
		<input type='text' name='course_code' id='course_code' value='$value' class='form-control' required />
</div> ";

}
	 function getDepartment_idFormField($value=''){
	$fk=null;//change the value of this variable to array('table'=>'department','display'=>'department_name'); if you want to preload the value from the database where the display key is the name of the field to use for display in the table.

	if (is_null($fk)) {
		return $result="<input type='hidden' value='$value' name='department_id' id='department_id' class='form-control' />
			";
	}
	if (is_array($fk)) {
		$result ="<div class='form-group'>
		<label for='department_id'>Department Id</label>";
		$option = $this->loadOption($fk,$value);
		//load the value from the given table given the name of the table to load and the display field
		$result.="<select name='department_id' id='department_id' class='form-control'>
			$option
		</select>";
	}
	$result.="</div>";
	return  $result;

}


		
protected function getDepartment(){
	$query ='SELECT * FROM department WHERE id=?';
	if (!isset($this->array['ID'])) {
		return null;
	}
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Department.php');
	$resultObject = new Department($result[0]);
	return $resultObject;
}
		
protected function getCourse_prerequisite(){
	$query ='SELECT * FROM course_prerequisite WHERE course_id=?';
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Course_prerequisite.php');
	$resultobjects = array();
	foreach ($result as  $value) {
		$resultObjects[] = new Course_prerequisite($value);
	}

	return $resultObjects;
}
		
protected function getSession_semester_course(){
	$query ='SELECT * FROM session_semester_course WHERE course_id=?';
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Session_semester_course.php');
	$resultobjects = array();
	foreach ($result as  $value) {
		$resultObjects[] = new Session_semester_course($value);
	}

	return $resultObjects;
}
		}
		?>