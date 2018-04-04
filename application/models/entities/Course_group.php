<?php
		require_once('application/models/Crud.php');
		/**
		* This class  is automatically generated based on the structure of the table. And it represent the model of the course_group table.
		*/
		class Course_group extends Crud
		{
protected static $tablename='Course_group';
/* this array contains the field that can be null*/
static $nullArray=array('group_name' ,'status' );
static $compositePrimaryKey=array();
static $uploadDependency = array();
/*this array contains the fields that are unique*/
static $uniqueArray=array('group_name' );
/*this is an associative array containing the fieldname and the type of the field*/
static $typeArray = array('group_name'=>'varchar','status'=>'tinyint');
/*this is a dictionary that map a field name with the label name that will be shown in a form*/
static $labelArray=array('ID'=>'','group_name'=>'','status'=>'');
/*associative array of fields that have default value*/
static $defaultArray = array();
//populate this array with fields that are meant to be displayed as document in the format array('fieldname'=>array('filetype','maxsize',foldertosave','preservefilename'))
//the folder to save must represent a path from the basepath. it should be a relative path,preserve filename will be either true or false. when true,the file will be uploaded with it default filename else the system will pick the current user id in the session as the name of the file.
static $documentField = array();//array containing an associative array of field that should be regareded as document field. it will contain the setting for max size and data type.
		
static $relation=array('session_course_group'=>array(array( 'ID', 'course_group_id', 1))
);
static $tableAction=array('enable'=>'getEnabled','delete'=>'delete/course_group','update'=>'edit/course_group');
function __construct($array=array())
{
	parent::__construct($array);
}
function getGroup_nameFormField($value=''){
	return "<div class='form-group'>
	<label for='group_name' >Group Name</label>
		<input type='text' name='group_name' id='group_name' value='$value' class='form-control'  />
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


		
protected function getSession_course_group(){
	$query ='SELECT * FROM session_course_group WHERE course_group_id=?';
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
		}
		?>