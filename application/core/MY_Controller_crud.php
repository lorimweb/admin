<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH . 'core/MY_Controller_list.php');
/**
 * Esta classe é serve para a assinatura dos CRUDS
 * 
 * @category  GG2_Admin
 * @package   Models
 * @author    Gihovani Filipp Pereira Demétrio <gihovani@gmail.com>
 * @copyright 2012-2014 GG2 Soluções
 * @license   http://gg2.com.br/license.html GG2 Soluções
 * @version   Release: 1.0
 * @link      http://gg2.com.br
 */
abstract class MY_Controller_crud extends MY_Controller_list {
	/**
	 * Configuracoes das validacoes e dos campos do formulario dinamico.
	 * 
	 * @var array
	 */
	protected $validacao = array();

	/**
	 * Construtor que inicializa a classe pai MY_Controller_list
	 *
	 * @param array $parametros os parametro de configuracao passados para a classe pai
	 *
	 * @return void
	 */
	public function __construct($parametros = TRUE)
	{
		parent::__construct($parametros);
	}
	/**
	 * Configuração extra do formulário.
	 *
	 * @param string $id    o valor do campo identificador
	 * @param object $dados os dados que serão preenchidos no formulário
	 *
	 * @return array
	 */
	protected function parametros_extra($id = 0, $dados = NULL)
	{
		$data = array(
			'action' => site_url($this->modulo . '/' . $this->acao . '/' . $id),
			'campos' => $this->validacao,
			'titulo' => $this->meu_model->titulo,
			'botoes' => implode(PHP_EOL, $this->form_botoes($id)),
			'dados'  => $dados
		);
		$this->gg2_layouts->navegacao($this->meu_model->titulo, $this->modulo . '/listar', '0');

		return $data;
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
		return $this->meu_model->adicionar($data);
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
		$this->meu_model->editar($data, array($this->meu_model->id => $id));
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
		return $this->meu_model->excluir($this->meu_model->id.' in ('.implode(',', $selecionados).')');
	}
	/**
	 * Inicializa os filtros da listagem.
	 *
	 * @param array  $valores as configuracoes dos filtros
	 * @param string $url     a url base da listagem
	 *
	 * @return void
	 */
	protected function init_filtros($valores = array(), $url = '')
	{
		return $this->gg2_filtros->init(array(), $valores, $url);
	}
	/**
	 * Função executada apos salvar os dados, serve para decidir o que fazer apos a operação
	 * ser feita com sucesso.
	 *
	 * @param array   $config as configurações ou o id do item identifcador
	 * @param array   $popup  as configurações do popup
	 * @param boolean $return se é para redirecionar ou se é para retornar o link
	 *
	 * @return void/string
	 */
	protected function redireciona_salvo($config = FALSE, $popup = NULL, $return = FALSE)
	{
		$id = FALSE;

		if (is_array($config))
			extract($config);
		else
			$id = $config;

		if ( ! is_array($popup))
			$popup = array('mensagem' => MSG_SALVO, 'titulo' => ': )', 'class' => 'success');
		if ( ! isset($popup['botoes']))
			$popup['botoes'] = $this->form_botoes($id, FALSE);

		mensagem_popup($popup);

		if($id && tem_permissao($this->modulo, 'ver'))
			$link = $this->modulo.'/ver/'.$id;
		elseif($id && tem_permissao($this->modulo, 'editar'))
			$link = $this->modulo.'/editar/'.$id;
		else
			$link = $this->modulo.'/listar';

		if ($return)
			return $link;
		else
			redirect($link);
	}
	/**
	 * Função onde ficam as configurações dos botões padrão do formulário.
	 *
	 * @param string  $id     o id do item identifcador
	 * @param boolean $voltar se é para mostrar o botão voltar
	 *
	 * @return array
	 */
	protected function form_botoes($id = 0, $voltar = TRUE)
	{
		$botoes = array();
		if (tem_permissao($this->modulo, 'listar'))
		{
			$botoes[] = '<a href="'.site_url($this->modulo.'/listar').'" class="btn btn-default"> ' .
				'<i class="glyphicon glyphicon-list-alt"></i> Listar</a>'.PHP_EOL;
		}
		if ($voltar)
		{
			$botoes[] = '<a href="'.$this->agent->referrer().'" class="btn btn-default"> ' .
				'<i class="glyphicon glyphicon-arrow-left"></i> Tela Anterior</a>'.PHP_EOL;
		}
		elseif (tem_permissao($this->modulo, 'adicionar'))
		{
			$botoes[] = '<a href="'.site_url($this->modulo.'/adicionar/').'" class="btn btn-default"> ' .
				'<i class="glyphicon glyphicon-plus"></i> Novo</a>'.PHP_EOL;
		}
		if ($id && tem_permissao($this->modulo, 'editar'))
		{
			$botoes[] = '<a href="'.site_url($this->modulo.'/editar/'.$id).'" class="btn btn-default"> ' .
				'<i class="glyphicon glyphicon-pencil"></i> Editar Registro</a>'.PHP_EOL;
		}

		return $botoes;
	}
	/**
	 * Função que verifica se existe um arquivo na pasta do modulo com o nome da ação
	 * ou se chama o formulario padrão.
	 *
	 * @param array $data os dados que serão passados para a view
	 *
	 * @return void
	 */
	private function _carrega_view($data)
	{
		$view = 'layouts/form';
		if(is_file(APPPATH.'views/'.$this->modulo.'/'.$this->acao.'.php'))
			$view = $this->modulo.'/'.$this->acao;

		$this->gg2_layouts->view($view, $data);
	}
	/**
	 * Função que configura a validação/campos do formulario dinamico.
	 * Verifica se existe uma validação para aquela ação ou se é validação padrão.
	 *
	 * @return void
	 */
	private function _init_validacao()
	{
		if (isset($this->validacao[$this->acao]))
			$this->validacao = $this->validacao[$this->acao];

		$this->form_validation->set_rules($this->validacao);
	}
	/**
	 * Função padrão que redireciona para a listagem.
	 *
	 * @return void
	 */
	public function index()
	{
		redirect($this->modulo.'/listar/');
	}
	/**
	 * Função que mostra o formulário de create do Crud.
	 *
	 * @return void
	 */
	public function adicionar()
	{
		$this->_init_validacao();
		if ($this->form_validation->run())
		{
			$id = $this->salva_adicionar();
			$this->redireciona_salvo($id);
		}
		else
		{
			$data = $this->parametros_extra();
			$data['validacao'] = mensagem_validacao();
			$data['edit'] = TRUE;

			$this->gg2_layouts->navegacao('Adicionar', $this->modulo . '/' . $this->acao, '1');
			$this->_carrega_view($data);
		}
	}
	/**
	 * Função que mostra o formulário de read do cRud.
	 *
	 * @param string $id o id do item identifcador
	 * 
	 * @return void
	 */
	public function ver($id = NULL)
	{
		$dados = $this->meu_model->id($id);
		if ($dados)
		{
			$this->_init_validacao();
			$data = $this->parametros_extra($id, $dados);
			$data['edit'] = FALSE;

			$this->gg2_layouts->navegacao('Ver', $this->modulo.'/'.$this->acao.'/'.$id.'/', '1');
			$this->_carrega_view($data);
		}
		else
		{
			redirect($this->modulo.'/listar');
		}
	}
	/**
	 * Função que mostra o formulário de update do crUd.
	 *
	 * @param string $id o id do item identifcador
	 * 
	 * @return void
	 */
	public function editar($id = NULL)
	{
		$dados = $this->meu_model->id($id);
		if ($dados)
		{
			$this->_init_validacao();
			if ($this->form_validation->run())
			{
				$id = $this->salva_editar($id);
				$this->redireciona_salvo($id);
			}
			else
			{
				$data = $this->parametros_extra($id, $dados);
				$data['validacao'] = mensagem_validacao();
				$data['edit'] = TRUE;

				$this->gg2_layouts->navegacao('Editar', $this->modulo.'/'.$this->acao.'/'.$id.'/', '1');
				$this->_carrega_view($data);
			}
		}
		else
		{
			redirect($this->modulo.'/listar');
		}
	}
	/**
	 * Função que mostra o formulário de delete do cruD.
	 *
	 * @param string $id o id do item identifcador
	 * 
	 * @return void
	 */
	public function remover($id = 0)
	{
		$popup = array(
			'class' => 'success',
			'mensagem' => '',
			'titulo' => ': )',
			'botoes' => array()
		);
		$quantidade = $this->salva_remover($id);
		switch ($quantidade)
		{
			case 0:
				$popup['titulo'] = ':/';
				$popup['class'] = 'warning';
				$popup['mensagem'] = 'Nenhum item apagado.';
				break;
			case 1:
				$popup['mensagem'] = 'Um item foi apagado.';
				break;
			default:
				$popup['mensagem'] = $quantidade.' itens foram apagados.';
				break;
		}
		$this->redireciona_salvo(FALSE, $popup);
	}
}

/* End of file MY_Controller_crud.php */
/* Location: ./core/MY_Controller_crud.php */