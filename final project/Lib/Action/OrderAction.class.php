<?php
/****************************************************
*         Author: Beihai - changyibeihai@gmail.com
 *  Last modified: 2013/10/27
 *       Filename: OrderAction.class.php
 *    Description: Action to show the order and add order.
 *****************************************************/
/*用户添加订单时，首先给用户列出他以前用过的地址跟电话(call showAddress fonction)，
    1.如果选用户择了以前用过的地址跟电话,转到第4步
    2.如果用户要改变他以前用过的地址、电话(call modifyAddress function)，将原地址保留，存储新地址,转到第4步
    3.如果用户要增加新的地址、电话(call addAddress function),存储新地址,取出新地址,转到第4步
    4.向addorder函数传送此地址与电话，存放在address字段里*/
 	
class OrderAction extends Action {
    public function order(){
	//$this->display();
    }

    /*Function showAllOrderToAdmin
    show all the foodinfo with json
	we can see it in "./fastfood/index.php/Order/showAllOrderToAdmin"
    */

    // public function showAllOrderToAdmin(){
    // 	//$key = $_REQUEST['key'];
    // 	//$key = "未确认";
    // 	//$condition['status'] = $key;
    // 	$form = M("orderinfo");
    // 	$data = $form->select();
    // 	$data = json_encode($data);
    // 	echo $data;
    // 	//$this->display();
    // }

    /*Function showOrderToAdmin
    receive the order status and show the foodinfo of that status with json
	we can see it in "./fastfood/index.php/Order/showOrderToAdmin"
    */

    public function showOrderToAdmin(){
        $cookies = new CookieModel();
         $cookie_id = $cookies -> read();
         $Form = M('userinfo');
         $condition_user['stuid'] = $cookie_id;
         $data = $Form->where($condition_user)->find();
         if (! $data['manager']) $this->redirect('/');
    	$key = $_GET['status'];    	
        $page = $_GET['page'] - 1;         
        $condition['pay_status'] = 1;
    	$form = M("orderinfo");
    	if ($key == "all") {
    		 $data = $form->where($condition)->limit(0+25*$page,25+25*$page)->order('ordertime desc')->select();
    	}
    	else
    	{
    		//$key = "未确认";
    		$condition['status'] = $key;
    		$data = $form->where($condition)->limit(0+25*$page,25+25*$page)->order('ordertime desc')->select();
    	}
    	$data = json_encode($data);
       // $this->ajaxReturn($data);
    	echo $data;
    	//$this->display();
  }
    
    public function showOrderAmount(){
        $key = $_GET['status'];       
        $form = M("orderinfo");
        $condition['pay_status'] = 1;
        if ($key == "all") {
             $data = $form->where($condition)->select();
             echo(count($data));
        }
        else
        {
            //$key = "未确认";
            $condition['status'] = $key;
            $data = $form->where($condition)->select();
            echo(count($data));
        }
  }
    public function showOrderToUser(){
        //$key = $_GET['stuid'];     
        //echo $_SESSION['stuid']; 
        $cookie = $_GET['iPlanetDirectoryPro'];
        //if ($key != $_SESSION['stuid']) $this->redirect('/');
        $form = M("logininfo");     
        $condition_user['ssid'] = $cookie;
        $data_in = $form->where($condition_user)->find();
        $key = $data_in['username'];
        $form = M("orderinfo");
        $condition['stuid'] = $key;
		$condition['pay_status'] = 1;
        $data = $form->where($condition)->order('ordertime desc')->select();
        $data = json_encode($data);
       // $this->ajaxReturn($data);
        echo $data;
        //$this->display();
    }   
    /*Function changeOrderStatus
    receive the order status and change the foodinfo of that status into the next status 
	we can see it in "./fastfood/index.php/Order/changeOrderStatus"
    */

