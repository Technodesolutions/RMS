<?php
		require_once('application/models/Crud.php');
		/**
		* This class  is automatically generated based on the structure of the table. And it represent the model of the study_mode table.
		*/
		class Study_mode extends Crud
		{
protected static $tablename='Study_mode';
/* this array contains the field that can be null*/
static $nullArray=array('description' ,'status' );
static $compositePrimaryKey=array();
static $uploadDependency = array();
/*this array contains the fields that are unique*/
static $uniqueArray=array('mode_of_study' );
/*this is an associative array containing the fieldname and the type of the field*/
static $typeArray = array('mode_of_study'=>'varchar','description'=>'text','status'=>'tinyint');
/*this is a dictionary that map a field name with the label name that will be shown in a form*/
static $labelArray=array('ID'=>'','mode_of_study'=>'','description'=>'','status'=>'');
/*associative array of fields that have default value*/
static $defaultArray = array('status'=>'1');
//populate this array with fields that are meant to be displayed as document in the format array('fieldname'=>array('filetype','maxsize',foldertosave','preservefilename'))
//the folder to save must represent a path from the basepath. it should be a relative path,preserve filename will be either true or false. when true,the file will be uploaded with it default filename else the system will pick the current user id in the session as the name of the file.
static $documentField = array();//array containing an associative array of field that should be regareded as document field. it will contain the setting for max size and data type.
		
static $relation=array('program'=>array(array( 'ID', 'study_mode_id', 1))
,'student_biodata'=>array(array( 'ID', 'study_mode_id', 1))
);
static $tableAction=array('enable'=>'getEnabled','delete'=>'delete/study_mode','update'=>'edit/study_mode');
function __construct($array=array())
{
	parent::__construct($array);
}
function getMode_of_studyFormField($value=''){
	return "<div class='form-group'>
	<label for='mode_of_study' >Mode Of Study</label>
		<input type='text' name='mode_of_study' id='mode_of_study' value='$value' class='form-control' required />
</div> ";

}
function getDescriptionFormField($value=''){
	return "<div class='form-group'>
	<label for='description' >Description</label>
<textarea id='description' name='description' class='form-control' >$value</textarea>
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


		
protected function getProgram(){
	$query ='SELECT * FROM program WHERE study_mode_id=?';
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
		
protected function getStudent_biodata(){
	$query ='SELECT * FROM student_biodata WHERE study_mode_id=?';
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