<?php
class IndexAction extends Action {
	public function index()
		{
			
			    	$this->display();
		}
	Public function verify()
		{
    		import('ORG.Util.Image');
   			Image::buildImageVerify();
		 }
	
}
?>