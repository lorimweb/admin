<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once (APPPATH . 'core/MY_Controller_CRUD.php');

/**
 * Esta classe que controla os Banneres do site
 *
 * @category  Site
 * @package   Controllers
 * @author    Gihovani Filipp Pereira Demétrio <gihovani@gmail.com>
 * @copyright 2014 Quantity LTDA
 * @license   http://gg2.com.br/license.html GG2
 * @version   Release: 1.0
 * @link      http://gg2.com.br
 */
class Banneres extends MY_Controller_CRUD 
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
		$this->load->model('banneres_model');
		$this->meu_model = $this->banneres_model;
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
			regra_validacao(
				'imagem', 
				'Imagem <small>('.$this->meu_model->dimensoes.'px / jpg,png,gif,swf)</small>',
				'trim|callback_envia_arquivo[imagem,'.$this->meu_model->pasta.'/,0,gif|jpg|jepg|png|swf]', 
				'class="col-md-6"', 
				'', 
				'file'
			),
			regra_validacao('nome', 'Nome', '', 'class="col-md-6"'),
			regra_validacao('link', 'link', 'trim', 'class="col-md-4"'),
			regra_validacao('ordem', 'Ordem', 'trim', 'class="col-md-4"', '', 'number'),
			regra_validacao('ativo', 'Ativo', '', 'class="col-md-4"', '', 'select', sim_nao()),
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
			'img' => 'Imagem',
			'dt_registro' => 'DT Registro',
			'nome' => 'Nome',
			'link' => 'Link',
			'ordem' => 'Ordem',
			'ativo' => 'Ativo',
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
		$data['enctype'] = 'multipart/form-data';
		return $data;
	}
	/**
	 * Função executada apos salvar os dados, serve para decidir o que fazer apos a operação
	 * ser feita com sucesso.
	 *
	 * @param array   $id     as configurações ou o id do item identifcador
	 * @param array   $popup  as configurações do popup
	 * @param boolean $return se é para redirecionar ou se é para retornar o link
	 *
	 * @return void
	protected function redireciona_salvo($id = 0, $popup = NULL, $return = TRUE)
	{
		$data = parent::redireciona_salvo($id, $popup, $return);
		$link = $this->meu_model->imagem_link_recortar($id);
		redirect($link);
	}
	 */
	/**
	 * Função sobrescrita para adicionar nos botões padrão do formulário.
	 * o botão de recortar a imagem.
	 *
	 * @param string  $id     o id do item identifcador
	 * @param boolean $voltar se é para mostrar o botão voltar
	 *
	 * @return array
	 */
	protected function form_botoes($id = 0, $voltar = TRUE)
	{
		$botoes = parent::form_botoes($id, $voltar);
		$link = $this->meu_model->imagem_link_recortar($id);
		if ($link)
			$botoes[] = '<a href="'.$link.'" class="btn btn-default"> ' .
				'<i class="glyphicon glyphicon-picture"></i> Recortar</a>'.PHP_EOL;

		return $botoes;
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

/* End of file banneres.php */
/* Location: ./controllers/banneres.php */