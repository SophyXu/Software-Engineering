<?php
/****************************************************
 *         Author: Beihai - changyibeihai@gmail.com
 *  Last modified: 2013/10/27
 *       Filearea: AreaAction.class.php
 *    Description: Action to show the Areainfo and add Area.
 *****************************************************/

class AreaAction extends Action {
    public function Area(){
	//$this->display();
    }
    /*
    Fcuntion showArea
    传入用户的学号 字段为id
    输出用户的地址信息
	"./fastfood/index.php/Area/showArea"
    */
    public function showArea(){
    	$key = $_GET['key'];        
        $form = M("areainfo");
        if ($key == "all") {
             $data = $form->select();
        }
        else
        {
            //$key = "未确认";
            $condition['root'] = $key;
            $data = $form->where($condition)->select();
        }
        $data = json_encode($data);
       // $this->ajaxReturn($data);
        echo $data;
    	//$this->display();
    }
 	/*Do the add function. The incoming data should 
 	include "area"
    */
 	public function addArea(){ 
            $cookies = new CookieModel();
            $cookie_id = $cookies -> read();
            if (! $cookie_id) $this->redirect('/');
	    	$name = $_REQUEST['name'];
            $father = $_REQUEST['father'];
            $root = $_REQUEST['root'];
			$form = M("areainfo");
			$addnew['name'] = $name;
            $addnew['father'] = $father;
            $addnew['root'] = $root;
			$result = $form->add($addnew);
			$this->redirect('/index.php/Admin/area');
				//else {$this->error('添加失败');}
    }

        /*Function modifyFood
	receive tha address id which the user want to modify.
	add a new message into the database when the address is modifid and save the old one.
	we can see it in "./fastfood/index.php/Order/modifyFood"
    */

	public function modifyArea(){ 
        $cookies = new CookieModel();
        $cookie_id = $cookies -> read();
        if (! $cookie_id) $this->redirect('/');
		$id = $_REQUEST['id'];		
		$name = $_REQUEST['name'];
        $father = $_REQUEST['father'];
        $root = $_REQUEST['root'];
		$form = M('areainfo');
    	//$key = 2;
    	$condition['id'] = $id;
    	$data = $form->where($condition)->find();
    	//var_dump($data);
    	$data['name'] = $name;
        $data['father'] = $father;
        $data['root'] = $root;
    	//echo $data;
		$result = $form->save($data);
		$this->redirect('/index.php/Admin/area');
						//	else {$this->error('修改失败');}
		}

     /*Function deleteArea
    receive the order id and delete it.
    */

    public function deleteArea(){ 
        $cookies = new CookieModel();
        $cookie_id = $cookies -> read();
        if (! $cookie_id) $this->redirect('/');
        $key = $_REQUEST['id'];
        $condition['id'] = $key;
        $form = M("areainfo");
        $data = $form->where($condition)->delete(); 
        $data_all = $form->select();
        for ($i = 0;$i< count($data_all);$i++)
        {
            if ($data_all[$i]['father'] == $key) 
            {
                $condition['id'] = $data_all[$i]['id'];
                $form->where($condition)->delete();
            }
            if ($data_all[$i]['root'] == $key) 
            {
                $condition['id'] = $data_all[$i]['id'];
                $form->where($condition)->delete();
            }
        }        
        $this->redirect('/index.php/Admin/area');
        //$this->display();
    }   
}
?>