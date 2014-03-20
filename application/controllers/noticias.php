<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once (APPPATH . 'core/MY_Controller_CRUD.php');

/**
 * Esta classe que controla os Notícias do site
 *
 * @category  Site
 * @package   Controllers
 * @author    Gihovani Filipp Pereira Demétrio <gihovani@gmail.com>
 * @copyright 2014 Quantity LTDA
 * @license   http://gg2.com.br/license.html GG2
 * @version   Release: 1.0
 * @link      http://gg2.com.br
 */
class Noticias extends MY_Controller_CRUD
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
		$this->load->model('noticias_model');
		$this->meu_model = $this->noticias_model;
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
				'class="col-md-3"',
				'',
				'file'
			),
			regra_validacao('titulo', 'Título', '', 'class="col-md-5"'),
			regra_validacao('slug', 'Slug', 'trim', 'class="col-md-4"'),
			regra_validacao('dt_registro', 'Data Registro', 'trim', 'class="col-md-3"', '', 'date'),
			regra_validacao('ordem', 'Ordem', 'trim', 'class="col-md-3"', '', 'number'),
			regra_validacao('destaque', 'Destaque', '', 'class="col-md-3"', '', 'select', sim_nao()),
			regra_validacao('ativo', 'Ativo', '', 'class="col-md-3"', '', 'select', sim_nao()),
			regra_validacao('chamada', 'Chamada', 'trim', '', '', 'textarea'),
			regra_validacao('conteudo', 'Conteúdo', 'trim', '', 'class="ckeditor"', 'textarea'),
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
			'titulo' => 'Título',
			'slug' => 'Slug',
			'ordem' => 'Ordem',
			'destaque' => 'Destaque',
			'ativo' => 'Ativo',
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
		$data['slug'] = slug($data['titulo']);
		if ( ! empty($data['dt_registro']))
			$data['dt_registro'] = formata_data_mysql($data['dt_registro']).' 23:59:59';

		return $data;
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
		$this->gg2_layouts
			->arquivos_extras(JS . 'ckeditor/ckeditor.js')
			->arquivos_extras(JS . 'ckeditor/adapters/jquery.js');
		return $data;
	}
	/**
	 * Função executada apos salvar os dados, serve para decidir o que fazer apos a operação
	 * ser feita com sucesso.
	 *
	 * @param array   $config as configurações ou o id do item identifcador
	 * @param array   $popup  as configurações do popup
	 * @param boolean $return se é para redirecionar ou se é para retornar o link
	 *
	 * @return void
	 */
	protected function redireciona_salvo($config = 0, $popup = NULL, $return = TRUE)
	{
		$data = parent::redireciona_salvo($config, $popup, $return);
		$link = $this->meu_model->imagem_link_recortar($config);
		redirect($link);
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
		$itens[] = filtro_config('titulo', 'Título', 'like');
		$itens[] = filtro_config('destaque', 'Destaque', 'where', 'select', sim_nao());
		$itens[] = filtro_config('ativo', 'Ativo', 'where', 'select', sim_nao());

		$filtros = $this->gg2_filtros->init($itens, $valores, $url, count($itens), $this->botoes_filtro());
		return $filtros;
	}
}

/* End of file noticias.php */
/* Location: ./controllers/noticias.php */