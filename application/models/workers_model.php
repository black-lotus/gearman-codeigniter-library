<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Workers_model extends CI_Model {

	function __construct() {
		parent::__construct();

		$this->load->library('gearman_worker');
	}

	public function gearman_worker() {
		/* $gm = new GearmanWorker();
		$gm->addServer();

		// add functions
		$gm->addFunction('image_resize', 'Workers_model::image_resize');
		$gm->addFunction('image_watermark', 'Workers_model::image_watermark');
		while ($gm->work()); */

		$this->gearman_worker->add_function('image_resize', 'Workers_model::image_resize');
		while($this->gearman_worker->instance->work());
	}

	static public function image_resize($job) {
		$CI =& get_instance();

		$CI->load->library('image_lib');

		$filename = json_decode($job->workload(), true);
		

		echo "file name : ". $filename['file_name'] . "\n";
		echo "resizing....... \n";

		$config = array(
				'source_image' => $filename['file_path'] .'/'. $filename['file_name'],
				'new_image' => $filename['file_path'] .'/resize/'. $filename['file_name'],
				'create_thumbnail' => TRUE,
				'maintain_ratio' => TRUE,
				'width' => 200,
				'height' => 200
			);
		$CI->image_lib->initialize($config);
		$CI->image_lib->resize();
		$CI->image_lib->clear();

		echo "done \n";
		echo "\n";

		return TRUE;
	}

	static public function image_watermark($job) {
		$filename = $job->workload();
	}


}