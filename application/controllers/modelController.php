<?php
/**
* The controller that link to the model.
*all response in this class returns a json object return
*/

class ModelController extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('accessControl');//for authentication authorization validation
		// $this->load->model('entities/application_log');
		$this->load->model('modelControllerDataValidator');
		$this->load->model('webSessionManager');
		$this->load->model('modelControllerCallback');
		// $this->checkPermission();
	}
	private function checkPermission(){
		return true;
		//check that the user has permission to modify
		$cookie = getPageCookie();
		if (!in_array($this->webSessionManager->getCurrentUserProp('user_type'), array('student','admin','lecturer')) && @!$this->role->canModify($cookie[0],$cookie[1])) {
		  # who the access denied page.
			if (isset($_GET['a']) && $_GET['a']) {
				echo createJsonMessage('status',false,'message','you do not  have permission to perform this action','description','access denied');exit;
			}
		  show_operation_denied($this->load);
		}
	}
	function view($model,$start=0,$len=100,$paged=true){
		if (!$this->isModel($model)) {
			show_404();
			exit;
		}
		$this->load->model('tableViewModel');
		$html =  $this->tableViewModel->getTableHtml($model,$message,array(),array(),$paged,$start,$len);
		$data['tableData']=$html==true?$html:$message;
		$this->load->view('pages/modelTableView',$data);
	}

	//function that will enable the ajax call and return just the table content by passing the url link
	function tableContent($model,$start=0,$len=100,$paged=false){
		if (!$this->isModel($model)) {
			show_404();
			exit;
		}
		$this->load->model('tableViewModel');
		$html =  $this->tableViewModel->getTableHtml($model,$message,array(),array(),$paged,$start,$len);
		$data['tableData']=$html==true?$html:$message;
		$this->load->view('pages/modelTableView',$data);
	}

	function add($model,$filter=false,$parent=''){//the parent field is optional
		try{
			if (empty($model)) { //make sure empty value for model is not allowed.
				echo createJsonMessage('status',false,'message','an error occured while processing information','description','the model parameter is null so it must not be null');
				return;

			}
			unset($_POST['MAX_FILE_SIZE']);
			if ($model=='many') {
				$this->insertMany($filter,$parent);
			}
			else{
				$this->log($model,"inserting $model");
				$this->insertSingle($model,$filter);
			}
		}
		catch(Exception $ex){
			echo $ex->getMessage();
			$this->db->trans_rollback();
		}

	}

	private function insertMany($filter){
		$appended = '_ID';
		//make sure the parent name exist
		if (!isset($_POST['parent_generated'])) {
			throw new Exception("is like you forgot to set a parent table for this form,kindly do and try again", 1);
		}
		//first validate the model
		$parentName =$_POST['parent_generated'];//remove the appended from the back
		unset($_POST['parent_generated']);
		$parent= $parentName.$appended;
		$prevCount = 0;
		$models =$this->validateModels('c',$message);//validate the models and return the model arrays on success of return false and return message
		$desc = implode(' , ', $models);
		$this->log($desc,"attempting to insert $desc");
		if (!$models) {
			echo createJsonMessage('status',false,'message','an error occured while processing information','description',$message);
			exit;
		}
		$inTable =array_key_exists($parentName, $models);
		$this->db->trans_begin();//start transaction
		$data = $this->input->post(null,$filter);
		$parentValue=@$data[$parent];
		$isFirst=true;
		$insertids='';
		$message='';
		foreach ($models as $model => $prop) {
			if (is_array($prop) || !is_int($prop)) {
				$this->db->trans_rollback();
				throw new Exception("invalid model properties");
			}
			if (!class_exists(ucfirst($model))) {
				$this->load->model("entities/$model");
			}
			$data = $this->processFormUpload($model,$data);
			$parameter = $this->extractSubset($data,$model);
			$parameter = removeEmptyAssoc($parameter);
			if (!$this->validateModelData($model,'insert',$parameter,$message)) {
				$this->db->trans_rollback();
				echo createJsonMessage('status',false,'message',$message);
				return;
			}
			$parentSet= false;
			if ($parentName==$model || $isFirst) {//if this is the parent or the first table
				$this->$model->setArray($parameter);
				if(!$this->$model->insert($this->db,$message)){
					//if tere is any problem with the current insertion just remove rollback the transaction and  exit with error that will be faster.
					$this->db->trans_rollback();
					echo createJsonMessage('status',false,'message',$message);
					return false;
					// break;
				}
				$prevCount=$prop;
				if ($inTable) {
					$parentValue = $this->getLastInsertId();//or another means of getting the parent value
					$insertids .=$parentValue.'#';
					$parentSet = true;
				}
				$isFirst=false;
				continue;
			}
			$ins = $this->getLastInsertId();
			$ins.='#';
			$insertids.=$parentSet?"":$ins;
			$parameter[$parent] = $parentValue;
			if ($model=='next_of_kin') {
				unset($parameter['guardian_ID']);
			}
			$this->$model->setArray($parameter);
			$this->$model->insert($this->db);
			$prevCount=$prop;
		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$message = empty($message)?'error occured while inserting record':$message;
			echo createJsonMessage('status',false,'message',$message);
			$this->log($desc,$message);
			return false;
		}
		//load the insert many method here before the db is committed so that the transaction is atomic.
		$data['LAST_INSERT_ID']= $insertids;
		if($this->afterManyInserts(array_keys($models),'insert',$data,$this->db)){
			$this->db->trans_commit();//end the transaction
			// $result = array('status'=>)
			echo createJsonMessage('status',true,'message','records inserted successfully','data',$parentValue);
			$this->log($desc," $desc Inserted");
			return true;
		}
		$this->db->trans_rollback();
		echo createJsonMessage('status',false,'message','error occured while inserting records');
		$this->log($desc," error inserting $desc");
		return false;
	}
	// the models is the array of all the models inserted, type specify if it an update or an insert,
	// data is the data that was worked on. the filter post data.
	// the db is the database passed as reference.
	private function afterManyInserts($models,$type,$data,&$db){
		//delegate to a method in the callback class
		$method= 'onInsertMany';
		if (method_exists($this->modelControllerCallback, $method)) {
			return $this->modelControllerCallback->$method($models,$type,$data,$db);
		}
		return true;
	}
	private function updateMany($filter){
		//first validate the model
		$appended = '_ID';
		//make sure the parent name exist
		if (!isset($_POST['parent_generated'])) {
			throw new Exception("is like you forgot to set a parent table for this form,kindly do and try again", 1);
		}
		//first validate the model
		$parentName =$_POST['parent_generated'];//remove the appended from the back
		unset($_POST['parent_generated']);
		unset($_POST['MAX_FILE_SIZE']);
		$parent= $parentName.$appended;
		$prevCount = 0;
		$models =$this->validateModels('u',$message);//validate the models and return the model arrays on success of return false and return message
		if (!$models) {
			echo createJsonMessage('status',false,'message',$message);
			return;
		}
		// $inTable =array_key_exists($parentName, $models);
		$this->db->trans_begin();//start transaction
		$data = $this->input->post(null,$filter);
		$parentValue=isset($data[$parent])?$data[$parent]:false;
		$isFirst = true;
		foreach ($models as $model => $prop) {
			if (empty($prop) || !is_array($prop) || count($prop)!=2) {
				$this->db->trans_rollback();
				throw new Exception("invalid model properties");
			}
			//load the model
			$this->load->model("entities/$model");
			// $parameter = subArrayAssoc($data,$prevCount,$prop[0]-$prevCount);
			$data = $this->processFormUpload($model,$data);
			$parameter = $this->extractSubset($data,$model);
			if (empty($parameter) || $this->validateModelData($model,'update',$parameter,$message)==false) {
				$this->db->trans_rollback();
				if (empty($message)) {
					$message ='error occured while performing operation';
				}
				throw new Exception($message, 1);

			}
			if ($parentName==$model || $isFirst) {//this is the first transaction
				$this->$model->setArray($parameter);
			
				$this->$model->update($prop[1],$this->db);
				$prevCount=$prop[0];
				$isFirst= false;
				continue;
			}
			if ($model=='next_of_kin') {
				
				// print_r($parameter);
				// echo "got here";exit;
				unset($parameter['guardian_ID']);
			}

			$this->$model->setArray($parameter);
			$this->$model->update($prop[1],$this->db);
			$prevCount=$prop[0];
		}

		if ($this->db->trans_status() === FALSE) {
			echo createJsonMessage('status',true,'message','error occured while updating record');
			return false;
		}
		if($this->afterManyInserts(array_keys($models),'update',$data,$this->db)){
			$this->db->trans_commit();//end the transaction
			echo createJsonMessage('status',true,'message','records updated successfully','data',$parentValue);
			return true;
		}
		$this->db->trans_rollback();
		echo createJsonMessage('status',false,'message','error occured while updating record');
		return false;
	}

	//this function is used to  document
	private function processFormUpload($model,$parameter){
		$paramFile= $model::$documentField;
		$fields = array_keys($_FILES);
		if (empty($paramFile)) {
			return $parameter;
		}
		foreach ($paramFile as $name => $value) {
			$this->log($model,"uploading file $name");
			if (in_array($name, $fields)) {//if the field name is present in the fields the upload the document
				list($type,$size,$directory) = $value;
				$method ="get".ucfirst($model)."Directory";
				$this->load->model('uploadDirectoryManager');
				if (method_exists($this->uploadDirectoryManager, $method)) {
					$dir  = $this->uploadDirectoryManager->$method($parameter);
					if ($dir===false) {
						showUploadErrorMessage($this->webSessionManager,"Error while uploading file",false);
					}
					$directory.=$dir;
				}
				$currentUpload = $this->uploadFile($model,$name,$type,$size,$directory,$message);

				if ($currentUpload==false) {
					return $parameter;
					// print_r($_SERVER);exit;
					// $tempPath =$_SERVER['REQUEST_URI'];
					// if (strpos('tempPath', needle)) {
					// 	# code...
					// }
					// showUploadErrorMessage($this->webSessionManager,$message,false);
				}
				$this->log($model,"file $name uploaded successfully");
				$parameter[$name]=$message;
			}
			else{
				$this->log($model,"error uploading file $name");
				continue;
			}
		}
		return $parameter;
	}

	private function uploadFile($model,$name,$type,$maxSize,$destination,&$message=''){
		if (!$this->checkFile($name,$message)) {
			return false;
		}
		$filename=$_FILES[$name]['name'];
		$ext = getFileExtension($filename);
		$fileSize = $_FILES[$name]['size'];
		$typeValid = is_array($type)?in_array(strtolower($ext), $type):strtolower($ext)==strtolower($type);
		if (!empty($filename) &&  $typeValid  && !empty($destination)) {
			if ($fileSize > $maxSize) {
				$message='file too large to be saved';return false;
			}
			
			$user = $this->webSessionManager->getCurrentuserProp('user_type').$this->webSessionManager->getCurrentuserProp('ID');
			$destination='uploads/'.$_SERVER['HTTP_HOST'].'/'.$destination;
			if (!is_dir($destination)) {
				mkdir($destination,0777,true);
			}
			$destination.="$user.".$ext;//the test should be replaced by the name of the current user.		
			if(move_uploaded_file($_FILES[$name]['tmp_name'], $destination)){
				$message=$destination;
				return true;//$destination;
			}
			else{
				$message = "error while uploading file. please try again";return false;
				// exit(createJsonMessage('status',false,'message','error while uploading file. please try again'));
			}
		}
		else{
			$message = "error while uploading file. please try again";return false;
			// exit(createJsonMessage('status',false,'message','error while uploading file. please try again conddition not satisfy'));
		}
		$message='error while uploading file. please try again';
		return false;
	}

	private function checkFile($name,&$message=''){
		$error = !$_FILES[$name]['name'] || $_FILES[$name]['error'];
		if ($error) {
			if ((int)$error===2) {
				$message = 'file larger than expected';
				return false;
			}
			return false;
		}
		
		if (!is_uploaded_file($_FILES[$name]['tmp_name'])) {
			$this->db->trans_rollback();
			$message='uploaded file not found';
			return false;
		}
		return true;
	}


	//this function will return the last auto generated id of the last insert statement
	private function getLastInsertId(){
		$query = "SELECT LAST_INSERT_ID() AS last";//sud specify the table
		$result =$this->db->query($query);
		$result = $result->result_array();
		return $result[0]['last'];

	}
	private function DoAfterInsertion($model,$type,$data,&$db,&$message=''){
		$method = 'on'.ucfirst($model).'Inserted';
		if (method_exists($this->modelControllerCallback, $method)) {
			return $this->modelControllerCallback->$method($data,$type,$db,$message);
		}
		return true;
	}

