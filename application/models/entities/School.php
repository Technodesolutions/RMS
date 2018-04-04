<?php
		require_once('application/models/Crud.php');
		/**
		* This class  is automatically generated based on the structure of the table. And it represent the model of the school table.
		*/
		class School extends Crud
		{
protected static $tablename='School';
/* this array contains the field that can be null*/
static $nullArray=array('school_name' ,'school_logo' ,'slogan' ,'warning_count' ,'location' ,'description' );
static $compositePrimaryKey=array();
static $uploadDependency = array();
/*this array contains the fields that are unique*/
static $uniqueArray=array();
/*this is an associative array containing the fieldname and the type of the field*/
static $typeArray = array('school_name'=>'varchar','school_logo'=>'varchar','slogan'=>'text','warning_count'=>'int','location'=>'text','description'=>'text');
/*this is a dictionary that map a field name with the label name that will be shown in a form*/
static $labelArray=array('ID'=>'','school_name'=>'','school_logo'=>'','slogan'=>'','warning_count'=>'','location'=>'','description'=>'');
/*associative array of fields that have default value*/
static $defaultArray = array('warning_count'=>'2');
//populate this array with fields that are meant to be displayed as document in the format array('fieldname'=>array('filetype','maxsize',foldertosave','preservefilename'))
//the folder to save must represent a path from the basepath. it should be a relative path,preserve filename will be either true or false. when true,the file will be uploaded with it default filename else the system will pick the current user id in the session as the name of the file.
static $documentField = array();//array containing an associative array of field that should be regareded as document field. it will contain the setting for max size and data type.
		
static $relation=array();
static $tableAction=array('enable'=>'getEnabled','delete'=>'delete/school','update'=>'edit/school');
function __construct($array=array())
{
	parent::__construct($array);
}
function getSchool_nameFormField($value=''){
	return "<div class='form-group'>
	<label for='school_name' >School Name</label>
		<input type='text' name='school_name' id='school_name' value='$value' class='form-control'  />
</div> ";

}
function getSchool_logoFormField($value=''){
	return "<div class='form-group'>
	<label for='school_logo' >School Logo</label>
		<input type='text' name='school_logo' id='school_logo' value='$value' class='form-control'  />
</div> ";

}
function getSloganFormField($value=''){
	return "<div class='form-group'>
	<label for='slogan' >Slogan</label>
<textarea id='slogan' name='slogan' class='form-control' >$value</textarea>
</div> ";

}
function getWarning_countFormField($value=''){
	return "<div class='form-group'>
	<label for='warning_count' >Warning Count</label><input type='number' name='warning_count' id='warning_count' value='$value' class='form-control'  />
</div> ";

}
function getLocationFormField($value=''){
	return "<div class='form-group'>
	<label for='location' >Location</label>
<textarea id='location' name='location' class='form-control' >$value</textarea>
</div> ";

}
function getDescriptionFormField($value=''){
	return "<div class='form-group'>
	<label for='description' >Description</label>
<textarea id='description' name='description' class='form-control' >$value</textarea>
</div> ";

}


		}
		?>