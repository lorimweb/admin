<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once (APPPATH . 'core/MY_Controller_crud.php');

/**
 * Esta classe que controla os Administradores do site
 *
 * @category  Site
 * @package   Controllers
 * @author    Gihovani Filipp Pereira Demétrio <gihovani@gmail.com>
 * @copyright 2014 Quantity LTDA
 * @license   http://gg2.com.br/license.html GG2
 * @version   Release: 1.0
 * @link      http://gg2.com.br
 */
class Admins extends MY_Controller_crud {
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
		$this->load->model('admins_model');
		$this->meu_model = $this->admins_model;
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
			regra_validacao('nome', 'Nome', '', 'class="col-md-6"'),
			regra_validacao('login', 'Login', 'trim|required'.$unico, 'class="col-md-6"'),
			regra_validacao('senha', 'Senha', '', 'class="col-md-6"'),
			regra_validacao('ativo', 'Ativo', '', 'class="col-md-6"', '', 'select', sim_nao()),
		);
		$this->validacao['ver'] = $this->validacao['editar'] = $this->validacao['adicionar'];
		$this->validacao['editar'][1]['rules'] = '';
		$this->validacao['editar'][2]['rules'] = 'trim';
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
			'listar' => array(
				'id' 	=> 'ID',
				'nome'	=> 'Nome',
				'login'	=> 'Login',
				'ativo'	=> 'Ativo',
			),
			'exportar' => array(
				'id' 	=> 'ID',
				'nome'	=> 'Nome',
				'login'	=> 'Login',
				'senha'	=> 'Senha',
				'ativo'	=> 'Ativo',
			)
		);
	}
	/**
	 * Função sobrescrita que retorna todos os dados postados
	 * 
	 * @param string $prefix os prefixos das chaves do array
	 * 
	 * @return array
	 */
	protected function dados_formulario($prefix = '')
	{
		$data = parent::dados_formulario($prefix);
		if ( ! empty($data['senha']))
			$data['senha'] = $this->meu_model->criptografa($data['login'], $data['senha']);

		return $data;
	}
}

/* End of file admins.php */
/* Location: ./controllers/admins.php */