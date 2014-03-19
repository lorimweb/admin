<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once (APPPATH . 'core/MY_Controller_CRUD.php');

/**
 * Esta classe que controla os Menus do painel administrativo
 *
 * @category  Site
 * @package   Controllers
 * @author    Gihovani Filipp Pereira Demétrio <gihovani@gmail.com>
 * @copyright 2014 Quantity LTDA
 * @license   http://gg2.com.br/license.html GG2
 * @version   1.0
 * @link      http://gg2.com.br
 */
class Admins_menus extends MY_Controller_CRUD 
{
	/**
	 * Controi a classe e inicializa os parametros do crud
	 * como o cabecalho da listagem as regras de validacao
	 *
	 * @return void
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('admins_menus_model');
		$this->meu_model = $this->admins_menus_model;
		$this->_init_cabecalho();
		$this->_init_validacao();
	}
	
	/**
	 * inicializa/configura as regras de validação e os campos do formulário dinamico.
	 *
	 * @return void
	 *
	 */
	private function _init_validacao()
	{
		$unico = ($this->acao === 'adicionar') ? '|is_unique['.$this->meu_model->tabela.'.login' : '';
		$this->validacao['adicionar'] = array(
			regra_validacao('titulo', 'Título', 'trim|required'.$unico, 'class="col-md-4"'),
			regra_validacao('link', 'Link', '', 'class="col-md-4"'),
			regra_validacao('ativo', 'Ativo', '', 'class="col-md-4"', '', 'select', sim_nao()),
		);
		$this->validacao['ver'] = $this->validacao['editar'] = $this->validacao['adicionar'];
		$this->validacao['editar'][0]['rules'] = '';
	}
	/**
	 * inicializa/configura as colunas da listagem.
	 *
	 * @return void
	 *
	 */
	private function _init_cabecalho()
	{
		$this->cabecalho = array(
			'id' 	 => 'ID',
			'titulo' => 'Título',
			'link'	 => 'Link',
			'ativo'	 => 'Ativo',
		);
	}
	/**
	 * inicializa e configura os filtros que vieram do formulário da busca
	 * tambem configura o html do formulario da busca
	 * 
	 * @param array  $valores os valores que vieram via get do formulário de busca
	 * @param string $url     a url base do formulário de busca
	 *
	 * @return Gg2_filtro
	 *
	 */
	protected function init_filtros($valores = array(), $url = '')
	{
		$itens[] = filtro_config('id', 'ID', 'where');
		$itens[] = filtro_config('titulo', 'Título', 'like');
		$itens[] = filtro_config('link', 'Link', 'like');
		$itens[] = filtro_config('ativo', 'Ativo', 'where', 'select', sim_nao());

		$filtros = $this->gg2_filtros->init($itens, $valores, $url, count($itens), $this->botoes_filtro());
		return $filtros;
	}
}

/* End of file admins_menus.php */
/* Location: ./controllers/admins_menus.php */