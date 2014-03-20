<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Esta classe é serve para a assinatura das listagens
 * 
 * @category  GG2_Admin
 * @package   Models
 * @author    Gihovani Filipp Pereira Demétrio <gihovani@gmail.com>
 * @copyright 2012-2014 GG2 Soluções
 * @license   http://gg2.com.br/license.html GG2 Soluções
 * @version   Release: 1.0
 * @link      http://gg2.com.br
 */
abstract class MY_Controller_list extends MY_Controller
{
	/**
	 * Nome do model padrão da classe.
	 * 
	 * @var model
	 */
	protected $meu_model;
	/**
	 * Nome do campo da ordenação.
	 * 
	 * @var string
	 */
	protected $ordenar_por;
	/**
	 * O sentido da ordenação.
	 * 
	 * @var string
	 */
	protected $ordenar_sentido;
	/**
	 * Os cabecalhos da listagem.
	 * 
	 * @var array
	 */
	protected $cabecalho = array();

	/**
	 * Construtor que inicializa a classe pai MY_Controller
	 *
	 * @param array $parametros os parametro de configuracao passados para a classe pai
	 *
	 * @return void
	 */
	public function __construct($parametros = NULL)
	{
		parent::__construct($parametros);
	}
	/**
	 * Assinatura da funcao que inicializa os filtros da listagem
	 *
	 * @param array  $valores as configuracoes dos filtros
	 * @param string $url     a url base da listagem
	 *
	 * @return void
	 */
	abstract protected function init_filtros($valores = array(), $url = '');
	/**
	 * Exportar para xls a listagem
	 *
	 * @return void
	 */
	public function exportar()
	{
		$this->load->helper('download');
		$filtros = $this->init_filtros($this->input->get('gg2-f'), '');
		$lista = $this->meu_model->lista($filtros->parametros_ci_where());
		$data = $this->init_listagem($lista['itens'], '', TRUE);
		$name = $this->modulo.'-'.date('Y-m-d').'.xls';
		force_download($name, ($data));
	}
	/**
	 * Mostrar o html da listagem 
	 *
	 * @param string $ordenar_por     o campo da ordenacao passado como parametro
	 * @param string $ordenar_sentido o sentido da ordenacao passado como parametro
	 *
	 * @return void
	 */
	public function listar($ordenar_por = 1, $ordenar_sentido = 'desc')
	{
		$this->ordenar_por = $ordenar_por;
		$this->ordenar_sentido = strtolower($ordenar_sentido);

		$data = $this->init_listar();
		$data['titulo'] = $this->meu_model->titulo;
		$this->gg2_layouts
			->navegacao($this->meu_model->titulo, $this->modulo . '/' . $this->acao, '1')
			->view('layouts/listar', $data);
	}
	/**
	 * Os botões padrão do formulario html
	 *
	 * @return string
	 */
	protected function botoes_filtro()
	{
		$botoes = array();
		if (tem_permissao($this->modulo, 'adicionar'))
		{
			$botoes[] = '<a class="btn btn-default" id="adicionar_' . $this->modulo . '" href="' . site_url($this->modulo . '/adicionar') . '"> '.
				'<i class="glyphicon glyphicon-plus"></i> Adicionar Novo</a>';
		}
		if (tem_permissao($this->modulo, 'exportar'))
		{
			$query = $this->input->query_string();
			$botoes[] = ' <a class="btn btn-default" id="exportar_xls" href="' . site_url($this->modulo . '/exportar/?' . $query) .'"> '.
				'<i class="glyphicon glyphicon-download-alt"></i> Exportar Dados</a>';
		}

		return implode(PHP_EOL, $botoes);
	}
	/**
	 * Os botões padrão da que vao aparecer nos itens da listagem
	 *
	 * @return array
	 */
	protected function botoes_listar()
	{
		$botoes = array();
		if(tem_permissao($this->modulo, 'ver'))
		{
			$botoes[] = '<a href="'.site_url($this->modulo . '/ver/[id]').'" title="Vizualizar Item ID: [id]"> '.
				'<i class="glyphicon glyphicon-folder-open"></i></a>';
		}
		if(tem_permissao($this->modulo, 'editar'))
		{
			$botoes[] = '<a href="'.site_url($this->modulo . '/editar/[id]').'" title="Alterar Item ID: [id]"> ' .
				'<i class="glyphicon glyphicon-pencil"></i></a>';

		}
		if(tem_permissao($this->modulo, 'remover'))
		{
			$botoes[] = '<a href="'.site_url($this->modulo . '/remover/[id]').'" title="Remover Item ID: [id]"> ' .
				'<i class="glyphicon glyphicon-trash"></i></a>';
		}

		return $botoes;
	}
	/**
	 * Inicializacao da acao de listagem
	 *
	 * @param array   $itens        as linhas da listagem
	 * @param string  $url          a url base da listagem
	 * @param boolean $exportar     se é uma listagem para exportacao
	 * @param array   $selecionavel configuracao de selecao
	 *
	 * @return object
	 */
	protected function init_listagem($itens = array(), $url = '', $exportar = FALSE, $selecionavel = array())
	{
		$config = array('itens' => $itens);
		if ( ! $exportar)
		{
			$config['botoes'] = $this->botoes_listar();
			$config['cabecalhos'] = isset($this->cabecalho['listar']) ? $this->cabecalho['listar'] : $this->cabecalho;
			$config['selecionavel'] = empty($selecionavel) ? array('chave' => $this->meu_model->id, 'display' => 'none') : $selecionavel;
			$config['url'] = $url;
			$config['ordenar_por'] = pega_chave_array($config['cabecalhos'], ($this->ordenar_por - 1));
			$config['ordenar_sentido'] = $this->ordenar_sentido;

			return $this->gg2_listagem->init($config)->html();
		}
		else
		{
			$config['cabecalhos'] = isset($this->cabecalho['exportar']) ? $this->cabecalho['exportar'] : $this->cabecalho;

			return $this->gg2_listagem->init($config)->xls();
		}
	}
	/**
	 * Inicializacao da lista em html
	 *
	 * @return array
	 */
	protected function init_listar()
	{
		$data = array();
		$offset = $this->input->get('per_page');
		$url = site_url($this->modulo.'/'.$this->acao.'/'.$this->ordenar_por.'/'.$this->ordenar_sentido);

		$filtros = $this->init_filtros($this->input->get('gg2-f'), $url);
		$parametros_url = $filtros->parametros_url();

		$lista = $this->meu_model->lista($filtros->parametros_ci_where(), $this->ordenar_por, $this->ordenar_sentido, $offset, N_ITENS_PAGINA);
		$data['paginacao'] = $this->init_paginacao($lista['num_itens'], $url.'?'.$parametros_url);

		$url = site_url($this->modulo.'/'.$this->acao.'/[sort_by]/[sort_order]').'?'.$parametros_url;

		$data['num_itens'] = ($offset + 1) . ' - ' . (
			($offset + N_ITENS_PAGINA) > $lista['num_itens'] ? $lista['num_itens'] : $offset + N_ITENS_PAGINA
		) . ' de '.$lista['num_itens'];

		$data['listagem'] = $this->init_listagem($lista['itens'], $url);
		$data['filtro'] = $filtros->formulario_html();

		$data['acoes'] = $this->init_acoes();

		return $data;
	}
	/**
	 * Inicializacao das acoes possiveis da lista
	 *
	 * @return array
	 */
	protected function init_acoes()
	{
		$data = array();
		$data['exportar'] 	= $this->modulo.'/exportar/';
		$data['adicionar'] 	= $this->modulo.'/adicionar/';
		$data['ver'] 		= $this->modulo.'/ver/';
		$data['editar'] 	= $this->modulo.'/editar/';
		$data['remover'] 	= $this->modulo.'/remover/';

		return $data;
	}
	/**
	 * Inicializacao da biblioteca de paginacao
	 *
	 * @param integer $total_itens a quantidade de itens
	 * @param string  $url         a url base da listagem
	 *
	 * @return string
	 */
	protected function init_paginacao($total_itens, $url)
	{
		$this->load->library('pagination');
		$config = array(
			'page_query_string' => TRUE,
			'base_url' 			=> $url,
			'total_rows' 		=> $total_itens,
			'per_page' 			=> N_ITENS_PAGINA,
			'uri_segment' 		=> 5,
			'first_link'		=> '&larr;',
			'last_link'			=> '&rarr;',
			'next_link'			=> '&raquo;',
			'prev_link'			=> '&laquo;',
			'first_tag_open' 	=> '<li>',
			'first_tag_close'	=> '</li>',
			'last_tag_open' 	=> '<li>',
			'last_tag_close'	=> '</li>',
			'next_tag_open' 	=> '<li>',
			'next_tag_close'	=> '</li>',
			'prev_tag_open' 	=> '<li>',
			'prev_tag_close'	=> '</li>',
			'num_tag_open' 		=> '<li>',
			'num_tag_close'		=> '</li>',
			'cur_tag_open' 		=> '<li class="active"><a href="#">',
			'cur_tag_close'		=> '<span class="sr-only">(current)</span></a></li>',
		);
		$this->pagination->initialize($config);

		return $this->pagination->create_links();
	}
}

/* End of file MY_Controller_list.php */
/* Location: ./core/MY_Controller_list.php */