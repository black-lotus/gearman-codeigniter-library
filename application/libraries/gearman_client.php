<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed gearman');

/**
 *
 * Class to utilize Gearman http://gearman.org/
 *
 * @package     CodeIgniter
 * @author 		Romdoni Agung Purbayanto <donnydiunindra@gmail.com>
 * @link 		https://github.com/black-lotus/gearman-codeigniter-library
 */
class Gearman_client {

	/**
     * ci
     *
     * @param instance object
     */
	private $CI = null;

	/**
     * gearman client
     *
     * @param instance object
     */
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

	public function set_created_callback($callback) {
		if ($callback) {
			$this->instance->setCreatedCallback($callback);
		}
	}

	public function set_data_callback($callback) {
		if ($callback) {
			$this->instance->setDataCallback($callback);
		}
	}

	public function set_status_callback($callback) {
		if ($callback) {
			$this->instance->setStatusCallback($callback);
		}
	}

	public function set_complete_callback($callback) {
		if ($callback) {
			$this->instance->setCompleteCallback($callback);
		}
	}

	public function set_fail_callback($callback) {
		if ($callback) {
			$this->instance->setFailCallback($callback);
		}
	}

	public function addTask($task_name = null, $task_params = null, $context = null, $unique = null) {
		if ($task_name) {
			$this->instance->addTask($task_name, $task_params, $context, $unique);
		}
	}

}