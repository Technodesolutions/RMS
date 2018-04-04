<?php
		require_once('application/models/Crud.php');
		/**
		* This class  is automatically generated based on the structure of the table. And it represent the model of the department table.
		*/
		class Department extends Crud
		{
protected static $tablename='Department';
/* this array contains the field that can be null*/
static $nullArray=array();
static $compositePrimaryKey=array();
static $uploadDependency = array();
/*this array contains the fields that are unique*/
static $uniqueArray=array('department_name' );
/*this is an associative array containing the fieldname and the type of the field*/
static $typeArray = array('department_name'=>'varchar','faculty_id'=>'int','date_created'=>'int');
/*this is a dictionary that map a field name with the label name that will be shown in a form*/
static $labelArray=array('ID'=>'','department_name'=>'','faculty_id'=>'','date_created'=>'');
/*associative array of fields that have default value*/
static $defaultArray = array();
//populate this array with fields that are meant to be displayed as document in the format array('fieldname'=>array('filetype','maxsize',foldertosave','preservefilename'))
//the folder to save must represent a path from the basepath. it should be a relative path,preserve filename will be either true or false. when true,the file will be uploaded with it default filename else the system will pick the current user id in the session as the name of the file.
static $documentField = array();//array containing an associative array of field that should be regareded as document field. it will contain the setting for max size and data type.
		
static $relation=array('faculty'=>array( 'faculty_id', 'ID')
,'course'=>array(array( 'ID', 'department_id', 1))
,'department_min_max_unit'=>array(array( 'ID', 'department_id', 1))
,'lecturer'=>array(array( 'ID', 'department_id', 1))
,'program'=>array(array( 'ID', 'department_id', 1))
,'session_semester_course'=>array(array( 'ID', 'department_id', 1))
);
static $tableAction=array('enable'=>'getEnabled','delete'=>'delete/department','update'=>'edit/department');
function __construct($array=array())
{
	parent::__construct($array);
}
function getDepartment_nameFormField($value=''){
	return "<div class='form-group'>
	<label for='department_name' >Department Name</label>
		<input type='text' name='department_name' id='department_name' value='$value' class='form-control' required />
</div> ";

}
	 function getFaculty_idFormField($value=''){
	$fk=null;//change the value of this variable to array('table'=>'faculty','display'=>'faculty_name'); if you want to preload the value from the database where the display key is the name of the field to use for display in the table.

	if (is_null($fk)) {
		return $result="<input type='hidden' value='$value' name='faculty_id' id='faculty_id' class='form-control' />
			";
	}
	if (is_array($fk)) {
		$result ="<div class='form-group'>
		<label for='faculty_id'>Faculty Id</label>";
		$option = $this->loadOption($fk,$value);
		//load the value from the given table given the name of the table to load and the display field
		$result.="<select name='faculty_id' id='faculty_id' class='form-control'>
			$option
		</select>";
	}
	$result.="</div>";
	return  $result;

}
function getDate_createdFormField($value=''){
	return "<div class='form-group'>
	<label for='date_created' >Date Created</label><input type='number' name='date_created' id='date_created' value='$value' class='form-control' required />
</div> ";

}


		
protected function getFaculty(){
	$query ='SELECT * FROM faculty WHERE id=?';
	if (!isset($this->array['ID'])) {
		return null;
	}
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Faculty.php');
	$resultObject = new Faculty($result[0]);
	return $resultObject;
}
		
protected function getCourse(){
	$query ='SELECT * FROM course WHERE department_id=?';
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Course.php');
	$resultobjects = array();
	foreach ($result as  $value) {
		$resultObjects[] = new Course($value);
	}

	return $resultObjects;
}
		
protected function getDepartment_min_max_unit(){
	$query ='SELECT * FROM department_min_max_unit WHERE department_id=?';
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
		
protected function getLecturer(){
	$query ='SELECT * FROM lecturer WHERE department_id=?';
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Lecturer.php');
	$resultobjects = array();
	foreach ($result as  $value) {
		$resultObjects[] = new Lecturer($value);
	}

	return $resultObjects;
}
		
protected function getProgram(){
	$query ='SELECT * FROM program WHERE department_id=?';
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Program.php');
	$resultobjects = array();
	foreach ($result as  $value) {
		$resultObjects[] = new Program($value);
	}

	return $resultObjects;
}
		
protected function getSession_semester_course(){
	$query ='SELECT * FROM session_semester_course WHERE department_id=?';
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