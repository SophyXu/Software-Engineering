 <?php

/****************************************************
 *         Author: Beihai - changyibeihai@gmail.com
 *  Last modified: 2013/10/27
 *       Filename: UserAction.class.php
 *    Description: Action to show the userinfo and add user.
 ****************************************************
*/
    $data  = null;
    $addnew = null;
    $extends = null;
    class UserAction extends Action {

	// static public function readHeader($ch, $header) {
	// 	global $data;
	// 	global $addnew;
	// 	$back = strlen($header);
	// 	$header = mb_convert_encoding($header, "UTF-8","GBK");
	// 	preg_match('/\bname\b.*[^\s]/i',$header,$result);			
	// 	preg_match('/\bdepName\b.*[^\s]/i',$header,$result1);			
	// 	if ($result) {		
	// 		$data['name'] =  substr($result[0],6);
	// 		$addnew['name'] = $data['name'];
	// 	}
	// 	if ($result1) {
	// 		$data['dep'] =  substr($result1[0],9);
	// 		$addnew['dep'] = $data['dep'];
	// 	}
	// 	return $back;
	// }
	// static public function readHeader1($ch, $header) {
	// 	$back = strlen($header);
	// 	$header = mb_convert_encoding($header, "UTF-8","GBK");
	// 	var_dump($header);
	// 	return $back;
	// }
	static public function readHeader2($ch, $header) {
		global $extends;
		$back = strlen($header);
		$header = mb_convert_encoding($header, "UTF-8","GBK");
		preg_match('/\bname\b.*[^\s]/i',$header,$result);			
		preg_match('/\bdepName\b.*[^\s]/i',$header,$result1);			
		if ($result) {		
			$extends['name'] =  substr($result[0],6);
		}
		if ($result1) {
			$extends['dep'] =  substr($result1[0],9);
		}
		return $back;
	}

	/*
	Login 函数用来登录
	传入用户的学号跟密码
	如果成功则返回状态status==1 用户的姓名 并加载cookie
	如果失败则返回状态status==0 不加载cookie
	*/

    public function Login(){
	    	$name = $_REQUEST['username'];
    		$password = $_REQUEST['password'];
    		$method = $_REQUEST['method'];
            if(!$iPlanetDirectoryPro){
                $iPlanetDirectoryPro = $_COOKIE["iPlanetDirectoryPro"];
            }
    		if (!$iPlanetDirectoryPro){

				$url="http://elife.zju.edu.cn:8050/account/TakeMeSignInDetail";
				if ($method == "（学号或职工号）")
				$send=array(
					'signtype'=>"UIAS",
					'username'=>$name,//"",
					'password'=>$password//""
							);
				else if ($method == "（校园卡号）")
				$send=array(
					'signtype'=>"SynCard",
					'username'=>$name,//"",
					'password'=>$password//""
							);
				$ch=curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_POST, 1);
				//curl_setopt($ch, CURLOPT_HTTPHEADER, $header);	
				curl_setopt($ch, CURLOPT_POSTFIELDS, $send);
				$request=curl_exec($ch);				
				$sign = $request[0];  //登录标志  标志为0则登录成功
  				$ssid = substr($request, 21);
				//$form = M("logininfo");
				// $condition['username'] = $name;
				// $addnew = $form->where($condition)->find();
				// $addnew['username']  = $name;
				// $addnew['ssid']  = $request;
				$data['status'] = 0;
				if (! $sign){		
					$result['ssid'] = $ssid;
					$data['status'] = 1;
					////////////////////get detail//////////////////////	
					$url="http://elife.zju.edu.cn:8050/account/TakeMeRequest";
					$da=array(
						'iPlanetDirectoryPro'=> urlencode($ssid)
						);
					$cookie = "iPlanetDirectoryPro=".urlencode($ssid);
					$ch=curl_init();
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_HEADER, 0);				
					curl_setopt($ch, CURLOPT_COOKIE, $cookie);
					curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($da));
					$detail = curl_exec($ch);	
					//$detail = json_encode($detail);	
					//echo($detail);
					preg_match('/<sno>(\w+)/i',$detail,$result_sno);
					$result['username'] =  substr($result_sno[0],5);	
					preg_match('/<cardno>(\w+)/i',$detail,$result_cardno);
					$result['cardid'] =  substr($result_cardno[0],8);	
					preg_match('/<name.*?</',$detail,$result_name);
					$result['name'] = substr($result_name[0],0,strlen($result_name[0])-1);
					$result['name'] = substr($result['name'],6); 
					//$result['name'] = json_decode($result['name']);	
		 			$da=array(
		                'cardno'=>$result['cardid']
		                        );
		            $url = "http://elife.zju.edu.cn:8050/Account/GetCardInfo";
		            $ch=curl_init();
		            curl_setopt($ch, CURLOPT_URL, $url);
		            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		            curl_setopt($ch, CURLOPT_POST, 1);
		            curl_setopt($ch, CURLOPT_POSTFIELDS, $da);
		            $cardinfo = curl_exec($ch);
		            $cardinfo = json_decode($cardinfo,true);
		            $result['dep'] = $cardinfo['obj']['deptname'];
		            $result['cardmacid'] = $cardinfo['obj']['cardid'];
		            $data['name'] = $result['name'];
			 		$data['dep'] = $result['dep'];
			 		$form = M('logininfo');
		            $condition['username'] = $result['username'];
					$form_user = M('userinfo');
		            $condition_user['stuid'] = $result['username'];
		            $data_user = $form_user -> where($condition_user) -> find();
		         	$form->add($result,$options=array(),$replace=true);
					//if ($form->where($condition)->find()) $form->save($result);
		         	//else $form->add($result);
			        if ($data_user['manager'])
			        {
				        setcookie("user",$result['username'],time()+315360000,"/");				
						//setcookie("user",$result['username'],time()+315360000,"/",".zju.edu.cn");
						setcookie("iPlanetDirectoryPro",$ssid,time()+315360000,"/");
						//setcookie("iPlanetDirectoryPro",$ssid,time()+315360000,"/",".zju.edu.cn");
			        }
			        else
			        {
						setcookie("user",$result['username'],time()+1800,"/");					
						//setcookie("user",$result['username'],time()+1800,"/",".zju.edu.cn");
						setcookie("iPlanetDirectoryPro",$ssid,time()+1800,"/");
						//setcookie("iPlanetDirectoryPro",$ssid,time()+1800,"/",".zju.edu.cn");
					}
				}	
			}
			else
			{
			$data['status'] = 0;
			$form = M("logininfo");					
			$condition['ssid'] = $iPlanetDirectoryPro;			
			if ($result = $form->where($condition)->find()) {
			 	$data['status'] = 1;
			 	$data['name'] = $result['name'];
			 	$data['dep'] = $result['dep'];//NEW
				}
			else
				{
				setcookie("user","",time()-1,"/");					
				//setcookie("user","",time()-1,"/",".zju.edu.cn");		
				setcookie("iPlanetDirectoryPro","",time()-1,"/");			
				//setcookie("iPlanetDirectoryPro","",time()-1,"/",".zju.edu.cn");
				}
			}
			
     	$data = json_encode($data);
     	echo $data;
    }
    /*
    Logout 用来注销登录
    */
    
    public function Logout(){
    		$request = $_COOKIE['iPlanetDirectoryPro'];
        //		setcookie("user",0,time()-1,"/");	
        //	var_dump($request);
        	$url="http://zuec.zju.edu.cn/synpay/login/exit";	
			$da=array(
				'iPlanetDirectoryPro'=> urlencode($request)
				);
			$cookie = "iPlanetDirectoryPro=".urlencode($request);
			//var_dump($cookie);
			$ch=curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);				
			curl_setopt($ch, CURLOPT_COOKIE, $cookie);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($da));
			$request=curl_exec($ch);
			setcookie("user","",time()-1,"/");					
			//setcookie("user","",time()-1,"/",".zju.edu.cn");	
			setcookie("iPlanetDirectoryPro","",time()-1,"/");					
			//setcookie("iPlanetDirectoryPro","",time()-1,"/",".zju.edu.cn");
    }
   
    /*
    addUser用来添加用户权限 用stuid来分别用户 manager:管理权限 white:白名单 black:黑名单
    */
 	public function addUser(){
 		global $extends;
 		function __construct() {
			parent::__construct();
            $cookies = new CookieModel();
            $cookie_id = $cookies -> read();
            $Form = M('userinfo');
            $condition_in['stuid'] = $cookie_id;
         	$data = $Form->where($condition_in)->find();
         	if (! $data['manager']) $this->redirect('/');
            if (! $cookie_id) $this->redirect('/');
        }
	    	$stuid =  $_REQUEST['stuid'];
	    	$manager = "" || $_REQUEST['manager'];
	    	$white = "" || $_REQUEST['white'];
	    	$black = "" || $_REQUEST['black'];
	    	$form = M("userinfo");
	    	$condition['stuid'] = $stuid;
	    	$data = $form->where($condition)->find();
    		$url="http://zuinfo.zju.edu.cn:8080/AMWebService/UserProfile";
			$header[] = "appUid: publictest";   
			$header[] = "appPwd: zjuinfo"; 
			$header[] = "id: ".$stuid	;
			$header[] = "type: 1"; 
			$ch=curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			curl_setopt($ch, CURLOPT_HEADERFUNCTION, array(self, 'readHeader2'));
			$request = curl_exec($ch);
			if ($manager) 
				{					
					$data['stuid'] = $stuid;
					$data['manager'] = $manager;
					$data['name'] = $extends['name'];
					$data['dep'] = $extends['dep'];
						//var_dump($data);
					if (! $data['id']) $form->add($data);
						else $form->save($data);
					$this->redirect('/index.php/Admin/manager');			
				}
			if ($white)
				{					
					$data['stuid'] = $stuid;
					$data['white'] = $white;
					$data['name'] = $extends['name'];
					$data['dep'] = $extends['dep'];		
					if (! $data['id']) $form->add($data);
						else $form->save($data);
					$this->redirect('/index.php/Admin/white');
				}
			if  ($black)
				{					
					$data['stuid'] = $stuid;
					$data['black'] = $black;
					$data['name'] = $extends['name'];
					$data['dep'] = $extends['dep'];		
					if (! $data['id']) $form->add($data);
						else $form->save($data);
					$this->redirect('/index.php/Admin/black');
				}
				//else {$this->error('添加失败');}
    }

    /*
    modifyUser用来修改用户权限 传入在数据库里用户的id来分别用户  再传入用户的部门dep进行修改
    */
	public function modifyUser(){
       function __construct() {
			parent::__construct();
            $cookies = new CookieModel();
            $cookie_id = $cookies -> read();
            $Form = M('userinfo');
            $condition_in['stuid'] = $cookie_id;
         	$data = $Form->where($condition_in)->find();
         	if (! $data['manager']) $this->redirect('/');
            if (! $cookie_id) $this->redirect('/');
        }
		$id = $_REQUEST['id'];		
		$dep = $_REQUEST['dep'];
		$form = M('userinfo');
    	$condition['id'] = $id;
    	$data = $form->where($condition)->find();
        $data['id'] = $id;
        $data['dep'] = $dep;
		$result = $form->save($data);
		$this->redirect('/index.php/Admin/white');
						
		}

