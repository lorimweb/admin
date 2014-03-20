<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Esta classe que controla o Login do painel administrativo.
 *
 * @category  Site
 * @package   Controllers
 * @author    Gihovani Filipp Pereira Demétrio <gihovani@gmail.com>
 * @copyright 2014 Quantity LTDA
 * @license   http://gg2.com.br/license.html GG2
 * @version   Release: 1.0
 * @link      http://gg2.com.br
 */
class Login extends MY_Controller
{
	/**
	 * Controi a classe e inicializa a classe pai 
	 * sem a verificacao de login
	 *
	 * @return void
	 *
	 */
	public function __construct()
	{
		parent::__construct(FALSE);
	}
	/**
	 * Mostra o formulário de login
	 *
	 * @param integer $tipo o tipo da mensagem
	 *
	 * @return void
	 *
	 */
	public function index($tipo = 0)
	{
		$validacao = array(
			array(
				'field' => 'login',
				'label' => 'Login',
				'rules' => 'trim|required|callback_verifica_login')
			,
			array(
				'field' => 'senha',
				'label' => 'Senha',
				'rules' => 'trim|required'
			)
		);
		$this->form_validation->set_rules($validacao);
		if ($this->form_validation->run() OR user_logado())
		{
			redirect(HOME);
		}
		else
		{
			switch ($tipo)
			{
				case 2:
					mensagem_popup('Você foi desconectado do sistema');
					break;
				case 3:
					mensagem_popup('Você não tem autorização para acessar esta página!', ': (', 'danger');
					break;
			}
		}
		$data['validacao'] = mensagem_validacao();
		$this->gg2_layouts->view($this->modulo.'/'.$this->acao, $data, 'site');
	}
	/**
	 * Desloga o usuário do sistema
	 *
	 * @return void
	 *
	 */
	public function logout()
	{
		$this->gg2_sessao->remove();
	}

	/**
	 * Função de callback do formulário que verifica o login.
	 *
	 * @return boolean
	 *
	 */
	public function verifica_login()
	{
		$this->load->model('admins_model');
		$login = $this->input->post('login');
		$senha = $this->input->post('senha');
		$admin = $this->admins_model->login($login, $senha);
		if (isset($admin->id))
		{
			$this->gg2_sessao->registra($admin);
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('verifica_login', 'NOME DO USUÁRIO OU SENHA INVÁLIDOS');
			return FALSE;
		}
	}
}

/* End of file login.php */
/* Location: ./controllers/login.php */