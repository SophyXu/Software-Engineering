<?php
/****************************************************
 *         Author: Beihai - changyibeihai@gmail.com
 *  Last modified: 2013/10/31
 *       Filename: AddressAction.class.php
 *    Description: Action to show the address, modify the address and add address info.
 *****************************************************/
 /*用户添加订单时，首先给用户列出他以前用过的地址跟电话(call showAddress fonction)，
    1.如果选用户择了以前用过的地址跟电话,转到第4步
    2.如果用户要改变他以前用过的地址、电话(call modifyAddress function)，将原地址保留，存储新地址,转到第4步
    3.如果用户要增加新的地址、电话(call addAddress function),存储新地址,取出新地址,转到第4步
    4.向addorder函数传送此地址与电话，存放在address字段里*/ 
class AddressAction extends Action { 
     function __construct() {
            $cookies = new CookieModel();
            $cookie_id = $cookies -> read();
            if (! $cookie_id) $this->redirect('/');
            $form = M("userinfo");   
        }
	public function index(){
	//$this->display();
    }
   
    /*Function showAddress
    传入用户的学号 字段为id
    输出用户的地址信息
    查看网址"./fastfood/index.php/Address/showAddress"
    */

    public function showAddress(){
    	$key = $_REQUEST['id'];
    	//$key = "未确认";
    	$condition['stuid'] = $key;
    	$form = M("addressinfo");
    	$data = $form->where($condition)->select();
    	$data = json_encode($data);
    	echo $data;
    }

    /*Function modifyAddress
	receive tha address id which the user want to modify.
	add a new message into the database when the address is modifid and save the old one.
	we can see it in "./fastfood/index.php/Address/modifyAddress"
    */

	public function modifyAddress(){
		$id = $_REQUEST['id'];		
		$name = $_REQUEST['name'];
        $stuid = $_REQUEST['stuid'];
		$address = $_REQUEST['address'];
		$phone = $_REQUEST['phone'];
    	//$key = 2;
    	$condition['id'] = $id;
    	$form = M("addressinfo");
    	$data = $form->where($condition)->find();
    	$data['name'] = $name;
    	$data['address'] = $address;
        $data['stuid'] = $stuid;
    	$data['phone'] = $phone;
    	//echo $data;
		$result = $form->add($data);
		if ($result) {$this->redirect('/fastfood/index.php/Admin/admin');}
			else {$this->error('添加失败');}
    }

     /*Function addAddress
 	do the add function. The incoming data should 
 	include "name","address","phone"
    */

	public function addAddress(){
		$name = $_REQUEST['name'];
		$address = $_REQUEST['address'];
        $stuid = $_REQUEST['stuid'];
		$phone = $_REQUEST['phone'];
		$form = M("addressinfo");
		$data['name'] = $name;
    	$data['address'] = $address;
    	$data['phone'] = $phone;
        $data['stuid'] = $stuid;
    	$result = $form->add($data);
		if ($result) {$this->redirect('/fastfood/index.php/Admin/admin');}
			else {$this->error('添加失败');}
    }

     /*Function deleteAddress
    receive the order id and delete it.
    */

    public function deleteAddress(){
        $key = $_REQUEST['id'];
        //$key = 2;
        $condition['id'] = $key;
        $form = M("addressinfo");
        $data = $form->where($condition)->delete();
        //$this->display();
    }
     
}
?>
   