// Test For SmilENow, All pages

	public function CheckAdmin(){
        $data['result']=1;
        $data['type']="系统管理员";
        $data = json_encode($data);
        echo($data);
    }   

    public function ShowAdminInfo(){
    	$ret['managerID'] = "SmilENow_test_managerID";
    	$ret['manager_name'] = "SmilENow_test_manager_name";
    	$ret['email'] = "SmilENow_test_email";
    	$ret['address'] = "SmilENow_test_address";
    	$ret['phone_number'] = "SmilENow_test_phone_number";
    	$ret['manager_type'] = "SmilENow_test_manager_type";
		$ret = json_encode($ret);
        echo($ret);
    }

    public function ShowChangePWD(){
    	$ret['managerID'] = "SmilENow_test_managerID";
    	$ret['email'] = "SmilENow_test_email";
		$ret = json_encode($ret);
        echo($ret);
    }

    public function UpdatedAdminPWD(){
        $data['result']=1;
        $data = json_encode($data);
        echo($data);
    }

    public function ShowEditInfo(){
    	$ret['managerID'] = "SmilENow_test_managerID ||  For EditInfo";
    	$ret['manager_name'] = "SmilENow_test_manager_name ||  For EditInfo";
    	$ret['true_name'] = "SmilENow_test_true_name ||  For EditInfo";
    	$ret['email'] = "SmilENow_test_email ||  For EditInfo";
    	$ret['address'] = "SmilENow_test_address ||  For EditInfo";
    	$ret['phone_number'] = "SmilENow_test_phone_number ||  For EditInfo";
    	$ret['manager_type'] = "SmilENow_test_manager_type ||  For EditInfo";
		$ret = json_encode($ret);
        echo($ret);
    }

    public function UpdatedAdminInfo(){
        $data['result']=1;
        $data = json_encode($data);
        echo($data);
    }

    public function ShowManager(){
    	$ret[1][managerId] = "001";
    	$ret[1][manager_name] = "SmilENow_test_ShowManager_Name1";
    	$ret[1][password] = "SmilENow_test_ShowManager_PWD1";
    	$ret[2][managerId] = "002";
    	$ret[2][manager_name] = "SmilENow_test_ShowManager_Name2";
    	$ret[2][password] = "SmilENow_test_ShowManager_PWD2";
    	$ret[3][managerId] = "003";
    	$ret[3][manager_name] = "SmilENow_test_ShowManager_Name3";
    	$ret[3][password] = "SmilENow_test_ShowManager_PWD3";
    	$ret = json_encode($ret);
        echo($ret);
    }

    public function DelManager(){
    	$data['result']=1;
        $data = json_encode($data);
        echo($data);
    }

    public function AddManager(){
    	$data['result']=1;
        $data = json_encode($data);
        echo($data);
    }

    public function ShowYuDingManager(){
    	$ret[1][managerId] = "001";
    	$ret[1][manager_name] = "SmilENow_test_Yuding_Name1";
    	$ret[1][password] = "SmilENow_test_ShowManager_PWD1";
    	$ret[2][managerId] = "002";
    	$ret[2][manager_name] = "SmilENow_test_YuDing_Name2";
    	$ret[2][password] = "SmilENow_test_ShowManager_PWD2";
    	$ret[3][managerId] = "003";
    	$ret[3][manager_name] = "SmilENow_test_YuDing_Name3";
    	$ret[3][password] = "SmilENow_test_ShowManager_PWD3";
    	$ret = json_encode($ret);
        echo($ret);
    }

    public function ShowUser(){
    	$ret[1][user_id] = "001";
    	$ret[1][user_name] = "SmilENow_test_User_Name1";
    	$ret[1][user_type] = "SmilENow_test_Type1";
    	$ret[1][user_valid] = "Y";
    	$ret[2][user_id] = "002";
    	$ret[2][user_name] = "SmilENow_test_User_Name2";
    	$ret[2][user_type] = "SmilENow_test_Type2";
    	$ret[2][user_valid] = "Y";
    	$ret[3][user_id] = "003";
    	$ret[3][user_name] = "SmilENow_test_User_Name3";
    	$ret[3][user_type] = "SmilENow_test_Type3";
    	$ret[3][user_valid] = "N";
    	$ret = json_encode($ret);
        echo($ret);
    }

    public function ValidUser(){
    	$data['result']=1;
        $data = json_encode($data);
        echo($data);
    }

    public function ChangeNormal(){
    	$data['result']=1;
        $data = json_encode($data);
        echo($data);
    }

    public function ChangeVIP(){
    	$data['result']=1;
        $data = json_encode($data);
        echo($data);
    }

    public function showWhiteList(){
    	$ret[1][user_truename] = "SmilENow_test_TrueName1";
    	$ret[1][user_type] = "SmilENow_test_Type1";
    	$ret[1][user_id] = "001";
    	$ret = json_encode($ret);
        echo($ret);
    }

    public function deleteWhiteList(){
    	$data['result']=1;
        $data = json_encode($data);
        echo($data);
    }

    public function addWhiteList(){
    	$data['result']=1;
        $data = json_encode($data);
        echo($data);
    }

    public function showBlackList(){
    	$ret[1][user_truename] = "SmilENow_test_TrueName1";
    	$ret[1][user_type] = "SmilENow_test_Type1";
    	$ret[1][user_id] = "001";
    	$ret[2][user_truename] = "SmilENow_test_TrueName2";
    	$ret[2][user_type] = "SmilENow_test_Type2";
    	$ret[2][user_id] = "002";
    	$ret = json_encode($ret);
        echo($ret);
    }

    public function deleteBlackList(){
    	$data['result']=1;
        $data = json_encode($data);
        echo($data);
    }

    public function addBlackList(){
    	$data['result']=1;
        $data = json_encode($data);
        echo($data);
    }

    public function ShowHotel(){
    	$ret[1][orderID] = "849302";
    	$ret[1][hotelName] = "北京五洲大酒店";
    	$ret[1][hotelLocation] = "北京朝阳区安定门外北辰东路8号";
    	$ret[1][roomType] = "商务间";
    	$ret[1][checkInTime] = "2015-7-23";
    	$ret[1][checkOutTime] = "2015-7-25";
    	$ret[1][price] = 1240;

    	$ret[2][orderID] = "302864";
    	$ret[2][hotelName] = "杭州浙江饭店";
    	$ret[2][hotelLocation] = "杭州市下城区延安路447号";
    	$ret[2][roomType] = "行政套房";
    	$ret[2][checkInTime] = "2015-6-11";
    	$ret[2][checkOutTime] = "2015-6-12";
    	$ret[2][price] = 732;

    	$ret[3][orderID] = "694038";
    	$ret[3][hotelName] = "杭州蜗牛酒店";
    	$ret[3][hotelLocation] = "杭州玉古路青芝坞113号";
    	$ret[3][roomType] = "大床房";
    	$ret[3][checkInTime] = "2015-6-18";
    	$ret[3][checkOutTime] = "2015-6-19";
    	$ret[3][price] = 281;
    	$ret = json_encode($ret);
        echo($ret);
    }

    public function deleteHotel(){
    	$data['result']=1;
        $data = json_encode($data);
        echo($data);
    }

    public function showFlight(){
    	$ret[1][orderID] = "123904";
    	$ret[1][flightID] = "KN5911";
    	$ret[1][flightCompanyName] = "联合航空";
    	$ret[1][planeType] = "波音737(中)";
    	$ret[1][takeOffTime] = "13:00";
    	$ret[1][landTime] = "17:00";
    	$ret[1][departure] = 北京;
    	$ret[1][destination] = 深圳;
    	$ret[1][price] = 833;

    	$ret[2][orderID] = "930284";
    	$ret[2][flightID] = "CA4612";
    	$ret[2][flightCompanyName] = "中国国航";
    	$ret[2][planeType] = "波音737(中)";
    	$ret[2][takeOffTime] = "11:45";
    	$ret[2][landTime] = "18:00";
    	$ret[2][departure] = 乌鲁木齐;
    	$ret[2][destination] = 青岛;
    	$ret[2][price] = 1320;

    	$ret[3][orderID] = "505843";
    	$ret[3][flightID] = "HU7047";
    	$ret[3][flightCompanyName] = "海南航空";
    	$ret[3][planeType] = "波音737(中)";
    	$ret[3][takeOffTime] = "07:10";
    	$ret[3][landTime] = "08:50";
    	$ret[3][departure] = 海口;
    	$ret[3][destination] = 厦门;
    	$ret[3][price] = 495;

    	$ret = json_encode($ret);
        echo($ret);
    }

    public function deleteFlight(){
    	$data['result']=1;
        $data = json_encode($data);
        echo($data);
    }

