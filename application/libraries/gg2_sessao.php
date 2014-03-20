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
class Gg2_sessao
{
	/**
	 * Instancia do core do framework CodeIgniter.
	 * 
	 * @var CodeIgniter
	 */
	private $_ci;
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
		$this->_ci =& get_instance();
		$this->_db = $this->_ci->db;
	}

	/**
	 * verifica se a sessao está valida
	 *
	 * @return void
	 */
	private function _esta_valida()
	{
		$tmp = $this->_buscar($this->session->userdata('session_id'));
		if ($tmp)
			$tmp = tem_permissao($this->_ci->router->class, $this->_ci->router->method);
		return $tmp;
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
			'url' 			=> $this->_ci->uri->uri_string(),
			'ip' 			=> $this->_ci->input->ip_address(),
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
		if (isset($tmp[0]))
			$tmp = $tmp[0];
		else
			$tmp = FALSE;
		//print $this->_db->last_query();die();
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
		if ($this->_ci->session->userdata('usuario'))
		{
			if ($this->_esta_valida())
				$this->registra();
			else
				$this->remove(3);
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
			$dados = array(
				'usuario' => $admin->id,
				'nome' => $admin->nome,
				'permissoes' => $admin->permissoes,
				'menu' => $admin->menu,
			);
		}
		$this->_gravar($this->_ci->session->userdata('session_id'), $this->_ci->session->userdata('usuario'));

		//print $this->_db->last_query();die();
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
		$this->_apagar($this->_ci->session->userdata('session_id'));
		$this->_ci->session->sess_destroy();
		redirect('login/index/'.$id);
	}
}

/* End of file gg2_sessao.php */
/* Location: ./libraries/gg2_sessao.php */