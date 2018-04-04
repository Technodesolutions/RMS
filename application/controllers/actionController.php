<?php 
	/**
	* This class like other controller class will have full access control capability
	*/
	class ActionController extends CI_Controller
	{
		
		function __construct()
		{
			parent::__construct();
			$this->load->model('crud');
		}

		public function edit($model,$id){

		}
		public function disable($model,$id){
			$this->load->model("entities/$model");
			//check that model is actually a subclass
			if ( empty($id)===false && is_subclass_of($this->$model,'Crud' )) {
				if($this->$model->disable($id)){
					echo "disable successfully";exit;
				}
				echo "cannot disable item";
			}
			else{
				echo "cannot disable item";
			}
		}
		public function enable($model,$id){
			$this->load->model("entities/$model");
			//check that model is actually a subclass
			if ( !empty($id) && is_subclass_of($this->$model,'Crud' )) {
				$this->$model->enable($id);
				echo "enabled successfully";
			}
			else{
				echo "cannot enable item";
			}
		}
		public function view($model,$id){

		}
		public function delete($model,$id){
			//kindly verify this action before performing it
			$this->load->model("entities/$model");
			//check that model is actually a subclass
			if ( !empty($id) && is_subclass_of($this->$model,'Crud' )) {
				$this->$model->delete($id);
				echo "deleted successfully";
			}
		}
	}
 ?>