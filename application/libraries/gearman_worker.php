<?php

class Gearman_worker {

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
		$this->instance = new GearmanWorker();

		$servers = $this->CI->config->item('gearman_worker_servers');

		$total_server = count($servers);
		if ($total_server > 0) {
			foreach ($servers as $key => $value) {
				if (!$this->instance->addServer($value['ip'], $value['port'])) {
					echo "Cannot connect to gearman server!! \n";
				} else {
					echo "Connection is successful!!\n";
				}
			}
		} else {
			$this->instance->addServer();
		}
	}

	public function add_function($function_name, $function) {
		$this->instance->addFunction($function_name, $function);
	}

}