<?php
/****************************************************
 *         Author: Beihai - changyibeihai@gmail.com
 *  Last modified: 2014/3/14
 *       Filename: CategoryAction.class.php
 *    Description: Action to show the categoryinfo and add category.
 *****************************************************/

class SenderAction extends Action {
    public function Sender(){
	//$this->display();
    }
    /*
    输出送餐员对应信息
    */
    public function showSender(){
    	$form = M("senderinfo");
    	$data = $form->select();
    	$data = json_encode($data);
    	echo $data;
    	//$this->display();
    }
    public function showSenderArea(){
        $form = M("areainfo");
        $condition['father'] = -1;
        $condition['root'] = array('neq',-1);
        $data = $form->where($condition)->select();
        $data = json_encode($data);
        echo $data;
        //$this->display();
    }
 	/*
    增加订餐员对应关系
    */
 	public function addSender(){            
	    	$stuid = $_REQUEST['stuid'];
            $area = $_REQUEST['area'];
			$form = M("senderinfo");
			$addnew['stuid'] = $stuid;
            $addnew['area'] = $area;
			$result = $form->add($addnew);
			$this->redirect('/index.php/Admin/sender');
				//else {$this->error('添加失败');}
    }

     /*
     删除对应关系
    */
    public function deleteSender(){
        $key = $_REQUEST['id'];
        $condition['id'] = $key;
        $form = M("senderinfo");
        $data = $form->where($condition)->delete(); 
		$this->redirect('/index.php/Admin/sender');
        //$this->display();
    }   
    public function showMyOrder()
    {
        $id = $_REQUEST['sid'];
        $form = M('senderinfo');
        $data_out = array();
        $condition['stuid'] = $id;
        $data = $form->where($condition)->select();  
        for ($i = 0;$i < count($data);$i++)
        {
            $area = $data[$i]['area'];
            $form_order = M('orderinfo');            
            $condition_order['area'] = $area;
            $condition_order['pay_status'] = 1;
            $data_order = $form_order->where($condition_order)->select();
            $data_out = array_merge($data_out,$data_order);        

        }
        for($i = 0; $i < count($data_out); $i++)
            {
             $new[$i]['username'] = $data_out[$i]['username'];
            $new[$i]['stuid'] = $data_out[$i]['stuid'];
                $search = M('logininfo');
                $condition_s['username'] = $data_out[$i]['stuid'];
                $result = $search ->where($condition_s)->find();
            $new[$i]['cardmacid'] = $result['cardmacid'];
            $new[$i]['food'] = $data_out[$i]['food'];   
            $new[$i]['area'] = $data_out[$i]['area'];
            $new[$i]['dep'] = $data_out[$i]['dep'];
            $new[$i]['address'] = $data_out[$i]['address'];
            $new[$i]['ordertime'] = $data_out[$i]['ordertime'];
            $new[$i]['number'] = $data_out[$i]['number'];
            $new[$i]['amount'] = $data_out[$i]['amount'];
            $new[$i]['phone'] = $data_out[$i]['phone'];
            $new[$i]['status'] = $data_out[$i]['status'];

            if ($data_out[$i]['amount'])
                $new[$i]['price'] = (float)$data_out[$i]['price']/$data_out[$i]['amount'];
            }
            $new = json_encode($new);
        echo $new;
    }
    public function showMyOrderToMobile()
    {
        $id = $_REQUEST['sid'];
        $form = M('senderinfo');
        $data_out = array();
        $condition['stuid'] = $id;            
        $data = $form->where($condition)->select();  
        $time = date("Y-m-d");
        for ($i = 0;$i < count($data);$i++)
        {
            $area = $data[$i]['area'];
            $form_order = M('orderinfo');            
            $condition_order['area'] = $area;
            $condition_order['pay_status'] = 1;
            $condition_order['ordertime'] = array('like','%'.$time.'%');
            $condition_order['status'] = "送餐途中";
            $data_order = $form_order->where($condition_order)->select();
            $data_out = array_merge($data_out,$data_order);        
        }
        for($i = 0; $i < count($data_out); $i++)
            {
            $new[$i]['username'] = $data_out[$i]['username'];
            $new[$i]['stuid'] = $data_out[$i]['stuid'];
                $search = M('logininfo');
                $condition_s['username'] = $data_out[$i]['stuid'];
                $result = $search ->where($condition_s)->find();
            $new[$i]['cardmacid'] = $result['cardmacid'];
            $new[$i]['food'] = $data_out[$i]['food'];
            $new[$i]['pay'] = $data_out[$i]['pay'];  			
            $new[$i]['area'] = $data_out[$i]['area'];
            $new[$i]['dep'] = $data_out[$i]['dep'];
            $new[$i]['address'] = $data_out[$i]['address'];
            $new[$i]['ordertime'] = $data_out[$i]['ordertime'];
            $new[$i]['number'] = $data_out[$i]['number'];
            $new[$i]['amount'] = $data_out[$i]['amount'];
            $new[$i]['phone'] = $data_out[$i]['phone'];
            $new[$i]['status'] = $data_out[$i]['status'];
            $new[$i]['price'] = $data_out[$i]['price'];
            }
            $new = json_encode($new);
        echo $new;
    }
}
?>