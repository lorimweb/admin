<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Esta classe que controla a versão ou instala do sistema.
 *
 * @category  Site
 * @package   Controllers
 * @author    Gihovani Filipp Pereira Demétrio <gihovani@gmail.com>
 * @copyright 2014 Quantity LTDA
 * @license   http://gg2.com.br/license.html GG2
 * @version   Release: 1.0
 * @link      http://gg2.com.br
 */
class Migrate extends CI_Controller {
	/**
	 * Controi a classe e inicializa a classe
	 * e carrega a biblioteca migration
	 *
	 * @return void
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->library('migration');
	}
	/**
	 * Instala ou atualiza o sistema de acordo com a versão
	 *
	 * @return void
	 *
	 */
	public function index()
	{
		if ( ! $this->migration->current())
			show_error($this->migration->error_string());
		else
			$this->output->set_output(utf8_decode('Instalação feita com louvor!'));
	}
}

/* End of file migrate.php */
/* Location: ./controllers/migrate.php */