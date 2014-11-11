<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once (APPPATH . 'core/MY_Controller_crud.php');

/**
 * Esta classe que controla as Ações dos Módulos do site
 *
 * @category  Site
 * @package   Controllers
 * @author    Gihovani Filipp Pereira Demétrio <gihovani@gmail.com>
 * @copyright 2014 Quantity LTDA
 * @license   http://gg2.com.br/license.html GG2
 * @version   Release: 1.0
 * @link      http://gg2.com.br
 */
class Modulos_acoes extends MY_Controller_crud {
	/**
	 * Array com os módulos disponíveis.
	 * 
	 * @var array
	 */
	private $_modulos = array();
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
		$this->load->model('modulos_acoes_model');
		$this->load->model('modulos_model');
		$this->meu_model = $this->modulos_acoes_model;
		$this->_modulos = $this->modulos_model->options();
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
		$this->validacao = array(
			regra_validacao('modulo_id', 'Módulo', '', 'class="col-md-6"', '', 'select', $this->_modulos),
			regra_validacao('ativo', 'Ativo', '', 'class="col-md-6"', '', 'select', sim_nao()),
			regra_validacao('nome', 'Nome', '', 'class="col-md-6"'),
			regra_validacao('descricao', 'Descrição', '', 'class="col-md-6"'),
		);
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
			'id' => 'ID',
			'modulo' => 'Módulo',
			'nome' => 'Nome',
			'descricao' => 'Descrição',
			'ativo' => 'Ativo',
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
		$itens[] = filtro_config('a.id', 'ID', 'where');
		$itens[] = filtro_config('a.modulo_id', 'Módulo', 'where', 'select', $this->_modulos);
		$itens[] = filtro_config('a.descricao', 'Descrição', 'like');
		$itens[] = filtro_config('a.ativo', 'Ativo', 'where', 'select', sim_nao());

		$filtros = $this->gg2_filtros->init($itens, $valores, $url, count($itens), $this->botoes_filtro());
		return $filtros;
	}
}

/* End of file modulos_acoes.php */
/* Location: ./controllers/modulos_acoes.php */