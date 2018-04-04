<?php 
	function removeUnderscore($fieldname){
		$result = '';
		if (empty($fieldname)) {
			return $result;
		}
		$list = explode("_", $fieldname);
		
		for ($i=0; $i < count($list); $i++) { 
			$current= ucfirst($list[$i]);
			$result.=$i==0?$current:" $current";
		}
		return $result;
	}

	function generateReceipt()
	{
		$rand = mt_rand(0x000000, 0xffffff); // generate a random number between 0 and 0xffffff
		$rand = dechex($rand & 0xffffff); // make sure we're not over 0xffffff, which shouldn't happen anyway
		$rand = str_pad($rand, 6, '0', STR_PAD_LEFT); // add zeroes in front of the generated string
		$code = date('Y')."".$rand;
		return strtoupper($code);
	}
	//this function returns the json encoded string based on the key pair paremter saved on it.
	//
	function createJsonMessage(){
		$argNum = func_num_args();
		if ($argNum % 2!=0) {
			throw new Exception('argument must be a key-pair and therefore argument length must be even');
		}
		$argument = func_get_args();
		$result= array();
		for ($i=0; $i < count($argument); $i+=2) { 
			$key = $argument[$i];
			$value = $argument[$i+1];
			$result[$key]=$value;
		}
		return json_encode($result);
	}

	//the function to get the currently logged on use from the sessions
	/**
	 * check that non of the given paramter is empty
	 * @return boolean [description]
	 */
	function isNotEmpty(){
		$args = func_get_args();
		for ($i=0; $i < count($args); $i++) { 
			if (empty($args[$i])) {
				return false;
			}
		}
		return true;
	}
