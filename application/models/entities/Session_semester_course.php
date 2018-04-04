<?php
		require_once('application/models/Crud.php');
		/**
		* This class  is automatically generated based on the structure of the table. And it represent the model of the session_semester_course table.
		*/
		class Session_semester_course extends Crud
		{
protected static $tablename='Session_semester_course';
/* this array contains the field that can be null*/
static $nullArray=array('lecturer_id' ,'program_id' ,'department_id' );
static $compositePrimaryKey=array();
static $uploadDependency = array();
/*this array contains the fields that are unique*/
static $uniqueArray=array();
/*this is an associative array containing the fieldname and the type of the field*/
static $typeArray = array('course_status'=>'enum','course_id'=>'int','level_id'=>'int','course_unit'=>'int','lecturer_id'=>'int','program_id'=>'int','department_id'=>'int','session_semester_id'=>'int');
/*this is a dictionary that map a field name with the label name that will be shown in a form*/
static $labelArray=array('ID'=>'','course_status'=>'','course_id'=>'','level_id'=>'','course_unit'=>'','lecturer_id'=>'','program_id'=>'','department_id'=>'','session_semester_id'=>'');
/*associative array of fields that have default value*/
static $defaultArray = array();
//populate this array with fields that are meant to be displayed as document in the format array('fieldname'=>array('filetype','maxsize',foldertosave','preservefilename'))
//the folder to save must represent a path from the basepath. it should be a relative path,preserve filename will be either true or false. when true,the file will be uploaded with it default filename else the system will pick the current user id in the session as the name of the file.
static $documentField = array();//array containing an associative array of field that should be regareded as document field. it will contain the setting for max size and data type.
		
static $relation=array('course'=>array( 'course_id', 'ID')
,'level'=>array( 'level_id', 'ID')
,'lecturer'=>array( 'lecturer_id', 'ID')
,'program'=>array( 'program_id', 'ID')
,'department'=>array( 'department_id', 'ID')
,'session_semester'=>array( 'session_semester_id', 'ID')
,'course_registration_history'=>array(array( 'ID', 'session_semester_course_id', 1))
,'session_course_group'=>array(array( 'ID', 'session_semester_course_id', 1))
,'student_course_registration'=>array(array( 'ID', 'session_semester_course_id', 1))
);
static $tableAction=array('enable'=>'getEnabled','delete'=>'delete/session_semester_course','update'=>'edit/session_semester_course');
function __construct($array=array())
{
	parent::__construct($array);
}
function getCourse_statusFormField($value=''){
	return "<div class='form-group'>
	<label for='course_status' >Course Status</label><select name='course_status' id='course_status' value='$value' class='form-control' required>
	<option>..choose..</option><option> required </option><option> elective </option><option> compulsory </option><option> prerequisite </option>
</select>
</div> ";

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
	 function getLevel_idFormField($value=''){
	$fk=null;//change the value of this variable to array('table'=>'level','display'=>'level_name'); if you want to preload the value from the database where the display key is the name of the field to use for display in the table.

	if (is_null($fk)) {
		return $result="<input type='hidden' value='$value' name='level_id' id='level_id' class='form-control' />
			";
	}
	if (is_array($fk)) {
		$result ="<div class='form-group'>
		<label for='level_id'>Level Id</label>";
		$option = $this->loadOption($fk,$value);
		//load the value from the given table given the name of the table to load and the display field
		$result.="<select name='level_id' id='level_id' class='form-control'>
			$option
		</select>";
	}
	$result.="</div>";
	return  $result;

}
function getCourse_unitFormField($value=''){
	return "<div class='form-group'>
	<label for='course_unit' >Course Unit</label><input type='number' name='course_unit' id='course_unit' value='$value' class='form-control' required />
</div> ";

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
	 function getProgram_idFormField($value=''){
	$fk=null;//change the value of this variable to array('table'=>'program','display'=>'program_name'); if you want to preload the value from the database where the display key is the name of the field to use for display in the table.

	if (is_null($fk)) {
		return $result="<input type='hidden' value='$value' name='program_id' id='program_id' class='form-control' />
			";
	}
	if (is_array($fk)) {
		$result ="<div class='form-group'>
		<label for='program_id'>Program Id</label>";
		$option = $this->loadOption($fk,$value);
		//load the value from the given table given the name of the table to load and the display field
		$result.="<select name='program_id' id='program_id' class='form-control'>
			$option
		</select>";
	}
	$result.="</div>";
	return  $result;

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
		
protected function getLevel(){
	$query ='SELECT * FROM level WHERE id=?';
	if (!isset($this->array['ID'])) {
		return null;
	}
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Level.php');
	$resultObject = new Level($result[0]);
	return $resultObject;
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
		
protected function getProgram(){
	$query ='SELECT * FROM program WHERE id=?';
	if (!isset($this->array['ID'])) {
		return null;
	}
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Program.php');
	$resultObject = new Program($result[0]);
	return $resultObject;
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
		
protected function getCourse_registration_history(){
	$query ='SELECT * FROM course_registration_history WHERE session_semester_course_id=?';
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Course_registration_history.php');
	$resultobjects = array();
	foreach ($result as  $value) {
		$resultObjects[] = new Course_registration_history($value);
	}

	return $resultObjects;
}
		
protected function getSession_course_group(){
	$query ='SELECT * FROM session_course_group WHERE session_semester_course_id=?';
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Session_course_group.php');
	$resultobjects = array();
	foreach ($result as  $value) {
		$resultObjects[] = new Session_course_group($value);
	}

	return $resultObjects;
}
		
protected function getStudent_course_registration(){
	$query ='SELECT * FROM student_course_registration WHERE session_semester_course_id=?';
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Student_course_registration.php');
	$resultobjects = array();
	foreach ($result as  $value) {
		$resultObjects[] = new Student_course_registration($value);
	}

	return $resultObjects;
}
		}
		?>