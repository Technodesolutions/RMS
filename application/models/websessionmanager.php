<?php
/**
*
*/
class WebSessionManager extends CI_Model
{
	private $defaultRole = array('applicant','student','lecturer','staff');
	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model('crud');
	}
	/**
	 * This functio save the current user into the session
	 * @param  Crud    $user        [The user object needed to be saved in the session]
	 * @param  boolean $saveAllInfo [specify to save the user category data that the user belongs to]
	 * @return void               void
	 */
	public function saveCurrentUser(Crud $user,$saveAllInfo=false){
      $userType = $user->user_type;
      $uid = $user->user_ID;
      $moreInfo = array();
      loadClass($this->load,$userType);
      $moreInfo = $this->$userType->getWhere(array('ID'=>$uid,'status'=>1),$c,0,null,false);
      if (!$moreInfo) {
         echo "an unexpected error occured";exit;
      }
      $moreInfo = $moreInfo[0];
      $userArray = $moreInfo->toArray();
      $temp =$user->toArray();
      unset($temp['ID']);
      $all = array_merge($userArray,$temp);
      $this->session->set_userdata($all);
	}

	public function getCurrentUserDefaultRole(){
		$rolename = $this->getCurrentUserProp('usertype');
		if ($rolename==false) {
			redirect(base_url().'auth/logout');
		}
		return in_array($rolename, $this->defaultRole)?$rolename:'admin';
	}
	public function getCurrentUser(&$more){
		$userType = $this->session->userdata('usertype');
		$user = $this->loadObjectFromSession('User');
		$len = func_num_args();
		if ($len == 1) {
			$more = $this->loadObjectFromSession(ucfirst($userType));
		}
		return $user;
	}
	private function loadObjectFromSession($classname){
		$this->load->model(lcfirst($classname));
		$field = array_keys($classname::$fieldLabel);
		for ($i=0; $i < count($field); $i++) {
			$temp =$this->session->userdata($field[$i]);
			if (!$temp) {
				continue;
			}
			$array[]= $temp;
		}
		return new $classname($array);//return the object for some process
	}
	public function logout(){
		//just clear the session
		$this->session->sess_destroy();
	}
	/**
	 * get the user property saved in the session
	 * @param  [string] $propname [the property to get from the session]
	 * @return [mixed]           [the value saved in the session with the key or empty string if the item is not present in the database]
	 */
	public function getCurrentUserProp($propname){
		return $this->session->userdata($propname);
   	}
   	/**
   	 * checks if the session is active or not
   	 * @return boolean [true if the session is active or false otherwise]
   	 */
   	public function isSessionActive(){
   		$userid = $this->session->userdata('ID');
   		if (!empty($userid)) {
   			return true;
   		}
   		else{
   			return false;
   		}
   	}

   	public function getFlashMessage($name){
   		return $this->session->flashdata($name);
   	}

   	public function setFlashMessage($name,$value){
   		$this->session->set_flashData($name,$value);
   	}

   	public function isApplicantSessionActive(){
   		$userid = $this->getCurrentUserProp('ID');
   		$application = $this->getCurrentUserProp('admission_Application_ID');
   		if (!(empty($userid) || empty($application))) {
   			return true;
   		}
   		else{
   			return false;
   		}
   	}

//this function is used to set content on the session. This is delegating to the default session function on codeigniter
   	public function setContent($name,$value){
   		$this->session->set_userdata($name,$value);
   	}
   	function setArrayContent($array){
   		$this->session->set_userdata($array);
   	}
   	private function loadClass($classname){
   		if (!class_exists(ucfirst($classname))) {
   			$this->load->model("entities/$classname");
   		}
   	}

   	// this set of function check the type of user that is currently logged in
   	function isCurrentUserType($userType){
         $temp=$userType==$this->getCurrentUserProp('user_type');
         if (!$temp) {
            return false;
         }
         $st= $this->getCurrentUserProp('ID');
         loadClass($this->load,$userType);
         $className = ucfirst($userType);
         $result = new $className(array('ID'=>$st));
         $result->load();
         return $result;
   	}

   	function getCurrentUserType(){

   	}

   	//function to get the current session
   	function getCurrentSession($branch=false,$force=false){
         if ($this->getCurrentUserProp('user_type')=='student' || $branch) {
            $branch =$branch?$branch:$this->getCurrentUserProp('branch_ID');
            return $this->getStudentSession($branch);
         }

   		$id = $this->getCurrentUserProp('ed_cur_ses');
   		if (!empty($id) && !$force) {
   			return $id;
   		}
   		$this->load->database();
   		$query = "select ID from academic_session where status='1' limit 1";
   		$result = $this->db->query($query);
   		$result = $result->result_array();
         if ($result ==false) {
            return false;
         }
   		$result = $result[0]['ID'];
   		$this->setContent('ed_cur_ses',$result);
   		return $result;
   	}

      public function getStudentSession($branch=false)
      {
         // if ($this->getCurrentUserProp('st_ss')) {
         //    return $this->getCurrentUserProp('st_ss');
         // }
         // $branch = $this->getCurrentUserProp('branch_ID');
         $query="select ID from academic_session where status =1 and branch_ID = $branch order by ID desc limit 1";
         $result = $this->db->query($query);
         $result =$result->result_array();
         if (!$result) {
            exit("an error occured please contact the administrator: NOCURSS");
         }
         $session= $result[0]['ID'];
         $this->setContent('st_ss',$session);
         return $session;
      }

   	//function to get user type object, that is get lecturer, student etc,admin etc
   	function getCurrentSessionSemester(){
   		$id = $this->getCurrentUserProp('ed_cur_ses_sem');
   		if (!empty($id)) {
   			return $id;
   		}
   		$currentSession = $this->getCurrentSession();
   		$query="select session_semester.ID from session_semester where academic_session_ID=?  and registrationOpen =1 order by start_date desc";
   		$result = $this->db->query($query,array($currentSession));
   		$result = $result->result_array();
         if ($result==false) {
            return false;
         }
   		$result = $result[0]['ID'];
   		$this->setContent('ed_cur_ses_sem',$result);
   		return $result;
   	}

   function getAllData(){
      return $this->session->all_userdata();
   }
}

 ?>
