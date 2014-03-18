<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_cria_admins extends CI_Migration {

	/**
	 * Instala a migracao sessao.
	 *
	 * @return void
	 */
	public function up()
	{
		$this->_cria_tabela();
		$this->_add_registros();
	}
	/**
	 * remove a migracao da sessao.
	 *
	 * @return void
	 */
	public function down()
	{

		$this->dbforge->drop_table('admins');
	}
	/**
	 * cria a tabela da sessao
	 *
	 * @return integer
	 */
	private function _cria_tabela()
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
	 * adiciona o admin padrão
	 *
	 * @return integer
	 */
	private function _add_registros()
	{
		$data = array(
			'nome'  => 'Admin',
			'login' => 'admin',
			'senha' => 'admin',
			'ativo' => 'S'
		);
		$this->db->insert('admins', $data);
		return $this->db->insert_id();
	}
}

/* End of file 001_cria_admins.php */
/* Location: ./migrations/001_cria_admins.php */