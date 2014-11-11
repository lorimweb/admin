<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once (APPPATH . 'core/MY_Controller_crud.php');

/**
 * Esta classe que controla os Grupos dos Administradores do painel administrativo
 *
 * @category  Site
 * @package   Controllers
 * @author    Gihovani Filipp Pereira Demétrio <gihovani@gmail.com>
 * @copyright 2014 Quantity LTDA
 * @license   http://gg2.com.br/license.html GG2
 * @version   Release: 1.0
 * @link      http://gg2.com.br
 */
class Admins_grupos extends MY_Controller_crud {
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
		$this->load->model('admins_grupos_model');
		$this->load->model('modulos_acoes_model');
		$this->meu_model = $this->admins_grupos_model;
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
			'ativo'	 => 'Ativo',
		);
	}
	/** 
	 * Função sobrescrita que faz a configuração extra do formulário.
	 *
	 * @param string $id    o valor do campo identificador
	 * @param object $dados os dados que serão preenchidos no formulário
	 *
	 * @return array
	 */
	protected function parametros_extra($id = 0, $dados = NULL)
	{
		$data = parent::parametros_extra($id, $dados);
		$data['form_html'] = $this->_carrega_extra_form($id, ($this->acao === 'ver'));
		return $data;
	}

	/**
	 * Função que adiciona os campos dos convenios no form do grupo
	 * 
	 * @param integer $grupo_id     o identificador do grupo
	 * @param boolean $desabilitado se o campo está desabilitado
	 *
	 * @return string
	 *
	 */
	private function _carrega_extra_form($grupo_id = 0, $desabilitado = FALSE)
	{
		$ret = '';
		// procedimentos
		$data = array();
		$data['desabilitado'] = $desabilitado;
		$permissoes = $this->modulos_acoes_model->lista(NULL, 2);
		$data['permissoes'] = $permissoes['itens'];
		$data['permissoes_ativos'] = ( ! empty($grupo_id)) ? $this->meu_model->lista_permissoes($grupo_id) : array();
		$ret .= $this->load->view('admins_grupos/permissoes', $data, TRUE);
		return $ret;
	}

	/**
	 * Salva os dados do formulário na base de dados
	 * e retorna o id do item inserido.
	 *
	 * @return integer
	 */
	protected function salva_adicionar()
	{
		$data = $this->dados_formulario($this->prefix);
		$permissoes = isset($data['permissoes']) ? $data['permissoes'] : array();
		unset($data['permissoes']);
		$id = $this->meu_model->adicionar($data);
		$this->_add_permissoes($permissoes, $id);
		return $id;
	}
	/**
	 * Altera os dados do formulário na base de dados
	 * e retorna o id do item.
	 *
	 * @param string $id o valor do item identificador
	 *
	 * @return integer
	 */
	protected function salva_editar($id)
	{
		$data = $this->dados_formulario($this->prefix);
		$permissoes = isset($data['permissoes']) ? $data['permissoes'] : array();
		unset($data['permissoes']);
		$this->meu_model->editar($data, array($this->meu_model->id => $id));
		$this->_add_permissoes($permissoes, $id);
		return $id;
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
		$selecionados = ($id) ? array($id) : $this->input->post('selecionados');
		foreach ($selecionados as $grupo_id)
		{
			$this->meu_model->excluir_permissoes($grupo_id);
		}
		return $this->meu_model->excluir($this->meu_model->id.' in ('.implode(',', $selecionados).')');
	}
	/**
	 * Função que adiciona os procedimentos para o grupo.
	 * 
	 * @param array   $itens    o array com os procedimentos
	 * @param integer $grupo_id o identificador do grupo
	 *
	 * @return void
	 *
	 */
	private function _add_permissoes($itens = array(), $grupo_id = 0)
	{
		if ( ! empty($grupo_id))
		{
			$this->meu_model->excluir_permissoes($grupo_id);
			if (is_array($itens))
			{
				foreach ($itens as $acao_id)
				{
					$this->meu_model->adicionar_permissoes($acao_id, $grupo_id);
				}
			}
		}
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
		$itens[] = filtro_config('ativo', 'Ativo', 'where', 'select', sim_nao());

		$filtros = $this->gg2_filtros->init($itens, $valores, $url, count($itens), $this->botoes_filtro());
		return $filtros;
	}
}

/* End of file admins_grupos.php */
/* Location: ./controllers/admins_grupos.php */