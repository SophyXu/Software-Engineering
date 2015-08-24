<?php
class CookieModel extends Model {
	function read(){
		$form = M("logininfo");		
		$condition['ssid'] = $_COOKIE['iPlanetDirectoryPro'];
		$data = $form->where($condition)->find();
		return $data['username'];
		//return 1;
	}

}
?>