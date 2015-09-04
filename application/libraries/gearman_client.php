<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed gearman');

/**
 *
 * Class to utilize Gearman http://gearman.org/
 * @author Romdoni Agung Purbayanto <donnydiunindra@gmail.com>
 */
class Gearman_client {

	private $CI = null;

	var $instance = null;

	function __construct() {
		$this->CI =& get_instance();
		$this->CI->config->load('gearman');

		if (!$this->is_supported()) {
            return false;
        }

		$this->__setup();
	}

	/*
	* function to check gearman extension
	*/
	private function is_supported()
    {
        if ( ! extension_loaded('gearman')) {
            log_message('error', 'Please load the gearman extension in your php.ini');
            return false;
        }
        return true;
    }

    /*
	* function to setup gearman client
    */
	private function __setup() {
		$this->instance = new GearmanClient();

		$servers = $this->CI->config->item('gearman_client_servers');

		$total_server = count($servers);
		if ($total_server > 0) {
			foreach ($servers as $key => $value) {
				if (!$this->instance->addServer($value['ip'], $value['port'])) {
					log_message('error', 'Cannot connect to gearman server');
				}
			}
		} else {
			$this->instance->addServer();
		}
	}

	public function do_background($function_name, $params) {
		$res = $this->instance->doBackground($function_name, $params);

		if (!$res) {
			return FALSE;
		}

		return TRUE;
	}

	public function do_normal($function_name, $params) {
		do {
			$res = $this->instance->doNormal($function_name, $params);
			switch ($this->instance->returnCode()) {
				case GEARMAN_WORK_FAIL:
					return FALSE;
					break;
				
				case GEARMAN_SUCCESS:
					return $res;
					break;
			}
		} while ($this->instance->returnCode() != GEARMAN_SUCCESS);

		return TRUE;
	}

	

}