<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Esta classe é para pre configurar os controllers
 * 
 * @category  GG2_Admin
 * @package   Models
 * @author    Gihovani Filipp Pereira Demétrio <gihovani@gmail.com>
 * @copyright 2012-2014 GG2 Soluções
 * @license   http://gg2.com.br/license.html GG2 Soluções
 * @version   Release: 1.0
 * @link      http://gg2.com.br
 */
class MY_Controller extends CI_Controller
{
	/**
	 * Nome do modulo.
	 * 
	 * @var string
	 */
	protected $modulo;
	/**
	 * Nome da funcao.
	 * 
	 * @var string
	 */
	protected $acao;
	/**
	 * O prefixo das colunas.
	 * 
	 * @var string
	 */
	protected $prefix = '';
	/**
	 * O usuário logado.
	 * 
	 * @var string
	 */
	public $user = NULL;

	/**
	 * Construtor que inicializa a classe pai CI_Model
	 * preenche as variaveis
	 * e verifica se o usuario tem permissao
	 *
	 * @param boolean $verificar_login parametro de configuracao para verificar permissao do usuario
	 *
	 * @return void
	 */
	public function __construct($verificar_login = TRUE)
	{
		parent::__construct();
		$this->modulo = $this->router->class;
		$this->acao = $this->router->method;

		$this->load->library(array('gg2_layouts','gg2_listagem','gg2_filtros','gg2_sessao'));
		$this->load->helper(array('gg2_extra','gg2_formatacoes','gg2_html','gg2_validacoes'));
		if ($verificar_login)
		{
			$this->gg2_sessao->verifica();
			$this->user = $this->session->userdata('usuario');
		}
	}

	/**
	 * Função callback dos formularios de enviar arquivo
	 * 
	 * @param string $str    coluna default do ci
	 * @param array  $params os parametros
	 * 
	 * @return boolean
	 */
	public function envia_arquivo($str, $params)
	{
		$nome = NULL;
		$tmp = explode(',', $params);
		$campo = isset($tmp[0]) ? $tmp[0] : $tmp;
		$config = array(
			'overwrite' 	=> FALSE,
			//'file_name' 	=> mktime(),
			'allowed_types' => '*',
			'max_size'		=> '8192'// 8MB
		);
		if (isset($tmp[1])) $config['upload_path'] = $tmp[1];
		if (isset($tmp[2])) $required = ( ! empty($tmp[2]));
		if (isset($tmp[3])) $allowed_types = explode(' ', $tmp[3]);
		if ( ! empty($_FILES[$campo]['name']))
		{
			$extencao = pega_extensao_arquivo($_FILES[$campo]['name']);
			if ( ! isset($allowed_types) OR in_array($extencao, $allowed_types))
			{
				$this->load->library('upload', $config);
				if ($this->upload->do_upload($campo))
				{
					$upload_data = $this->upload->data();
					$nome = $upload_data['file_name'];
					$_POST[$campo] = $nome;
					$ret = TRUE;
				}
				else
				{
					$mensagem = $this->upload->display_errors('', '');
					$this->form_validation->set_message('envia_arquivo', $mensagem);
					$ret = FALSE;
				}
			}
			else
			{
				$this->form_validation->set_message('envia_arquivo', 'O campo %s é de um tipo não válido ('.$extencao.'). Tipos aceitos: '.implode(',', $allowed_types));
				$ret = FALSE;
			}
		}
		else
		{
			if($required)
			{
				$this->form_validation->set_message('envia_arquivo', 'O campo %s é obrigatório.');
				$ret = FALSE;
			}
			else
			{
				$ret = TRUE;
			}
		}
		return $ret;
	}
	/**
	 * Função que retorna todos os dados postados
	 * 
	 * @param string $prefix os prefixos das chaves do array
	 * 
	 * @return array
	 */
	protected function dados_formulario($prefix = '')
	{
		$data = $this->input->post_to_array($prefix);
		return $data;
	}
}

/* End of file MY_Controller.php */
/* Location: ./core/MY_Controller.php */