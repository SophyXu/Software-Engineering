<?php
/****************************************************
 *         Author: Beihai - changyibeihai@gmail.com
 *  Last modified: 2013/10/27
 *       Filename: FoodAction.class.php
 *    Description: Action to show the foodinfo and add food.
 *****************************************************/

class FoodAction extends Action {
    public function food(){
	//$this->display();
    }
    /*show the foodinfo with json
	we can see it in "./fastfood/index.php/Food/showfood"
    */
    public function showFood(){
    	$key = $_GET['category'];
    	$father = $_GET['father'];
    	//$father = 2;
    	$form = M("foodinfo");
    	if ($father == '-1') {
		     $condition['status'] = 1;
    		 $data = $form->where($condition)->order("price")->select();
    	}
    	else if ($key != 0)
    	{
    		//$key = "未确认";
    		$condition['category'] = $key;
    		$condition['status'] = 1;
    		$data = $form->where($condition)->select();
    	}
    	else if ($father != 0)
    	{
    		//$key = "未确认";
    		$condition['status'] = 1;
    		$condition['father'] = $father;
    		$data = $form->where($condition)->select();
    	}
    	$data = json_encode($data);
       // $this->ajaxReturn($data);
    	echo $data;
    	//$this->display();
    }
	 public function showFoodtoAdmin(){            
	 	$cookies = new CookieModel();
        $cookie_id = $cookies -> read();
        if (! $cookie_id) $this->redirect('/');

    	$form = M("foodinfo");
		$data = $form->order("price")->select();
    	$data = json_encode($data);
    	echo $data;
    }
 	/*Do the add function. The incoming data should 
 	include "name","category","amount","ingredient","remark" and "picture"
    */
 	public function addFood(){
 		     $cookies = new CookieModel();
            $cookie_id = $cookies -> read();
            if (! $cookie_id) $this->redirect('/');

	    	$name = $_REQUEST['name'];
	    	$amount = $_REQUEST['amount'];
	    	$category = $_REQUEST['category'];
	    	$price = $_REQUEST['price'];
	    	$ingredient = $_REQUEST['ingredient'];
	    	$remark = $_REQUEST['remark'];
			$form = M("categoryinfo");
			$condition['id'] = $category;
    		$data = $form->where($condition)->find();
			if ($data['father'] == '-1') $data['father'] = $category;
    		$father = $data['father'];
	    	////////////////////////////////////////////////////////
			  if ($_FILES["picture"]["error"] > 0)
			    {
			    	$picname = '';
			  //  echo "Return Code: " . $_FILES["picture"]["error"] . "<br />";
			    }
			  else
			    {
				$picname =  "upload/" . uniqid() . preg_replace('/^.*(\..*)$/', '$1', $_FILES["picture"]["name"]);
				move_uploaded_file($_FILES["picture"]["tmp_name"],$picname);
			    }
			$form = M("foodinfo");
			$addnew['name'] = $name;
			$addnew['amount'] =(int) $amount;
			$addnew['category'] = $category;
			$addnew['father'] = $father;
			$addnew['price'] = $price;
			$addnew['ingredient'] = $ingredient;
			$addnew['remark'] = $remark;
			$addnew['status'] = 1;	
			$addnew['picture'] = $picname;	
			$result = $form->add($addnew);
			$this->redirect('/index.php/Admin/dish');
				//else {$this->error('添加失败');}
    }

        /*Function modifyFood
	receive tha address id which the user want to modify.
	add a new message into the database when the address is modifid and save the old one.
	we can see it in "./fastfood/index.php/Order/modifyFood"
    */

	public function modifyFood(){
		$cookies = new CookieModel();
        $cookie_id = $cookies -> read();
        if (! $cookie_id) $this->redirect('/');
	//!!!!!!!!!!!!!!!!TODOTODO
		$id = $_REQUEST['id'];		
		$name = $_REQUEST['name'];
	    $amount = $_REQUEST['amount'];
	    $category = $_REQUEST['category'];
	    $price = $_REQUEST['price'];
	    $ingredient = $_REQUEST['ingredient'];
	    $remark = $_REQUEST['remark'];
		$form = M('foodinfo');
    	//$key = 2;
    	$condition['id'] = $id;
    	$data = $form->where($condition)->find();
    	//var_dump($data);        
    	if ($_FILES["picture"]["error"] > 0)
			    {
			    	$picname = '';
			  //  echo "Return Code: " . $_FILES["picture"]["error"] . "<br />";
			    }
			  else
			    {			    	
				$picname =  "upload/" . uniqid() . preg_replace('/^.*(\..*)$/', '$1', $_FILES["picture"]["name"]);
				move_uploaded_file($_FILES["picture"]["tmp_name"],$picname);
			    }

    	$data['id'] = $id;
    	$data['name'] = $name;
		$data['amount'] = $amount;
		$data['category'] = $category;
		$data['price'] = $price;
		$data['ingredient'] = $ingredient;
		$data['remark'] = $remark;
		if ($_FILES["picture"]["name"]) $data['picture'] = $picname;
    	//var_dump($data) ;
		//var_dump($_FILES["picture"]);
		$result = $form->save($data);
		$this->redirect('/index.php/Admin/dish');
						//	else {$this->error('修改失败');}
		}
	/*Function Foodstatus
    change the status of food.
    */
	public function foodStatus(){
		$cookies = new CookieModel();
        $cookie_id = $cookies -> read();
        if (! $cookie_id) $this->redirect('/');
		$form = M('foodinfo');
		$id = $_REQUEST['id'];
		$condition['id'] = $id;
    	$data = $form->where($condition)->find();
		if ($data['status'] == 0) $data['status'] = 1;
			else $data['status'] = 0;		
		$result = $form->save($data);
		echo "success";

	}
	// public function decreaseFoodAmount(){
 //    	$cookies = new CookieModel();
 //        $cookie_id = $cookies -> read();
 //        if (! $cookie_id) $this->redirect('/');
 //        $key = $_REQUEST['id'];
 //        //$key = 2;
 //        $condition['id'] = $key;
 //        $form = M("foodinfo");
 //        $data = $form->where($condition)->find();
 //        $data['amount'] = $data['amount'] - 1;	
	// 	$result = $form->save($data);
	// 	//$this->redirect('/index.php/Admin/dish');
 //        //$this->display();
 //    }

	 /*Function deleteFood
    receive the order id and delete it.
    */

    public function deleteFood(){
    	$cookies = new CookieModel();
        $cookie_id = $cookies -> read();
        if (! $cookie_id) $this->redirect('/');
        $key = $_REQUEST['id'];
        //$key = 2;
        $condition['id'] = $key;
        $form = M("foodinfo");
        $data = $form->where($condition)->delete();
       
		$this->redirect('/index.php/Admin/dish');
        //$this->display();
    }
       
}
?>