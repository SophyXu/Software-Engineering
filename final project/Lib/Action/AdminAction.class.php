<?php
/****************************************************
 *         Author: Beihai - changyibeihai@gmail.com
 *  Last modified: 2014/1/31
 *       Filename: AdminAction.class.php
 *    Description: Action to do the management
 *****************************************************/
class AdminAction extends Action {
	 // function __construct() {
	 // 		parent::__construct();
  //            $cookies = new CookieModel();
  //            $cookie_id = $cookies -> read();
  //            $Form = M('userinfo');
  //            $condition['stuid'] = $cookie_id;
  //          	 $data = $Form->where($condition)->find();
  //         	 if (! $data['manager']) $this->redirect('/');
  //            if (! $cookie_id) $this->redirect('/');
  //        }
	static public function readHeader($ch, $header) {
		$back = strlen($header);
		$header = mb_convert_encoding($header, "UTF-8","GBK");
		echo $header;
		return $back;
	}   
	// static public function readHeader1($ch, $header) {
	// 	global $addnew;
	// 	$back = strlen($header);
	// 	$header = mb_convert_encoding($header, "UTF-8","GBK");
	// 	preg_match('/\bdepName\b.*[^\s]/i',$header,$result);
	// 	if ($result) {		
	// 		$addnew['depName']  =  substr($result[0],9);		
	// 		var_dump($addnew);
	// 	}
	// 	return $back;
	// }
	/*Function index
    当用户具有管理员权限的时候输出管理页面
    */
	public function index()
		{
		
			//if ($data['manager'] ==1) 
				$this->display();
		}
    public function dish()
		{
			//if ($data['manager'] ==1) 
			    		$this->display();
		}
	public function sender()
		{
			//if ($data['manager'] ==1) 
			    		$this->display();
		}
    public function old_order()
		{
			//if ($data['manager'] ==1) 
			    		$this->display();
		}
    public function unsend_order()
		{
		//	if ($data['manager'] ==1) 
			    		$this->display();
		}
	public function area()
		{
			//if ($data['manager'] ==1) 
			    		$this->display();
		}
    public function setUser()
		{
			//if ($data['manager'] ==1) 
			    		$this->display();
		}   
	 public function register()
	 {
	 	//     $url="http://elife.zju.edu.cn:8050/account/TakeMeSignInDetail";
			// $data=array(
			// 	'signtype'=>"UIAS",
			// 		'username'=>"3120000019",
			// 		'password'=>""
			// 			);
			// $ch=curl_init();
			// curl_setopt($ch, CURLOPT_URL, $url);
			// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			// curl_setopt($ch, CURLOPT_POST, 1);
			// //curl_setopt($ch, CURLOPT_HTTPHEADER, $header);	
			// curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			// $request=curl_exec($ch);
			// $request=substr($request, 21);			
		 // 	var_dump($request);					
			//setcookie("iPlanetDirectoryPro",$request,time()+360000*24,"/",".zju.edu.cn");
		//	setcookie("iPlanetDirectoryPro",$request,time()+360000*24,"/fastfood/index.php");
		var_dump($_COOKIE['iPlanetDirectoryPro']);
			$url="http://elife.zju.edu.cn:8050/account/TakeMeRequest";
		//	$url="http://localhost/fastfood/index.php/Admin/register1";
			$da=array(
				'iPlanetDirectoryPro'=> urlencode($_COOKIE['iPlanetDirectoryPro'])
				);
			$cookie = "iPlanetDirectoryPro=".urlencode($_COOKIE['iPlanetDirectoryPro']);
			//var_dump($cookie);
			$ch=curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);				
			curl_setopt($ch, CURLOPT_COOKIE, $cookie);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($da));
			$request=curl_exec($ch);	
			$request=substr($request, 21);
			$request = json_encode($request);
			var_dump($request);
			// $url="http://elife.zju.edu.cn:8050/Account/SignOff";
			// //	$url="http://localhost/astfood/index.php/Admin/register1";
			// 	$da=array(
			// 		'iPlanetDirectoryPro'=> urlencode($request)
			// 		);
			// 	$cookie = "iPlanetDirectoryPro=".urlencode($request);
			// 	//var_dump($cookie);
			// 	$ch=curl_init();
			// 	curl_setopt($ch, CURLOPT_URL, $url);
			// 	curl_setopt($ch, CURLOPT_POST, 1);
			// 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			// 	curl_setopt($ch, CURLOPT_HEADER, 0);				
			// 	curl_setopt($ch, CURLOPT_COOKIE, $cookie);
			// 	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($da));
			// 	$request=curl_exec($ch);
			// var_dump($request);

	 }  
	 public function showID()
		{
			$url="http://zuinfo.zju.edu.cn:8080/AMWebService/UserProfile";
			$header[] = "appUid: publictest";   
			$header[] = "appPwd: zjuinfo"; 
			$header[] = "id: "; 
			$header[] = "type: 1"; 
			$ch=curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			curl_setopt($ch, CURLOPT_HEADERFUNCTION, array(self, 'readHeader'));
			$request = curl_exec($ch);
			var_dump($request);
		}   
		 public function showForm()
		{			
			$form = M('orderinfo');
			$condition['pay_status'] = 1;
            $data = $form->where($condition)->select();
            for($i = 0; $i < count($data); $i++)
            {
            $new[$i]['username'] = $data[$i]['username'];
            $new[$i]['food'] = $data[$i]['food'];	
            $new[$i]['area'] = $data[$i]['area'];
            $new[$i]['dep'] = $data[$i]['dep'];
            $new[$i]['address'] = $data[$i]['address'];
            $new[$i]['ordertime'] = $data[$i]['ordertime'];
            $new[$i]['number'] = $data[$i]['number'];
            $new[$i]['amount'] = $data[$i]['amount'];
            $new[$i]['phone'] = $data[$i]['phone'];
            $new[$i]['status'] = $data[$i]['status'];

            if ($data[$i]['amount'])
            	$new[$i]['price'] = (float)$data[$i]['price']/$data[$i]['amount'];
            }
            $new = json_encode($new);
    		echo $new;
		}
		 public function showMobileForm()
		{	
			$form = M('orderinfo');
			$condition['pay_status'] = 1;
			date_default_timezone_set("Asia/Shanghai");
            $condition['ordertime'] = array('like','%'.date('Y-m-d').'%');
            $data = $form->where($condition)->select();
            for($i = 0; $i < count($data); $i++)
            {
            $new[$i]['username'] = $data[$i]['username'];
            $new[$i]['food'] = $data[$i]['food'];	
            $new[$i]['area'] = $data[$i]['area'];
            $new[$i]['dep'] = $data[$i]['dep'];
            $new[$i]['address'] = $data[$i]['address'];
            $new[$i]['ordertime'] = $data[$i]['ordertime'];
            $new[$i]['number'] = $data[$i]['number'];
            $new[$i]['amount'] = $data[$i]['amount'];
            $new[$i]['phone'] = $data[$i]['phone'];
            $new[$i]['status'] = $data[$i]['status'];
            if ($data[$i]['amount'])
            	$new[$i]['price'] = (float)$data[$i]['price']/$data[$i]['amount'];
            }
            $new = json_encode($new);
			$form1 = M('morderinfo');
			$condition1['ordertime'] = array('like','%'.date('Y-m-d').'%');
            $data = $form1->where($condition1)->select();
            $data = json_encode($data);
            $new = '{"pc":'.$new.',"mobile":'.$data.'}';
    		echo $new;
		}
		 public function showDateForm()
		{			
			/////TODOTODO  月份少了一个0 字段名字不能带中文 下面的//要解开
			$date = $_REQUEST['date'];
			$pay = $_REQUEST['pay'];
			$form = M('orderinfo');
			$condition['pay_status'] = 1;
			if ($pay == '签单' || $pay == '电子支付') $condition['pay'] = $pay;
			$condition['ordertime'] = array('like','%'.$date.'%');
            $data = $form->where($condition)->Distinct('true')->field('dep')->select();
            for($i = 0; $i < count($data); $i++)
            {
            $new[$i]['name'] = $data[$i]['dep'];	        
            $new[$i]['8'] = 0;
            $new[$i]['10'] = 0;
            $new[$i]['15'] = 0;
            $new[$i]['20'] = 0;
            $new[$i]['30'] = 0;
            $condition_price['pay_status'] = 1;
            if ($pay == '签单' || $pay == '电子支付') $condition['pay'] = $pay;
            $condition_price['dep'] = $data[$i]['dep'];			
            $condition_price['ordertime'] = array('like','%'.$date.'%');
	        $all = $form->where($condition_price)->select();    
            for ($j = 0;$j < count($all); $j++)
            {
            	if ($all[$j]['amount'] == 0) continue;
            	if ($all[$j]['price'] / $all[$j]['amount'] == 8) 
            		$new[$i]['8'] = $new[$i]['8'] + $all[$j]['amount']; 
            	if ($all[$j]['price'] / $all[$j]['amount'] == 10) 
            		$new[$i]['10'] = $new[$i]['10'] + $all[$j]['amount']; 
            	if ($all[$j]['price'] / $all[$j]['amount'] == 15) 
            		$new[$i]['15'] = $new[$i]['15'] + $all[$j]['amount']; 
            	if ($all[$j]['price'] / $all[$j]['amount'] == 20) 
            		$new[$i]['20'] = $new[$i]['20'] + $all[$j]['amount']; 
            	if ($all[$j]['price'] / $all[$j]['amount'] == 30) 
            		$new[$i]['30'] = $new[$i]['30'] + $all[$j]['amount']; 
            }
            }
            $new = json_encode($new);
    		echo $new;
		}    
		//传入查询的时间区间，部门，销账显示，输出符合要求的订单详情和总金额
		 public function showData()
		{			
			$date1 = $_REQUEST['date1'];//'2014-01-01';
			$date2 = $_REQUEST['date2'];//'2014-05-01'; 
			$dept = $_REQUEST['dep'];//$_REQUEST['dept'];
			$finish = $_REQUEST['finish'];//传入显示所有还是只显示没销账的 1为只显示没销账的	
			$form = M('orderinfo');
			$condition['pay_status'] = 1;
			$condition['pay'] = '签单';
			if ($finish == 'true') $condition['finish'] = 0;
		    $condition['dep'] = $dept;
			$condition['ordertime'] = array('between',array($date1,$date2));
            $data = $form->where($condition)->order('stuid desc')->select();
            $sum = $form->where($condition)->sum('price');         
            $data[count($data)] = round($sum,2);   
            $new = json_encode($data);//详单
    		echo $new;
		}  
		 public function showDep()
		{			
			$date1 = $_REQUEST['date1'];//'2014-01-01';
			$date2 = $_REQUEST['date2'];//'2014-05-01'; 
			$form = M('orderinfo');
			$condition['pay_status'] = 1;
			$condition['pay'] = '签单';
			$condition['finish'] = 0;
			$condition['ordertime'] = array('between',array($date1,$date2));
			if ($date1 == $date2) 
				$condition['ordertime'] = array('like','%'.$date1.'%');
            $data = $form->Distinct(true)->field(array('dep','sum(price)'=>'sum'))->group('dep')->where($condition)->select();
           // var_dump($data);
            $new = json_encode($data);
    		echo $new;
		}
		
		public function addPicture(){
 		     $cookies = new CookieModel();
            $cookie_id = $cookies -> read();
            if (! $cookie_id) $this->redirect('/');
	    	////////////////////////////////////////////////////////
			  if ($_FILES["picture"]["error"] > 0)
			    {
			    	$picname = '';
			   // echo "Return Code: " . $_FILES["picture"]["error"] . "<br />";
			    }
			  else
			    {
				$picname =  "upload/banner".preg_replace('/^.*(\..*)$/', '$1', $_FILES["picture"]["name"]);
				move_uploaded_file($_FILES["picture"]["tmp_name"],$picname);
			    }
			$this->redirect('/index.php/Admin/banner');
				//else {$this->error('添加失败');}
    }
}
?>
