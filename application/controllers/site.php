<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Esta classe que controla as páginas do site
 *
 * @category  GG2_Admin
 * @package   Controllers
 * @author    Gihovani Filipp Pereira Demétrio <gihovani@gmail.com>
 * @copyright 2012-2014 GG2 Soluções
 * @license   http://gg2.com.br/license.html GG2 Soluções
 * @version   Release: 1.0
 * @link      http://gg2.com.br
 */
class Site extends MY_Controller {
	/**
	 * Controi a classe e inicializa a classe pai passado false para 
	 * não verificar se o admin está logado.
	 *
	 * @return void
	 *
	 */
	public function __construct()
	{
		parent::__construct(FALSE);
		$this->load->model('configuracoes_model');
		$this->load->model('noticias_model');
		$this->load->model('paginas_model');
		$this->load->model('banneres_model');
	}
	/**
	 * Função que inicializa as configurações do layout
	 * 
	 * @return array configurações do layout
	 */
	private function _init()
	{
		$data = array();
		$data['config'] = $this->_config();
		return $data;
	}
	/**
	 * Função que pega as configurações basicas do site
	 * na base de dados
	 * 
	 * @return object configuracoes padrão
	 */
	private function _config()
	{
		$config = $this->configuracoes_model->id(1);
		return $config;
	}
	/**
	 * Função que retorna todas as notícias.
	 * 
	 * @param integer $quantidade quantidade de registros retornados
	 * @param char    $destaque   (S, N)
	 * @param string  $url        a url padrão da navegação
	 * 
	 * @return array              um array com os objetos das noticias
	 */
	private function _noticias($quantidade = NULL, $destaque = NULL, $url = FALSE)
	{
		$ret = array();
		$inicio = intval($this->input->get('per_page'));
		$filtro = 'ativo = "S"';
		$ordem = '1';
		$ordem_tipo = 'desc';
		if ( ! is_null($destaque)) $filtro .= ' and destaque = "'.$destaque.'"';
		if (is_null($quantidade) && ! empty($url)) $quantidade = N_ITENS_SITE;

		$ret = $this->noticias_model->lista($filtro, $ordem, $ordem_tipo, $inicio, $quantidade);

		if ($url)
			$ret['paginacao'] = $this->_paginacao($ret['num_itens'], $url);
		else
			$ret = $ret['itens'];
		return $ret;
	}
	/**
	 * Função que retorna todas os banneres.
	 * 
	 * @return array um array com os objetos dos banneres
	 */
	private function _banneres()
	{
		$filtro = 'ativo = "S"';
		$ordem = 'ordem';
		$ordem_tipo = 'ASC';
		$lista = $this->banneres_model->lista($filtro, $ordem, $ordem_tipo);
		return $lista['itens'];
	}
	/**
	 * Index Page a home do site.
	 *
	 * @return void
	 */
	public function index()
	{
		$data = $this->_init();
		$data['banneres'] = $this->_banneres();
		$this->_carrega_view($data, 'Página Inicial');
	}
	/**
	 * Página de noticias.
	 *
	 * @param string $slug o slug da notícia (pode ser vazio quando for para listar todas)
	 *
	 * @return void
	 */
	public function noticias($slug = '')
	{
		$data = $this->_init();
		$titulo = 'Notícias';
		$data['dados'] = $this->noticias_model->registro('slug', $slug);
		if ( ! empty($data['dados']))
		{
			$titulo = $data['dados']->titulo;
			$quantidade = 3;
		}
		else
		{
			$quantidade = NULL;
		}
		$data['noticias'] = $this->_noticias($quantidade, NULL, site_url('site/noticias/?'));
		$this->_carrega_view($data, $titulo);
	}
	/**
	 * Carrega paginação
	 *
	 * @param int    $total_itens o numero total de registros.
	 * @param string $url         a url que esta sendo acessada.
	 *
	 * @return string             html da paginacao
	 */
	private function _paginacao($total_itens, $url)
	{
		$this->load->library('pagination');
		$config = array(
			'page_query_string' => TRUE,
			'base_url' 			=> $url,
			'total_rows' 		=> $total_itens,
			'per_page' 			=> N_ITENS_SITE,
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
	/**
	 * Página estáticas.
	 *
	 * @param string $slug o slug da página (pode ser vazio quando for para listar todas)
	 *
	 * @return void
	 */
	public function paginas($slug = '')
	{
		$data = $this->_init();
		$data['dados'] = $this->paginas_model->registro('slug', $slug);
		if (empty($data['dados']))
		{
			show_404();
		}
		else
		{
			$titulo = $data['dados']->titulo;
			$this->_carrega_view($data, $titulo);
		}
	}
	/**
	 * Página de Contato.
	 *
	 * @return void
	 */
	public function contato()
	{
		$data = $this->_init();
		$validacao = array(
			array('field' => 'nome', 'label' => 'Nome', 'rules' => 'trim|required'),
			array('field' => 'email', 'label' => 'E-mail', 'rules' => 'trim|required|valid_email'),
			array('field' => 'telefone1', 'label' => 'Telefone Fixo', 'rules' => 'trim'),
			array('field' => 'telefone2', 'label' => 'Telefone Celular', 'rules' => 'trim'),
			array('field' => 'conteudo', 'label' => 'Conteúdo', 'rules' => 'trim|required'),
		);
		$this->form_validation->set_rules($validacao);
		if ($this->form_validation->run())
		{
			$dados = $this->dados_formulario();
			$dados['ativo'] = 'S';
			$tmp = 'Data: '.date('d/m/Y H:i:s').'<br />';
			$tmp .= 'Nome: '.$dados['nome'].'<br />';
			$tmp .= 'E-mail: '.$dados['email'].'<br />';
			$tmp .= 'Telefone: '.$dados['telefone1'].'/'.$dados['telefone2'].'<br />';
			$tmp .= 'Conteúdo: '.$dados['conteudo'].'<br />';
			envia_email($data['config']->email, NM_EMPRESA.': Contato do Site.', $tmp);
			mensagem_popup('O e-mail foi enviado com sucesso!');

			$this->load->model('contatos_model');
			$this->contatos_model->adicionar($dados);
		}
		$data['validacao'] = mensagem_validacao();
		$this->_carrega_view($data, 'Fale Conosco');
	}
	/**
	 * Carrega a página personalizada
	 *
	 * @param array  $data   os dados que serão passados para a view
	 * @param string $titulo o titulo da página
	 *
	 * @return void
	 */
	private function _carrega_view($data, $titulo = NM_EMPRESA)
	{
		$view = $this->modulo.'/'.$this->acao;
		$this->gg2_layouts
			->titulo($titulo)
			->view($view, $data, LAYOUT_SITE);
	}
}

/* End of file site.php */
/* Location: ./controllers/site.php */