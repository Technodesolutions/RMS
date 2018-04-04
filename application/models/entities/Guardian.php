<?php
		require_once('application/models/Crud.php');
		/**
		* This class  is automatically generated based on the structure of the table. And it represent the model of the guardian table.
		*/
		class Guardian extends Crud
		{
protected static $tablename='Guardian';
/* this array contains the field that can be null*/
static $nullArray=array('email' ,'phone' ,'address' );
static $compositePrimaryKey=array();
static $uploadDependency = array();
/*this array contains the fields that are unique*/
static $uniqueArray=array();
/*this is an associative array containing the fieldname and the type of the field*/
static $typeArray = array('student_biodata_id'=>'int','title_id'=>'int','firstname'=>'varchar','surname'=>'varchar','email'=>'text','phone'=>'text','address'=>'text');
/*this is a dictionary that map a field name with the label name that will be shown in a form*/
static $labelArray=array('ID'=>'','student_biodata_id'=>'','title_id'=>'','firstname'=>'','surname'=>'','email'=>'','phone'=>'','address'=>'');
/*associative array of fields that have default value*/
static $defaultArray = array();
//populate this array with fields that are meant to be displayed as document in the format array('fieldname'=>array('filetype','maxsize',foldertosave','preservefilename'))
//the folder to save must represent a path from the basepath. it should be a relative path,preserve filename will be either true or false. when true,the file will be uploaded with it default filename else the system will pick the current user id in the session as the name of the file.
static $documentField = array();//array containing an associative array of field that should be regareded as document field. it will contain the setting for max size and data type.
		
static $relation=array('student_biodata'=>array( 'student_biodata_id', 'ID')
,'title'=>array( 'title_id', 'ID')
);
static $tableAction=array('enable'=>'getEnabled','delete'=>'delete/guardian','update'=>'edit/guardian');
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
function getFirstnameFormField($value=''){
	return "<div class='form-group'>
	<label for='firstname' >Firstname</label>
		<input type='text' name='firstname' id='firstname' value='$value' class='form-control' required />
</div> ";

}
function getSurnameFormField($value=''){
	return "<div class='form-group'>
	<label for='surname' >Surname</label>
		<input type='text' name='surname' id='surname' value='$value' class='form-control' required />
</div> ";

}
function getEmailFormField($value=''){
	return "<div class='form-group'>
	<label for='email' >Email</label>
<textarea id='email' name='email' class='form-control' >$value</textarea>
</div> ";

}
function getPhoneFormField($value=''){
	return "<div class='form-group'>
	<label for='phone' >Phone</label>
<textarea id='phone' name='phone' class='form-control' >$value</textarea>
</div> ";

}
function getAddressFormField($value=''){
	return "<div class='form-group'>
	<label for='address' >Address</label>
<textarea id='address' name='address' class='form-control' >$value</textarea>
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
		}
		?>