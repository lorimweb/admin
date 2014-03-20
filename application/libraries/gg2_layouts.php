<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Esta classe é uma biblioteca responsável por:
 * controlar os layouts
 * 
 * @category  GG2_Admin
 * @package   Libraries
 * @author    Gihovani Filipp Pereira Demétrio <gihovani@gmail.com>
 * @copyright 2012-2014 GG2 Soluções
 * @license   http://gg2.com.br/license.html GG2 Soluções
 * @version   Release: 1.0
 * @link      http://gg2.com.br
 */
class Gg2_layouts
{
	/**
	 * Instancia do core do framework CodeIgniter.
	 * 
	 * @var CodeIgniter
	 */
	private $_ci;
	/**
	 * Titulo da pagina, Nome da Empresa por padrão.
	 * 
	 * @var string
	 */
	private $_titulo = '';
	/**
	 * Palavras Chave da pagina.
	 * 
	 * @var array
	 */
	private $_palavras_chave = array();
	/**
	 * Descrição da pagina.
	 * 
	 * @var string
	 */
	private $_descricao = '';
	/**
	 * Barra de navegação (breadcrumb).
	 * 
	 * @var array
	 */
	private $_barra_navegacao = array();
	/**
	 * Arquivos que serão adicionados na página como os estilos css e javascripts.
	 * 
	 * @var array
	 */
	private $_arquivos_extras = array('css' => array(), 'js' => array());

	/**
	 * Construtor que inicializa a biblioteca de Layout
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->_ci =& get_instance();
		$this->_init();
		$this->titulo();
		$this->descricao();
		$this->palavras_chave();
	}
	/**
	 * Inicializa a classe com os arquivos extra padrão
	 *
	 * @return void
	 */
	private function _init()
	{
		$this
			->arquivos_extras(CSS . 'bootstrap.min.css')
			->arquivos_extras(CSS . 'aplicacao.css')
			->arquivos_extras(JS . 'jquery-1.11.0.min.js')
			->arquivos_extras(JS . 'jquery.plugins.min.js')
			->arquivos_extras(JS . 'bootstrap.min.js')
			->arquivos_extras(JS . 'aplicacao.min.js');
	}

	/**
	 * Função para configurarmos o título da página
	 *
	 * @param string $titulo       o título da página
	 * @param string $separador    o caracter separador
	 * @param string $nome_empresa o nome da empresa
	 * 
	 * @return Gg2_layouts
	 */
	public function titulo($titulo = '', $separador = ' - ', $nome_empresa = NM_EMPRESA)
	{
		$this->_titulo = ( ! empty($titulo) ? $titulo . $separador : '') . $nome_empresa;

		return $this;
	}

	/**
	 * Função para configurarmos as palavras chave da página
	 *
	 * @param array $palavras as keywords da página
	 * 
	 * @return Gg2_layouts
	 */
	public function palavras_chave($palavras = NM_EMPRESA)
	{
		$this->_palavras_chave = (is_array($palavras)) ? implode(', ', $palavras) : $palavras;

		return $this;
	}

	/**
	 * Função para configurarmos a description chave da página
	 *
	 * @param array $conteudo o conteudo da descricao
	 * 
	 * @return Gg2_layouts
	 */
	public function descricao($conteudo = '')
	{
		$this->_descricao  = $conteudo;
		return $this;
	}

	/**
	 * Função para adicionar os javascript ou os css na página
	 *
	 * @param string  $caminho  o caminho o arquivo
	 * @param boolean $remove   se queremos remover o arquivo
	 * @param string  $url_base a url base
	 * 
	 * @return Gg2_layouts
	 */
	public function arquivos_extras($caminho, $remove = FALSE, $url_base = URL_HTTP)
	{
		if ($url_base)
			$caminho = $url_base . $caminho;

		if (preg_match('/js$/', $caminho))
		{
			if ( ! $remove)
				$this->_arquivos_extras['js'][$caminho] = $caminho . '?v=' . VERSAO;
			else
				unset($this->_arquivos_extras['js'][$caminho]);
		}
		elseif (preg_match('/css$/', $caminho))
		{
			if ( ! $remove)
				$this->_arquivos_extras['css'][$caminho] = $caminho . '?v=' . VERSAO;
			else
				unset($this->_arquivos_extras['css'][$caminho]);

		}
		return $this;
	}

	/**
	 * Função para um breadcumb/barra de navegacao
	 *
	 * @param string  $titulo   o titulo
	 * @param string  $url      a url do caminho
	 * @param integer $ativo    se este item está ativo
	 * @param string  $url_base a url base
	 * 
	 * @return Gg2_layouts
	 */
	public function navegacao($titulo, $url, $ativo = 0, $url_base = URL_HTTP)
	{
		if ($url_base)
			$url = $url_base . $url;

		$this->_barra_navegacao[] = (object) array('titulo' => $titulo, 'url' => $url, 'ativo' => $ativo);
		return $this;
	}

	/**
	 * Função que carrega a view/html
	 *
	 * @param string $view       o caminho do arquivo da view
	 * @param arrat  $parametros os dados para configurar a view
	 * @param string $layout     o caminho do arquivo do layout padrão
	 * 
	 * @return void
	 */
	public function view($view, $parametros = array(), $layout = LAYOUT)
	{
		// carrega o conteudo da view com os parametros passados
		$conteudo = $this->_ci->load->view($view, $parametros, TRUE);
		if ($this->_ci->input->is_ajax_request())
		{
			$this->_ci->output->set_output($conteudo);
		}
		else
		{
			// agora carrega o conteudo do layout e com o conteudo da view
			$params = array(
				'cabecalho' => $this->_cabecalho(),
				'rodape' => $this->_rodape(),
				'conteudo' => $conteudo,
				'navegacao' => $this->_barra_navegacao,
			);
			$this->_ci->load->view('layouts/' . $layout, $params);
		}
	}

	/**
	 * Função que configura e carrega o cabecalho da pagina
	 *
	 * @return string
	 */
	private function _cabecalho()
	{
		$data = array();
		$data['css'] = $this->_arquivos_extras['css'];
		$data['titulo'] = $this->_titulo;
		$data['descricao'] = $this->_descricao;
		$data['palavras_chave'] = $this->_palavras_chave;
		return $this->_ci->load->view('layouts/cabecalho', $data, TRUE);
	}

	/**
	 * Função que configura e carrega o rodape da pagina
	 *
	 * @return string
	 */
	private function _rodape()
	{
		$data = array();
		$data['js'] = $this->_arquivos_extras['js'];
		return $this->_ci->load->view('layouts/rodape', $data, TRUE);
	}
}

/* End of file gg2_layouts.php */
/* Location: ./libraries/gg2_layouts.php */