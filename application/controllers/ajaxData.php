<?php
	/**
	* model for loading extra data needed by pages through ajax
	*/
	class AjaxData extends CI_Controller
	{

		function __construct()
		{
			parent::__construct();
			$this->load->model("modelFormBuilder");
			$this->load->database();
			$this->load->model('webSessionManager');
			$this->load->model('entities/application_log');
			$this->load->helper('string');

			//dont load this class if the session is not active.
			//it is done in the constructor so that the check is done for all the method s here.

			if (!$this->webSessionManager->isSessionActive()) {
				exit;
			}
			// loadClass($this->load,'role');
			// $cookie = getPageCookie();
			// if (!in_array($this->webSessionManager->getCurrentUserProp('usertype'), array('student','applicant','lecturer','staff')) && !$this->role->canModify($cookie[0],$cookie[1])) {
			//   # who the access denied page.
			//   echo createJsonMessage('status',false,'message',"operation denied");exit;
			// }
		}
		//function to load payment information
		function paymentControlData($payment){
			$this->load->model('paymentModel');
			$data =$this->getPaymentChannelData($payment);//this will contain the righ url for the form submission. based on the payment and all
			$data['action']='loadpayment'; 
			echo json_encode($data);
		}
		// function for submitting data finally
		function saveApplication(){
			if ($this->webSessionManager->getCurrentUserProp('user_type')=='applicant') {
				$this->load->model('applicantModel');
				$this->applicantModel->loadApplicant();
		 	 	if ($this->applicantModel->submitApplication($message)) {
		 	 		echo createJsonMessage('status',true,'message',"form saved successfully");
		 	 		exit;
		 	 	}
		 	 	echo $message;exit;
		 	 	echo createJsonMessage('status',false,'message',$message);
		 	 	exit;
			}
			$this->application_log->log('applicant registration','problem saving application');
			echo createJsonMessage('status',false,'message',"access denied");
		}

		public function submitAcceptance()
		{
			$check = true;
			if (isset($_POST['acc_btn'])) {
				if (!isset($_POST['agree'])) {
					$this->webSessionManager->setFlashMessage('mmessage',"check the agreement to proceed please");
					$check = false;
				}
				$path = $_POST['img_path'];
				if (!$path || !file_exists($path)) {
					$this->webSessionManager->setFlashMessage('mmessage','please affix passport to continue');
					$check = false;
				}

				loadClass($this->load,'applicant');
				$this->applicant->accepted = 1;
				$this->applicant->date_accepted = date('Y-m-d:h:i:s');
				$val =$this->applicant->update(array('ID'=>$this->webSessionManager->getCurrentUserProp('ID')));
				if ($check && $val) {
					//move applicant to student;
					$this->applicant->ID = $this->webSessionManager->getCurrentUserProp('ID');
					if ($this->applicant->moveToStudent()) {
						header("Location:".base_url('vc/admissions/acc_letter'));exit;
					}
					else{
						echo "error occured";exit;
						$this->webSessionManager->setFlashMessage('mmessage','error occured please try again: UNSTD');
						header("Location:".base_url('vc/admissions/acceptance_form'));exit;
					}
				}
				else{
					header("Location:".base_url('vc/admissions/acceptance_form'));exit;
				}
			}
		}
		public function periodType($type){
			switch (strtolower($type)) {
				case 'semester':
					$this->sessionSemester();
					break;
				case 'session':
					$this->academicSession();
					break;
			}
		}
		public function extensiontype($type){
			$type =strtolower($type);
			switch ($type) {
				case 'department':
				case 'level':
					$this->department();
					break;
				case 'program':
					$this->program();
					break;
				case 'faculty':
					$this->faculty();
					break;

			}
		}

		public function lga($state){
			$result = loadLga($state);
			echo $this->returnJSONFromNonAssocArray($result);

		}
		public function removePrograms($id)
		{
			$degree = $_POST['dg'];
			$query ="delete from admission_application_program where admission_application_ID='$id' AND program_ID in (select program_ID from program where degree_ID='$degree' )";
			$res =$this->db->query($query);
			echo 'done';
		}
		private function returnJSONFromNonAssocArray($array){
			//process if into id and value then
			$result =array();
			for ($i=0; $i < count($array); $i++) {
				$current =$array[$i];
				$result[]=array('id'=>$current,'value'=>$current);
			}
			return json_encode($result);
		}
		public function block($hostel){			
			$query = "select id, block_name as value from hostel_block where hostel_ID=?";
			echo $this->returnJsonFromQueryResult($query,array($hostel));
		}
		public function manageRoom($block){			
			$query = "select id, room_name as value from hostel_room where hostel_block_ID=?";
			echo $this->returnJsonFromQueryResult($query,array($block));
		}
		public function room($block){	
		$currentSession = $this->webSessionManager->getCurrentSession();		
			$query = "select hostel_room.id, room_name as value from hostel_room join hostel_allocation on hostel_allocation.ID = hostel_room.ID
						 where hostel_block_ID=? and academic_session_id=? group by hostel_allocation.ID,hostel_room.rooms_number having count(hostel_allocation.ID) < hostel_room.rooms_number";
			echo $this->returnJsonFromQueryResult($query,array($block,$currentSession));
		}
		public function faculty(){
			$query = "select id, faculty_name as value from faculty";
			echo $this->returnJsonFromQueryResult($query);
		}
		public function department($faculty=''){
			$where ='';
			$data = array();
			if ($faculty) {
				$where = ' where faculty_ID=?';
				$data[]= $faculty;
			}			
			$query = "select id, department_name as value from department $where";
			echo $this->returnJsonFromQueryResult($query,$data);
		}
		public function program($department=''){
			$where ='';
			$data = array();
			if ($department) {
				$where=' where department_ID = ? ';
				$data[]=$department;
			}
			$query = "select id, program_name as value from program $where";
			echo $this->returnJsonFromQueryResult($query,$data);
		}
		//loads a json value for session semester
		private function sessionSemester(){
			$query = "select session_semester.id as id,concat(academic_session.name,' ',semester.name) as value from session_semester join academic_session on session_semester.academic_session_id = academic_session.id join semester on session_semester.semester_id= semester.id";
			echo $this->returnJsonFromQueryResult($query);
		}
		//function to load the registeration filter
		function periodFilter($type){
			if ($type=='Session') {
				return $this->academicSession();
			}
			else if ($type=='Semester') {
				return $this->sessionSemester();
			}
		}
		//function for loading department faculty or program
		function contentFilter($value){
			switch ($value) {
				case 'Department':
					return $this->department();
					break;
				case 'Faculty':
					return $this->faculty();
					break;
				case 'Program':
					return $this->program();
					break;
				default:
					# code...
					break;
			}
		}
		//loads a json value for session semester filtered by academic session
    private function sessionSemesterF($academicSession){
      $query = "SELECT session_semester.id AS id,concat(academic_session.name,' ',semester.name) AS value FROM session_semester JOIN academic_session ON session_semester.academic_session_id = academic_session.id JOIN semester ON session_semester.semester_id= semester.id WHERE academic_session.id = ?";
      echo $this->returnJsonFromQueryResult($query,array($academicSession));
    }
		//loads academic session
		private function academicSession(){
			$query = "select id, name as value from academic_session";
			echo $this->returnJsonFromQueryResult($query);
		}

		private function returnJsonFromQueryResult($query,$data=array()){
			$result = $this->db->query($query,$data);
			if ($result->num_rows() > 0) {
				$result = $result->result_array();

				return  json_encode($result);
			}
			else{
				return  "";
			}
		}

		//function to add subject to olevel

		function removeOLevelSubject(){
			//user the o level id and the subject name.
			$this->application_log->log('student registration','removing o level subject');
			if (isset($_POST['subsub'])) {
				$olevel = $_POST['olevel'];
				$subject = $_POST['subject'];
				$query = "delete from o_level_subject where o_level_ID=? AND subject_name=?";
				if ($this->db->query($query,array($olevel,$subject))) {
					$data['status']=true;
					$data['message']='subject removed successfully';
					$this->application_log->log('student registration','o level subject removed successfully');
					echo json_encode($data);
				}
				else{
					$data['status']=false;
					$data['message']='unable to remove subject';
					$this->application_log->log('student registration','error removing olevel subject');
					echo json_encode($data);
				}
			}
		}

		// function to upload user passport
		function addAttendance(){
			$this->application_log->log('lms module','adding attendance');
			if (isset($_POST['submit-attendance'])) {
				$data=$_POST['matrics'];
				if (empty($data)) {
					echo createJsonMessage('status',false,'message',"no student selected");
				}
				$course = $_POST['course'];
				$date  = $_POST['date'];
				$student= explode(',', $data);
				loadClass($this->load,'class_attendance');
				$this->class_attendance->department_lecture_course_ID =$course;
				$this->class_attendance->date= $date;
				if ($this->class_attendance->markAttendance($student)) {
					echo createJsonMessage('status',true,'message','attendance marked successfully');
					$this->application_log->log('lms module','added attendance');
					return;
				}
				$this->application_log->log('lms module','error adding attendance');
				echo createJsonMessage('status',false,'message',$message);
			}
		}
		function migrateStudent(){
			if (isset($_POST['sub']) && $_POST['sub']=='ajax-submit') {
				$loadedPrograms = array();
				loadClass($this->load,'applicant');
				$registrations = explode(',', $_POST['regs']);
				$this->db->trans_begin();
				loadClass($this->load,'student');
				for ($i=0; $i < count($registrations); $i++) { 
					$current = $registrations[$i];
					if ($this->student->getWhere(array('registration_number'))) {
						continue;
					}
					$app = $this->applicant->getWhere(array('registration_number'=>$current),$row,0,null,false,'',$this->db);
					$app = $app[0];
					$program = $app->program_ID;
					$rules =isset($loadedPrograms[$program])?$loadedPrograms[$program]:$app->generateApplicantPrefixSuffix($this->db);
					$rules['matric']= $rules['matric']+1;
					$loadedPrograms[$program]=$rules;
					$matric = $rules['prefix'].padNumber($rules['minimum_length']?$rules['minimum_length']:0,$rules['matric']).$rules['suffix'];
					if(!$app->migrateToStudent($matric,$this->db)){
						$this->db->trans_rollback();
						echo createJsonMessage('status',false,'message','error occured while performig operation');
						exit;
					}
				}
				$this->db->trans_commit();
				echo createJsonMessage('status',true,'message','operation successfull');
			}
			else{
				echo createJsonMessage('status',false,'message','invalid operation');
			}
		}
		function publish_admission(){
			$this->application_log->log('admission module','publishing admission');
			if (isset($_POST['sub'])) {
				$adm = $_POST['adms'];
				$batch = $_POST['batch'];
				if (!(empty($adm) || empty($batch))) {
					loadClass($this->load,'admission_list_approval');
					$test = $this->admission_list_approval->getWhere(array('admission_application_ID' => $admission,'batch_ID'=>$batch ));
					if ($test) {
						echo createJsonMessage('status',false,'message','List has already been published');
						exit;
					}
					loadClass($this->load,'screening_list');
					if ($this->screening_list->approveScreening($adm,$batch)) {
						echo createJsonMessage('status',true,'message','List published successfully');
						$this->application_log->log('admission module','list published');
						exit;
					}
				}

			}
			$this->application_log->log('admission module','error publishing list');
			echo createJsonMessage('status',false,'message','invalid operation');
		}
		//function to remove selected course
		function removeCourseRegistration(){
			$this->application_log->log('student registration','removing course registration');
			if (isset($_POST['edu-submit'])) {
				$department_lecture_course_ID = $_POST['course_ID'];
				$student_ID = $_POST['student'];
				$query = "DELETE FROM course_registration WHERE student_ID=? AND department_lecture_course_ID=?";
				if ($this->db->query($query,array($student_ID,$department_lecture_course_ID))) {
					$data['status']=true;
					$data['message']='Course removed successfully';
					$this->application_log->log('student registration','course  removed successfully');
					echo json_encode($data);
				}
				else{
					$data['status']=false;
					$data['message']='Unable to remove course';
					$this->application_log->log('student registration','unable to remove course');
					echo json_encode($data);
				}
			}
		}
		//function to submit student course Registration 
		function cRegister(){
			$this->application_log->log('student registration','submiting course registration');
			if ($this->webSessionManager->getCurrentUserProp('usertype')=='student' && isset($_POST['courses']) && isset($_POST['edu-submit'])) {
				$this->load->model('studentModel');
				$courses = $_POST['courses'];
				$this->studentModel->loadStudent();
				$student = $this->studentModel->getStudent();

				$result =$student->registerCourse($courses);
				if ($result) {
					$this->application_log->log('student registration','course registration submitted successfully');
					echo createJsonMessage('status',true,'message','courses registered successfully');exit;
				}
				else{
					$this->application_log->log('student registration','error submitting course registration');
					echo createJsonMessage('status',true,'message','error registering some courses');exit;;
				}
			}
		}
		function submitRegistration(){
			$this->application_log->log('student registration','submitting course registration');
			if ($this->webSessionManager->isCurrentUserType('student') && (isset($_POST['edu-submit']))) {
					$ssid = $_POST['session_semester'];
					$this->load->model('studentModel');
					$this->studentModel->loadStudent();
			 	 	$this->studentModel->completeRegistration($ssid);
			}else{
				$data['status']=false;
				$data['message']='Warning!!! Illegal Entry';
				echo json_encode($data);
			}
		}

		function depCourse($dep){
			$SSID = $this->webSessionManager->getCurrentUserProp('ssid');
			$query = "SELECT department_lecture_course.ID AS id,concat(course_name,'_',course_code, '_',course_unit,' units') AS value
		FROM department_lecture_course
		JOIN lecture_course on department_lecture_course.lecture_course_id=lecture_course.id
		WHERE department_lecture_course.session_semester_id = $SSID and department_lecture_course.department_ID = $dep
		and department_lecture_course.id not in (select department_lecture_course_id from course_registration where session_semester_id=? and student_id=?)";
			echo $this->returnJsonFromQueryResult($query,array($SSID,$this->webSessionManager->getCurrentUserProp('user_ID')));
		}

		//function for changing the password for user.
		function changePassword(){
			// $this->application_log->log('profile module','changing password');
			if (isset($_POST['ajax-sub'])) {
				$old = $_POST['oldpassword'];
				$new = $_POST['newpassword'];
				$confirm = $_POST['confirmPassword'];
				if ($new !==$confirm) {
					// $this->application_log->log('profile module','password does not match');
					echo createJsonMessage('status',false,'message','new password does not match with the confirmaton');exit;
				}
				//check that this user owns the password
				loadClass($this->load,'user');
				$this->user->user_ID = $this->webSessionManager->getCurrentUserProp('ID');
				$result = $this->user->changePassword($old,$new,$message);
				// $this->application_log->log('profile module',$message);
				echo createJsonMessage('status',$result,'message',$message);
			}
		}
		public function checkPin()
		{
			echo "i am going to integrate with etranzact just calm down";
		}
		//function to estimate non-graduating student
		function estimateNonGraduating(){
			//get the pass score in score in school
			$min = 40;//update with the school setting later
			$passUnit = 20;//load this from the real page.
			$currentSession = $this->webSessionManager->getCurrentSession();
			$query ="select '$currentSession', id from student y where y.matric_number in (select result.matric_number from result join result_submission on result_submission.id = result.rvid join department_lecture_course on result.department_lecture_course_ID = department_lecture_course.id where result.matric_number = y.matric_number and (result.exam_score + result.ca_score) < $min and department_lecture_course.course_status='compulsory' ) or (select sum(lecture_course.course_unit) as unit_passed from result join department_lecture_course on department_lecture_course.id = result.department_lecture_course_ID join lecture_course on lecture_course.id = department_lecture_course.lecture_course_id where result.matric_number=y.matric_number)";
			// $query = "insert into non_graduating_student (academic_session_id,student_id) select '$currentSession', id from student y where y.matric_number in (select result.matric_number from result join result_submission on result_submission.id = result.rvid join department_lecture_course on result.department_lecture_course_ID = department_lecture_course.id where result.matric_number = y.matric_number and (result.exam_score + result.ca_score) < $min and department_lecture_course.course_status='compulsory' ) or (select sum(lecture_course.course_unit) as unit_passed from result join department_lecture_course on department_lecture_course.id = result.department_lecture_course_ID join lecture_course on lecture_course.id = department_lecture_course.lecture_course_id where result.matric_number=y.matric_number)";
			$result = $this->db->query($query);
			$result = $result->result_array();
			print_r($result);exit;
			if ($result) {
				echo createJsonMessage('status',true,'message','operation successfull');
				return;
			}
			echo createJsonMessage('status',false,'message','error occured while performing operation');
		}
	}
 ?>
