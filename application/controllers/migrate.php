<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migrate extends CI_Controller {

	function __construct() {
        parent::__construct();

        // Prevents this controller to be accessed through /index.php/
        if (preg_match('/index.php/i', $_SERVER['REQUEST_URI'])){
		    if ($this->config->item('index_page') == ''){
		        redirect('admin');  
		    }
		 
		    die();  
		}
    }



    /**
     *
     * Controller of the migration part
     *
     */

	public function index() {

		// We first check that the user is allowed to access this controller
		$ip = $this->get_client_ip();
		
		if(in_array($ip, unserialize(WHITELISTED_IPS))) {
			$this->load->library('migration');

			if ( ! $this->migration->version(3)) {
		     	show_error($this->migration->error_string());
		    }
		    else
		    	echo "ok";
		}
		else {
			echo "You're not allowed to access this controller.";
		}
	}




	// Function to get the client IP address
	private function get_client_ip() {
	    $ipaddress = '';
	    if(isset($_SERVER['HTTP_CLIENT_IP']))
	        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	    elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
	        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	    elseif(isset($_SERVER['HTTP_X_FORWARDED']))
	        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	    elseif(isset($_SERVER['HTTP_FORWARDED_FOR']))
	        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	    elseif(isset($_SERVER['HTTP_FORWARDED']))
	        $ipaddress = $_SERVER['HTTP_FORWARDED'];
	    elseif(isset($_SERVER['REMOTE_ADDR']))
	        $ipaddress = $_SERVER['REMOTE_ADDR'];
	    else
	        $ipaddress = 'UNKNOWN';
	    return $ipaddress;
	}
}

/* End of file ecorners.php */
/* Location: ./application/controllers/ecorners.php */