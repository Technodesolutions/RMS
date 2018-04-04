<?php 
	/**
	* this class will be used to generate table using normal query and will also contain action like the other query
	* will also include paging ability
	*/
	class QueryHtmlTableModel extends CI_Model
	{
		private $defaultPagingLength =10;
		private $classname;
		function __construct()
		{
			parent::__construct();
		}
		public function buildOrdinaryTable($data,$action=array(),$header=null){
			return $this->buildHtmlAndAction($data,$action,$header);
		}
		/**
		 * This method generate html table output based on the query given an the action parameter
		 * @param  [string]  $query        the query to send to the database . it must be a select query
		 * @param  [mixed]  $header       the array containing the header table
		 * @param  [array]  $queryData    the query must be a paramterized query and this represeent the array of paramter data
		 * @param  mixed $totalLength the total length of this query is passed to this variable
		 * @param  integer $lower        [if paging is used this is the starting point ]
		 * @param  [type]  $length       [this is the length of the data to be retrieved]
		 * @param  array   $actionArray  this is an associative array of associative array that contains the information needed to generate action 
		 * @return [string]                the html table string generated
		 */
		public function getHtmlTableWithQuery($query,$queryData=NULL, &$totalLength,$actionArray=array(),$header=null,$paged=true,$lower=0, $length=NULL){
			if (empty($query)) {
				throw new Exception("you must specify query to be used.");
			}
			$limit="";
			$array = array();
			if ($paged) {
				$length = $length?$length:$this->defaultPagingLength;
				//use get function for the len and the start index for the sorting
				$lower = (isset($_GET['p_start'])&& is_numeric($_GET['p_start']) )?(int)$_GET['p_start']:$lower;
				$length = (isset($_GET['p_len'])&& is_numeric($_GET['p_len']) )?(int)$_GET['p_len']:$length;
				if ($length!=NULL) {
					$limit = " LIMIT $lower,$length ";
					// $array=array($lower,$length);
				}
			}

			//check that the query is a select query and the there an id field specified is query array is not empty
			if (!empty($actionArray) && ( strpos($query, "ID") ===false || strpos($query, " * ")===false)  && strpos(strtolower($query), "select") ===false) {
				throw new Exception("the query must be a select query and the an id field must be set");
			}
			$query.=$limit;
			//merge the array
			if (empty($queryData) ){
				$queryData =$array;
			}
			else{
				$queryData = array_merge($queryData,$array);
			}
			//add calculate found rows rule to the query
			$query=$paged?replaceFirst("select", "select SQL_CALC_FOUND_ROWS ", $query):$query;
			$result = $this->db->query($query,$queryData);

			if ($paged) {
				$result2 = $this->db->query("SELECT FOUND_ROWS() as totalCount");
				$result2=$result2->result_array();
				$totalLength=$result2[0]['totalCount']; 
			}
			$result = $result->result_array();
			// var_dump($result);exit;
			$totalLength= $totalLength?$totalLength:count($result);
			$extra ='';
			if ($paged) {
				$this->load->model('tableViewModel');
				$classname = $this->extractClassnameFromQuery($query);
				$extra =$this->tableViewModel->generatePagedFooter($totalLength,$lower,$length);
			}
			return $this->buildHtmlAndAction($result,$actionArray,$header).$extra;
		}

		//function to extract classname from query
		private function extractClassnameFromQuery($query){
			$pos = strpos($query, ".ID");
			if ($pos!==false) {
				$len = strlen($query);
				$div = $pos - $len;
				$spaceIndex = strrpos(substr($query,0,$pos), ' ');
				$this->classname = trim(substr($query, $spaceIndex+1,($pos - ($spaceIndex+1))));
				if ($this->classname=='std') {
					$this->classname='student';
				}
				return;
			}
			//if .id is not present then validate the id is present and get the first string after the from keywork
			$pos = strpos($query, "ID");
			if ($pos!==false) {
				//get the index of rrom and then get the index of space 
				$from = stripos($query, 'from');
				$from+=strlen("from") + 1;

				$classname = substr($query,$from,strpos($query, ' ',$from) -$from);
				// echo "testing showng classname check file. $classname";exit;
				$this->classname = $classname;
			}
		}
		private function buildHtmlAndAction($data,$action,$header=null){
			if (empty($data)) {
				return "<div class='empty-rows'>empty rows </div>";	
			}
			$result = $this->openTableTag();
			$result.= $this->extractheader(empty($header)?array_keys($data[0]):$header,!empty($action));
			$result.= $this->buildTableBody($data,$action);
			$result.= $this->closeTableTag();
			return $result;
		}
		private function openTableTag(){
			return "<table class='table table-striped'>";
		}
		private function extractheader($keys,$includeAction=true){
			if ($includeAction) {
				$keys[]='Action';
			}
			$result="<thead>
			<tr>";
			for ($i=0; $i < count($keys); $i++) { 
				if ($keys[$i]=='ID') {
					continue;
				}
				$header =removeUnderscore($keys[$i]);
				$result.="<th>$header</th>";
			}
			$result.="</tr>
			<thead>";
			return $result;
		}

		private function buildTableBody($data,$action){
			$result ="<tbody>";
			for ($i=0; $i < count($data); $i++) { 
				$current = $data[$i];
				$result.=$this->buildTableRow($current,$action);
			}
			$result.='</tbody>';
			return $result;
		}

		private function buildTableRow($data,$action){
			$result ='<tr>';
			$keys = array_keys($data);
			for ($i=0; $i < count($keys); $i++) { 
				if ($keys[$i]=='ID') {
					continue;
				}
				$current = $data[$keys[$i]];
				if (isFilePath($current)) {
					$link = base_url().$current;
					$current = "<a href='$link' download >Download</a>";
				}
				if (strtolower($keys[$i])=='status') {
					$current = $data[$keys[$i]]?'Enabled':'Disabled';
				}
				$result.="<td>$current </td>";
			}
			$result.=empty($action)?'':$this->addTableAction($action,$data);
			$result.='</tr>';
			return $result;
		}
		private function addTableAction($action,$data){
			$result= "<td class='action-column'>
			<div class='dropdownbtn btn btn-primary'>Action <i class='fa fa-arrow-down'></i>
			<ul class='table-action' data-model=''> 
			";
			foreach ($action as $key => $value) {
				$critical = 0;
				$ajax=0;
				$link ='';
				$label =$key;
				$default=1;
				$this->load->model('tableActionModel');
				if (method_exists($this->tableActionModel, $value)) {
					$value = $this->tableActionModel->$value($data,$this->classname);
					$value = array_values($value);
					$key = array_shift($value);
					$label = $key;
				}
				$id = isset($data['ID'])?$data['ID']:$data['receipt_number'];//the condition is for the payment table
				
				if (is_array($value)) {
					$link=$value[0].'/'.$id;
					$critical =$value[1];
					$ajax =$value[2];
				}
				else{
				$criticalArray = array('delete','disable','reset password');
				if (in_array(strtolower($key), $criticalArray)) {
					$critical =1;
				}
				$link = $value.'/'.$id;
				$link = base_url($link);
				}
				
				$result.="<li data-ajax='$ajax' data-critical='$critical' ><a class='action-item'  href='$link'>$label</a></li>";
			}
			$result.= '</ul></div></td>';
			return $result;
		}

		private function closeTableTag(){
			return "</table>";
		}
	}
 ?>