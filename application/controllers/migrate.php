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
	 * Função padrão que chama a instalação.
	 * 
	 * @return void
	 */
	public function index()
	{
		$this->install();
	}
	/**
	 * Instala ou atualiza o sistema de acordo com a versão
	 *
	 * @return void
	 *
	 */
	public function install()
	{
		$this->migration->version(1);
		$this->output->set_output(utf8_decode('Instalação feita com louvor!'));
	}
	/**
	 * Desinstala o sistema de acordo com a versão
	 *
	 * @return void
	 *
	 */
	public function uninstall()
	{
		$this->migration->version(0);
		require_once(APPPATH . 'migrations/001_cria_estrutura_inicial.php');
		$classe = new Migration_cria_estrutura_inicial();
		$classe->down();
		$this->output->set_output(utf8_decode('Desinstalação feita com louvor!'));
	}
}

/* End of file migrate.php */
/* Location: ./controllers/migrate.php */