    public function changeOrderStatus(){
         $cookies = new CookieModel();
         $cookie_id = $cookies -> read();
         $Form = M('userinfo');
         $condition_in['stuid'] = $cookie_id;
         $data = $Form->where($condition_in)->find();
         if (! $data['manager']) $this->redirect('/');
    	$key = $_REQUEST['id'];
    	//$key = 2;
    	$condition['id'] = $key;
    	$form = M("orderinfo");
    	$data = $form->where($condition)->find();
    	//echo $data;
    	if ($data['status'] == "下单成功") $data['status'] = "烹饪中";
    	else if ($data['status'] == "烹饪中") $data['status'] = "送餐途中"; 
    	else if ($data['status'] == "送餐途中") $data['status'] = "餐已收到";
    	$form->save($data);
    	//$this->display();
    }
    public function changeStatusAll(){
         $cookies = new CookieModel();
         $cookie_id = $cookies -> read();
         $Form = M('userinfo');
         $condition_in['stuid'] = $cookie_id;
         $data = $Form->where($condition_in)->find();
         if (! $data['manager']) $this->redirect('/');
        $key = $_REQUEST['status'];
        //$key = 2;
        $condition['status'] = $key;
        $form = M("orderinfo");
        $data = $form->where($condition)->select();
        //echo $data;
        for  ($i = 0;$i < count($data); $i++)
        {
            if ($data[$i]['status'] == "下单成功") $data[$i]['status'] = "烹饪中";
            else if ($data[$i]['status'] == "烹饪中") $data[$i]['status'] = "送餐途中"; 
            else if ($data[$i]['status'] == "送餐途中") $data[$i]['status'] = "餐已收到";
            $form->save($data[$i]);
        }
        //$this->display();
    }
    /*Function modifyOrder
    receive all the things of the modified order and save. 
    we can see it in "./fastfood/index.php/Order/changeOrderStatus"
    */

