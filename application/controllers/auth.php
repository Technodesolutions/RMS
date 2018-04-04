<?php 
/**
* 
*/
class Auth extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
	}

	function web(){
		$isAjax =  isset($_POST['isajax']) && $_POST['isajax']=='yes';
		if (isset($_POST['login_btn'])) {
			$username = $this->input->post('username',true);
			$password = $this->input->post('password',true);
			if (!isNotEmpty($username,$password)) {
				echo "empty field detected . please fill all required field and try again";
			}
			$array = array('username'=>$username,'password'=>md5($password),'status'=>1);
			$user = $this->user->getWhere($array,$count,0,null,false," order by field(user_type,'admin','lecturer','student','applicant')");
			if ($user==false) {
				if ($isAjax) {
					echo "invalid username or password";
					return;
				}
				else{
					redirect(base_url());
				}
				
			}
			else{
				$user = $user[0];
				$baseurl = base_url();
				$this->webSessionManager->saveCurrentUser($user,true);
				$baseurl.=$this->getUserPage($user);//'statics/sample';//redirect to the original dashboard page;
				$this->application_log->log('login','user logged in successfully');
				if ($isAjax) {
					$arr['status']=true;
					$arr['message']= $baseurl;
					echo  json_encode($arr);
					return;
				}else{
					redirect($baseurl);exit;
				}
			}
			// else{
			// 	$arr['status']=false;
			// 	$arr['message'] = 'invalid username or password';
			// 	echo json_encode($arr);exit;
			// }

		}
		else{
			if (!$isAjax) {
				show_404();
			}else{
				$array['status']= false;
				$array['an error occured thats all we know'];
				echo json_encode($array);
			}
			
		}
	}

	private function getUserPage($user){
		$link= array('student'=>'vc/stdn/dashboard','applicant'=>'vc/admissions/personal','lecturer'=>'vc/lect/dashboard','admin'=>'vc/admin/dashboard');
		$roleName = $user->user_type;
		return $link[$roleName];
	}

//function for sending post http request using curl
function sendPost($url,$post,&$errorMessage,$returnResult=false){
	$res = curl_init($url);
	curl_setopt($res, CURLOPT_POST,true);
	curl_setopt($res, CURLOPT_POSTFIELDS, $post);
	$certPath =str_replace( "application\helpers\MY_url_helper.php",'cacert.pem', __FILE__);
	curl_setopt($res, CURLOPT_CAINFO, $certPath);
	if ($returnResult) {
		curl_setopt($res, CURLOPT_RETURNTRANSFER, true);
	}
	$referer = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	curl_setopt($res, CURLOPT_REFERER, $referer);
	$result = curl_exec($res);
	$errorMessage = curl_error($res);
	curl_close($res);
	return $result;
}

	function mobile(){

	}
	function logout(){
		$link ='';
		$base = base_url();
		// if (isset($_GET['rdr'])) {
		// 	$link = 'vc/admissions/login';
		// }
		$this->webSessionManager->logout();
		$this->application_log->log('logout','user logs out');
		$path = $base.$link;
		header("location:$path");exit;
	}
//the login function for the applicant
	function applicantLogin(){
		//process the applicant login here and just move too the payment page.
		if (isset($_POST['login_btn'])) {
			unset($_POST['login_btn']);
			$empty = checkEmpty($_POST);
			if ($empty === false) {
				$username = $_POST['username'];
				$password = $_POST['password'];
				$query = "select * from pre_admission where email =?";
				$result = query($this->db,$query,array($username));
				if (!empty($result) && crypt($password,$result[0]['password'])==$result[0]['password']) {
					$result['direct_application']='yes';
					$this->webSessionManager->setArrayContent($result[0]);
					$link = base_url('vc/admissions/payment');
					redirect($link);
				}
				else{
					echo " invalid login details";
				}
			}
			else{
				echo "$empty cannot be empty";
			}

		}
		else{
			show_404();
		}
	}
}
 ?>