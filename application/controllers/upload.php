<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Upload extends CI_Controller {

	function __construct() {
		parent::__construct();

		$this->load->model('photo_model');
		$this->load->helper(array('url', 'form'));
	}

	public function index() {
		$this->load->view('upload_form', array('error' => ' ' ));
	}

	public function do_upload() {
		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']	= '4000';
		$config['max_width']  = '4000';
		$config['max_height']  = '4000';

		$this->load->library('upload', $config);

		if ($this->upload->do_upload()) {
			$file_info = $this->upload->data();
			$this->photo_model->resize_sync(json_encode($file_info));

			$data = array('upload_data' => $file_info);

			$this->load->view('upload_success', $data);
		} else {
			$error = array('error' => $this->upload->display_errors());

			$this->load->view('upload_form', $error);
		}
	}

	// php /Library/WebServer/Documents/gearman/codeigniter/index.php upload worker
	public function worker() {
		$this->load->model('workers_model');
		$this->workers_model->gearman_worker();
	}

}