       public function modifyOrder(){
         $cookies = new CookieModel();
         $cookie_id = $cookies -> read();
         $Form = M('userinfo');
         $condition_in['stuid'] = $cookie_id;
         $data = $Form->where($condition_in)->find();
         if (! $data['manager']) $this->redirect('/');
        $key = $_REQUEST['id'];
        $condition['id'] = $key;
        $form = M("orderinfo");
        $data = $form->where($condition)->find();
        if ($_REQUEST['number']) $data['number'] = $_REQUEST['number'];
        if ($_REQUEST['area']) $data['area'] = $_REQUEST['area'];
        if ($_REQUEST['ordertime']) $data['ordertime'] = $_REQUEST['ordertime'];
        if ($_REQUEST['address']) $data['address'] = $_REQUEST['address'];
        if ($_REQUEST['pay']) $data['pay'] = $_REQUEST['pay'];
        if ($_REQUEST['phone']) $data['phone'] = $_REQUEST['phone'];
        if ($_REQUEST['food']) $data['food'] = $_REQUEST['food'];
        if ($_REQUEST['amount']) $data['amount'] = $_REQUEST['amount'];
        if ($_REQUEST['status']) $data['status'] = $_REQUEST['status'];   
        if ($_REQUEST['pay_status']) $data['pay_status'] = $_REQUEST['pay_status'];
        $data['id'] = $key;
        $form->save($data);
        $this->redirect('/index.php/Admin/index');
        //$this->display();
    }
    public function modifyOrder_finish(){
         // $cookies = new CookieModel();
         // $cookie_id = $cookies -> read();
         // $Form = M('userinfo');
         // $condition_in['stuid'] = $cookie_id;
         // $data = $Form->where($condition_in)->find();
         // if (! $data['manager']) $this->redirect('/');
        $key = $_REQUEST['id'];
        $condition['id'] = $key;
        $form = M("orderinfo");
        $data = $form->where($condition)->find();
        if ($_REQUEST['finish']) $data['finish'] = 1;
        $data['id'] = $key;
        $form->save($data);
        var_dump($data);
        //$this->display();
    }
 	/*Function addorder
 	do the add function. The incoming data should 
 	include "username","ordertime","arrivaltime","address","phone","remark","food","amount","status"
    */
    public function addOrder(){
        $order =  $_REQUEST['order'];            
        $form = M("logininfo");     
        $condition['ssid'] = $_COOKIE['iPlanetDirectoryPro'];
        $data_user = $form->where($condition)->find();
        $username = $data_user['name'];
        $dep = $data_user['dep'];
        $stuid = $data_user['username'];
        $form_user = M('userinfo');
        $condition_user['stuid'] = $stuid;
        $data_final = $form_user->where($condition_user)->find();
        if ($data_final) $dep = $data_final['dep'];
        $trade = "";
        for ($ii = 0;$ii < count($order);$ii++)
        {
            date_default_timezone_set("Asia/Shanghai"); 
	    	$ordertime = date('Y-m-d H:i:s');
            $checktime = date('Hi');
            // if (!(($checktime >= '0830' && $checktime<= '1030')||($checktime>='1400' && $checktime<='1630')))
            // {
            //     //echo $checktime;
            //     $data_error['error'] = "对不起，现在不是订餐时间";
            //     $data_error = json_encode($data_error);
            //     echo $data_error;
            //     return;
            // } 
            $new['trade_date'] = date('YmdHis');
            $search = date('Ymd');             
            //var_dump($search);
            $form_order = M("orderinfo");   
            $condition = array();        
            $condition['number'] =  array('like','%'.$search.'%');            

            $data_order = $form_order->where($condition)->select();            
            $jnl = (String)count($data_order)+1;
            $zero = '';
            for ($i = 0; $i < 6-strlen($jnl); $i++) $zero = $zero.'0';
            $jnl = $zero.$jnl;
            //var_dump($jnl); 计算订单号jnl
            $number[$ii] = $search.$jnl; 
	    	$address = $order[$ii]['address'];
	    	$phone = $order[$ii]['phone'];
	    	$remark = $order[$ii]['remark'];
	    	$amount = $order[$ii]['amount'];
            $pay = $order[$ii]['pay'];
            $area = $order[$ii]['area'];
	    	$status = "下单成功";
            $id = $order[$ii]['id'];//食品的ID
           // var_dump($id);
            $condition = array();        
            $condition['id'] = $id;
            $form = M("foodinfo");
            $data = array();
            $data = $form->where($condition)->find();
          // var_dump($data);
            if ($data['amount'] - $amount >=0)
			{
				if ($pay == '签单') $data['amount'] = $data['amount'] - $amount;
			}
            else 
            {
                $data_error['error'] = "对不起，套餐已经卖光啦，下次早点来吧！";
                $data_error = json_encode($data_error);
                echo $data_error;
                return;
            }
            $form -> save($data);            
            $addnew = array();
            $form = array();
            $addnew['food'] = $data['name'];
            //var_dump($data['name']);
            $addnew['price'] = $data['price']*$amount;            
			$form = M('orderinfo');            
            $addnew['number'] = $number[$ii];
			$addnew['username'] = $username;
            $addnew['dep'] = $dep;
            $addnew['stuid'] = $stuid;
			$addnew['ordertime'] = $ordertime;
			$addnew['address'] = $address;
			$addnew['phone'] = $phone;
			$addnew['remark'] = "";//所有数据都不能留空！！！
            $addnew['pay'] = $pay;
            if ($addnew['pay'] == '签单') $addnew['pay_status'] = 1;
             else $addnew['pay_status'] = 0;
			$addnew['area'] = $area;
			$addnew['amount'] = $amount;
			$addnew['status'] = $status;
            // $trade = $trade."<trade><user_id>".$stuid."</user_id><user_name>".$username."</user_name><jnl>".$number."</jnl><rec_acc>"."zjgkc"."</rec_acc><amt>".$addnew['price']."</amt><trade_code></trade_code><comment>浙江大学订餐系统</comment><resv_col1></resv_col1><resv_col2></resv_col2><resv_col3></resv_col3><Msg></Msg></trade>";
            //var_dump($addnew);
			$form->add($addnew);            
        }
        if ($addnew['pay_status'] == 0)
            {
                $jnl = $number[0];
                $new['app_id'] = 'ZDYS';
                $new['user_name'] = $username;
                $new['user_id'] =  $stuid;
                $new['trade_jnl'] = 'ys'.$jnl;
                $new['trade_mode'] = "";
                $total_amt = 0;$total_num = 0;
                $total_jnl = $number[0];
                for ($i = 0;$i<count($order);$i++)
                {
                    $total_num = $total_num + 1;
                    $id = $order[$i]['id'];//食品的ID
                    $condition = array();        
                    $condition['id'] = $id;
                    $form_food = M("foodinfo");
                    $data_food = array();
                    $data_food = $form_food->where($condition)->find();
                    $total_amt = $total_amt + $order[$i]['amount']*$data_food['price'];
                    if ($i != 0) $total_jnl = $total_jnl.','.(string)$number[$i];  
                }
                $form_jnl = M('jnlinfo');//添加订单对应关系
                $new_jnl['first'] = $number[0];
                $new_jnl['total'] = $total_jnl;
                $form_jnl -> add($new_jnl);
                $trade = "<trade><user_id>".$stuid."</user_id><user_name>".$username."</user_name><jnl>".$number[0]."</jnl><rec_acc>"."zjgkc"."</rec_acc><amt>".$total_amt."</amt><trade_code></trade_code><comment>浙江大学订餐系统</comment><resv_col1></resv_col1><resv_col2></resv_col2><resv_col3></resv_col3><Msg></Msg></trade>";
                $new['trade_req'] = "<trade_req><pay_acc></pay_acc><pay_acc_name></pay_acc_name><total_num>".'1'."</total_num><total_amt>".$total_amt."</total_amt><resv_col1></resv_col1><resv_col2></resv_col2><resv_col3></resv_col3><trades>".$trade."</trades></trade_req>";
                $new['res_mode'] = 'res_notify';
                $new['trade_chars'] = 'utf-8';
                $new['trade_type'] = 'pay';
                $new['sign_type']="md5";
                $new['iPlanetDirectoryPro'] = urlencode($_COOKIE['iPlanetDirectoryPro']);
                $new['notify_url'] = "http://10.203.2.68/fastfood/index.php/Pay/request";
                $salt = "synZDYS";
                $sign = "app_id=".$new['app_id']."&user_id=".$new['user_id']."&trade_jnl=".$new['trade_jnl']."&trade_req=".$new['trade_req']."&salt=".$salt;
                $new['sign'] = strtoupper(md5($sign));
                $new = json_encode($new);
                echo $new;

            }
    }

