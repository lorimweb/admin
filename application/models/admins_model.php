<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Esta classe que persiste os dados dos Usuários no banco de dados
 *
 * @category  Site
 * @package   Models
 * @author    Gihovani Filipp Pereira Demétrio <gihovani@gmail.com>
 * @copyright 2014 Quantity LTDA
 * @license   http://gg2.com.br/license.html GG2
 * @version   Release: 1.0
 * @link      http://gg2.com.br
 */
class Admins_model extends MY_Model {
	/**
	 * Construtor que inicializa a classe pai MY_Model
	 * e configura o nome da tabela principal e das colunas da tabela.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->titulo = 'Administradores';
		$this->tabela = 'admins';
		$this->colunas = 'id, nome, login, ativo, senha';
	}
	/**
	 * função que verifica o login e senha do admin.
	 *
	 * @param string  $login       o login do admin
	 * @param string  $senha       a senha do admin
	 * @param boolean $criptografa se é para verificar a senha criptografada.
	 *
	 * @return string
	 */
	public function login($login, $senha = '', $criptografa = TRUE)
	{
		if ($criptografa) $senha = $this->criptografa($login, $senha);
		$filtro = array('login' => $login, 'senha' => $senha);
		$this->colunas = 'id, nome';
		$ret = $this->lista($filtro);
		if (isset($ret['itens'][0]))
		{
			$ret = $ret['itens'][0];
			$ret->permissoes = array();
			$ret->menu = array();
		}
		return $ret;
	}
	/**
	 * função que criptografa a senha do admin.
	 *
	 * @param string $login o login do admin
	 * @param string $senha a senha do admin
	 *
	 * @return string
	 */
	public function criptografa($login, $senha)
	{
		return sha1($senha.$login);
	}
	/**
	 * exclui a tabela
	 *
	 * @return void
	 */
	public function remover_tabela()
	{
		$sql = 'DROP TABLE IF EXISTS `'.$this->tabela.'_sessoes`';
		$this->db->query($sql);
		parent::remover_tabela();
	}
	/**
	 * cria a tabela dos admins
	 *
	 * @return integer
	 */
	public function adicionar_tabela()
	{
		$sql = 'CREATE TABLE IF NOT EXISTS `'.$this->tabela.'` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `dt_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
		  `nome` varchar(100) NOT NULL,
		  `login` varchar(50) NOT NULL,
		  `senha` varchar(50) NOT NULL,
		  `ativo` set(\'S\',\'N\') NOT NULL,
		  PRIMARY KEY (`id`),
		  UNIQUE KEY `login` (`login`)
		)';
		$this->db->query($sql);
		$this->_cria_tabela_sessoes();
		$ret = $this->_add_registros();
		return $ret;
	}
	/**
	 * cria a tabela da sessao
	 *
	 * @return integer
	 */
	private function _cria_tabela_sessoes()
	{
		$sql = 'CREATE TABLE IF NOT EXISTS `'.$this->tabela.'_sessoes` (
		  `id` char(32) NOT NULL,
		  `dh_inicio` datetime DEFAULT NULL,
		  `dh_termino` datetime DEFAULT NULL,
		  `url` tinytext,
		  `ip` varchar(15) DEFAULT NULL,
		  `admin_id` int(11) NOT NULL,
		  PRIMARY KEY (`id`)
		)';
		$this->db->query($sql);
		return $this->db->affected_rows();
	}
	/**
	 * adiciona o admin padrão
	 *
	 * @return integer
	 */
	private function _add_registros()
	{
		$data = array(
			'nome'  => 'Admin',
			'login' => 'admin',
			'senha' => 'dd94709528bb1c83d08f3088d4043f4742891f4f', //admin
			'ativo' => 'S'
		);
		return $this->adicionar($data);
	}
}

/* End of file admins_model.php */
/* Location: ./models/admins_model.php */