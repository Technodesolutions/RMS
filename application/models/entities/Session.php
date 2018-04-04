<?php
		require_once('application/models/Crud.php');
		/**
		* This class  is automatically generated based on the structure of the table. And it represent the model of the session table.
		*/
		class Session extends Crud
		{
protected static $tablename='Session';
/* this array contains the field that can be null*/
static $nullArray=array('status' );
static $compositePrimaryKey=array();
static $uploadDependency = array();
/*this array contains the fields that are unique*/
static $uniqueArray=array('session_name' );
/*this is an associative array containing the fieldname and the type of the field*/
static $typeArray = array('session_name'=>'varchar','start_date'=>'date','end_date'=>'date','status'=>'tinyint');
/*this is a dictionary that map a field name with the label name that will be shown in a form*/
static $labelArray=array('ID'=>'','session_name'=>'','start_date'=>'','end_date'=>'','status'=>'');
/*associative array of fields that have default value*/
static $defaultArray = array('status'=>'1');
//populate this array with fields that are meant to be displayed as document in the format array('fieldname'=>array('filetype','maxsize',foldertosave','preservefilename'))
//the folder to save must represent a path from the basepath. it should be a relative path,preserve filename will be either true or false. when true,the file will be uploaded with it default filename else the system will pick the current user id in the session as the name of the file.
static $documentField = array();//array containing an associative array of field that should be regareded as document field. it will contain the setting for max size and data type.
		
static $relation=array('department_min_max_unit'=>array(array( 'ID', 'session_id', 1))
,'faculty_min_max_unit'=>array(array( 'ID', 'session_id', 1))
,'grade_scale'=>array(array( 'ID', 'session_id', 1))
,'min_max_unit'=>array(array( 'ID', 'session_id', 1))
,'program_min_max_unit'=>array(array( 'ID', 'session_id', 1))
,'session_scale'=>array(array( 'ID', 'session_id', 1))
,'session_semester'=>array(array( 'ID', 'session_id', 1))
,'student_biodata'=>array(array( 'ID', 'session_id', 1))
,'student_min_max_unit'=>array(array( 'ID', 'session_id', 1))
,'student_transfer'=>array(array( 'ID', 'session_id', 1))
);
static $tableAction=array('enable'=>'getEnabled','delete'=>'delete/session','update'=>'edit/session');
function __construct($array=array())
{
	parent::__construct($array);
}
function getSession_nameFormField($value=''){
	return "<div class='form-group'>
	<label for='session_name' >Session Name</label>
		<input type='text' name='session_name' id='session_name' value='$value' class='form-control' required />
</div> ";

}
function getStart_dateFormField($value=''){
	return "<div class='form-group'>
	<label for='start_date' >Start Date</label>
	<input type='date' name='start_date' id='start_date' value='$value' class='form-control' required />
</div> ";

}
function getEnd_dateFormField($value=''){
	return "<div class='form-group'>
	<label for='end_date' >End Date</label>
	<input type='date' name='end_date' id='end_date' value='$value' class='form-control' required />
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


		
protected function getDepartment_min_max_unit(){
	$query ='SELECT * FROM department_min_max_unit WHERE session_id=?';
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Department_min_max_unit.php');
	$resultobjects = array();
	foreach ($result as  $value) {
		$resultObjects[] = new Department_min_max_unit($value);
	}

	return $resultObjects;
}
		
protected function getFaculty_min_max_unit(){
	$query ='SELECT * FROM faculty_min_max_unit WHERE session_id=?';
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Faculty_min_max_unit.php');
	$resultobjects = array();
	foreach ($result as  $value) {
		$resultObjects[] = new Faculty_min_max_unit($value);
	}

	return $resultObjects;
}
		
protected function getGrade_scale(){
	$query ='SELECT * FROM grade_scale WHERE session_id=?';
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Grade_scale.php');
	$resultobjects = array();
	foreach ($result as  $value) {
		$resultObjects[] = new Grade_scale($value);
	}

	return $resultObjects;
}
		
protected function getMin_max_unit(){
	$query ='SELECT * FROM min_max_unit WHERE session_id=?';
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Min_max_unit.php');
	$resultobjects = array();
	foreach ($result as  $value) {
		$resultObjects[] = new Min_max_unit($value);
	}

	return $resultObjects;
}
		
protected function getProgram_min_max_unit(){
	$query ='SELECT * FROM program_min_max_unit WHERE session_id=?';
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Program_min_max_unit.php');
	$resultobjects = array();
	foreach ($result as  $value) {
		$resultObjects[] = new Program_min_max_unit($value);
	}

	return $resultObjects;
}
		
protected function getSession_scale(){
	$query ='SELECT * FROM session_scale WHERE session_id=?';
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Session_scale.php');
	$resultobjects = array();
	foreach ($result as  $value) {
		$resultObjects[] = new Session_scale($value);
	}

	return $resultObjects;
}
		
protected function getSession_semester(){
	$query ='SELECT * FROM session_semester WHERE session_id=?';
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Session_semester.php');
	$resultobjects = array();
	foreach ($result as  $value) {
		$resultObjects[] = new Session_semester($value);
	}

	return $resultObjects;
}
		
protected function getStudent_biodata(){
	$query ='SELECT * FROM student_biodata WHERE session_id=?';
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Student_biodata.php');
	$resultobjects = array();
	foreach ($result as  $value) {
		$resultObjects[] = new Student_biodata($value);
	}

	return $resultObjects;
}
		
protected function getStudent_min_max_unit(){
	$query ='SELECT * FROM student_min_max_unit WHERE session_id=?';
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Student_min_max_unit.php');
	$resultobjects = array();
	foreach ($result as  $value) {
		$resultObjects[] = new Student_min_max_unit($value);
	}

	return $resultObjects;
}
		
protected function getStudent_transfer(){
	$query ='SELECT * FROM student_transfer WHERE session_id=?';
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Student_transfer.php');
	$resultobjects = array();
	foreach ($result as  $value) {
		$resultObjects[] = new Student_transfer($value);
	}

	return $resultObjects;
}
		}
		?>