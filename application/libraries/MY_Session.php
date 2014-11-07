<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Esta classe é uma biblioteca responsável verificar sessoes ativas.
 * 
 * @category  GG2_Admin
 * @package   Libraries
 * @author    Gihovani Filipp Pereira Demétrio <gihovani@gmail.com>
 * @copyright 2012-2014 GG2 Soluções
 * @license   http://gg2.com.br/license.html GG2 Soluções
 * @version   Release: 1.0
 * @link      http://gg2.com.br
 */
class MY_Session extends CI_Session {
	/**
	 * Nome da tabela na base de dados.
	 * 
	 * @var string
	 */
	private $_tabela = 'admins_sessoes';
	/**
	 * Instancia do banco de dados para executar os comandos sql.
	 * 
	 * @var BancoDados
	 */
	private $_db;

	/**
	 * Construtor que inicializa a biblioteca da sessao
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->_db = $this->CI->db;
	}

	/**
	 * verifica se a sessao está valida
	 *
	 * @return void
	 */
	private function _esta_valida()
	{
		$ret = 0;
		$tmp = $this->_buscar($this->userdata('session_id'));
		if ($tmp)
		{
			$ret = 1;
			if (tem_permissao($this->CI->router->class, $this->CI->router->method)) $ret = 2;
		}
		return $ret;
	}
	/**
	 * grava ou atualiza na tabela da sessao o id da sessao e o usuario 
	 *
	 * @param string  $id      o id da sessao
	 * @param integer $id_user o id do usuario
	 * 
	 * @return void
	 */
	private function _gravar($id, $id_user)
	{
		$data = array(
			'id' 			=> $id,
			'dh_inicio' 	=> date('Y-m-d H:i:s', mktime()),
			'dh_termino' 	=> date('Y-m-d H:i:s', mktime() + TEMPO_SESSAO),
			'url' 			=> $this->CI->uri->uri_string(),
			'ip' 			=> $this->CI->input->ip_address(),
			'admin_id' 		=> $id_user,
		);
		if ($this->_buscar($id, FALSE))
			$this->_db->update($this->_tabela, $data, array('id' => $id));
		else
			$this->_db->insert($this->_tabela, $data);
	}
	/**
	 * apaga da tabela da sessao o id
	 *
	 * @param string $id o id da sessao
	 * 
	 * @return void
	 */
	private function _apagar($id)
	{
		$this->_db->delete($this->_tabela, array('id' => $id));
	}
	/**
	 * busca na tabela da sessao os dados referente ao id
	 *
	 * @param string  $id     o id da sessao
	 * @param boolean $valida parametro para verificar se é uma sessao valida ou nao
	 * 
	 * @return object
	 */
	private function _buscar($id, $valida = TRUE)
	{
		$filtro = array('id' => $id);
		if ($valida)
			$filtro['dh_termino > NOW()'] = NULL;

		$tmp = $this->_db
			->select('id, dh_inicio, dh_termino, url, ip, admin_id')
			->where($filtro)
			->limit(1)
			->get($this->_tabela)
			->result();
		$tmp = (isset($tmp[0])) ? $tmp[0] : FALSE;
		return $tmp;
	}
	/**
	 * verifica se é uma sessao valida se é atualiza a sessao 
	 * se nao for remove qualquer indicio da sessao
	 *
	 * @return void
	 */
	public function verifica()
	{
		if ($this->userdata('usuario'))
		{
			$valida = $this->_esta_valida();
			if ( ! empty($valida))
			{
				$this->registra();
				if ($valida === 1)
					$this->remove(5);
			}
			else
			{
				$this->remove(3);
			}
		}
		else
		{
			$this->remove(3);
		}
	}
	/**
	 * registra e ativa a sessao
	 *
	 * @param object $admin os dados do usuario
	 *
	 * @return void
	 */
	public function registra($admin = FALSE)
	{
		if ($admin)
		{
			$this->set_userdata('usuario', $admin->id);
			$this->set_userdata('nome', $admin->nome);
			$this->set_userdata('permissoes', $admin->permissoes);
			$this->set_userdata('menu', $admin->menu);
		}
		$this->_gravar($this->userdata('session_id'), $this->userdata('usuario'));
	}
	/**
	 * remove a sessao
	 *
	 * @param string $id o id da sessao
	 *
	 * @return void
	 */
	public function remove($id = 2)
	{
		$this->_apagar($this->userdata('session_id'));
		$this->sess_destroy();
		redirect('login/index/'.$id);
	}
}

/* End of file MY_Session.php */
/* Location: ./libraries/MY_Session.php */