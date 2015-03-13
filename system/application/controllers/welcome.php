<?php

class Welcome extends Website {

	function Welcome()
	{
		parent::Website();	
	}
	
	function index()
	{
		$this->load->view('welcome_message');
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */