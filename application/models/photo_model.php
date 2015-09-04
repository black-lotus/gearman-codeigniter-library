<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Photo_model extends CI_Model {

	function __construct() {
		parent::__construct();
		$this->load->library('gearman_client');
	}

	public function resize_sync($filename) {
		/* $gc = new GearmanClient();
		$gc->addServer();
		
		do {
			$res = $gc->doNormal('image_resize', $filename);
			switch ($gc->returnCode()) {
				case GEARMAN_WORK_FAIL:
					return FALSE;
					break;
				
				case GEARMAN_SUCCESS:
					return TRUE;
					break;
			}
		} while ($gc->returnCode() != GEARMAN_SUCCESS); */

		$res = $this->gearman_client->do_normal('image_resize', $filename);
		if (!$res) {
			return FALSE;
		}

		return TRUE;
	}

	public function resize_async($filename) {
		/*$gc = new GearmanClient();
		$gc->addServer();
		$res = $gc->doBackground('image_resize', $filename);

		if (!$res) {
			return FALSE;
		}*/

		$res = $this->gearman_client->do_background('image_resize', $filename);
		if (!$res) {
			return FALSE;
		}

		return TRUE;
	}

}