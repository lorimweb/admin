<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once (APPPATH . 'core/MY_Controller_CRUD.php');
/**
 * Esta classe que controla as páginas do site
 *
 * @category  GG2_Admin
 * @package   Controllers
 * @author    Gihovani Filipp Pereira Demétrio <gihovani@gmail.com>
 * @copyright 2012-2014 GG2 Soluções
 * @license   http://gg2.com.br/license.html GG2 Soluções
 * @version   Release: 1.0
 * @link      http://gg2.com.br
 */
class Site extends MY_Controller {
	/**
	 * Controi a classe e inicializa a classe pai passado false para 
	 * não verificar se o admin está logado.
	 *
	 * @return void
	 *
	 */
	public function __construct()
	{
		parent::__construct(FALSE);
	}
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/site
	 *	- or -  
	 * 		http://example.com/index.php/site/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/site/<method_name>
	 * 
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 * 
	 * @return void
	 */
	public function index()
	{
		$this->load->view('site/index');
	}
}

/* End of file site.php */
/* Location: ./controllers/site.php */