// the message variable will give the eror message if there is an error and the variable is passed
	private function validateModelData($model,$type,$data,&$message=''){
		if (!$this->modelControllerDataValidator->checkApplicant($data)) {// do this for other user types.
			return false;
		}
		$method = 'validate'.ucfirst($model).'Data';
		if (method_exists($this->modelControllerDataValidator, $method)) {
			$result =$this->modelControllerDataValidator->$method($data,$type,$message);
			return $result;
		}
		return true;
	}

	private function validateModels($method,&$message){
		if (!isset($_POST['edu-submit'])) {
			$message= 'fatal error!';
			return false;
		}
		$jsonEncode = $_POST['combined-models'];
		unset($_POST['edu-submit'],$_POST['edu-reset'],$_POST['combined-models']);
		$arr = json_decode($jsonEncode,true);
		$keys = array_keys($arr);
		$allGood = $this->isAllModel($keys,$method,$message);
		if ($allGood) {
			return $arr;
		}
		return false;
	}

	private function isAllModel($keys,$method,$message){
		for ($i=0; $i < count($keys); $i++) {
			$model = $keys[$i];
			if (!$this->isModel($model) ) {
				$message ="$model is not a valid model";
				return false;
			}
			// if (!$this->accessControl->moduleAccess($model,$method)) {
			// 	$message="access denied";
			// 	return false;
			// }
		}
		return true;
	}
	public function directPay()
	{
		if (!$this->webSessionManager->isSessionActive()) {
			header("Location:".base_url());exit;
		}
		if (isset($_POST['btn'])) {
			$content = $this->loadUploadedFileContent();
			$content = trim($content);
			$array = stringToCsv($content);
			$header = array_shift($array);
			$query = "insert into payment_log(transaction_number,registration_number,userkey,reference_number,amount, extraCharge,paymentPurpose,payment_status,payment_channel,academic_level_ID,email,mobile,responseCode,academic_session_ID,time_paid,user_ID,user_type) values ";
			
			loadClass($this->load,'student');
			$toAdd = "";
			foreach ($array as $val) {
				$temp = array();
				$student = $this->student->getWhere(array('matric_number'=>trim($val[1])),$c,0,null,false);
				if (!$student) {
					continue;
				}
				$student=$student[0];
				$registration_number = $student->registration_number;
				$email =$student->email;
				$phone = $student->phone_number;
				$userID = $student->ID;
				$transID = $val[0];
				$matric = $val[1];
				$conf = $val[2];
				$amount = $val[3];
				$extra= $val[4];
				$purpose = $val[5];
				$channel=$val[6];
				$level = $val[7];
				$secription = $val[8];
				$session = $val[9];
				$total = $val[10];
				$separate = $toAdd?',':'';
				$toAdd.=$separate."('$transID','$registration_number','$matric','$conf',$amount,$extra,'$purpose',1,'$channel','$level','$email','$phone','00',$session,current_timestamp,$userID,'student')";
			}

			$query.=$toAdd;
			if ($this->db->query($query)) {
				$this->webSessionManager->setFlashMessage('response',$this->db->conn_id->info);
			}
			else{
				$this->webSessionManager->setFlashMessage('response',"error occured while inserting payment records");
			}
			header("Location:".base_url('vc/payment/report'));exit;
		}
	}
	//this method is called when a single insertion is to be made.
	private function  insertSingle($model,$filter){
		$this->modelCheck($model,'c');
		$message ='';
		$filter = (bool)$filter;
		$data = $this->input->post(null,$filter);
		$data = $this->processFormUpload($model,$data);
		if (in_array('password', array_keys($data))) {
			$data['password']=@crypt($data['password']);
		}
		unset($data["edu-submit"]);
		$this->load->model("entities/$model");
		$parameter = $this->extractSubset($data,$model);
		$parameter = removeEmptyAssoc($parameter);
		$this->$model->setArray($parameter);
		if (!$this->validateModel($model,$message) || $this->validateModelData($model,'insert',$parameter,$message)==false) {
			echo createJsonMessage('status',false,'message',$message);
			return;
		}
		$message = '';
		$this->db->trans_begin();
		if($this->$model->insert($this->db,$message)){
			$inserted = $this->getLastInsertId();
			$data['LAST_INSERT_ID']= $inserted;
			if($this->DoAfterInsertion($model,'insert',$data,$this->db,$message)){
				$this->db->trans_commit();
				$message = empty($message)?'operation successfull ':$message;
				echo createJsonMessage('status',true,'message',$message,'data',$inserted);
				$this->log($model,"inserted new $model information");//log the activity
				return;
			}
			// echo createJsonMessage('status',false,'message',$message);
		}
		$this->db->trans_rollback();
		$message = empty($message)?"an error occured while saving information":$message;
		echo createJsonMessage('status',false,'message',$message);
		$this->log($model,"unable to insert $model information");
	}

	private function log($model,$description){
		$this->application_log->log($model,$description);
	}

	function update($model,$id='',$filter=false){
		if (empty($id) || empty($model)) {
			echo createJsonMessage('status',false,'message','an error occured while processing information','description','the model parameter is null so it must not be null');
			return;
		}
		if ($model=='many') {
			$this->updateMany($filter);
		} else {
			$this->updateSingle($model,$id,__METHOD__,$filter);
		}

	}

	private function updateSingle($model,$id,$method,$filter){
		$this->modelCheck($model,'u');
		$this->load->model("entities/$model");
		$filter = (bool)$filter;
		$data = $this->input->post(null,$filter);
		unset($data["edu-submit"],$data["edu-reset"]);
		$data = $this->processFormUpload($model,$data);
		//pass in the value needed by the model itself and discard the rest.
		$parameter = $this->extractSubset($data,$model);
		$this->$model->setArray($parameter);
		$this->db->trans_begin();
		if ($this->validateModelData($model,'update',$parameter,$message) && ($this->$model->update($id,$this->db))) {
			$data['ID']=$id;
		if($this->DoAfterInsertion($model,'update',$data,$this->db,$message)){
			$this->db->trans_commit();
			$message = empty($message)?'operation successfull':$message;

			echo createJsonMessage('status',true,'message',$message);
			return;
		}
		else{
			$this->db->trans_rollback();
			 echo createJsonMessage('status',false,'message',$message);
			return ;
		}
		}
		else{

			$this->db->trans_rollback();
			 echo createJsonMessage('status',false,'message',$message);
			return ;
		}
	}

