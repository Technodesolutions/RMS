<?php
		require_once('application/models/Crud.php');
		/**
		* This class  is automatically generated based on the structure of the table. And it represent the model of the lecturer table.
		*/
		class Lecturer extends Crud
		{
protected static $tablename='Lecturer';
/* this array contains the field that can be null*/
static $nullArray=array('title_id' ,'surname' ,'firstname' ,'middlename' ,'department_id' ,'portfolio' ,'email' ,'phone_number' ,'dob' ,'status' ,'address' ,'state_of_origin' ,'staff_no' ,'lga_of_origin' ,'disability' ,'date_of_first_appointment' ,'nationality' );
static $compositePrimaryKey=array();
static $uploadDependency = array();
/*this array contains the fields that are unique*/
static $uniqueArray=array();
/*this is an associative array containing the fieldname and the type of the field*/
static $typeArray = array('title_id'=>'int','surname'=>'varchar','firstname'=>'varchar','middlename'=>'varchar','department_id'=>'int','portfolio'=>'varchar','email'=>'text','phone_number'=>'text','dob'=>'date','status'=>'tinyint','address'=>'varchar','state_of_origin'=>'varchar','staff_no'=>'varchar','lga_of_origin'=>'varchar','disability'=>'tinyint','date_of_first_appointment'=>'date','nationality'=>'varchar');
/*this is a dictionary that map a field name with the label name that will be shown in a form*/
static $labelArray=array('ID'=>'','title_id'=>'','surname'=>'','firstname'=>'','middlename'=>'','department_id'=>'','portfolio'=>'','email'=>'','phone_number'=>'','dob'=>'','status'=>'','address'=>'','state_of_origin'=>'','staff_no'=>'','lga_of_origin'=>'','disability'=>'','date_of_first_appointment'=>'','nationality'=>'');
/*associative array of fields that have default value*/
static $defaultArray = array('status'=>'1');
//populate this array with fields that are meant to be displayed as document in the format array('fieldname'=>array('filetype','maxsize',foldertosave','preservefilename'))
//the folder to save must represent a path from the basepath. it should be a relative path,preserve filename will be either true or false. when true,the file will be uploaded with it default filename else the system will pick the current user id in the session as the name of the file.
static $documentField = array();//array containing an associative array of field that should be regareded as document field. it will contain the setting for max size and data type.
		
static $relation=array('title'=>array( 'title_id', 'ID')
,'department'=>array( 'department_id', 'ID')
,'session_semester_course'=>array(array( 'ID', 'lecturer_id', 1))
,'upload_history'=>array(array( 'ID', 'lecturer_id', 1))
);
static $tableAction=array('enable'=>'getEnabled','delete'=>'delete/lecturer','update'=>'edit/lecturer');
function __construct($array=array())
{
	parent::__construct($array);
}
	 function getTitle_idFormField($value=''){
	$fk=null;//change the value of this variable to array('table'=>'title','display'=>'title_name'); if you want to preload the value from the database where the display key is the name of the field to use for display in the table.

	if (is_null($fk)) {
		return $result="<input type='hidden' value='$value' name='title_id' id='title_id' class='form-control' />
			";
	}
	if (is_array($fk)) {
		$result ="<div class='form-group'>
		<label for='title_id'>Title Id</label>";
		$option = $this->loadOption($fk,$value);
		//load the value from the given table given the name of the table to load and the display field
		$result.="<select name='title_id' id='title_id' class='form-control'>
			$option
		</select>";
	}
	$result.="</div>";
	return  $result;

}
function getSurnameFormField($value=''){
	return "<div class='form-group'>
	<label for='surname' >Surname</label>
		<input type='text' name='surname' id='surname' value='$value' class='form-control'  />
</div> ";

}
function getFirstnameFormField($value=''){
	return "<div class='form-group'>
	<label for='firstname' >Firstname</label>
		<input type='text' name='firstname' id='firstname' value='$value' class='form-control'  />
</div> ";

}
function getMiddlenameFormField($value=''){
	return "<div class='form-group'>
	<label for='middlename' >Middlename</label>
		<input type='text' name='middlename' id='middlename' value='$value' class='form-control'  />
</div> ";

}
	 function getDepartment_idFormField($value=''){
	$fk=null;//change the value of this variable to array('table'=>'department','display'=>'department_name'); if you want to preload the value from the database where the display key is the name of the field to use for display in the table.

	if (is_null($fk)) {
		return $result="<input type='hidden' value='$value' name='department_id' id='department_id' class='form-control' />
			";
	}
	if (is_array($fk)) {
		$result ="<div class='form-group'>
		<label for='department_id'>Department Id</label>";
		$option = $this->loadOption($fk,$value);
		//load the value from the given table given the name of the table to load and the display field
		$result.="<select name='department_id' id='department_id' class='form-control'>
			$option
		</select>";
	}
	$result.="</div>";
	return  $result;

}
function getPortfolioFormField($value=''){
	return "<div class='form-group'>
	<label for='portfolio' >Portfolio</label>
		<input type='text' name='portfolio' id='portfolio' value='$value' class='form-control'  />
</div> ";

}
function getEmailFormField($value=''){
	return "<div class='form-group'>
	<label for='email' >Email</label>
<textarea id='email' name='email' class='form-control' >$value</textarea>
</div> ";

}
function getPhone_numberFormField($value=''){
	return "<div class='form-group'>
	<label for='phone_number' >Phone Number</label>
<textarea id='phone_number' name='phone_number' class='form-control' >$value</textarea>
</div> ";

}
function getDobFormField($value=''){
	return "<div class='form-group'>
	<label for='dob' >Dob</label>
	<input type='date' name='dob' id='dob' value='$value' class='form-control'  />
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
function getAddressFormField($value=''){
	return "<div class='form-group'>
	<label for='address' >Address</label>
		<input type='text' name='address' id='address' value='$value' class='form-control'  />
</div> ";

}
function getState_of_originFormField($value=''){
	return "<div class='form-group'>
	<label for='state_of_origin' >State Of Origin</label>
		<input type='text' name='state_of_origin' id='state_of_origin' value='$value' class='form-control'  />
</div> ";

}
function getStaff_noFormField($value=''){
	return "<div class='form-group'>
	<label for='staff_no' >Staff No</label>
		<input type='text' name='staff_no' id='staff_no' value='$value' class='form-control'  />
</div> ";

}
function getLga_of_originFormField($value=''){
	return "<div class='form-group'>
	<label for='lga_of_origin' >Lga Of Origin</label>
		<input type='text' name='lga_of_origin' id='lga_of_origin' value='$value' class='form-control'  />
</div> ";

}
function getDisabilityFormField($value=''){
	return "<div class='form-group'>
	<label class='form-checkbox'>Disability</label>
	<select class='form-control' id='disability' name='disability' >
		<option value='1'>Yes</option>
		<option value='0' selected='selected'>No</option>
	</select>
	</div> ";

}
function getDate_of_first_appointmentFormField($value=''){
	return "<div class='form-group'>
	<label for='date_of_first_appointment' >Date Of First Appointment</label>
	<input type='date' name='date_of_first_appointment' id='date_of_first_appointment' value='$value' class='form-control'  />
</div> ";

}
function getNationalityFormField($value=''){
	return "<div class='form-group'>
	<label for='nationality' >Nationality</label>
		<input type='text' name='nationality' id='nationality' value='$value' class='form-control'  />
</div> ";

}


		
protected function getTitle(){
	$query ='SELECT * FROM title WHERE id=?';
	if (!isset($this->array['ID'])) {
		return null;
	}
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Title.php');
	$resultObject = new Title($result[0]);
	return $resultObject;
}
		
protected function getDepartment(){
	$query ='SELECT * FROM department WHERE id=?';
	if (!isset($this->array['ID'])) {
		return null;
	}
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Department.php');
	$resultObject = new Department($result[0]);
	return $resultObject;
}
		
protected function getSession_semester_course(){
	$query ='SELECT * FROM session_semester_course WHERE lecturer_id=?';
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
		
protected function getUpload_history(){
	$query ='SELECT * FROM upload_history WHERE lecturer_id=?';
	$id = $this->array['ID'];
	$result = $this->db->query($query,array($id));
	$result =$result->result_array();
	if (empty($result)) {
		return false;
	}
	include_once('Upload_history.php');
	$resultobjects = array();
	foreach ($result as  $value) {
		$resultObjects[] = new Upload_history($value);
	}

	return $resultObjects;
}
		}
		?>