// End of All tests

      /*
    deleteUser用来删除用户 传入在数据库里用户的id来分别用户  stuid:用户学号 manager:管理权限 white:白名单 black:黑名单
    */

    public function deleteUser(){
    	function __construct() {
			parent::__construct();
            $cookies = new CookieModel();
            $cookie_id = $cookies -> read();
            $Form = M('userinfo');
            $condition_in['stuid'] = $cookie_id;
         	$data = $Form->where($condition_in)->find();
         	if (! $data['manager']) $this->redirect('/');
            if (! $cookie_id) $this->redirect('/');
        }
       	    $id =  $_REQUEST['id'];
	    	$manager = "" || $_REQUEST['manager'];
	    	$white = "" || $_REQUEST['white'];
	    	$black = "" || $_REQUEST['black'];
	    	//var_dump($manager);
	    	$form = M("userinfo");
	    	$condition['id'] = $id;
	    	$data = $form->where($condition)->find();
			if ($manager) 
				{					
					$data['manager'] = 0;					
					$form->save($data);
					$this->redirect('/index.php/Admin/manager');			
				}
			if ($white)
				{					
					$data['white'] = 0;
				    $form->save($data);
					$this->redirect('/index.php/Admin/white');
				}
			if  ($black)
				{					
					$data['black'] = 0;
					$form->save($data);
					$this->redirect('/index.php/Admin/black');
				}
    }  
 
}
?>
