<?php
		require_once('application/models/Crud.php');
		/**
		* This class  is automatically generated based on the structure of the table. And it represent the model of the faculty table.
		*/
		class Faculty extends Crud
		{
protected static $tablename='Faculty';
/* this array contains the field that can be null*/
static $nullArray=array('faculty_color' ,'slogan' ,'date_created' );
static $compositePrimaryKey=array();
static $uploadDependency = array();
/*this array contains the fields that are unique*/
static $uniqueArray=array('faculty_name' );
/*this is an associative array containing the fieldname and the type of the field*/
static $typeArray = array('faculty_name'=>'varchar','faculty_color'=>'varchar','slogan'=>'varchar','date_created'=>'date','description'=>'text');
/*this is a dictionary that map a field name with the label name that will be shown in a form*/
static $labelArray=array('ID'=>'','faculty_name'=>'','faculty_color'=>'','slogan'=>'','date_created'=>'','description'=>'');
/*associative array of fields that have default value*/
static $defaultArray = array('date_created'=>'current_timestamp()');
//populate this array with fields that are meant to be displayed as document in the format array('fieldname'=>array('filetype','maxsize',foldertosave','preservefilename'))
//the folder to save must represent a path from the basepath. it should be a relative path,preserve filename will be either true or false. when true,the file will be uploaded with it default filename else the system will pick the current user id in the session as the name of the file.
static $documentField = array();//array containing an associative array of field that should be regareded as document field. it will contain the setting for max size and data type.
		
static $relation=array('department'=>array(array( 'ID', 'faculty_id', 1))
,'faculty_min_max_unit'=>array(array( 'ID', 'faculty_id', 1))
);
static $tableAction=array('enable'=>'getEnabled','delete'=>'delete/faculty','update'=>'edit/faculty');
function __construct($array=array())
{
	parent::__construct($array);
}
function getFaculty_nameFormField($value=''){
	return "<div class='form-group'>
	<label for='faculty_name' >Faculty Name</label>
		<input type='text' name='faculty_name' id='faculty_name' value='$value' class='form-control' required />
</div> ";

}
function getFaculty_colorFormField($value=''){
	return "<div class='form-group'>
	<label for='faculty_color' >Faculty Color</label>
		<input type='text' name='faculty_color' id='faculty_color' value='$value' class='form-control'  />
</div> ";

}
function getSloganFormField($value=''){
	return "<div class='form-group'>
	<label for='slogan' >Slogan</label>
		<input type='text' name='slogan' id='slogan' value='$value' class='form-control'  />
</div> ";

}
function getDate_createdFormField($value=''){
	return "<div class='form-group'>
	<label for='date_created' >Date Created</label>
	<input type='date' name='date_created' id='date_created' value='$value' class='form-control'  />
</div> ";

}
function getDescriptionFormField($value=''){
	return "<div class='form-group'>
	<label for='description' >Description</label>
<textarea id='description' name='description' class='form-control' required>$value</textarea>
</div> ";

}


		
protected function getDepartment(){
	$query ='SELECT * FROM department WHERE faculty_id=?';
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Department.php');
	$resultobjects = array();
	foreach ($result as  $value) {
		$resultObjects[] = new Department($value);
	}

	return $resultObjects;
}
		
protected function getFaculty_min_max_unit(){
	$query ='SELECT * FROM faculty_min_max_unit WHERE faculty_id=?';
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
		}
		?>