<?php
class PayAction extends Action {
	// function __construct() {			
	// 		parent::__construct();
 //            $cookies = new CookieModel();
 //            $cookie_id = $cookies -> read();
 //            if (! $cookie_id) $this->redirect('/');
 //        }
    public function request(){
         $a = $_POST['trade_resp'];
         $a = base64_decode($a);
         preg_match('/\bstatus_code\b.*[\d]{4}/i',$a,$result);          
         $succ =  substr($result[0],12);   
         if ($succ != '0000') return;
         $number = substr($_POST['trade_jnl'],2);
    	 $form =M("orderinfo");
         $form_jnl = M('jnlinfo');         
         $conditon_jn['first'] = $number;
         $total_str = $form_jnl -> where($conditon_jn) -> find(); 
         $str = $total_str['total'];
         $total = split (',', $str);
         for ($i = 0; $i < count($total); $i++)
         {
    	   $condition['number'] = $total[$i];
    	   $data = $form ->where($condition)->find();
    	   $data['pay_status'] = 1;   
           $form_food = M("foodinfo");
           $condition_food['name'] = $data['food'];
           $data_food = $form_food->where($condition_food)->find();
           $data_food['amount'] = $data_food['amount'] - $data['amount'];      
           $form -> save($data);
           $form_food -> save($data_food);
         }
         /*$form = M("request");        
         $postdata = file_get_contents("php://input");
         $addnew['pay_acc'] = $postdata;
         $addnew['batch_id'] =" 8";
         $addnew['amt'] = $_POST['ret_msg'];
         $addnew['process_date'] = $_POST['trade_resp'];
         $addnew['status_code'] = $total;
         $addnew['status_desc'] = $number;
         $result = $form->add($addnew);*/
    }

	public function pay()
		{
			//https://zuec.zju.edu.cn:8443/synpay/web/doPay
			$form = M("logininfo");		
			$condition['ssid'] = $_COOKIE['iPlanetDirectoryPro'];
			$data = $form->where($condition)->find();
			$new['app_id'] = 'ZDYS';
	    	$new['user_name'] = $data['name'];
	    	$new['user_id'] = $data['username'];
	    	date_default_timezone_set("Asia/Shanghai"); 
	    	$ordertime = date('Y-m-d H:i:s');
            $new['trade_date'] = date('YmdHis');
            $search = date('Ymd'); 
            $form_order = M("orderinfo");            
            $condition['number'] =  array('like','%'.$search.'%');
            $data_order = $form_order->where($condition)->select();            

            $jnl = (String)count($data_order)+10;///TODO CHANGE
            $zero = '';
            for ($i = 0; $i < 6-strlen($jnl); $i++) $zero = $zero.'0';
            $jnl = $zero.$jnl;
            //var_dump($jnl); 计算订单号jnl
            $jnl = $search.$jnl; 
	    	$new['trade_jnl'] = 'ys'.$jnl;//$_REQUEST['trade_jnl'];
	    	$new['trade_mode'] = "";
	    	$new['trade_req'] = "<trade_req><pay_acc></pay_acc><pay_acc_name></pay_acc_name><total_num></total_num><total_amt></total_amt><resv_col1></resv_col1><resv_col2></resv_col2><resv_col3></resv_col3><trades><trade><user_id>".$new['user_id']."</user_id><user_name>".$new['user_name']."</user_name><jnl>".$jnl."</jnl><rec_acc>"."zjgkc"."</rec_acc><amt></amt><trade_code></trade_code><comment>浙江大学订餐系统</comment><resv_col1></resv_col1><resv_col2></resv_col2><resv_col3></resv_col3><Msg></Msg></trade></trades></trade_req>";
	    	$new['res_mode'] = 'res_notify';//$_REQUEST['res_mode'];
	    	$new['notify_url'] = "http://10.189.88.130/fastfood/index.php/Pay/request";//$_REQUEST['notify_url'];

	    	$new['trade_chars'] = 'utf-8';//$_REQUEST['trade_chars'];
	    	$new['trade_type'] = 'pay';//$_REQUEST['trade_type'];
	    	$new['sign_type']="md5";
	    	$salt = "synZDYS";
	    	$sign = "app_id=".$new['app_id']."&user_id=".$new['user_id']."&trade_jnl=".$new['trade_jnl']."&trade_req=".$new['trade_req']."&salt=".$salt;

	    	$new['sign'] = strtoupper(md5($sign));
			$new = json_encode($new);
			echo $new;
		}
     public function showOrderToMobile()
        {           
            ////////////////////////获取物理ID/////////////////
            
            // $data=array(
            //     'cardno'=>'293871'
            //             );
            // $url = "http://elife.zju.edu.cn:8050/Account/GetCardInfo";
            // $ch=curl_init();
            // curl_setopt($ch, CURLOPT_URL, $url);
            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            // curl_setopt($ch, CURLOPT_POST, 1);
            // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            // $request=curl_exec($ch);
            // var_dump($request);
            $form = M('orderinfo');
            $condition['pay_status'] = 1;
            $data = $form->where($condition)->select();
            for($i = 0; $i < count($data); $i++)
            {
            $new[$i]['username'] = $data[$i]['username'];
            $new[$i]['stuid'] = $data[$i]['stuid'];
                $search = M('logininfo');
                $condition_s['username'] = $data[$i]['stuid'];
                $result = $search ->where($condition_s)->find();
            $new[$i]['cardmacid'] = $result['cardmacid'];
            $new[$i]['food'] = $data[$i]['food'];   
            $new[$i]['area'] = $data[$i]['area'];
            $new[$i]['dep'] = $data[$i]['dep'];
            $new[$i]['address'] = $data[$i]['address'];
            $new[$i]['ordertime'] = $data[$i]['ordertime'];
            $new[$i]['number'] = $data[$i]['number'];
            $new[$i]['amount'] = $data[$i]['amount'];
            $new[$i]['phone'] = $data[$i]['phone'];
            $new[$i]['status'] = $data[$i]['status'];
			$new[$i]['pay'] = $data[$i]['pay'];
			$new[$i]['price'] = $data[$i]['price'];
            }
            $new = json_encode($new);
            echo $new;
        }      
	public function ReceiveMessageFromMobile()
        {           
           $receive = $GLOBALS['HTTP_RAW_POST_DATA'];
           $receive = json_decode($receive,true);
           $form = M('orderinfo');
           for ($i = 0; $i < count($receive); $i++)
           {
                       $condition['number'] = $receive[$i]['number'];
                       $data = $form->where($condition)->find();
                       if ($data)
                       {
                            $data['status'] = $receive[$i]['status'];
                            $form -> save($data);
                       }
           }
        }      
}
?>