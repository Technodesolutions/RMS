<?php 
/**
* model to manage table action information
*/
class tableActionModel extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
	}

		//function to generate the action link needed for the enable and disable field
	public function getEnabled($object,$classname=''){
		$classname = empty($classname)?lcfirst(get_class($object)):ucfirst($classname);
		$link = base_url("ac/disable/$classname");
		$label = "disable";
		if (strtolower($classname)=='academic_session') {
			$label='Deactivate';
		}
		$status = is_array($object)?$object['status']:$object->status;
		if (!$status) {
			$link = base_url("ac/enable/$classname");
			$label = "enable";
			if (strtolower($classname)=='academic_session') {
				$label='Activate';
			}
		}
		return $this->buildActionArray($label,$link,1,1);
	}
	public function getHostelApproved($object){
		$link = "vc/hostel/manageApplication/reject/";
		$label = "reject";
		if (!$object['approval_status']) {
			$link = "vc/hostel/manageApplication/approve/";
			$label = "approve";
		}
		return $this->buildActionArray($label,$link,1,1);
	}
	public function uploadAssignmentResult($object){
		if ($object->hasResult()) {
			return "";
		}
		$classname = lcfirst(get_class($object));
		$link = base_url("vc/course/uploadResult");
		$label = "upload Result";
		return $this->buildActionArray($label,$link,0,0);
	}
	public function getAssignmentResult($object){
		if (!$object->hasResult()) {
			return "";
		}
		$classname = lcfirst(get_class($object));
		$link = base_url("vc/course/aResult");
		$label = "View Result";
		return $this->buildActionArray($label,$link,0,0);
	}

		//function to return the array need
	protected function buildActionArray($label,$link,$critical,$ajax){
		$result = array();
		$result['label']=$label;
		$result['link']=$link;
		$result['isCritical']=$critical;
		$result['ajax']=$ajax;
		return $result;
	}
}
 ?>