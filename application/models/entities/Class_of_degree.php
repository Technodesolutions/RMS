<?php
		require_once('application/models/Crud.php');
		/**
		* This class  is automatically generated based on the structure of the table. And it represent the model of the class_of_degree table.
		*/
		class Class_of_degree extends Crud
		{
protected static $tablename='Class_of_degree';
/* this array contains the field that can be null*/
static $nullArray=array('min_cgpa' ,'max_cgpa' ,'cgpa_class' ,'class_order' ,'scale_id' );
static $compositePrimaryKey=array();
static $uploadDependency = array();
/*this array contains the fields that are unique*/
static $uniqueArray=array();
/*this is an associative array containing the fieldname and the type of the field*/
static $typeArray = array('min_cgpa'=>'float','max_cgpa'=>'float','cgpa_class'=>'varchar','class_order'=>'int','scale_id'=>'int');
/*this is a dictionary that map a field name with the label name that will be shown in a form*/
static $labelArray=array('ID'=>'','min_cgpa'=>'','max_cgpa'=>'','cgpa_class'=>'','class_order'=>'','scale_id'=>'');
/*associative array of fields that have default value*/
static $defaultArray = array();
//populate this array with fields that are meant to be displayed as document in the format array('fieldname'=>array('filetype','maxsize',foldertosave','preservefilename'))
//the folder to save must represent a path from the basepath. it should be a relative path,preserve filename will be either true or false. when true,the file will be uploaded with it default filename else the system will pick the current user id in the session as the name of the file.
static $documentField = array();//array containing an associative array of field that should be regareded as document field. it will contain the setting for max size and data type.
		
static $relation=array();
static $tableAction=array('enable'=>'getEnabled','delete'=>'delete/class_of_degree','update'=>'edit/class_of_degree');
function __construct($array=array())
{
	parent::__construct($array);
}
function getMin_cgpaFormField($value=''){
	return "<div class='form-group'>
	<label for='min_cgpa' >Min Cgpa</label>
		<input type='text' name='min_cgpa' id='min_cgpa' value='$value' class='form-control'  />
</div> ";

}
function getMax_cgpaFormField($value=''){
	return "<div class='form-group'>
	<label for='max_cgpa' >Max Cgpa</label>
		<input type='text' name='max_cgpa' id='max_cgpa' value='$value' class='form-control'  />
</div> ";

}
function getCgpa_classFormField($value=''){
	return "<div class='form-group'>
	<label for='cgpa_class' >Cgpa Class</label>
		<input type='text' name='cgpa_class' id='cgpa_class' value='$value' class='form-control'  />
</div> ";

}
function getClass_orderFormField($value=''){
	return "<div class='form-group'>
	<label for='class_order' >Class Order</label><input type='number' name='class_order' id='class_order' value='$value' class='form-control'  />
</div> ";

}
	 function getScale_idFormField($value=''){
	$fk=null;//change the value of this variable to array('table'=>'scale','display'=>'scale_name'); if you want to preload the value from the database where the display key is the name of the field to use for display in the table.

	if (is_null($fk)) {
		return $result="<input type='hidden' value='$value' name='scale_id' id='scale_id' class='form-control' />
			";
	}
	if (is_array($fk)) {
		$result ="<div class='form-group'>
		<label for='scale_id'>Scale Id</label>";
		$option = $this->loadOption($fk,$value);
		//load the value from the given table given the name of the table to load and the display field
		$result.="<select name='scale_id' id='scale_id' class='form-control'>
			$option
		</select>";
	}
	$result.="</div>";
	return  $result;

}


		}
		?>