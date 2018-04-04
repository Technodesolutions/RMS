<?php
		require_once('application/models/Crud.php');
		/**
		* This class  is automatically generated based on the structure of the table. And it represent the model of the action_type table.
		*/
		class Action_type extends Crud
		{
protected static $tablename='Action_type';
/* this array contains the field that can be null*/
static $nullArray=array('action_name' ,'status' );
static $compositePrimaryKey=array();
static $uploadDependency = array();
/*this array contains the fields that are unique*/
static $uniqueArray=array();
/*this is an associative array containing the fieldname and the type of the field*/
static $typeArray = array('action_name'=>'varchar','status'=>'tinyint');
/*this is a dictionary that map a field name with the label name that will be shown in a form*/
static $labelArray=array('ID'=>'','action_name'=>'','status'=>'');
/*associative array of fields that have default value*/
static $defaultArray = array('status'=>'1');
//populate this array with fields that are meant to be displayed as document in the format array('fieldname'=>array('filetype','maxsize',foldertosave','preservefilename'))
//the folder to save must represent a path from the basepath. it should be a relative path,preserve filename will be either true or false. when true,the file will be uploaded with it default filename else the system will pick the current user id in the session as the name of the file.
static $documentField = array();//array containing an associative array of field that should be regareded as document field. it will contain the setting for max size and data type.
		
static $relation=array('action_list'=>array(array( 'ID', 'action_type_id', 1))
);
static $tableAction=array('enable'=>'getEnabled','delete'=>'delete/action_type','update'=>'edit/action_type');
function __construct($array=array())
{
	parent::__construct($array);
}
function getAction_nameFormField($value=''){
	return "<div class='form-group'>
	<label for='action_name' >Action Name</label>
		<input type='text' name='action_name' id='action_name' value='$value' class='form-control'  />
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


		
protected function getAction_list(){
	$query ='SELECT * FROM action_list WHERE action_type_id=?';
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
		}
		?>