//function to build csv file into a mutidimentaional array
	function stringToCsv($string){
		$result = array();
		$lines = explode("\n", trim($string));
		for ($i=0; $i < count($lines); $i++) { 
			$current  = $lines[$i];
			$result[]=explode(',', trim($current));
		}
		return $result;
	}

	function array2csv($array,$header=false){
		$content='';
		if ($array) {
			$content = strtoupper(implode(',', $header?$header:array_keys($array[0])))."\n";
		}
		foreach ($array as $value) {
		 $content.=implode(',', $value)."\n";
		}
		return $content;
	}

	function endsWith($string, $end){
		$temp = substr($string, strlen($string)-strlen($end));
		return $end == $temp;
	}

	//function migrated from  crud.php
	function extractDbField($dbType){
		$index =strpos($dbType, '(');
		if ($index) {
			return substr($dbType, 0,$index);
		}
		return $dbType;
	}

	function extractDbTypeLength($dbType){
		$index =strpos($dbType, '(');
		if ($index) {
			$len = strlen($dbType)-($index+2);
			return substr($dbType, $index+1,$len);
		}
		return '';
	}

	function getPhpType($dbType){
		$type=array('varchar'=>'string','text'=>'string','int'=>'integer','year'=>'integer','real'=>'double','float'=>'float','double'=>'double','timestamp'=>'date','date'=>'date','datetime'=>'date','time'=>'time','varbinary'=>'byte_array','blob'=>'byte_array','boolean'=>'boolean','tinyint'=>'boolean','bit'=>'boolean');
		$dbType = extractDbField($dbType);
		$dbType = strtolower($dbType);
		return $type[$dbType];
	}

	//function to build select option from array object with id and value key
	function buildOption($array,$val=''){
		if (empty($array)) {
			return '';
		}
		$result ='';
		for ($i=0; $i < count($array); $i++) { 
			$current = $array[$i];
			$id = $current['id'];
			$value = $current['value'];
			$selected = $val==$id?"selected='selected'":'';
			$result.="<option value='$id' $selected>$value</option> \n";
		}
		return $result;
	}
	function getRoleIdByName($db,$name){
		$query = "select id from role where role_name=?";
		$result = $db->query($query,array($name));
		$result = $result->result_array();
		return $result[0]['id'];
	}
	function buildOptionFromQuery($db,$query,$data=null,$val=''){
		$result = $db->query($query,$data);
		if ($result->num_rows ==0) {
			return '';
		}
		$result = $result->result_array();
		return buildOption($result,$val);
	}
	//function to buiild select option from an array of numerical keys
	function buildOptionUnassoc($array,$val=''){
		if (empty($array) || !is_array($array)) {
			return '';
		}
		$val = trim($val);
		$result = '';
		foreach ($array as $key => $value) {
			$current = trim($value);
			$selected = $val==$current?"selected='selected'":'';
			$result.="<option $selected >$current</option>";
		} 
			
		return $result;
	}

	//function to tell if a string start with another string
	function startsWith($str,$sub){
		$len = strlen($sub);
		$temp = substr($str, 0,$len);
		return $temp ===$sub;
	}

	function showUploadErrorMessage($webSessionManager,$message,$isSuccess=true,$ajax=false){
		if ($ajax) {
			echo $message;exit;
		}
		$referer = $_SERVER['HTTP_REFERER'];
		$base = base_url();
		if (startsWith($referer,$base)) {
			$webSessionManager->setFlashMessage('flash_status',$isSuccess);
			$webSessionManager->setFlashMessage('message',$message);
			header("location:$referer");
			exit;
		}
		echo $message;exit;
	}
	function loadClass($load,$classname){
		if (!class_exists(ucfirst($classname))) {
			$load->model("entities/$classname");
		}
	}

	// function to get date difference
	function getDateDifference($first,$second){
		$interval = date_diff(date_create($first),date_create($second));
		return $interval;
	}

	//function to get is first function is greater than the second
	function isDateGreater($first,$second){
		$interval = getDateDifference($first,$second);
		return $interval->invert;
	}

	// function to send download request of a file to the browser
	function sendDownload($content,$header,$filename){
		$content = trim($content);
		$header = trim($header);
		$filename = trim($filename);
		header("Content-Type:$header");
		header("Content-disposition: attachment;filename=$filename");
		echo $content; exit;
	}

	//function to generate
	function generateApplicationNumber($db,$application,$program){
		$format = getFormat($db,$application,$program);
		$matric = getMatric($db,$format) + 1;
		return $format['application_number_prefix'].padNumber($format['application_number_min_length'],$matric).$format['application_number_suffix'];
	}
	function getMatric($db,$format){
		$query = "select registration_number from applicant where registration_number like ? order by registration_number desc";
		$param = array($format['application_number_prefix'].'%'.$format['application_number_suffix']);
		$result = $db->query($query,$param);
		$reg =0;
		if ($result->num_rows() > 0) {
			$tempResult = $result->result_array();
			$matric = $tempResult[0];
			$matric =$matric['registration_number'];
			$ind1 =  strpos($matric, $format['application_number_prefix']) + strlen($format['application_number_prefix']);
			$ind2 = strpos($matric, $format['application_number_suffix']);
			$reg = substr($matric,$ind1, $ind2-$ind1);
		}
		return $reg;
	}
	function getFormat($db,$application,$program){
		$query = 'select application_number_prefix,application_number_suffix,application_number_min_length from application_number_gen  join admission_application_program on application_number_gen.admission_application_program_id=admission_application_program.id join program on admission_application_program.program_id= program.id where admission_application_program.admission_application_id=? and program.id=?';
		$result = $db->query($query,array($application,$program));
		if ($result->num_rows > 0) {
			$result = $result->result_array();
			return $result[0];
		}

		$query = "select application_number_prefix,application_number_suffix,application_number_min_length from admission_application where id=?";
		$result = $db->query($query,array($application));
		$result = $result->result_array();
		return $result[0];
	}
	//function to generate inc number
	function generateInc($db,$pos,$format){
		$pos2= $pos + strpos($format, ')',$pos);
		$n = (int)substr($format, $pos+4,$pos2);
		$query = "select ID from applicant order by ID desc limit 1";
		$result = $db->query($query);
		$value = 0;
		if ($result->num_rows > 0) {
			$result = $result->result_array();
			$value =$result[0]['ID'];
		}
		$value++;
		return padNumber($n,$value);
	}
	function padNumber($n,$value){
		$value = ''+$value; //convert the type to string
		$prevLen= strlen($value);
		// if ($prevLen > $n) {
		// 	throw new Exception("Error occur while processing");
			
		// }
		$num = $n -$prevLen;
		for ($i=0; $i < $num; $i++) { 
			$value='0'.$value;
		}
		return $value;
	}
	function getFileExtension($filename){
		$index = strripos($filename, '.',-1);//start from the back
		if ($index === -1) {
			return '';
		}
		return substr($filename, $index+1);
	}
	//function to determine if a string is a file path
	function isFilePath($str){
		$recognisedExtension = array('doc','docx','pdf','ppt','pptx','xls','xlsx','txt','csv');
		$extension = getFileExtension($str);
		return (startsWith($str,'uploads') && strpos($str, '/') && in_array($extension, $recognisedExtension)) ;
	}

	//function to pad a string by a number of zeros
	function padwithZeros($str,$len){
		$str.='';
		$count = $len - strlen($str);
		for ($i=0; $i < $count; $i++) { 
			$str='0'.$str;
		}
		return $str;
	}
	function generatePassword(){
		return randStrGen(10);
	}
	function randStrGen($len){
	    $result = "";
	    $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
	    $charArray = str_split($chars);
	    for($i = 0; $i < $len; $i++){
		    $randItem = array_rand($charArray);
		    $ra = mt_rand(0,10);
		    $result .= "".$ra>5?$charArray[$randItem]:strtoupper($charArray[$randItem]);
	    }
	    return $result;
	}

	//function to get the recent page cookie information
	function getPageCookie(){
		$result = array();
		if (isset($_COOKIE['edu_per'])) {
			$content = $_COOKIE['edu_per'];
			$result = explode('-', $content);
		}
		return $result;
	}

	//function to save the page cookie
	function sendPageCookie($module,$page){
		$content = $module.'-'.$page;
		setcookie('edu_per',$content,0,'/','',false,true);
	}
	function show_access_denied($loader){
		$loader->view('access_denied');
	}

	function show_operation_denied($loader){
		$loader->view('operation_denied');
	}
	//function to replace the first occurrence of a string
	function replaceFirst($toReplace,$replacement,$string){
		$pos = stripos($string, $toReplace);
		if ($pos===false) {
			return $string;
		}
		$len = strlen($toReplace);
		return substr_replace($string, $replacement, $pos,$len);
	}