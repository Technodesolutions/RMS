<?php
		require_once('application/models/Crud.php');
		/**
		* This class  is automatically generated based on the structure of the table. And it represent the model of the result_complaint table.
		*/
		class Result_complaint extends Crud
		{
protected static $tablename='Result_complaint';
/* this array contains the field that can be null*/
static $nullArray=array('complaint' ,'date' );
static $compositePrimaryKey=array();
static $uploadDependency = array();
/*this array contains the fields that are unique*/
static $uniqueArray=array();
/*this is an associative array containing the fieldname and the type of the field*/
static $typeArray = array('course_score_id'=>'int','complaint'=>'text','date'=>'timestamp');
/*this is a dictionary that map a field name with the label name that will be shown in a form*/
static $labelArray=array('ID'=>'','course_score_id'=>'','complaint'=>'','date'=>'');
/*associative array of fields that have default value*/
static $defaultArray = array('date'=>'current_timestamp()');
//populate this array with fields that are meant to be displayed as document in the format array('fieldname'=>array('filetype','maxsize',foldertosave','preservefilename'))
//the folder to save must represent a path from the basepath. it should be a relative path,preserve filename will be either true or false. when true,the file will be uploaded with it default filename else the system will pick the current user id in the session as the name of the file.
static $documentField = array();//array containing an associative array of field that should be regareded as document field. it will contain the setting for max size and data type.
		
static $relation=array('course_score'=>array( 'course_score_id', 'ID')
,'complaint_response'=>array(array( 'ID', 'result_complaint_id', 1))
);
static $tableAction=array('enable'=>'getEnabled','delete'=>'delete/result_complaint','update'=>'edit/result_complaint');
function __construct($array=array())
{
	parent::__construct($array);
}
	 function getCourse_score_idFormField($value=''){
	$fk=null;//change the value of this variable to array('table'=>'course_score','display'=>'course_score_name'); if you want to preload the value from the database where the display key is the name of the field to use for display in the table.

	if (is_null($fk)) {
		return $result="<input type='hidden' value='$value' name='course_score_id' id='course_score_id' class='form-control' />
			";
	}
	if (is_array($fk)) {
		$result ="<div class='form-group'>
		<label for='course_score_id'>Course Score Id</label>";
		$option = $this->loadOption($fk,$value);
		//load the value from the given table given the name of the table to load and the display field
		$result.="<select name='course_score_id' id='course_score_id' class='form-control'>
			$option
		</select>";
	}
	$result.="</div>";
	return  $result;

}
function getComplaintFormField($value=''){
	return "<div class='form-group'>
	<label for='complaint' >Complaint</label>
<textarea id='complaint' name='complaint' class='form-control' >$value</textarea>
</div> ";

}
function getDateFormField($value=''){
	return " ";

}


		
protected function getCourse_score(){
	$query ='SELECT * FROM course_score WHERE id=?';
	if (!isset($this->array['ID'])) {
		return null;
	}
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Course_score.php');
	$resultObject = new Course_score($result[0]);
	return $resultObject;
}
		
protected function getComplaint_response(){
	$query ='SELECT * FROM complaint_response WHERE result_complaint_id=?';
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Complaint_response.php');
	$resultobjects = array();
	foreach ($result as  $value) {
		$resultObjects[] = new Complaint_response($value);
	}

	return $resultObjects;
}
		}
		?>