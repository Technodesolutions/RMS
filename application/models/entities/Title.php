<?php
		require_once('application/models/Crud.php');
		/**
		* This class  is automatically generated based on the structure of the table. And it represent the model of the title table.
		*/
		class Title extends Crud
		{
protected static $tablename='Title';
/* this array contains the field that can be null*/
static $nullArray=array('status' );
static $compositePrimaryKey=array();
static $uploadDependency = array();
/*this array contains the fields that are unique*/
static $uniqueArray=array('title_name' );
/*this is an associative array containing the fieldname and the type of the field*/
static $typeArray = array('title_name'=>'varchar','status'=>'tinyint');
/*this is a dictionary that map a field name with the label name that will be shown in a form*/
static $labelArray=array('ID'=>'','title_name'=>'','status'=>'');
/*associative array of fields that have default value*/
static $defaultArray = array('status'=>'1');
//populate this array with fields that are meant to be displayed as document in the format array('fieldname'=>array('filetype','maxsize',foldertosave','preservefilename'))
//the folder to save must represent a path from the basepath. it should be a relative path,preserve filename will be either true or false. when true,the file will be uploaded with it default filename else the system will pick the current user id in the session as the name of the file.
static $documentField = array();//array containing an associative array of field that should be regareded as document field. it will contain the setting for max size and data type.
		
static $relation=array('guardian'=>array(array( 'ID', 'title_id', 1))
,'lecturer'=>array(array( 'ID', 'title_id', 1))
);
static $tableAction=array('enable'=>'getEnabled','delete'=>'delete/title','update'=>'edit/title');
function __construct($array=array())
{
	parent::__construct($array);
}
function getTitle_nameFormField($value=''){
	return "<div class='form-group'>
	<label for='title_name' >Title Name</label>
		<input type='text' name='title_name' id='title_name' value='$value' class='form-control' required />
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


		
protected function getGuardian(){
	$query ='SELECT * FROM guardian WHERE title_id=?';
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
		
protected function getLecturer(){
	$query ='SELECT * FROM lecturer WHERE title_id=?';
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
		}
		?>