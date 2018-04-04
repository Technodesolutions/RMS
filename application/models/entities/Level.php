<?php
		require_once('application/models/Crud.php');
		/**
		* This class  is automatically generated based on the structure of the table. And it represent the model of the level table.
		*/
		class Level extends Crud
		{
protected static $tablename='Level';
/* this array contains the field that can be null*/
static $nullArray=array();
static $compositePrimaryKey=array();
static $uploadDependency = array();
/*this array contains the fields that are unique*/
static $uniqueArray=array('level_name' );
/*this is an associative array containing the fieldname and the type of the field*/
static $typeArray = array('level_name'=>'varchar','description'=>'text','level_order'=>'int');
/*this is a dictionary that map a field name with the label name that will be shown in a form*/
static $labelArray=array('ID'=>'','level_name'=>'','description'=>'','level_order'=>'');
/*associative array of fields that have default value*/
static $defaultArray = array();
//populate this array with fields that are meant to be displayed as document in the format array('fieldname'=>array('filetype','maxsize',foldertosave','preservefilename'))
//the folder to save must represent a path from the basepath. it should be a relative path,preserve filename will be either true or false. when true,the file will be uploaded with it default filename else the system will pick the current user id in the session as the name of the file.
static $documentField = array();//array containing an associative array of field that should be regareded as document field. it will contain the setting for max size and data type.
		
static $relation=array('session_semester_course'=>array(array( 'ID', 'level_id', 1))
);
static $tableAction=array('enable'=>'getEnabled','delete'=>'delete/level','update'=>'edit/level');
function __construct($array=array())
{
	parent::__construct($array);
}
function getLevel_nameFormField($value=''){
	return "<div class='form-group'>
	<label for='level_name' >Level Name</label>
		<input type='text' name='level_name' id='level_name' value='$value' class='form-control' required />
</div> ";

}
function getDescriptionFormField($value=''){
	return "<div class='form-group'>
	<label for='description' >Description</label>
<textarea id='description' name='description' class='form-control' required>$value</textarea>
</div> ";

}
function getLevel_orderFormField($value=''){
	return "<div class='form-group'>
	<label for='level_order' >Level Order</label><input type='number' name='level_order' id='level_order' value='$value' class='form-control' required />
</div> ";

}


		
protected function getSession_semester_course(){
	$query ='SELECT * FROM session_semester_course WHERE level_id=?';
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