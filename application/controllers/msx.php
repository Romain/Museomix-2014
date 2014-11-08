<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Msx extends CI_Controller {

	function __construct() {
        parent::__construct();
		$this->load->model('msx_model');
    }



    /**
     *
     * Homepage of the site
     *
     */

	public function index() {

		// We prepare data that need to be displayed.
		$data = array();
		$data = $this->prepare_data($data);

		if(isset($data))
			$this->load->view('msx/index', $data);
		else
			$this->load->view('msx/index');
	}



    /**
     *
     * Controller which allows the visitor to add picture to the system
     *
     */

	public function add_picture() {

		// We prepare data that need to be displayed.
		$data = array();
		$data = $this->prepare_data($data);

		if(!is_dir('pictures/'))
			mkdir('pictures/', 0700);

		if(!is_dir('pictures/'.date('Y').'/'))
			mkdir('pictures/'.date('Y').'/', 0700);	

		if(!is_dir('pictures/'.date('Y').'/'.date('m').'/'))
			mkdir('pictures/'.date('Y').'/'.date('m').'/', 0700);

		$path = 'pictures/'.date('Y').'/'.date('m').'/';

		if(isset($_FILES['picture'])) {
			$config['upload_path'] = $path;
			$config['allowed_types'] = 'gif|jpg|jpeg|png';
			$config['max_size']	= '10000';
			$config['max_width']  = '5000';
			$config['max_height']  = '5000';
			$config['encrypt_name']  = TRUE;
			$config['remove_spaces']  = TRUE;

			$this->load->library('upload', $config);

			// If the upload failed
			if ( ! $this->upload->do_upload('picture')) {
				$data['message'] = "Une erreur est survenue lors de l'envoi de votre photo.";
				$data['message_type'] = "error";
			}

			// If the upload occured properly
			else {
				// Set the variables
				if(isset($_POST['comment']) && (strlen($_POST['comment']) != 0))
					$comment = $this->input->post('comment', TRUE);
				else
					$comment = NULL;

				$upload = array('upload_data' => $this->upload->data());
				$file = $upload['upload_data']['file_name'];
				$file_path = $path.$file;

				// Generate a secret_id
				$secret_id_availability = TRUE;
				while($secret_id_availability) {
					$secret_id = $this->generate_token();
					$secret_id = substr($secret_id, 0, 10);
					$secret_id_availability = $this->msx_model->get_picture_info(array('secret_id' => $secret_id));
				}

				// Insert the data in the DB
				$params = array(
					"secret_id" => $secret_id,
					"picture" => $file_path,
					"comment" => $comment,
					"created_at" => time()
				);
				$picture_id = $this->msx_model->add_picture($params);

				$data['message'] = "Votre photo a bien été envoyée.<br />"
									."Retrouvez-la dans l'espace détente.";
				$data['message_type'] = "success";
			}
		}

		// No picture has been selected
		else {
			
		}

		if(isset($data))
			$this->load->view('msx/add_picture', $data);
		else
			$this->load->view('msx/add_picture');
	}





	/* Private functions */	


    /**
     *
     * Method to initialize the data to pass to the view.
     * 
     * Params:
     * none
     * 
     *
     */

	private function prepare_data($data = array()) {

		// Display the profiler in the development environment
		if(strcmp(ENVIRONMENT, "development") == 0)
			$this->output->enable_profiler(TRUE);

		return $data;
	}


    /**
     *
     * Method to generate a token.
     * 
     * Params:
     * none
     * 
     *
     */

	private function generate_token() {
		return hash("sha1", $this->config->item('encryption_key').date('Y-m-d H:i:s').rand());
	}
}

/* End of file ecorners.php */
/* Location: ./application/controllers/ecorners.php */