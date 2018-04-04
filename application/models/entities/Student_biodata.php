<?php
		require_once('application/models/Crud.php');
		/**
		* This class  is automatically generated based on the structure of the table. And it represent the model of the student_biodata table.
		*/
		class Student_biodata extends Crud
		{
protected static $tablename='Student_biodata';
/* this array contains the field that can be null*/
static $nullArray=array('middlename' ,'dob' ,'email' ,'phone_number' ,'gender' ,'address' ,'state_of_origin' ,'lga_of_origin' ,'registration_number' ,'entry_mode_id' ,'study_mode_id' ,'img_path' ,'nationality' );
static $compositePrimaryKey=array();
static $uploadDependency = array();
/*this array contains the fields that are unique*/
static $uniqueArray=array('matric_number' );
/*this is an associative array containing the fieldname and the type of the field*/
static $typeArray = array('surname'=>'varchar','firstname'=>'varchar','middlename'=>'varchar','dob'=>'date','email'=>'text','phone_number'=>'text','gender'=>'varchar','address'=>'text','state_of_origin'=>'varchar','lga_of_origin'=>'varchar','matric_number'=>'varchar','registration_number'=>'varchar','program_id'=>'int','session_id'=>'int','entry_mode_id'=>'int','study_mode_id'=>'int','img_path'=>'varchar','nationality'=>'varchar');
/*this is a dictionary that map a field name with the label name that will be shown in a form*/
static $labelArray=array('ID'=>'','surname'=>'','firstname'=>'','middlename'=>'','dob'=>'','email'=>'','phone_number'=>'','gender'=>'','address'=>'','state_of_origin'=>'','lga_of_origin'=>'','matric_number'=>'','registration_number'=>'','program_id'=>'','session_id'=>'','entry_mode_id'=>'','study_mode_id'=>'','img_path'=>'','nationality'=>'');
/*associative array of fields that have default value*/
static $defaultArray = array();
//populate this array with fields that are meant to be displayed as document in the format array('fieldname'=>array('filetype','maxsize',foldertosave','preservefilename'))
//the folder to save must represent a path from the basepath. it should be a relative path,preserve filename will be either true or false. when true,the file will be uploaded with it default filename else the system will pick the current user id in the session as the name of the file.
static $documentField = array();//array containing an associative array of field that should be regareded as document field. it will contain the setting for max size and data type.
		
static $relation=array('program'=>array( 'program_id', 'ID')
,'session'=>array( 'session_id', 'ID')
,'entry_mode'=>array( 'entry_mode_id', 'ID')
,'study_mode'=>array( 'study_mode_id', 'ID')
,'action_list'=>array(array( 'ID', 'student_biodata_id', 1))
,'course_registration_history'=>array(array( 'ID', 'student_biodata_id', 1))
,'guardian'=>array(array( 'ID', 'student_biodata_id', 1))
,'next_of_kin'=>array(array( 'ID', 'student_biodata_id', 1))
,'student_course_registration'=>array(array( 'ID', 'student_biodata_id', 1))
,'student_min_max_unit'=>array(array( 'ID', 'student_biodata_id', 1))
,'student_semester_history'=>array(array( 'ID', 'student_biodata_id', 1))
,'student_transfer'=>array(array( 'ID', 'student_biodata_id', 1))
);
static $tableAction=array('enable'=>'getEnabled','delete'=>'delete/student_biodata','update'=>'edit/student_biodata');
function __construct($array=array())
{
	parent::__construct($array);
}
function getSurnameFormField($value=''){
	return "<div class='form-group'>
	<label for='surname' >Surname</label>
		<input type='text' name='surname' id='surname' value='$value' class='form-control' required />
</div> ";

}
function getFirstnameFormField($value=''){
	return "<div class='form-group'>
	<label for='firstname' >Firstname</label>
		<input type='text' name='firstname' id='firstname' value='$value' class='form-control' required />
</div> ";

}
function getMiddlenameFormField($value=''){
	return "<div class='form-group'>
	<label for='middlename' >Middlename</label>
		<input type='text' name='middlename' id='middlename' value='$value' class='form-control'  />
</div> ";

}
function getDobFormField($value=''){
	return "<div class='form-group'>
	<label for='dob' >Dob</label>
	<input type='date' name='dob' id='dob' value='$value' class='form-control'  />
</div> ";

}
function getEmailFormField($value=''){
	return "<div class='form-group'>
	<label for='email' >Email</label>
<textarea id='email' name='email' class='form-control' >$value</textarea>
</div> ";

}
function getPhone_numberFormField($value=''){
	return "<div class='form-group'>
	<label for='phone_number' >Phone Number</label>
<textarea id='phone_number' name='phone_number' class='form-control' >$value</textarea>
</div> ";

}
function getGenderFormField($value=''){
	return "<div class='form-group'>
	<label for='gender' >Gender</label>
		<input type='text' name='gender' id='gender' value='$value' class='form-control'  />
</div> ";

}
function getAddressFormField($value=''){
	return "<div class='form-group'>
	<label for='address' >Address</label>
<textarea id='address' name='address' class='form-control' >$value</textarea>
</div> ";

}
function getState_of_originFormField($value=''){
	return "<div class='form-group'>
	<label for='state_of_origin' >State Of Origin</label>
		<input type='text' name='state_of_origin' id='state_of_origin' value='$value' class='form-control'  />
</div> ";

}
function getLga_of_originFormField($value=''){
	return "<div class='form-group'>
	<label for='lga_of_origin' >Lga Of Origin</label>
		<input type='text' name='lga_of_origin' id='lga_of_origin' value='$value' class='form-control'  />
</div> ";

}
function getMatric_numberFormField($value=''){
	return "<div class='form-group'>
	<label for='matric_number' >Matric Number</label>
		<input type='text' name='matric_number' id='matric_number' value='$value' class='form-control' required />
</div> ";

}
function getRegistration_numberFormField($value=''){
	return "<div class='form-group'>
	<label for='registration_number' >Registration Number</label>
		<input type='text' name='registration_number' id='registration_number' value='$value' class='form-control'  />
</div> ";

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
	 function getEntry_mode_idFormField($value=''){
	$fk=null;//change the value of this variable to array('table'=>'entry_mode','display'=>'entry_mode_name'); if you want to preload the value from the database where the display key is the name of the field to use for display in the table.

	if (is_null($fk)) {
		return $result="<input type='hidden' value='$value' name='entry_mode_id' id='entry_mode_id' class='form-control' />
			";
	}
	if (is_array($fk)) {
		$result ="<div class='form-group'>
		<label for='entry_mode_id'>Entry Mode Id</label>";
		$option = $this->loadOption($fk,$value);
		//load the value from the given table given the name of the table to load and the display field
		$result.="<select name='entry_mode_id' id='entry_mode_id' class='form-control'>
			$option
		</select>";
	}
	$result.="</div>";
	return  $result;

}
	 function getStudy_mode_idFormField($value=''){
	$fk=null;//change the value of this variable to array('table'=>'study_mode','display'=>'study_mode_name'); if you want to preload the value from the database where the display key is the name of the field to use for display in the table.

	if (is_null($fk)) {
		return $result="<input type='hidden' value='$value' name='study_mode_id' id='study_mode_id' class='form-control' />
			";
	}
	if (is_array($fk)) {
		$result ="<div class='form-group'>
		<label for='study_mode_id'>Study Mode Id</label>";
		$option = $this->loadOption($fk,$value);
		//load the value from the given table given the name of the table to load and the display field
		$result.="<select name='study_mode_id' id='study_mode_id' class='form-control'>
			$option
		</select>";
	}
	$result.="</div>";
	return  $result;

}
function getImg_pathFormField($value=''){
	return "<div class='form-group'>
	<label for='img_path' >Img Path</label>
		<input type='text' name='img_path' id='img_path' value='$value' class='form-control'  />
</div> ";

}
function getNationalityFormField($value=''){
	return "<div class='form-group'>
	<label for='nationality' >Nationality</label>
		<input type='text' name='nationality' id='nationality' value='$value' class='form-control'  />
</div> ";

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
		
protected function getEntry_mode(){
	$query ='SELECT * FROM entry_mode WHERE id=?';
	if (!isset($this->array['ID'])) {
		return null;
	}
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Entry_mode.php');
	$resultObject = new Entry_mode($result[0]);
	return $resultObject;
}
		
protected function getStudy_mode(){
	$query ='SELECT * FROM study_mode WHERE id=?';
	if (!isset($this->array['ID'])) {
		return null;
	}
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Study_mode.php');
	$resultObject = new Study_mode($result[0]);
	return $resultObject;
}
		
protected function getAction_list(){
	$query ='SELECT * FROM action_list WHERE student_biodata_id=?';
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
		
protected function getCourse_registration_history(){
	$query ='SELECT * FROM course_registration_history WHERE student_biodata_id=?';
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
		
protected function getGuardian(){
	$query ='SELECT * FROM guardian WHERE student_biodata_id=?';
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Guardian.php');
	$resultobjects = array();
	foreach ($result as  $value) {
		$resultObjects[] = new Guardian($value);
	}

	return $resultObjects;
}
		
protected function getNext_of_kin(){
	$query ='SELECT * FROM next_of_kin WHERE student_biodata_id=?';
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Next_of_kin.php');
	$resultobjects = array();
	foreach ($result as  $value) {
		$resultObjects[] = new Next_of_kin($value);
	}

	return $resultObjects;
}
		
protected function getStudent_course_registration(){
	$query ='SELECT * FROM student_course_registration WHERE student_biodata_id=?';
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
		
protected function getStudent_min_max_unit(){
	$query ='SELECT * FROM student_min_max_unit WHERE student_biodata_id=?';
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
		
protected function getStudent_semester_history(){
	$query ='SELECT * FROM student_semester_history WHERE student_biodata_id=?';
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
		
protected function getStudent_transfer(){
	$query ='SELECT * FROM student_transfer WHERE student_biodata_id=?';
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