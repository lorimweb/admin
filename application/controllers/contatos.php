<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once (APPPATH . 'core/MY_Controller_crud.php');

/**
 * Esta classe que controla os contatos que vieram do site
 *
 * @category  Site
 * @package   Controllers
 * @author    Gihovani Filipp Pereira Demétrio <gihovani@gmail.com>
 * @copyright 2014 Quantity LTDA
 * @license   http://gg2.com.br/license.html GG2
 * @version   Release: 1.0
 * @link      http://gg2.com.br
 */
class Contatos extends MY_Controller_crud {
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
		$this->load->model('contatos_model');
		$this->meu_model = $this->contatos_model;
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
			regra_validacao('nome', 'Nome', '', 'class="col-md-6"'),
			regra_validacao('ativo', 'Ativo', '', 'class="col-md-6"', '', 'select', sim_nao()),
			regra_validacao('telefone1', 'Telefone 1', '', 'class="col-md-3"', '', 'tel'),
			regra_validacao('telefone2', 'Telefone 2', 'trim', 'class="col-md-3"', '', 'tel'),
			regra_validacao('email', 'E-mail', 'trim|required|valid_email', 'class="col-md-6"', '', 'email'),
			regra_validacao('conteudo', 'Conteúdo', 'trim', '', '', 'textarea')
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
			'dt_registro' => 'Dt Registro',
			'nome' => 'Nome',
			'telefone1' => 'Telefone 1',
			'telefone2' => 'Telefone 2',
			'email' => 'E-mail',
			'conteudo' => 'Conteúdo',
			'ativo' => 'Ativo'
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
		$itens[] = filtro_config('nome', 'Nome', 'like');
		$itens[] = filtro_config('email', 'E-mail', 'like');
		$itens[] = filtro_config('ativo', 'Ativo', 'where', 'select', sim_nao());

		$filtros = $this->gg2_filtros->init($itens, $valores, $url, count($itens), $this->botoes_filtro());
		return $filtros;
	}
}

/* End of file contatos.php */
/* Location: ./controllers/contatos.php */