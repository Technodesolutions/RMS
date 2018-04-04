<?php
		require_once('application/models/Crud.php');
		/**
		* This class  is automatically generated based on the structure of the table. And it represent the model of the session_semester table.
		*/
		class Session_semester extends Crud
		{
protected static $tablename='Session_semester';
/* this array contains the field that can be null*/
static $nullArray=array('start_date' ,'end_date' );
static $compositePrimaryKey=array();
static $uploadDependency = array();
/*this array contains the fields that are unique*/
static $uniqueArray=array();
/*this is an associative array containing the fieldname and the type of the field*/
static $typeArray = array('session_id'=>'int','semester_id'=>'int','start_date'=>'date','end_date'=>'date','status'=>'tinyint');
/*this is a dictionary that map a field name with the label name that will be shown in a form*/
static $labelArray=array('ID'=>'','session_id'=>'','semester_id'=>'','start_date'=>'','end_date'=>'','status'=>'');
/*associative array of fields that have default value*/
static $defaultArray = array();
//populate this array with fields that are meant to be displayed as document in the format array('fieldname'=>array('filetype','maxsize',foldertosave','preservefilename'))
//the folder to save must represent a path from the basepath. it should be a relative path,preserve filename will be either true or false. when true,the file will be uploaded with it default filename else the system will pick the current user id in the session as the name of the file.
static $documentField = array();//array containing an associative array of field that should be regareded as document field. it will contain the setting for max size and data type.
		
static $relation=array('session'=>array( 'session_id', 'ID')
,'semester'=>array( 'semester_id', 'ID')
,'action_list'=>array(array( 'ID', 'session_semester_id', 1))
,'course_prerequisite'=>array(array( 'ID', 'session_semester_id', 1))
,'session_semester_course'=>array(array( 'ID', 'session_semester_id', 1))
,'student_semester_history'=>array(array( 'ID', 'session_semester_id', 1))
);
static $tableAction=array('enable'=>'getEnabled','delete'=>'delete/session_semester','update'=>'edit/session_semester');
function __construct($array=array())
{
	parent::__construct($array);
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
	 function getSemester_idFormField($value=''){
	$fk=null;//change the value of this variable to array('table'=>'semester','display'=>'semester_name'); if you want to preload the value from the database where the display key is the name of the field to use for display in the table.

	if (is_null($fk)) {
		return $result="<input type='hidden' value='$value' name='semester_id' id='semester_id' class='form-control' />
			";
	}
	if (is_array($fk)) {
		$result ="<div class='form-group'>
		<label for='semester_id'>Semester Id</label>";
		$option = $this->loadOption($fk,$value);
		//load the value from the given table given the name of the table to load and the display field
		$result.="<select name='semester_id' id='semester_id' class='form-control'>
			$option
		</select>";
	}
	$result.="</div>";
	return  $result;

}
function getStart_dateFormField($value=''){
	return "<div class='form-group'>
	<label for='start_date' >Start Date</label>
	<input type='date' name='start_date' id='start_date' value='$value' class='form-control'  />
</div> ";

}
function getEnd_dateFormField($value=''){
	return "<div class='form-group'>
	<label for='end_date' >End Date</label>
	<input type='date' name='end_date' id='end_date' value='$value' class='form-control'  />
</div> ";

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
		
protected function getSemester(){
	$query ='SELECT * FROM semester WHERE id=?';
	if (!isset($this->array['ID'])) {
		return null;
	}
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Semester.php');
	$resultObject = new Semester($result[0]);
	return $resultObject;
}
		
protected function getAction_list(){
	$query ='SELECT * FROM action_list WHERE session_semester_id=?';
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Action_list.php');
	$resultobjects = array();
	foreach ($result as  $value) {
		$resultObjects[] = new Action_list($value);
	}

	return $resultObjects;
}
		
protected function getCourse_prerequisite(){
	$query ='SELECT * FROM course_prerequisite WHERE session_semester_id=?';
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
	$query ='SELECT * FROM session_semester_course WHERE session_semester_id=?';
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
		
protected function getStudent_semester_history(){
	$query ='SELECT * FROM student_semester_history WHERE session_semester_id=?';
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Student_semester_history.php');
	$resultobjects = array();
	foreach ($result as  $value) {
		$resultObjects[] = new Student_semester_history($value);
	}

	return $resultObjects;
}
		}
		?>