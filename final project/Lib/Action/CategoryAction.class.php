<?php
/****************************************************
 *         Author: Beihai - changyibeihai@gmail.com
 *  Last modified: 2013/10/27
 *       Filename: CategoryAction.class.php
 *    Description: Action to show the categoryinfo and add category.
 *****************************************************/

class CategoryAction extends Action {
    public function Category(){
	//$this->display();
    }
    /*show the foodinfo with json
	we can see it in "./fastfood/index.php/Category/showCategory"
    */
    public function showCategory(){
    	$form = M("categoryinfo");
    	$data = $form->select();
    	$data = json_encode($data);
    	echo $data;
    	//$this->display();
    }
 	/*Do the add function. The incoming data should 
 	include "name","category","amount","ingredient","remark" and "picture"
    */
 	public function addCategory(){            
            $cookies = new CookieModel();
            $cookie_id = $cookies -> read();
            if (! $cookie_id) $this->redirect('/');

	    	$name = $_REQUEST['name'];
            $father = $_REQUEST['father'];
			$form = M("categoryinfo");
			$addnew['name'] = $name;
            $addnew['father'] = $father;
			$result = $form->add($addnew);
			$this->redirect('/index.php/Admin/dish');
				//else {$this->error('添加失败');}
    }

        /*Function modifyFood
	receive tha address id which the user want to modify.
	add a new message into the database when the address is modifid and save the old one.
	we can see it in "./fastfood/index.php/Order/modifyFood"
    */

	public function modifyCategory(){
        $cookies = new CookieModel();
        $cookie_id = $cookies -> read();
         if (! $cookie_id) $this->redirect('/');

		$id = $_REQUEST['id'];		
		$name = $_REQUEST['name'];
	    $remark = $_REQUEST['remark'];
        $father = $_REQUEST['father'];
		$form = M('categoryinfo');
    	//$key = 2;
    	$condition['id'] = $id;
    	$data = $form->where($condition)->find();
    	//var_dump($data);     
        $data['id'] = $id;
    	$data['name'] = $name;
		$data['remark'] = $remark;
        $data['father'] = $father;
    	//echo $data;
		$result = $form->save($data);
		$this->redirect('/index.php/Admin/dish');
						//	else {$this->error('修改失败');}
		}

     /*Function deleteCategory
    receive the order id and delete it.
    */

    public function deleteCategory(){            
        $cookies = new CookieModel();
        $cookie_id = $cookies -> read();
        if (! $cookie_id) $this->redirect('/');
        /*删除分类下的子分类*/
        $key = $_REQUEST['id'];
        $condition['id'] = $key;
        $form = M("categoryinfo");
        $data = $form->where($condition)->delete(); 
        $data_all = $form->select();
        for ($i = 0;$i< count($data_all);$i++)
        {
            if ($data_all[$i]['father'] == $key) 
            {
                $condition['id'] = $data_all[$i]['id'];
                $form->where($condition)->delete();
            }
        }        
        /*删除分类下的菜品*/
        $food = M('foodinfo');
        $data_food = $food->select();
        for ($i = 0;$i< count($data_food);$i++)
        {
            if ($data_food[$i]['father'] == $key) 
            {
                $condition['id'] = $data_food[$i]['id'];
                $food->where($condition)->delete();
            }
            if ($data_food[$i]['category'] == $key) 
            {
                $condition['id'] = $data_food[$i]['id'];
                $food->where($condition)->delete();
            }
        }        
		$this->redirect('/index.php/Admin/dish');
        //$this->display();
    }   
}
?>