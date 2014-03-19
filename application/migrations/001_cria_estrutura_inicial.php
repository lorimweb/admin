<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_cria_estrutura_inicial extends CI_Migration {

	/**
	 * Instala a migracao.
	 *
	 * @return void
	 */
	public function up()
	{
		$this->_cria_tabela();
		$this->_add_registros();
	}
	/**
	 * remove a migracao.
	 *
	 * @return void
	 */
	public function down()
	{
		$this->dbforge->drop_table('admins_menus');
		$this->dbforge->drop_table('admins_sessoes');
		$this->dbforge->drop_table('admins');
	}
	/**
	 * cria as tabelas
	 *
	 * @return void
	 */
	private function _cria_tabela()
	{
		$this->_cria_tabela_admins();
		$this->_cria_tabela_sessoes();
		$this->_cria_tabela_menus();
	}
	/**
	 * cria as tabelas
	 *
	 * @return void
	 */
	private function _add_registros()
	{
		$this->_add_registros_admins();
		$this->_add_registros_menus();
	}
	/**
	 * cria a tabela dos admins
	 *
	 * @return integer
	 */
	private function _cria_tabela_admins()
	{
		$sql = 'CREATE TABLE IF NOT EXISTS `admins` (
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
		return $this->db->affected_rows();
	}
	/**
	 * cria a tabela da sessao
	 *
	 * @return integer
	 */
	private function _cria_tabela_sessoes()
	{
		$sql = 'CREATE TABLE IF NOT EXISTS `admins_sessoes` (
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
	 * cria a tabela dos menus
	 *
	 * @return integer
	 */
	private function _cria_tabela_menus()
	{
		$sql = 'CREATE TABLE IF NOT EXISTS `admins_menus` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `titulo` varchar(30),
		  `link` varchar(50) DEFAULT NULL,
		  `ativo` set(\'S\',\'N\') NOT NULL,
		  PRIMARY KEY (`id`),
		  UNIQUE KEY `titulo` (`titulo`)
		)';
		$this->db->query($sql);
		return $this->db->affected_rows();
	}
	/**
	 * adiciona o admin padrão
	 *
	 * @return integer
	 */
	private function _add_registros_admins()
	{
		$data = array(
			'nome'  => 'Admin',
			'login' => 'admin',
			'senha' => 'dd94709528bb1c83d08f3088d4043f4742891f4f', //admin
			'ativo' => 'S'
		);
		$this->db->insert('admins', $data);
		return $this->db->insert_id();
	}
	/**
	 * adiciona os menus padrão
	 *
	 * @return integer
	 */
	private function _add_registros_menus()
	{
		$data = array(
			array(
				'titulo' => 'Admin',
				'link' => 'admins',
				'ativo' => 'S'
			),
			array(
				'titulo' => 'Menus',
				'link' => 'admins_menus',
				'ativo' => 'S'
			),
			array(
				'titulo'  => 'Logout',
				'link' => 'login/logout',
				'ativo' => 'S'
			),
		);
		foreach ($data as $dados) {
			$this->db->insert('admins_menus', $dados);
		}
		return $this->db->insert_id();
	}
}

/* End of file 001_cria_estrutura_inicial.php */
/* Location: ./migrations/001_cria_estrutura_inicial.php */