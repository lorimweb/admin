<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_cria_sessao extends CI_Migration {

	/**
	 * Instala a migracao sessao.
	 *
	 * @return void
	 */
	public function up()
	{
		$this->_cria_tabela();	
	}
	/**
	 * remove a migracao da sessao.
	 *
	 * @return void
	 */
	public function down()
	{

		$this->dbforge->drop_table('admins_sessoes');
	}
	/**
	 * cria a tabela da sessao
	 *
	 * @return integer
	 */
	private function _cria_tabela()
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
}

/* End of file 001_cria_sessao.php */
/* Location: ./migrations/001_cria_sessao.php */