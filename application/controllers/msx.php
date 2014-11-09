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
			mkdir('pictures/', 0755);

		if(!is_dir('pictures/'.date('Y').'/'))
			mkdir('pictures/'.date('Y').'/', 0755);	

		if(!is_dir('pictures/'.date('Y').'/'.date('m').'/'))
			mkdir('pictures/'.date('Y').'/'.date('m').'/', 0755);

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
				$data['message'] = "Une erreur est survenue lors du partage de votre photo.";
				$data['message_type'] = "error";
			}

			// If the upload occured properly
			else {
				// Set the variables
				if(isset($_POST['comment']) && (strlen($_POST['comment']) != 0))
					$comment = $this->input->post('comment', TRUE);
				else
					$comment = NULL;

				if(isset($_POST['sound']) && (strlen($_POST['sound']) != 0))
					$sound = $this->input->post('sound', TRUE);
				else
					$sound = NULL;

				if(isset($_POST['name']) && (strlen($_POST['name']) != 0))
					$name = $this->input->post('name', TRUE);
				else
					$name = NULL;

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
					"sound" => $sound,
					"comment" => $comment,
					"name" => $name,
					"created_at" => time()
				);
				$picture_id = $this->msx_model->add_picture($params);

				$data['message'] = "Votre photo a bien été partagée.<br />"
									."Retrouvez-la dans l'espace détente.";
				$data['message_type'] = "success";
			}
		}

		if(isset($data))
			$this->load->view('msx/add_picture', $data);
		else
			$this->load->view('msx/add_picture');
	}



    /**
     *
     * Controller which displays the pictures
     *
     */

	public function show() {

		// We prepare data that need to be displayed.
		$data = array();
		$data = $this->prepare_data($data);

		$data['pictures'] = $this->msx_model->get_pictures();

		if(isset($data))
			$this->load->view('msx/show', $data);
		else
			$this->load->view('msx/show');
	}



    /**
     *
     * Controller which redirects to a flashcode app download page
     *
     */

	public function qrcode() {

		redirect('http://www.flashcode.fr/telecharger/');		
	}



    /**
     *
     * Controller which detects the actions of the user
     *
     */

	public function detect_action() {

		// We prepare data that need to be displayed.
		$data = array();
		$data = $this->prepare_data($data);

		if($this->uri->segment(3)) {
			$action = $this->uri->segment(3);

			// Generate a secret_id
			$secret_id_availability = TRUE;
			while($secret_id_availability) {
				$secret_id = $this->generate_token();
				$secret_id = substr($secret_id, 0, 10);
				$secret_id_availability = $this->msx_model->get_action_info(array('secret_id' => $secret_id));
			}

			$params = array(
				"secret_id" => $secret_id,
				"action" => $action,
				"created_at" => time()
			);
			$action_id = $this->msx_model->add_action($params);

			if($action_id != FALSE)
				$data["result"] = $action;
			else
				$data["result"] = "error";
		}

		if(isset($data))
			$this->load->view('msx/result', $data);
		else
			$this->load->view('msx/result');
	}



    /**
     *
     * Controller which updates the list of pictures from ajax requests
     *
     */

	public function update_pictures_list() {

		$pictures = $this->msx_model->get_pictures();
		echo json_encode( array("pictures" => $pictures) );
	}



    /**
     *
     * Controller which gets the last action requested
     *
     */

	public function get_last_action() {

		$actions = $this->msx_model->get_actions();

		if($actions != FALSE) {
			$action = $actions[0];
			for($i=0; $i<count($actions); $i++) {
				$this->msx_model->delete_action( array("id" => $actions[$i]->{'id'}) );
			}
		}
		else
			$action = 0;

		echo json_encode( array("action" => $action) );
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