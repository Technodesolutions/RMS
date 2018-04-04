<?php
		require_once('application/models/Crud.php');
		/**
		* This class  is automatically generated based on the structure of the table. And it represent the model of the admin table.
		*/
		class Admin extends Crud
		{
protected static $tablename='Admin';
/* this array contains the field that can be null*/
static $nullArray=array('middlename' ,'email' ,'phone_number' ,'address' ,'dob' ,'role_id' ,'status' );
static $compositePrimaryKey=array();
static $uploadDependency = array();
/*this array contains the fields that are unique*/
static $uniqueArray=array('email' ,'phone_number' );
/*this is an associative array containing the fieldname and the type of the field*/
static $typeArray = array('firstname'=>'varchar','middlename'=>'varchar','lastname'=>'varchar','email'=>'varchar','phone_number'=>'varchar','address'=>'text','dob'=>'date','role_id'=>'int','status'=>'tinyint');
/*this is a dictionary that map a field name with the label name that will be shown in a form*/
static $labelArray=array('ID'=>'','firstname'=>'','middlename'=>'','lastname'=>'','email'=>'','phone_number'=>'','address'=>'','dob'=>'','role_id'=>'','status'=>'');
/*associative array of fields that have default value*/
static $defaultArray = array('status'=>'1');
//populate this array with fields that are meant to be displayed as document in the format array('fieldname'=>array('filetype','maxsize',foldertosave','preservefilename'))
//the folder to save must represent a path from the basepath. it should be a relative path,preserve filename will be either true or false. when true,the file will be uploaded with it default filename else the system will pick the current user id in the session as the name of the file.
static $documentField = array();//array containing an associative array of field that should be regareded as document field. it will contain the setting for max size and data type.
		
static $relation=array('role'=>array( 'role_id', 'ID')
,'admin_log'=>array(array( 'ID', 'admin_id', 1))
);
static $tableAction=array('enable'=>'getEnabled','delete'=>'delete/admin','update'=>'edit/admin');
function __construct($array=array())
{
	parent::__construct($array);
}
function getFirstnameFormField($value=''){
	return "<div class='form-group'>
	<label for='firstname' >Firstname</label>
		<input type='text' name='firstname' id='firstname' value='$value' class='form-control' required />
</div> ";

}
function getMiddlenameFormField($value=''){
	return "<div class='form-group'>
	<label for='middlename' >Middlename</label>
		<input type='text' name='middlename' id='middlename' value='$value' class='form-control'  />
</div> ";

}
function getLastnameFormField($value=''){
	return "<div class='form-group'>
	<label for='lastname' >Lastname</label>
		<input type='text' name='lastname' id='lastname' value='$value' class='form-control' required />
</div> ";

}
function getEmailFormField($value=''){
	return "<div class='form-group'>
	<label for='email' >Email</label>
	<input type='email' name='email' id='email' value='$value' class='form-control'  />
</div> ";

}
function getPhone_numberFormField($value=''){
	return "<div class='form-group'>
	<label for='phone_number' >Phone Number</label>
		<input type='text' name='phone_number' id='phone_number' value='$value' class='form-control'  />
</div> ";

}
function getAddressFormField($value=''){
	return "<div class='form-group'>
	<label for='address' >Address</label>
<textarea id='address' name='address' class='form-control' >$value</textarea>
</div> ";

}
function getDobFormField($value=''){
	return "<div class='form-group'>
	<label for='dob' >Dob</label>
	<input type='date' name='dob' id='dob' value='$value' class='form-control'  />
</div> ";

}
	 function getRole_idFormField($value=''){
	$fk=null;//change the value of this variable to array('table'=>'role','display'=>'role_name'); if you want to preload the value from the database where the display key is the name of the field to use for display in the table.

	if (is_null($fk)) {
		return $result="<input type='hidden' value='$value' name='role_id' id='role_id' class='form-control' />
			";
	}
	if (is_array($fk)) {
		$result ="<div class='form-group'>
		<label for='role_id'>Role Id</label>";
		$option = $this->loadOption($fk,$value);
		//load the value from the given table given the name of the table to load and the display field
		$result.="<select name='role_id' id='role_id' class='form-control'>
			$option
		</select>";
	}
	$result.="</div>";
	return  $result;

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


		
protected function getRole(){
	$query ='SELECT * FROM role WHERE id=?';
	if (!isset($this->array['ID'])) {
		return null;
	}
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Role.php');
	$resultObject = new Role($result[0]);
	return $resultObject;
}
		
protected function getAdmin_log(){
	$query ='SELECT * FROM admin_log WHERE admin_id=?';
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Admin_log.php');
	$resultobjects = array();
	foreach ($result as  $value) {
		$resultObjects[] = new Admin_log($value);
	}

	return $resultObjects;
}
		}
		?>