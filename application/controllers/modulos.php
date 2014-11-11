<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once (APPPATH . 'core/MY_Controller_crud.php');

/**
 * Esta classe que controla os Módulos do site
 *
 * @category  Site
 * @package   Controllers
 * @author    Gihovani Filipp Pereira Demétrio <gihovani@gmail.com>
 * @copyright 2014 Quantity LTDA
 * @license   http://gg2.com.br/license.html GG2
 * @version   Release: 1.0
 * @link      http://gg2.com.br
 */
class Modulos extends MY_Controller_crud {
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
		$this->load->model('modulos_model');
		$this->meu_model = $this->modulos_model;
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
			regra_validacao('nome', 'Nome', '', 'class="col-md-5"'),
			regra_validacao('descricao', 'Descrição', '', 'class="col-md-5"'),
			regra_validacao('ativo', 'Ativo', '', 'class="col-md-2"', '', 'select', sim_nao()),
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
		$itens[] = filtro_config('id', 'ID', 'where');
		$itens[] = filtro_config('descricao', 'Descrição', 'like');
		$itens[] = filtro_config('ativo', 'Ativo', 'where', 'select', sim_nao());

		$filtros = $this->gg2_filtros->init($itens, $valores, $url, count($itens), $this->botoes_filtro());
		return $filtros;
	}
	/**
	 * Remove os dados da base de dados
	 * e retorna o numero de linhas removidas.
	 *
	 * @param string $id o valor do item identificador
	 * 
	 * @return integer
	 */
	protected function salva_remover($id = 0)
	{
		$this->load->model('modulos_acoes_model');
		$selecionados = ($id) ? array($id) : $this->input->post('selecionados');
		$this->modulos_acoes_model->excluir('modulo_id in ('.implode(',', $selecionados).')');
		return $this->meu_model->excluir($this->meu_model->id.' in ('.implode(',', $selecionados).')');
	}
}

/* End of file modulos.php */
/* Location: ./controllers/modulos.php */