    /*searchOrder查询订单
    可以按照 姓名 学号 下单时间 地址 电话 状态 进行模糊查询
    */
    public function searchOrder(){
         $cookies = new CookieModel();
         $cookie_id = $cookies -> read();
         $Form = M('userinfo');
         $condition_in['stuid'] = $cookie_id;
         $data = $Form->where($condition_in)->find();
         if (! $data['manager']) $this->redirect('/');

        $username = $_REQUEST['username'];
        $stuid = $_REQUEST['stuid'];
        $food = $_REQUEST['food'];
        $dep = $_REQUEST['dep'];
        $ordertime =$_REQUEST['ordertime'];
        $address = $_REQUEST['address'];
        $phone = $_REQUEST['phone'];
        $status =$_REQUEST['status'];
        $pay = $_REQUEST['pay'];
        $area = $_REQUEST['area'];

        $form = M("orderinfo");
        if ($username){
            $condition['username'] = array('like','%'.$username.'%');
            $data = $form->where($condition)->select();
        }
        else if ($stuid)
        {
            $condition['stuid'] = array('like','%'.$stuid.'%');
            $data = $form->where($condition)->select();
        }
        else if ($food)
        {
            $condition['food'] = array('like','%'.$food.'%');
            $data = $form->where($condition)->select();
        }
        else if ($dep)
        {
            $condition['dep'] = array('like','%'.$dep.'%');
            $data = $form->where($condition)->select();
        }
        else if ($ordertime)
        {
            $condition['ordertime'] = array('like','%'.$ordertime.'%');
            $data = $form->where($condition)->select();
        }
        else if ($address)
        {
            $condition['address'] = array('like','%'.$address.'%');
            $data = $form->where($condition)->select();
        }
        else if ($phone)
        {
            $condition['phone'] = array('like','%'.$phone.'%');
            $data = $form->where($condition)->select();
        }
        else if ($status)
        {
            $condition['status'] = array('like','%'.$status.'%');
            $data = $form->where($condition)->select();
        }
        else if ($pay)
        {
            $condition['pay'] = array('like','%'.$pay.'%');
            $data = $form->where($condition)->select();
        }
        else if ($area)
        {
            $condition['area'] = array('like','%'.$area.'%');
            $data = $form->where($condition)->select();
        }
        $data = json_encode($data);
       // $this->ajaxReturn($data);
        echo $data;
        //$this->display();
    }    

    /*Function deleteOrder
    receive the order id and delete it.
    */
    public function deleteOrder(){
         $cookies = new CookieModel();
         $cookie_id = $cookies -> read();
         $Form = M('userinfo');
         $condition_in['stuid'] = $cookie_id;
         $data = $Form->where($condition_in)->find();
         if (! $data['manager']) $this->redirect('/');

        $key = $_REQUEST['id'];
        //$key = 2;
        $condition['id'] = $key;
        $form = M("orderinfo");
        $data = $form->where($condition)->delete();
        $this->redirect('/index.php/Admin/index');
        //$this->display();
    }

    public function addMobileOrder(){            
            $cookies = new CookieModel();
            $cookie_id = $cookies -> read();
            if (! $cookie_id) $this->redirect('/');

            $category = $_REQUEST['category'];
            $food = $_REQUEST['food'];
            $address = $_REQUEST['address'];
            $amount = $_REQUEST['amount'];
            $remark = $_REQUEST['remark'];

            $form = M("morderinfo");
            $addnew['category'] = $category;
            $addnew['food'] = $food;
            $addnew['address'] = $address;
            $addnew['amount'] = $amount;
            $addnew['remark'] = $remark;
            date_default_timezone_set("Asia/Shanghai");
            $addnew['ordertime'] = date('Y-m-d H:i:s');
            $addnew['status'] = "下单成功";
            $result = $form->add($addnew);
            $this->redirect('/index.php/Admin/add_order');
                //else {$this->error('添加失败');}
    }
}
?>