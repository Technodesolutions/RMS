<?php
		require_once('application/models/Crud.php');
		/**
		* This class  is automatically generated based on the structure of the table. And it represent the model of the student_transfer table.
		*/
		class Student_transfer extends Crud
		{
protected static $tablename='Student_transfer';
/* this array contains the field that can be null*/
static $nullArray=array('previous_program_id' ,'previous_study_mode_id' ,'new_program_id' ,'new_study_mode_id' ,'comment' );
static $compositePrimaryKey=array();
static $uploadDependency = array();
/*this array contains the fields that are unique*/
static $uniqueArray=array();
/*this is an associative array containing the fieldname and the type of the field*/
static $typeArray = array('student_biodata_id'=>'int','previous_program_id'=>'int','previous_study_mode_id'=>'int','new_program_id'=>'int','new_study_mode_id'=>'int','session_id'=>'int','comment'=>'text');
/*this is a dictionary that map a field name with the label name that will be shown in a form*/
static $labelArray=array('ID'=>'','student_biodata_id'=>'','previous_program_id'=>'','previous_study_mode_id'=>'','new_program_id'=>'','new_study_mode_id'=>'','session_id'=>'','comment'=>'');
/*associative array of fields that have default value*/
static $defaultArray = array();
//populate this array with fields that are meant to be displayed as document in the format array('fieldname'=>array('filetype','maxsize',foldertosave','preservefilename'))
//the folder to save must represent a path from the basepath. it should be a relative path,preserve filename will be either true or false. when true,the file will be uploaded with it default filename else the system will pick the current user id in the session as the name of the file.
static $documentField = array();//array containing an associative array of field that should be regareded as document field. it will contain the setting for max size and data type.
		
static $relation=array('student_biodata'=>array( 'student_biodata_id', 'ID')
,'session'=>array( 'session_id', 'ID')
);
static $tableAction=array('enable'=>'getEnabled','delete'=>'delete/student_transfer','update'=>'edit/student_transfer');
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
	 function getPrevious_program_idFormField($value=''){
	$fk=null;//change the value of this variable to array('table'=>'previous_program','display'=>'previous_program_name'); if you want to preload the value from the database where the display key is the name of the field to use for display in the table.

	if (is_null($fk)) {
		return $result="<input type='hidden' value='$value' name='previous_program_id' id='previous_program_id' class='form-control' />
			";
	}
	if (is_array($fk)) {
		$result ="<div class='form-group'>
		<label for='previous_program_id'>Previous Program Id</label>";
		$option = $this->loadOption($fk,$value);
		//load the value from the given table given the name of the table to load and the display field
		$result.="<select name='previous_program_id' id='previous_program_id' class='form-control'>
			$option
		</select>";
	}
	$result.="</div>";
	return  $result;

}
	 function getPrevious_study_mode_idFormField($value=''){
	$fk=null;//change the value of this variable to array('table'=>'previous_study_mode','display'=>'previous_study_mode_name'); if you want to preload the value from the database where the display key is the name of the field to use for display in the table.

	if (is_null($fk)) {
		return $result="<input type='hidden' value='$value' name='previous_study_mode_id' id='previous_study_mode_id' class='form-control' />
			";
	}
	if (is_array($fk)) {
		$result ="<div class='form-group'>
		<label for='previous_study_mode_id'>Previous Study Mode Id</label>";
		$option = $this->loadOption($fk,$value);
		//load the value from the given table given the name of the table to load and the display field
		$result.="<select name='previous_study_mode_id' id='previous_study_mode_id' class='form-control'>
			$option
		</select>";
	}
	$result.="</div>";
	return  $result;

}
	 function getNew_program_idFormField($value=''){
	$fk=null;//change the value of this variable to array('table'=>'new_program','display'=>'new_program_name'); if you want to preload the value from the database where the display key is the name of the field to use for display in the table.

	if (is_null($fk)) {
		return $result="<input type='hidden' value='$value' name='new_program_id' id='new_program_id' class='form-control' />
			";
	}
	if (is_array($fk)) {
		$result ="<div class='form-group'>
		<label for='new_program_id'>New Program Id</label>";
		$option = $this->loadOption($fk,$value);
		//load the value from the given table given the name of the table to load and the display field
		$result.="<select name='new_program_id' id='new_program_id' class='form-control'>
			$option
		</select>";
	}
	$result.="</div>";
	return  $result;

}
	 function getNew_study_mode_idFormField($value=''){
	$fk=null;//change the value of this variable to array('table'=>'new_study_mode','display'=>'new_study_mode_name'); if you want to preload the value from the database where the display key is the name of the field to use for display in the table.

	if (is_null($fk)) {
		return $result="<input type='hidden' value='$value' name='new_study_mode_id' id='new_study_mode_id' class='form-control' />
			";
	}
	if (is_array($fk)) {
		$result ="<div class='form-group'>
		<label for='new_study_mode_id'>New Study Mode Id</label>";
		$option = $this->loadOption($fk,$value);
		//load the value from the given table given the name of the table to load and the display field
		$result.="<select name='new_study_mode_id' id='new_study_mode_id' class='form-control'>
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
function getCommentFormField($value=''){
	return "<div class='form-group'>
	<label for='comment' >Comment</label>
<textarea id='comment' name='comment' class='form-control' >$value</textarea>
</div> ";

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
		}
		?>