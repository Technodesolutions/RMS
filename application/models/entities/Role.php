<?php
		require_once('application/models/Crud.php');
		/**
		* This class  is automatically generated based on the structure of the table. And it represent the model of the role table.
		*/
		class Role extends Crud
		{
protected static $tablename='Role';
/* this array contains the field that can be null*/
static $nullArray=array('status' );
static $compositePrimaryKey=array();
static $uploadDependency = array();
/*this array contains the fields that are unique*/
static $uniqueArray=array('role_title' );
/*this is an associative array containing the fieldname and the type of the field*/
static $typeArray = array('role_title'=>'varchar','status'=>'tinyint');
/*this is a dictionary that map a field name with the label name that will be shown in a form*/
static $labelArray=array('ID'=>'','role_title'=>'','status'=>'');
/*associative array of fields that have default value*/
static $defaultArray = array('status'=>'1');
//populate this array with fields that are meant to be displayed as document in the format array('fieldname'=>array('filetype','maxsize',foldertosave','preservefilename'))
//the folder to save must represent a path from the basepath. it should be a relative path,preserve filename will be either true or false. when true,the file will be uploaded with it default filename else the system will pick the current user id in the session as the name of the file.
static $documentField = array();//array containing an associative array of field that should be regareded as document field. it will contain the setting for max size and data type.
		
static $relation=array('admin'=>array(array( 'ID', 'role_id', 1))
,'extra_role'=>array(array( 'ID', 'role_id', 1))
,'permission'=>array(array( 'ID', 'role_id', 1))
);
static $tableAction=array('enable'=>'getEnabled','delete'=>'delete/role','update'=>'edit/role');
function __construct($array=array())
{
	parent::__construct($array);
}
function getRole_titleFormField($value=''){
	return "<div class='form-group'>
	<label for='role_title' >Role Title</label>
		<input type='text' name='role_title' id='role_title' value='$value' class='form-control' required />
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


		
protected function getAdmin(){
	$query ='SELECT * FROM admin WHERE role_id=?';
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Admin.php');
	$resultobjects = array();
	foreach ($result as  $value) {
		$resultObjects[] = new Admin($value);
	}

	return $resultObjects;
}
		
protected function getExtra_role(){
	$query ='SELECT * FROM extra_role WHERE role_id=?';
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Extra_role.php');
	$resultobjects = array();
	foreach ($result as  $value) {
		$resultObjects[] = new Extra_role($value);
	}

	return $resultObjects;
}
		
protected function getPermission(){
	$query ='SELECT * FROM permission WHERE role_id=?';
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Permission.php');
	$resultobjects = array();
	foreach ($result as  $value) {
		$resultObjects[] = new Permission($value);
	}

	return $resultObjects;
}
		}
		?>