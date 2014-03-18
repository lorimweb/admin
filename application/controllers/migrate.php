<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migrate extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('migration');
	}

	public function index()
	{
		if ( ! $this->migration->current())
		{
			show_error($this->migration->error_string());
		}
		else
		{
			$this->output->set_output('Instalação feita com louvor!');
		}
	}
}

/* End of file migrate.php */
/* Location: ./controllers/migrate.php */