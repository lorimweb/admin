<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_cria_menus extends CI_Migration {

	/**
	 * Instala a migracao menus.
	 *
	 * @return void
	 */
	public function up()
	{
		$this->_cria_tabela();
		$this->_add_registros();
	}
	/**
	 * remove a migracao dos menus.
	 *
	 * @return void
	 */
	public function down()
	{

		$this->dbforge->drop_table('admins_menus');
	}
	/**
	 * cria a tabela dos menus
	 *
	 * @return integer
	 */
	private function _cria_tabela()
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
	 * adiciona os menus padrão
	 *
	 * @return integer
	 */
	private function _add_registros()
	{
		$data = array(
			array(
				'titulo'  => 'Admin',
				'link' => 'admins',
				'ativo' => 'S'
			),
			array(
				'titulo'  => 'Menus',
				'link' => 'menus',
				'ativo' => 'S'
			),
		);
		foreach ($data as $dados) {
			$this->db->insert('admins_menus', $dados);
		}
		return $this->db->insert_id();
	}
}

/* End of file 003_cria_menus.php */
/* Location: ./migrations/003_cria_menus.php */