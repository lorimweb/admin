<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once (APPPATH . 'core/MY_Controller_crud.php');

/**
 * Esta classe que controla as Configurações do site
 *
 * @category  Site
 * @package   Controllers
 * @author    Gihovani Filipp Pereira Demétrio <gihovani@gmail.com>
 * @copyright 2014 Quantity LTDA
 * @license   http://gg2.com.br/license.html GG2
 * @version   Release: 1.0
 * @link      http://gg2.com.br
 */
class Configuracoes extends MY_Controller_crud {
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
		$this->load->model('configuracoes_model');
		$this->meu_model = $this->configuracoes_model;
		$this->_init_validacao();
	}
	/**
	 * funcao listar do crud desabilitada.
	 *
	 * @return void
	 *
	 */
	public function listar()
	{
		redirect('configuracoes/editar/1');
	}
	/**
	 * funcao adicionar do crud desabilitada.
	 *
	 * @return void
	 *
	 */
	public function adicionar()
	{
		redirect('configuracoes/editar/1');
	}
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
		$imgs = array('topo', 'lateral');
		foreach ($imgs as $value)
		{
			$link = $this->meu_model->imagem_link_recortar($id, $value);
			if ($link)
			{
				$botoes[] = '<a href="'.$link.'" class="btn btn-default"> ' .
					'<i class="glyphicon glyphicon-picture"></i> IMG '.ucwords($value).'</a>'.PHP_EOL;
			}
		}
		return $botoes;
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
				'imagem_topo',
				'Imagem Topo <small>('.$this->meu_model->dimensoes_topo.'px / jpg,png,gif,swf)</small>',
				'trim|callback_envia_arquivo[imagem_topo,'.$this->meu_model->pasta.'/,0,gif|jpg|jepg|png|swf]',
				'class="col-md-6"',
				'',
				'file'
			),
			regra_validacao('link_topo', 'Link da Imagem Topo', 'trim|required', 'class="col-md-6"', '', 'url'),
			regra_validacao(
				'imagem_lateral',
				'Imagem Lateral <small>('.$this->meu_model->dimensoes_lateral.'px / jpg,png,gif,swf)</small>',
				'trim|callback_envia_arquivo[imagem_lateral,'.$this->meu_model->pasta.'/,0,gif|jpg|jepg|png|swf]',
				'class="col-md-6"',
				'',
				'file'
			),
			regra_validacao('link_lateral', 'Link da Imagem Lateral', 'trim|required', 'class="col-md-6"', '', 'url'),
			regra_validacao('email', 'E-mail <small>(recebe os contatos)</small>', 'trim|required|valid_email', 'class="col-md-6"', '', 'email'),
			regra_validacao('googlemaps', 'URL Google Maps', 'trim', '', '', 'textarea')
		);
	}
	/**
	 * inicializa e configura os filtros que vieram do formulário da busca
	 * tambem configura o html do formulario da busca (listagem desabilitada nesse módulo)
	 * 
	 * @param array  $valores os valores que vieram via get do formulário de busca
	 * @param string $url     a url base do formulário de busca
	 *
	 * @return NULL
	 *
	 */
	protected function init_filtros($valores = array(), $url = '')
	{
		return NULL;
	}
}

/* End of file configuracoes.php */
/* Location: ./controllers/configuracoes.php */