//innplement deleter where function here.
	function delete($model,$id=''){
		if (isset($_POST['ID'])) {
			$id = $_POST['ID'];
		}
		if (empty($id)) {
			echo createJsonMessage('status',false,'message','error occured while deleting information');
			return;
		}

		$this->modelCheck($model,'d');
		$this->load->model("entities/$model");
		if ($this->$model->delete($id)) {
			echo createJsonMessage('status',true,'message','information deleted successfully');
		}
		else{
			echo createJsonMessage('status',false,'message','error occured while deleting information');
		}
	}
	private function modelCheck($model,$method){
		// if (!isset($_POST["edu-submit"])) {
		// 	echo createJsonMessage('status',false,'message','error occured while deleting information');
		// 	exit;
		// }
		if (!$this->isModel($model)) {
			echo createJsonMessage('status',false,'message','error occured while deleting information');
			exit;
		}
		// echo "got here";
		// if (!$this->accessControl->moduleAccess($model,$method)) {
		// 	echo createJsonMessage('status',false,'message','operation access denied');
		// 	exit;
		// }
	}
	//this function checks if the argument id actually  a model
	private function isModel($model){
		$this->load->model("entities/$model");
		if (!empty($model) && $this->$model instanceof Crud) {
			return true;
		}
		return false;
	}
	//check that the algorithm fit and that required data are not empty
	private function validateModel($model,&$message){
		return $this->$model->validateInsert($message);
	}
		//function to extract a subset of fields from a particular field
	private function extractSubset($array, $model){
		//check that the model is instance of crud
		//take care of user upload substitute the necessary value for the username
		//dont specify username directly
		
		$result =array();
		if ($this->$model instanceof $this->crud) {
			$keys = array_keys($model::$labelArray);
			$valueKeys = array_keys($array);
			$temp =array_intersect($valueKeys, $keys);
			foreach ($temp as $value) {
				$result[$value]= $array[$value];
			}
		}
		if ($model=='user') {
			$result =$this->processUser($array,$result);
		}
		return $result;
	}

	private function processUser($array,$result)
	{
		if (isset($array['matric_number'])) {
			$result['username']=$array['matric_number'];
			$result['password']=$result['username'];
		}
		if (isset($array['level_grade_ID']) && isset($array['post_ID'])) {
			$result['username']=$array['email_address'];
			$result['password']=$result['username'];
		}
		
		return $result;
	}

	private function goPrevious($message,$path=''){
		$location=isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'';
		if (empty($location) || !startsWith($location,base_url())) {
			$location= $path;
		}
		$this->session->set_flashdata('message',$message);
		header("location:$location");
	}
	private function isValidAdmission($id){
		$result=query($this->db,'select * from admission_application where status=1 and id=?',array($id));
		return $result;
	}
	//function to save the applicant pre-registration
	function register (){
		if (isset($_POST['sub'])) {
			unset($_POST['sub']);
			$application = isset($_POST['admission_application_id'])?$_POST['admission_application_id']:false;
			$admission = $this->isValidAdmission($application);
			if (!$application || empty($admission)) {
				echo "$application invalid application";
				return;
			}
			$empty = checkEmpty($_POST);
			if ($empty===false) {
				$this->load->model('entities/pre_admission');
				$this->webSessionManager->setArrayContent($_POST);
				$this->webSessionManager->setContent('purpose','admission application');
				$this->webSessionManager->setContent('user_type','applicant');
				$this->webSessionManager->setContent('ID','-1');
				loadClass($this->load,'admission_application');
				$this->admission_application->ID = $application;

				$pattern = $this->admission_application->getPaymentPatternID();
				$this->admission_application->load();
				$this->webSessionManager->setContent('aid',$this->admission_application->ID);
				if (!$pattern) {
					echo "an error occured kindly contact the administrator";exit;
				}
				header("Location:".base_url('vc/payment/pay/'.$pattern));exit;
				//get the payment value and redirect to the payment page
			}
			else{
				echo "$empty cannot be empty";
			}
		}
		else{
			show_404();
		}
	}

	//function for downloading data template
	function template($model){
		//validate permission here too.
		if (empty($model)) {
			show_404();exit;
		}
		loadClass($this->load,$model);
		if (!is_subclass_of($this->$model, 'Crud')) {
			show_404();exit;
		}
		$exception = null;
		if (isset($_GET['exc'])) {
			$exception = explode('-', $_GET['exc']);
		}
		$this->$model->downloadTemplate($exception);
	}
	function export($model){
		$condition = null;
		$args  =func_get_args();
		if (count($args) > 1) {
			$method = 'export'.ucfirst($args[1]);
			if (method_exists($this, $method)) {
				$condition = $this->$method();
			}
		}
		if (empty($model)) {
			show_404();exit;
		}
		loadClass($this->load,$model);
		if (!is_subclass_of($this->$model, 'Crud')) {
			show_404();exit;
		}
		$this->$model->export($condition);
	}
	//section for setting and checking upload condition
	private function uploadAResult(){
		//just performing some security checks.
		$id = isset($_POST['_assign_'])?$_POST['_assign_']:'';
		if (empty($id)) {
			show_404();exit;
		}
		$this->load->model('lmsModel');
		$data =array('id'=>$id);
		$this->lmsModel->processAssignmentUploadView($data);
		return array('course_assignment_ID'=>$id);
	}
	private function uploadStudent()
	{
		return array('role_name'=>'student');
	}
	private function uploadLecturer()
	{
		return array('role_name'=>'lecturer');
	}
	private function uploadApplicant()
	{
		return array('role_name'=>'applicant');
	}
	private function uploadUApplicant(){
		$id = isset($_POST['_assign_'])?$_POST['_assign_']:'';
		if (empty($id)) {
			show_404();exit;
		}
		loadClass($this->load,'admission_application');
		$this->admission_application->ID=$id;
		$this->admission_application->load();
		return array('name'=>$this->admission_application->name,'role_name'=>'applicant');
	}
	//section for checking upload result condition
	private function uploadRResult(){
		$DLC_ID = isset($_POST['DLC_ID'])?$_POST['DLC_ID']:'';
		$RVID = isset($_POST['RVID'])?$_POST['RVID']:'';
		if (empty($DLC_ID) && empty($RVID)) {
			show_404();exit;
		}
		return array('department_lecture_course_ID'=>$DLC_ID,'RVID'=>$RVID);
	}

