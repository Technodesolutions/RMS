<?php
		require_once('application/models/Crud.php');
		/**
		* This class  is automatically generated based on the structure of the table. And it represent the model of the program table.
		*/
		class Program extends Crud
		{
protected static $tablename='Program';
/* this array contains the field that can be null*/
static $nullArray=array('status' );
static $compositePrimaryKey=array();
static $uploadDependency = array();
/*this array contains the fields that are unique*/
static $uniqueArray=array('program_name' );
/*this is an associative array containing the fieldname and the type of the field*/
static $typeArray = array('program_name'=>'varchar','study_mode_id'=>'int','department_id'=>'int','start_level'=>'int','end_level'=>'int','status'=>'tinyint');
/*this is a dictionary that map a field name with the label name that will be shown in a form*/
static $labelArray=array('ID'=>'','program_name'=>'','study_mode_id'=>'','department_id'=>'','start_level'=>'','end_level'=>'','status'=>'');
/*associative array of fields that have default value*/
static $defaultArray = array('status'=>'1');
//populate this array with fields that are meant to be displayed as document in the format array('fieldname'=>array('filetype','maxsize',foldertosave','preservefilename'))
//the folder to save must represent a path from the basepath. it should be a relative path,preserve filename will be either true or false. when true,the file will be uploaded with it default filename else the system will pick the current user id in the session as the name of the file.
static $documentField = array();//array containing an associative array of field that should be regareded as document field. it will contain the setting for max size and data type.
		
static $relation=array('study_mode'=>array( 'study_mode_id', 'ID')
,'department'=>array( 'department_id', 'ID')
,'program_degree'=>array(array( 'ID', 'program_id', 1))
,'program_min_max_unit'=>array(array( 'ID', 'program_id', 1))
,'session_semester_course'=>array(array( 'ID', 'program_id', 1))
,'student_biodata'=>array(array( 'ID', 'program_id', 1))
);
static $tableAction=array('enable'=>'getEnabled','delete'=>'delete/program','update'=>'edit/program');
function __construct($array=array())
{
	parent::__construct($array);
}
function getProgram_nameFormField($value=''){
	return "<div class='form-group'>
	<label for='program_name' >Program Name</label>
		<input type='text' name='program_name' id='program_name' value='$value' class='form-control' required />
</div> ";

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
function getStart_levelFormField($value=''){
	return "<div class='form-group'>
	<label for='start_level' >Start Level</label><input type='number' name='start_level' id='start_level' value='$value' class='form-control' required />
</div> ";

}
function getEnd_levelFormField($value=''){
	return "<div class='form-group'>
	<label for='end_level' >End Level</label><input type='number' name='end_level' id='end_level' value='$value' class='form-control' required />
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
		
protected function getProgram_degree(){
	$query ='SELECT * FROM program_degree WHERE program_id=?';
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Program_degree.php');
	$resultobjects = array();
	foreach ($result as  $value) {
		$resultObjects[] = new Program_degree($value);
	}

	return $resultObjects;
}
		
protected function getProgram_min_max_unit(){
	$query ='SELECT * FROM program_min_max_unit WHERE program_id=?';
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
		
protected function getSession_semester_course(){
	$query ='SELECT * FROM session_semester_course WHERE program_id=?';
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
		
protected function getStudent_biodata(){
	$query ='SELECT * FROM student_biodata WHERE program_id=?';
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
		}
		?>