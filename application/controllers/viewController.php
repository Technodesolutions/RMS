<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ViewController extends CI_Controller{

//field definition section
  private $needId= array();

  private $needMethod=array();
  private $errorMessage; // the error message currently produced from this cal if it is set, it can be used to produce relevant error to the user.
  private $access = array(
    'school'=>array('publication','payment','admin','user')
  );
//
  function __construct(){
		parent::__construct();
		$this->load->model("modelFormBuilder");
		$this->load->model("tableViewModel");
		$this->load->helper('url');
    $this->load->helper('string');
    $this->load->helper('array');
    $this->load->model('webSessionManager');
    $this->load->model('queryHtmlTableModel');
    $this->load->model('entities/application_log');
	}
//// bootsrapping functions 
  public function view($model,$page='index',$other=''){
    if ( !(file_exists("application/views/$model/") && file_exists("application/views/$model/$page".'.php')))
    {
      show_404();
    }
    //check the view permission here, dont set a strict rule for the dashboard
    // loadClass($this->load,'role');
    // if (!in_array($this->webSessionManager->getCurrentUserProp('usertype'), array('student','applicant','lecturer','staff')) && !$this->role->canView($model,$page)) {
    //   # who the access denied page.
    //   show_access_denied($this->load);
    // }
    $defaultArgNum =3;
    $tempTitle = removeUnderscore($model);
    $title = $page=='index'?$tempTitle:ucfirst($page)." $tempTitle";
    //$schoolName = empty($this->session->userdata('schoolName'))?//till the school name getter is added
    loadClass($this->load,'school');
    $school =$this->school->all();
    $schoolName = $school[0]->school_name.' ('.$school[0]->school_code.')';
    $data['pageTitle'] = "$schoolName ";
    $data['id'] = $other;
    if (func_num_args() > $defaultArgNum) {
      $args = func_get_args();
      $this->loadExtraArgs($data,$args,$defaultArgNum);
    }

    if ($this->webSessionManager->getCurrentUserProp('user_type')=='admin') {
        $accessType = $this->webSessionManager->getCurrentUserProp('accessType');
        $test =$this->processAdmin($model,$accessType);
        if (!$test) {
          show_404();exit;
        }
    }
    $exceptions = array('login','apply','check_transaction','revalidate');//pages that does not need active session
    if (!in_array($page, $exceptions)) {
      if (!$this->webSessionManager->isSessionActive()) {
        redirect(base_url());exit;
      }
    }

     $this->application_log->log($model,"trying to view $page");
    if (method_exists($this, $model)) {
      $this->$model($page,$data);
    }
    $methodName = $model.ucfirst($page);

    if (method_exists($this, $model.ucfirst($page))) {
      $this->$methodName($data);
    }

    $data['message']=$this->session->flashdata('message');
    $this->application_log->log($model,"loads $page");
    sendPageCookie($model,$page);

    return $this->load->view("$model/$page", $data);
  }

  private function processAdmin($model,$access)
  {
    if ($access=='feghas') {
      return true;
    }
    return in_array($model, $this->access[$access]);
  }
  private function loadExtraArgs(&$data,$args,$defaultArgNum){
    $count=1;
    for ($i=$defaultArgNum; $i < count($args); $i++) {
     $data['_'.$count] =$args[$i];
     $count++;
    }
  }
  //<--function for the admin section of the  applicant module
  private function screenSetup(&$data){
    //check that the application id exist
    $this->load->model('entities/admission_application');
    if (empty($data['id'])) {
      show_404();
      exit;
    }
    $application = $this->admission_application->load( $data['id']);
    if ($application) {
     $data['application']=$this->admission_application;
     $screening = $this->admission_application->screen;
     if ($screening) {
      $data['screening'] = $screening;//load the first item from screening

     }
     else{
     $data['screening'] = false;
    }
    }
    else{
      //if application with the id does not exist show 404;
      show_404();
      exit;

    }
  }

  private function user($page,&$data){
    $data['userType'] = $this->webSessionManager->getCurrentUserProp('user_type');
  }

  private function userMyComplaint(&$data){
    loadClass($this->load,'user');
    $this->user->ID = $this->webSessionManager->getCurrentUserProp('ID');
    $data['complaints']= $this->user->getComplaints();//load the complaint and sort it by time
  }

  private function admissionsCheck_transaction(&$data)
  {
    if (isset($_POST['pst'])) {
      $val = filter_var($_POST['query_field'],FILTER_SANITIZE_URL);
      $this->applicantModel->loadTransaction($val,$data);
    }
  }
//// admission section
  function admissions($page,&$data){
    $exception = array('apply','payment','application','check_transaction');
    if ($page!='login' ) {
     $this->load->model('applicantModel');
     if (in_array($page, $exception)) {
      return;
     }
     $this->applicantModel->loadApplicant();
     $data['formArray']= $this->applicantModel->getFormArray();
     $data['pagename'] = $page;
     loadClass($this->load,'pre_admission');
     $pattern = $this->pre_admission->getPaymentPattern($this->applicantModel->getapplicant()->admission_application_ID);
     $data['pattern_id'] = $pattern;
    }

  }
  //function for loading the admission application
  function admissionsApplication(&$data){
    $this->applicantModel->loadAvailableApplication($data);
  }
  function admissionsApply(&$data){
    $this->applicantModel->loadApplicationPage($data);
  }
  //function for the contact field
  private function admissionsContact(&$data){
    $this->applicantModel->loadContactPage($data);
  }
  //function for loading the logic for the payment page.
  private function admissionsPayment(&$data){
    $this->applicantModel->loadPaymentPage($data);
  }
  //function for handling applicant data view
  private function admissionsPersonal(&$data){
    $this->applicantModel->loadPersonalPage($data);
  }

  private function admissionsO_level(&$data){
    $this->applicantModel->loadOlevelData($data);
  }

  private function admissionsJamb(&$data){
    $this->applicantModel->loadJambResultPage($data);
  }

  private function admissionsDocument(&$data){
    $this->applicantModel->loadAdmissionDocument($data);
  }

  private function admissionsReferee(&$data){
    $this->applicantModel->loadRefereePage($data);
  }

  private function admissionsPreview(&$data){
      $this->applicantModel->loadApplicantPreview($data);
  }

  private function admissionsPrintout(&$data){
    $this->applicantModel->loadPrintout($data);
  }
  private function admissionsAlevel(&$data){
    $this->applicantModel->loadAlevel(); //will need more information on how direct entry is done.
  }
  private function admissionsSubmitted(&$data){
    $this->applicantModel->processPostRegistration($data);
  }
  private function admissionsAdm_letter(&$data){
    $this->applicantModel->loadAdmissionLetterInformation($data);
  }
  //// <---End of applicant admission Section --->////
//// admission application section
  ////function for admin  application set up side
  //initiation function for admission_application
  private function admission_application($page,&$data){
    if (!$this->webSessionManager->isSessionActive()) {
      redirect(base_url('auth/logout'));exit;
    }
    $userType= $this->webSessionManager->getCurrentUserProp('user_type');
    $nosidebar = array('admission_list');
    if ($userType=='applicant' && in_array($page, $nosidebar)) {
      $data['sidebar_visible']=false;
    }
    $exception = array('addfee','editfee','applicant','create');
    if ($page=='index') {
     return;
    }
    if (!isset($data['id'])) {
      show_404();exit;
    }
    $this->load->model('extra/admissionApplication');
    if (in_array($page, $exception)) {
      return;
    }

    loadClass($this->load,'admission_application');
    $application = new admission_application(array('ID'=>$data['id']));
    if(!$application->load()){
      show_404();exit;
    }
    $data['admission_application_name']=$application->name;
    $this->admissionApplication->setApplication($application);
  }
  private function admission_applicationMatricNum(&$data){
    //send the school id as part of the information needed to perform the update on the table files
    loadClass($this->load,'school');
    $temp = $this->school->all();
    $sch = $temp[0]->ID;
    $data['schoolid'] = $sch;
  }
  private function admission_applicationMeritList(&$data){
    $this->admissionApplication->processAdmissionList($data);
    $this->load->model('queryHtmlTableModel');
  }
  private function admissionsAcceptance_Form(&$data)
  {
    if ($this->applicantModel->hasAccepted()) {
      header("Location:".base_url('vc/admissions/acc_letter'));exit;
    }
    $this->applicantModel->getAcceptanceFormInfo($data);
  }

  private function admission_applicationApplicant(&$data){
    $this->admissionApplication->loadApplicant($data);
  }
  private function paymentBank_settlement(&$data)
  {
    $this->paymentModel->getBankSettlementInformation($data);
  }
  private function paymentLedger(&$data)
  {
    $this->paymentModel->getLedgerInfo($data);
  }

  private function paymentSummary(&$data)
  {
    $this->load->model('reportModel');
    $paymentCount = $this->reportModel->paymentCountByBranchSession();
    $data['payReport']=$paymentCount;
    $activeCount = $this->reportModel->getActiveStudentByBranchSession();
    $data['active']=$activeCount;

  }
  private function admission_applicationApplicants(&$data){
    $this->admissionApplication->loadApplicants($data);
  }
  private function admission_applicationForms(&$data){
    $this->admissionApplication->loadForms($data);
  }
  private function admission_applicationPrograms(&$data){
    $this->admissionApplication->loadPrograms($data);
  }
  private function admission_applicationSetup(&$data){
    $application = $this->admissionApplication->getApplication();
    $data['application']= $application;
  }

  private function admission_applicationAppNum(&$data){
    $data['admissionApplication']=$this->admissionApplication->getApplication();
    $this->load->model('queryHtmlTableModel');
  }
  private function admission_applicationAdmission_list(&$data){
    $exception = array('applicant' ,'student','staff');
    $usertype = $this->webSessionManager->getCurrentUserProp('user_type');
    if ($usertype!=='admin') {
      header("Location:".base_url('auth/logout'));
    }
    if (!in_array(trim($usertype), $exception) && isset($_GET['export']) && $_GET['export']=='yes') {
      $app = $this->admissionApplication->getApplication();
      $this->admissionApplication->exportAdmissionList($data,$app->name);
    }
    $data['userType']= $usertype=='admin'?'':$usertype.'/';
    if ($usertype=='applicant') {
      $this->admissions('admission_list',$data);
    }
    
    if (!in_array($usertype, $exception)) {
      $data['allow_operation'] = true;
    }
    $this->load->model('queryHtmlTableModel');
  }
//// misc
  private function screen($page,&$data){
    if (!isset($data['id'])) {
      show_404();exit;
    }
    loadClass($this->load,'screen');
    $screen = new Screen(array('ID'=>$data['id']));
    if (!$screen->load()) {
      show_404();exit;
    }
    $data['screen'] = $screen;
  }



    // the method for generate table view, insertion, modification and deletion
    public function gen($model,$page='index',$other=''){
      //  echo "$model and $page";
      // return;
      if(!file_exists("application/models/entities/$model".'.php')){
        echo "first";
        show_404();exit;
      }
      // $this->load->model('entities'.$model);
      if (! file_exists("application/views/$page".'.php'))
      {
        echo "second";
        show_404();exit;
      }

      $tempTitle = removeUnderscore($model);
      $title = $page=='index'?$tempTitle:ucfirst($page)." $tempTitle";
      //$schoolName = empty($this->session->userdata('schoolName'))?//till the school name getter is added
      $data['pageTitle'] = "school Name | $title";
      $data['displayName']= $tempTitle;
      $data['id'] = $other;
      $data['model']=$model;
      $data['message']=$this->session->flashdata('message');
      return $this->load->view("$page", $data);
    }

//<-- Result Management Section-->

    private function lect($page, &$data){
     $this->loadCurrentUserType($data);
      $this->load->model('lecturerModel');
      $this->lecturerModel->loadLecturer();//load the lecturer object in this class so that all other method can use it in the class.
    }
    private function  lectViewVersionResult(&$data){
      // echo $data['id'];exit;
      $temp =$this->lecturerModel->getResultCourseInformation($data['_1']);
      if ($temp) {
       $data['lectContent'] = $temp[0];
      }
      else{
        $this->webSessionManager->setFlashMessage('message','no result found for this version');
      }
      
    }
    private function lectDashboard(&$data){
      //fix the lecturers dashboard here.
      $this->lecturerModel->loadDashboardContent($data);
    }
    // function loads all courses taught by a lecturer
    private function lectCourseListing(&$data){
      $this->lecturerModel->loadCourses($data);
    }
    private function lectCreateResultVersion(&$data){
      
    }

    //function that loads result version
    private function lectResultVersionListing(&$data){
      if(isset($data['_1']) && $data['_1'] == 'create'){
        $this->lecturerModel->createResultVersion($data);     
      }
      $this->lecturerModel->loadCourseResultVersion($data);
      $this->load->model('queryHtmlTableModel');
    }
    //function to view the dashboard for a course being taught by the lecturer
    private function lectCourse_dashboard(&$data){
      $this->lecturerModel->loadCourseDashboard($data);
    }
    private function lectPublication(&$data){
      $this->loadCurrentUserType($data);
      $data['publication'] =$this->lecturerModel->getLecturer()->publication;
    }
    // the function for assessing the assignment download page for the lecturer
    private function lectAssign_sub(&$data){
      if (!isset($data['id'])) {
        show_404();exit;
      }
      $this->lecturerModel->loadAssignmentSubmission($data);
      $this->load->model('queryHtmlTableModel');
    }
    private function lectAssArchive(&$data){
      if (!isset($data['id'])) {
        show_404();exit;
      }
      $this->lecturerModel->loadSubmissionArchive($data);
    }

    private function lectProfile(&$data)
    {
      if ($this->webSessionManager->getCurrentUserProp('user_type')=='admin') {
        if (isset($data['id'])) {
          $this->lecturerModel->loadLecturer($data['id']);
          if (!$this->lecturerModel->getLecturer()) {
            show_404();exit;
          }
        }
        else{
          show_404();exit;
        }
      }
      $this->lecturerModel->loadProfile($data);
    }


   private function lectUploadResult(&$data){
    
   } 
   /** Student RMS Section **/
   // private function stdn($page,&$data){
   //   $this->load->model('studentModel');
   //   $this->studentModel->loadStudent();
   // }

   private function stdnViewResult(&$data){
    $this->studentModel->loadResult($data);
   }

  /** Admin RMS Section **/
  private function adminStudentRegistration(&$data){
    if (isset($_GET['export'])&& $_GET['export']=='yes') {
      $this->adminModel->exportRegistration();
    }
    $data['resultData']=$this->adminModel->getCourseRegistrationInfo();
  }
  private function adminProfile(&$data){
    loadClass($this->load,'admin');
    $this->admin->ID =$this->webSessionManager->getCurrentUserProp('ID');
    $this->admin->load(); 
    $data['user']= $this->admin;
  }
  private function adminManageResult(&$data){
    $this->load->model('adminModel');
    $this->load->model('resultModel');
    $this->load->model('resultSubmissionModel');
    if (isset($data['id'])) {
      if($data['id']=="approve" || $data['id']=="reject"){
        $this->adminModel->resultApproval($data);
      }
      elseif($data['id']=="approveAll" || $data['id']=="rejectAll"){
        $this->adminModel->approveAllCoursesResult($data);
      }
    }
    $this->adminModel->manageResult($data);
  }
  //the permission function section
  private function adminPermission(&$data){
    loadClass($this->load,'role');
    $this->role->ID = $data['id'];
    if(!$this->role->load()){show_404();exit;}
    $data['rolename']= $this->role->role_name;
    $arr = $this->role->toArray();
    $pm = $arr['permissions'];
    $pm = json_decode($pm,true);
    $data['permission']=$pm;
  }
  //Transcript Generation System Section
    private function adminManageTranscript(&$data){
      $this->load->model('adminModel');
      $this->load->model('resultModel');
      //ge the student id through posting and then use that to create the transcipt for eacho of the student one after the other
      if($data['id']== 'individual'&& $data['_1'] !=null){
        $this->adminModel->generateTranscript($data['_1']);
      }
      // if ($data['id']=='level'&&$data['_1'] != null) {
      //   $this->adminModel->generateDeptTranscript($data);
      // }
      $data['facultyOption'] = buildOptionFromQuery($this->db,"select id,faculty_name as value from faculty",array(),isset($_GET['f'])?$_GET['f']:'');
      $data['deptOption'] = buildOptionFromQuery($this->db,"select id,department_name as value from department",array(),isset($_GET['d'])?$_GET['d']:'');
      $data['levelOption'] = buildOptionFromQuery($this->db,"select id,level_name as value from academic_level",array(),isset($_GET['l'])?$_GET['l']:'');
    }

  ////the section for lms module starts here.
    private function course($page,&$data){
      $nonCourseIdException = array('uploadResult');
      if (!isset($data['id'])) {
        show_404();exit;
      }
      $this->loadCurrentUserType($data);
      $this->load->model('lmsModel');
       if (in_array($page, $nonCourseIdException)) {
        return;
      }
      $this->lmsModel->loadCourse($data['id']);
    }
    private function loadCurrentUserType(&$data){
      $userType = $this->webSessionManager->getCurrentUserDefaultRole();
      $data['userType']= $userType=='admin'?'':$userType.'/';
    }
    function courseContent(&$data){
      //loads the reading list for the course that the student could download or load for in the library
      $this->lmsModel->loadCourseContent($data);
    }

    private function courseAssignment(&$data){
      //loads the asssignment page for the course.
      $this->lmsModel->loadCourseAssignment($data);
    }
    private function courseUploadResult(&$data){
      $this->lmsModel->CheckAssignmentOperation($data);
    }

    private function courseAResult(&$data){
      $this->lmsModel->CheckAssignmentOperation($data);
    }

    private function courseStudent_list(&$data){
      $this->lmsModel->loadStudentList($data);
      $this->load->model('queryHtmlTableModel');
    }
    private function courseAnnouncement(&$data){
      $data['announcement'] = $this->lmsModel->getCourse()->course_announcement;
      $this->load->model('tableViewModel');
    }
    private function courseReading_list(&$data){
      $data['table']=$this->lmsModel->getCourse()->readings;
    }

    private function courseMark_attendance(&$data){
      $this->lmsModel->loadStudentListForAttendance($data);
    }

    private function courseAttendance_list(&$data){
      $data['attendance']=$this->lmsModel->getCourse()->getStudentAttendanceOverview();
      $this->load->model('queryHtmlTableModel');
    }
//student section begins here
  private function stdn($page,&$data){
    $this->load->model('studentModel');
    $this->studentModel->loadStudent();

  }
  private function stdnProject_fee(&$data)
  {
    $student = $this->studentModel->processProjectFee();

  }
  private function stdnPayment_history(&$data){
    // $this->studentModel->loadPaymentHistory($data);
    $this->load->model('queryHtmlTableModel');
  }
  private function stdnAttendance(&$data){
    $this->studentModel->loadAllAttendance($data);
  }
  private function stdnMy_courses(&$data){
    $this->studentModel->loadStudentCourses($data);
    $this->load->model('queryHtmlTableModel');
  }

  private function stdnCourse_home(&$data){
    if (!isset($data['id'])) {
      show_404();exit;
    }
    $this->studentModel->loadStudentCourseHome($data);
  }

  //function for the student assignment course submission
  private function stdnSub_assignment(&$data){
    if (!isset($data['id'])) {
      show_404();exit;
    }
    $data['student_ID'] = $this->studentModel->getStudent()->ID;
  }
  //function for showing the display field
  private function stdnRegistration(&$data){
    $ssid = $this->webSessionManager->getCurrentUserProp('ssid');
    if (isset($_GET['submit'])) {
      $this->studentModel->completeRegistration($ssid);
    }
    // display the registered courses for showing the information neeeded
    $data['registeredCourses']= $this->studentModel->getStudent()->getRegisteredCourses($ssid);
    $data['student']= $this->webSessionManager->isCurrentUserType('student');
  }
//section for publication information
  private function publicationPublication(&$data){
      $user = isset($data['id'])?$data['id']:'';
      $criteria = isset($_GET['q'])?$_GET['q']:'';
      $data['title']= empty($criteria)?"List of Publications":"List of Publications With Search keyword '".$criteria."'";
      // exit($criteria);
      loadClass($this->load,'publication');
      $data['publications']=$this->publication->searchPublication($criteria,$user);
      $data['userType'] = $this->webSessionManager->getCurrentUserProp('user_type').'/';
  }

  private function paymentFee_details(&$data){
    $this->paymentModel->loadPaymentFeeDetail($data);
  }

  private function payment($page,&$data){
    $this->load->model('paymentModel');
    if ($page=='revalidate') {
      return;
    }
   
    $exclusionArray = array('payment_pattern','report');
    $id = $data['id'];
    $userType = $this->webSessionManager->getCurrentUserProp('user_type');
    $data['userType']=$userType =='admin'?'':$userType;
    $nonAdmin= array('pay','amount_selection','receipt','payment_log');

    if (!in_array($page, $nonAdmin) &&  $userType!='admin' ) {
      show_404();exit;
    }
    if (in_array($page, $nonAdmin) && (empty($id) || $userType=='admin')) {
      redirect(base_url('auth/logout'));
    }
    if ($this->webSessionManager->getCurrentUserProp('accessType')!='feghas' && !in_array($page, array('fee_payment_report','bank_settlement','ledger')) && !in_array($page, $nonAdmin)) {
      show_404();exit;
    }
    if (in_array($page, $exclusionArray)) {
      return;
    }
    
    // print_r($this->webSessionManager->getCurrentUserProp('ID'));exit;
    $idCompusory = array('fee_details','payment_split','pay','payment_channel','amount_selection','payment_log','receipt','report','edit_payment_fee','edit_payment_pattern','commission_split');
    $this->load->model("webSessionManager");
    if (in_array($page, $idCompusory)) {
      if (empty($data['id'])) {
        show_404();exit;
      }
      return;
    }
    // $data['id']= $id;
    if ($userType!='admin') {
      return;
    }
   
  }
  private function paymentEdit_payment_pattern(&$data){
    loadClass($this->load,'payment_pattern');
    $this->payment_pattern->ID = $data['id'];
    $this->payment_pattern->load();
    $data['patternName'] = $this->payment_pattern->pattern_name;
  }

  private function paymentAmount_selection(&$data){
    if (!(isset($data['id']) || isset($data['_1']) || isset($data['_2']))) {
      exit('invalid payment parameters, please contact the administrator');
    }
    loadClass($this->load,'payment_fee');
    $this->payment_fee->ID = $data['id'];
    if (!$this->payment_fee->load()) {
      exit('invalid payment parameters, please contact the administrator');
    }
    loadClass($this->load,'student');
    $sid=$this->webSessionManager->getCurrentUserProp('ID');
    $this->student->ID=$sid;
    $totalPaid = $this->student->getTotalPaid($data['_1'],$data['_2']);
    $data['totalPaid']=$totalPaid;
    $this->student->load();
    $data['userType'].='/';
    $data['payment_name']=$this->payment_fee->payment_name;
    $data['totalAmount']=$this->payment_fee->amount;
    $data['hasPart']=count($this->payment_fee->payment_pattern) > 1;
    $pattern = $this->payment_fee->getPayablePattern($this->student->ID,$data['_1'],$data['_2'],$data['totalAmount'],$data['totalAmount']-$data['totalPaid']);
    if (!$pattern) {
      exit('error encountered , contact the adminstrator : NOPAT');
    }
    $data['payment_pattern']= $pattern;
    //set the session and the  total amount owed by the student
    $this->webSessionManager->setContent('ssid',$data['_1']);
     $this->webSessionManager->setContent('aid',$data['_1']);
    $this->webSessionManager->setContent('lid',$data['_2']);
    loadClass($this->load,'academic_session');
    $this->academic_session->ID = $data['_1'];
    $this->academic_session->load();
    $data['sss']=$this->academic_session->name;
    $this->webSessionManager->setContent('purpose','school fee');
  }
  private function paymentReport(&$data){
    $extraWhere ='';
    $qData = array();
    if (isset($id) && !empty($id)) {
      $extraWhere.=' and payment_fee_ID=?';
      $qData[]=$id;
    }
    if (isset($_GET['p'])) {
      $extraWhere.=' and payment_log.payment_pattern_ID=?';
      $qData[]=$_GET['p'];
    }
    if (isset($_GET['d'])) {
      $extraWhere.=' and date(time_paid)=?';
      $qData[]=$_GET['d'];
    }
    $query2 = "select stake_holder.name , sum(split_setting.amount) as amount from payment_log left join payment_pattern on payment_log.payment_pattern_ID = payment_pattern.ID left join payment_fee on payment_fee.ID = payment_pattern.payment_fee_ID left join split_setting on payment_pattern.ID = split_setting.payment_pattern_ID left join stake_holder on stake_holder.ID =split_setting.stake_holder_ID where payment_status=1 $extraWhere group by stake_holder.ID";

    $data['qData']=$qData;
    $data['extraWhere']=$extraWhere;
    $temp = $this->db->query($query2,$qData);
    $temp=$temp->result_array();
    $label = array();
    $vals=array();
    for ($i=0; $i < count($temp); $i++) { 
      $label[]="'{$temp[$i]['name']}'";
      $vals[]= $temp[$i]['amount'];
    }
    $data['labels']= $label;
    $data['values']= $vals;
    $this->load->model('queryHtmlTableModel');
  }
  private function paymentPayment_split(&$data){
    loadClass($this->load,'payment_fee');
    $payment = new Payment_fee();
    $payment->ID = $data['id'];

    $data['payment_pattern'] = $payment->payment_pattern;
  }


  private function admissionsAcc_letter(&$data)
  {
    $this->applicantModel->getAcceptanceFormInfo($data);
  }

  private function paymentRevalidate(&$data)
  {
    if ($this->webSessionManager->getCurrentUserProp('user_type')) {
       redirect(base_url('auth/logout'));exit;
    }
    $tid ='';
    if (isset($_GET['tid']) && $_GET['tid']) {
      $tid = $_GET['tid'];
    }
    else{
      show_404();
    }
    $this->paymentModel->revalidateTransaction($tid,$data);
  }

  private function paymentPay(&$data){
    $this->paymentModel->loadPayment($data); 
  }
  //function for performing payment fee edit
  private function paymentEdit_payment_fee(&$data){
    $id = $data['id'];
    $data['selected']= $this->paymentModel->getPaymentSelectedFee($id);
  }
  private function paymentPayment_log(&$data){
    $this->paymentModel->logPayment($data);
  }
  private function paymentReceipt(&$data){
    $this->paymentModel->buildReceipt($data);
  }
  //function for loading edit page for generalapplication
  function edit($model,$id){
    if ($this->webSessionManager->getCurrentUserProp('accessType')!='feghas') {
      show_404();
    }
    $ref = @$_SERVER['HTTP_REFERER'];
    if ($ref&&!startsWith($ref,base_url())) {
      show_404();
    }
    $this->webSessionManager->setFlashMessage('prev',$ref);
    $exceptionList= array('user','staff');//array('user','applicant','student','staff');
    if (empty($id) || in_array($model, $exceptionList)) {
      show_404();exit;
    }
    //validate the user first else remove the user from the view
    $data['userType']= $this->webSessionManager->getCurrentUserProp('user_type')=='admin'?'':$this->webSessionManager->getCurrentUserProp('user_type').'/';
    $data['model']=$model;
    $data['id']= $id;
     $data['pageTitle'] = "EKITI STATE UNIVERSITY | $model";
    $this->load->view('edit',$data);
  }
  
  function stdnCourseRegistration(&$data)
  {
    $this->studentModel->register($data);
  }

  private function stdnPayment()
  {
    $this->studentModel->processPayment();
  }

  private function admin($page,&$data){
   //custom operation needed to be performed on the admin is listed here.
    if (!$this->webSessionManager->isSessionActive()) {
      redirect('.');exit;
    }
    $userType = $this->webSessionManager->getCurrentUserProp('user_type');
    if ($userType!='admin') {
      header("Location:".base_url('auth/logout'));exit;
    }
    $accessType = $this->webSessionManager->getCurrentUserProp('accessType');
    $arr = array('profile','dashboard','changePassword');
    if ($accessType!='feghas' && !in_array($page, $arr)) {
      show_404();exit;
    }
    $this->load->model('adminModel');
  }
  private function adminViewTranscript(&$data){
    echo "got here jare";exit;
  }
  private function adminSchool(&$data){
    loadClass($this->load,'school');
    $all = $this->school->all($totalRow,false);
    $data['school'] = empty($all)?false:$all[0];
  }
  private function adminDashboard(&$data){
    //check that session is active
    $this->adminModel->loadDashboardContent($data);
  }
  function stdnExtraCourse(&$data)
  {
    $this->studentModel->registerExtraCourses($data);
  }

  function stdnPrintout(&$data)
  {
    $this->studentModel->regPrintout($data);
  }
  /*
  //depreceated
  function stdnRegisterCourses(&$data)
  {
    $this->studentModel->register($data);
  }

  function stdnRegistrations(&$data){
   $this->studentModel->loadRegistrations($data);
  }

  function stdnPreviewReg(&$data)
	{
    $this->studentModel->loadRegisteredCourses($data);
	}*/
  function stdnAccomodation(&$data)
  {
    $this->studentModel->loadAccomodationHistory($data);
  }

  private function stdnProfile(&$data)
  {
    echo "got here";exit;
    if (isset($data['id']) && $this->webSessionManager->getCurrentUserProp('user_type')!='student') {
      $this->studentModel->loadStudent($data['id']);
    }
    $this->studentModel->loadProfile($data);
  }

  private function  stdnDashboard(&$data){
    $this->studentModel->processDashboard($data);
  }
  private function hostel($page,&$data){
    $this->load->model('studentModel');
    $this->studentModel->loadStudent();
  }
  private function hostelOccupants(&$data)
  {
    # send the name of the room and the number
    loadClass($this->load,'hostel_room');
    $this->hostel_room->ID = $data['id'];
    $data['roomInfo'] = $this->hostel_room->getInfo();

  }
  /** student Hostel Section **/
  function hostelApply(&$data){
    //check that the registration has not been done before
     $this->load->model('studentModel');
     if ($this->studentModel->newRegistration()) {
       redirect(base_url('vc/stdn/accomodation'));
     }
    if($data['id']== 'submit'){
      $this->studentModel->submitHostelApplication($data);
      //get the accommodation payment fee
      loadClass($this->load,'accommodation');
      $session = $this->webSessionManager->getCurrentSession();
      $acc = $this->accommodation->getWhere(array('academic_session_ID'=>$session),$totalRow,0,NULL,false);
      if ($acc == false) {
        echo "an error occured please contact the administrator";exit;
      }
      $acc= $acc[0];
      $payment = $acc->payment_fee;
      if (!$payment || !($full = $payment->fullPayment)) {
        echo "an error occured please contact the administrator for solution";exit;
      }
      //redirect to the payment page
      redirect(base_url('vc/payment/pay/'.$full));
    }
    //REDIRECT TO A PAYMENT PAGE TO MAKE PAYMENT AND SUBMIT THE APPLICATION
    $this->studentModel->loadStudentAccomodation($data);
    if(!empty($data['stdnAccom'])){
      //redirect to the preview page
      redirect(base_url('vc/hostel/preview'));exit;
      // $this->hostelPreview($data);
    }
  }
  function hostelPreview(&$data)
  {
    // $this->load->model('studentModel');
    // $this->studentModel->previewAccomodation($data);
  }
  function hostelDetailedPreview(&$data)
  {
    $this->load->model('studentModel');
    $this->studentModel->loadAccomodationDetails($data);
  }

  /** Admin Hostel Section **/
  function hostelManageApplication(&$data){
    $this->load->model('adminModel');
    if($data['id']=="approve" || $data['id']=="reject"){
      $this->adminModel->hostelApproval($data);
    }
    elseif($data['id']=="approveAll" || $data['id']=="rejectAll"){
      $this->adminModel->approveAllHostelApplications($data);
    }
    $this->adminModel->loadHostelApplications($data);
  }

  function hostelAllocations(&$data){
    $this->load->model('adminModel');
    $this->adminModel->loadHostelAllocation($data);
  }
  //function for loading current session information for department lecture course
  function academic_contentCreate_department_lecture_course(&$data){
    loadClass($this->load,'academic_session');
    $this->academic_session->ID = $this->webSessionManager->getCurrentSession();
    $this->academic_session->load();
    $data['currentSession'] = $this->academic_session->name;
  }
  function hostelCreate(&$data){

  }

  function resetPassword($userType,$id)
  {
    $accepted = array("student",'applicant','admin');
    if (!in_array($userType, $accepted) || !is_numeric($id)) {
      exit("invalid operation");
    }
    $query = "update user set password = md5(username) where user_ID=? and user_type='$userType'";
    if ($this->db->query($query,array($id))) {
      exit("operation successfull");
    }
    else{
      exit("error occured during operation");
    }

  }
  //view and manage applications
  //add or create rooms
  //manage room defaulters
  
  }
?>
