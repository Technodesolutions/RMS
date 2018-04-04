<?php
		require_once('application/models/Crud.php');
		/**
		* This class  is automatically generated based on the structure of the table. And it represent the model of the course_registration_history table.
		*/
		class Course_registration_history extends Crud
		{
protected static $tablename='Course_registration_history';
/* this array contains the field that can be null*/
static $nullArray=array('date' );
static $compositePrimaryKey=array();
static $uploadDependency = array();
/*this array contains the fields that are unique*/
static $uniqueArray=array();
/*this is an associative array containing the fieldname and the type of the field*/
static $typeArray = array('student_biodata_id'=>'int','session_semester_course_id'=>'int','action'=>'enum','date'=>'timestamp');
/*this is a dictionary that map a field name with the label name that will be shown in a form*/
static $labelArray=array('ID'=>'','student_biodata_id'=>'','session_semester_course_id'=>'','action'=>'','date'=>'');
/*associative array of fields that have default value*/
static $defaultArray = array('date'=>'current_timestamp()');
//populate this array with fields that are meant to be displayed as document in the format array('fieldname'=>array('filetype','maxsize',foldertosave','preservefilename'))
//the folder to save must represent a path from the basepath. it should be a relative path,preserve filename will be either true or false. when true,the file will be uploaded with it default filename else the system will pick the current user id in the session as the name of the file.
static $documentField = array();//array containing an associative array of field that should be regareded as document field. it will contain the setting for max size and data type.
		
static $relation=array('student_biodata'=>array( 'student_biodata_id', 'ID')
,'session_semester_course'=>array( 'session_semester_course_id', 'ID')
);
static $tableAction=array('enable'=>'getEnabled','delete'=>'delete/course_registration_history','update'=>'edit/course_registration_history');
function __construct($array=array())
{
	parent::__construct($array);
}
	 function getStudent_biodata_idFormField($value=''){
	$fk=null;//change the value of this variable to array('table'=>'student_biodata','display'=>'student_biodata_name'); if you want to preload the value from the database where the display key is the name of the field to use for display in the table.

	if (is_null($fk)) {
		return $result="<input type='hidden' value='$value' name='student_biodata_id' id='student_biodata_id' class='form-control' />
			";
	}
	if (is_array($fk)) {
		$result ="<div class='form-group'>
		<label for='student_biodata_id'>Student Biodata Id</label>";
		$option = $this->loadOption($fk,$value);
		//load the value from the given table given the name of the table to load and the display field
		$result.="<select name='student_biodata_id' id='student_biodata_id' class='form-control'>
			$option
		</select>";
	}
	$result.="</div>";
	return  $result;

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
function getActionFormField($value=''){
	return "<div class='form-group'>
	<label for='action' >Action</label><select name='action' id='action' value='$value' class='form-control' required>
	<option>..choose..</option><option> add </option><option> delete </option>
</select>
</div> ";

}
function getDateFormField($value=''){
	return " ";

}


		
protected function getStudent_biodata(){
	$query ='SELECT * FROM student_biodata WHERE id=?';
	if (!isset($this->array['ID'])) {
		return null;
	}
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Student_biodata.php');
	$resultObject = new Student_biodata($result[0]);
	return $resultObject;
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
		}
		?>