// just create a the template function below to generate the needed paramter.
	function sFile($model){
		// $special = array('student','lecturer','applicant');
		$content = $this->loadUploadedFileContent();
		$content = trim($content);
		$array = stringToCsv($content);
		$header = array_shift($array);
		$defaultValues = null;
		$args = func_get_args();
		// if (in_array($model, $special)) {
		// 	$args[1]='student';
		// }
		if (count($args) > 1) {
			$method = 'upload'.ucfirst($args[1]);
			if (method_exists($this, $method)) {
				$defaultValues = $this->$method();
				$keys = array_keys($defaultValues);
				for ($i=0; $i < count($keys); $i++) { 
					$header[]=$keys[$i];
				}
				// $header = array_merge($header,);
				foreach ($defaultValues as $field => $value) {
					replaceIndexWith($array,$field,$value);
				}
			}
		}
		//check for rarecases when the information in one of the fields needed to be replaces
		if (isset($_GET['rp'] ) && $_GET['rp']) {
			$funcName = $_GET['rp'];
			# go ahead and call the function make the change
			$funcName = 'replace'.ucfirst($funcName);
			if (method_exists($this, $funcName)) {
				//the function must accept the parameter as a reference
				$this->$funcName($header,$array);
			}
		}
		loadClass($this->load,$model);
		$result = $this->$model->upload($header,$array,$message);
		$data=array();
		$data['pageTitle']='file upload report';
		if ($result) {
			$data['status']=true;
			$data['message']=$message;
			$data['model']=$model;
			$data['insert_info']=$this->db->conn_id->info;
		}
		else{
			$data['status']=false;
			$data['message']=$message;
			$data['model']=$model;
		}

		$arr =array('student','applicant','admin','lecturer');
		if (in_array($model, $arr)) {
			$this->updateUserType($model,$data);
			
		}
		$this->load->view('uploadreport',$data);

	}

	private function updateUserType($model,&$data)
	{
		$keyArray = array('student'=>'matric_number','applicant'=>'registration_number','admin'=>'username','lecturer'=>'username');
		$column = $keyArray[$model];
		$query ="insert into user (username,password,user_ID,user_type) select $model.$column,md5($model.$column),$model.ID,'$model' from $model LEFT join user on (user.user_ID = $model.ID and user.user_type='$model') where user.username is null and user.ID is null";
					if ($this->db->query($query)) {
						$data['additional'] =$this->db->conn_id->info;
					}
					else{
						$data['additional']='could not process login information';
					}
	}
	//this is a template for all functions that need to replace the information in the uploaded data array maybbe perform a transformational operation
	//enables the user to just enter the name of the semester and the information is converted into necessary id based on the session
	private function replaceSessionLecture(&$header,&$data){
		//
		//get the session
		$temp = array();
		//
		$header[]='session_semester_ID';
		$sessionIndex = array_search('academic_session', $header);
		$semesterIndex = array_search('semester', $header);

		for ($i=0;$i < count($data); $i++) {
			$cCurrent = '';	
			$cIndex =$data[$i][$sessionIndex].$data[$i][$semesterIndex];
			if (isset($temp[$cIndex])) {
				$cCurrent = $temp[$cIndex];
			}
			else{
				$query="select session_semester.id from session_semester  join academic_session on academic_session.ID = session_semester.academic_session_ID join semester on semester.ID = session_semester.semester_ID where academic_session.name=? and semester.name=?";
				$res = $this->db->query($query,array($data[$i][$sessionIndex],$data[$i][$semesterIndex]));
				$res = $res->result_array();
				$temp[$cIndex]=$res[0]['id'];
				$cCurrent=$temp[$cIndex];
			}
			
			$data[$i][]=$cCurrent;
			unset($data[$i][$sessionIndex]);
			unset($data[$i][$semesterIndex]);
		}
		unset($header[$sessionIndex]);
		unset($header[$semesterIndex]);
		// $result = $this->db->query($query,array($session));
		// $result = $result->result_array();
		// $index = array();
		// for ($i=0; $i < count($result); $i++) { 
		// 	$index[$result[$i]['name']] = $result[$i]['id'];
		// }
		// for ($i=0; $i < count($data); $i++) { 
		// 	$data[$i]['session_semester']=$index[$data[$i]['session_semester']];
		// }
	}
	private function submitApplication()
	{
		if ($this->webSessionManager->getCurrentUserProp('user_type')!='applicant') {
			show_404();
		}
		$id = $this->webSessionManager->getCurrentUserProp('ID');
		if (!$id) {
			show_404();
		}
		$query = "update applicant set formFilled=1 where ID=$id";
		$res=$this->db->query($query);
		if ($res) {
			header("Location:".base_url('vc/admissions/submitted'));exit;
		}
		else{
			echo "error occured while saving please try again or contact the administrator";exit;
		}
	}
	private function getscreeningsUpload($addBatch=true){
		$id = isset($_POST['_assign_'])?$_POST['_assign_']:'';
		if (empty($id)) {
			show_404();exit;
		}
		loadClass($this->load,'admission_application');
		$this->admission_application->ID=$id;
		$this->admission_application->load();
		$result = array('name'=>$this->admission_application->name,'username'=>$this->webSessionManager->getCurrentuserProp('username'));
		if ($addBatch && isset($_POST['admission_batch'])) {
			$result['batch_name']= $_POST['admission_batch'];
		}
		return $result;
	}
	private function uploadSessionCourses(){
		//automatically add session information
	}
	private function UploadAdmissionScreening(){
		return $this->getscreeningsUpload();
	}
	
	private function UploadAddList(){
		$val = 
		$id = $_GET['aid'];
		if (empty($id)) {
			show_404();exit;
		}
		loadClass($this->load,'admission_application');
		$this->admission_application->ID=$id;
		$this->admission_application->load();
		$result = array('name'=>$this->admission_application->name);
		return $result;
	}
	private function UploadResult_screening(){
		return $this->getscreeningsUpload();
	}
	private function loadUploadedFileContent(){
		$filename = 'bulk-upload';
		// if (isset($_POST['submit'])) {
			$status = $this->checkFile($filename,$message);
			if ($status) {
				if(!endsWith($_FILES[$filename]['name'],'.csv')){
					echo "invalid file format";exit;
				}
				$path = $_FILES[$filename]['tmp_name'];
				$content = file_get_contents($path);
				return $content;
			}
			else{
				echo "$message";exit;
			}
		// }
		// show_404();
		// exit;
	}

	//function to filter result version to download
	function exportResult(&$data){
		return array('RVID'=>$data['_1'],'DLC_ID'=>$data['DLC_ID']);
	}

	public function processPin($adm)
	{
		//this will process the admission checking pin
		if (isset($_POST['btn'])) {
			$confNumber = $_POST['CONFIRMATION_NO'];
			$receipt = $_POST['RECEIPT_NO'];
			$conf = $this->db->conn_id->escape_string($confNumber);
			$receipt = $this->db->conn_id->escape_string($receipt);
			loadClass($this->load,'admission_application');
			$app =$this->admission_application->ID=$adm;
			if (!$this->admission_application->load()) {
				exit("error while performing operation");
			}
			$checkFee = $this->admission_application->admission_checking_fee;
			loadClass($this->load,'payment_fee');
			$this->payment_fee->ID=$checkFee;
			if(!$this->payment_fee->load()){
				exit("configuration error occured,  please contact the adminstration: NOCHKFEE");
			}
			$amount = $this->payment_fee->amount;
			$query ="select * from pin_list where receiptNumber=? and confirmationNumber=?";
			$qResult =$this->db->query($query,array($receipt,$conf));
			$rr=$qResult->result_array();
			if ($rr) {
				//this means the pin has been used by someone else.
				$this->webSessionManager->setFlashMessage('response',"the pin  has already been used");
				header("Location:".base_url('vc/admissions/submitted'));exit;
			}
			$reg = $this->webSessionManager->getCurrentUserProp('registration_number');
			$qResult =$this->queryPayment($conf,$receipt);
			if (!$qResult || $qResult['TRANS_AMOUNT']!=$amount || $qResult['SERVICE_ID']!=$reg ) {
				$this->webSessionManager->setFlashMessage('response',"the pin cannot be validate");
				header("Location:".base_url('vc/admissions/submitted'));exit;
			}
			$q="insert into pin_list(regNumber,confirmationNumber,receiptNumber) values ('$reg','$conf','$receipt')";
			if (!$this->db->query($q)) {
				$this->webSessionManager->setFlashMessage('response','error processing pin please try again or contact the adminstrator is problem  persists');
				header("Location:".base_url('vc/admissions/submitted'));exit;
			}
			header("Location:".base_url('vc/admissions/submitted'));exit;
		}
		else{
			show_404();
		}
	}


	public function updateMatric()
	{
		if (!$this->webSessionManager->isSessionActive()) {
			header("Location:".base_url());exit;
		}
		if (isset($_POST['btn'])) {
			$content = $this->loadUploadedFileContent();
			$content = trim($content);
			$array = stringToCsv($content);
			$header = array_shift($array);
			$count = 0;
			$success =0;
			$failure =0;
			$total =0;
			$query = "update student set matric_number=? where matric_number=?";
			$query2 ="update user set username=?, password=md5(?) where user_ID=? and user_type='student'";
			loadClass($this->load,'student');
			foreach ($array as $val) {
				$student = $this->student->getWhere(array('matric_number'=>trim($val[1])),$c,0,null,false);
				if (!$student) {
					$total++;
					$failure++;
					continue;
				}
				$student=$student[0];
				$userID = $student->ID;
				$this->db->trans_start();
				if ($this->db->query($query,$val)) {
					$param = array(trim($val[0]),trim($val[0]),$userID);
					if (!$this->db->query($query2,$param)) {
						$total++;						
						$failure++;
						$this->db->trans_rollback();
						continue;
					}
					$this->db->trans_commit();
					$success++;
				}
				else{
					$failure++;
					$this->db->trans_rollback();
				}
				$total++;
			}
			$this->webSessionManager->setFlashMessage('response',"Record: $total, updated: $success, failed:$failure");
			header("Location:".base_url('vc/academics/uploadMatric'));exit;
		}
	}
	public function requeryTransaction($transNumber)
	{
		$this->load->model('paymentModel');
		$query = $this->paymentModel->queryTransaction($transNumber,$amount,$commission);
		$status = $query['ResponseCode']=='00' && $query['RetrievalReferenceNumber'];
		$userType = $this->webSessionManager->getCurrentUserProp('user_type');
		$user = $this->webSessionManager->getCurrentUserProp('ID');
		$registration_number = $this->webSessionManager->getCurrentUserProp('registration_number');
		$userKey = $userType=='student'?$this->webSessionManager->getCurrentUserProp('matric_number'):$registration_number;
		$result = $this->paymentModel->updateTransaction($this->db,$transNumber,$query,$commission,$user,$status,$registration_number,$amount,$userKey);
		$log = $this->payment_log->getWhere(array('transaction_number'=>$transNumber),$count,0,null,false);
		$log=$log[0];
		$message = $result?"transaction updated successfully ":"error occured while querying transaction";
		$this->webSessionManager->setFlashMessage('response',$message);
		if ($userType=='student') {
			header("Location:".base_url('vc/stdn/payment_history'));exit;
		}
		elseif ($userType=='applicant') {
			if ($status && $log->paymentPurpose=='admission checking') {
				//insert the receipt number and confiration number in the pin list tble
				$uid = $log->user_ID;
				loadClass($this->load,'applicant');
				$app =$this->applicant->getWhere(array('ID'=>$uid),$count,0,null,false);
				$regNumber = $app[0]->registration_number;
				$conf = $log->reference_number;
				$query = "insert ignore into pin_list(regNumber,confirmationNumber,receiptNumber) values('$regNumber','$conf','$regNumber')";
				$res = $this->db->query($query);
				if (!$res) {
					$this->webSessionManager->setFlashMessage('response',"error configuring payment, please try again or contact the admin");
				}
			}
			header("Location:".base_url('vc/admissions/my_transactions'));exit;
		}
	}

	function queryPayment($confirmation,$receipt){
		loadClass($this->load,'admission_application');
		$this->admission_application->ID= $this->webSessionManager->getCurrentUserProp('admission_application_ID');
		$this->admission_application->load();
		$terminal = "0350000011";//$this->admission_application->terminalID;
		$url = "https://www.etranzact.net/WebConnectPlus/query.jsp?TERMINAL_ID=$terminal&CONFIRMATION_NO=$confirmation";
		$ch = curl_init();		
		curl_setopt($ch, CURLOPT_URL, $url); 
		curl_setopt($ch, CURLOPT_TIMEOUT, '20'); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false );		
		$xmlstr = curl_exec($ch);
		curl_close($ch);
		if($xmlstr != '' && $xmlstr != NULL){
			parse_str($xmlstr,$response);
			return $response;
		}
		else{
			//die('No Response');
			return false;
		}
	}

	public function moveStudent($branch)
	{
		if (!$branch) {
			show_404();exit;
		}
		$message='';
		$currentSession = $this->webSessionManager->getCurrentSession($branch);
		loadClass($this->load,'branch');
		$this->branch->ID=$branch;
		$status=$this->branch->load();
		if (!$status) {
			show_404();exit;
		}
		if ($currentSession==$this->branch->last_session_move) {
			$message='student already moved in this session';
		}
		else{
			$this->db->trans_start();
			$query="update student,program set academic_level_ID=academic_level_ID+1 where branch_ID=$branch and student.academic_level_ID= program.end_level";
			if (!$this->db->query($query)) {
				$this->db->trans_rollback();
				$this->webSessionManager->setFlashMessage('response','error occured while moving student level please check your configuration and try again');
				header("Location:".base_url('vc/academics/branch'));exit;
			}

			$query = "update student,program set student.status=0  where program.end_level=student.academic_level_ID";
			if (!$this->db->query($query)) {
				$this->db->trans_rollback();
				$this->webSessionManager->setFlashMessage('response','error occured while moving student level please check your configuration and try again');
				header("Location:".base_url('vc/academics/branch'));exit;
			}
			$this->branch->last_session_move=$currentSession;
			if (!$this->branch->update()) {
				$this->db->trans_rollback();
				$this->webSessionManager->setFlashMessage('response','error occured while moving student level please check your configuration and try again');
				header("Location:".base_url('vc/academics/branch'));exit;
			}
			$this->db->trans_commit();
			$this->webSessionManager->setFlashMessage('response','student moved successfully');
			header("Location:".base_url('vc/academics/branch'));exit;
		}

	}

}