<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Esta classe é responsavel por criar na base de dados
 * a estrutura inicial da aplicação
 *
 * @category  GG2_Admin
 * @package   Libraries
 * @author    Gihovani Filipp Pereira Demétrio <gihovani@gmail.com>
 * @copyright 2012-2014 GG2 Soluções
 * @license   http://gg2.com.br/license.html GG2 Soluções
 * @version   Release: 1.0
 * @link      http://gg2.com.br
 */
class Migration_cria_estrutura_inicial extends CI_Migration {

	/**
	 * Os modulos que queremos carregar.
	 * 
	 * @var array
	 */
	private $_modulos = array(
		'admins',
		'admins_menus',
		'banneres',
		'configuracoes',
		'contatos',
		'noticias',
		'paginas',
	);
	/**
	 * Construtor que inicializa a classe pai CI_Migration
	 * e da o load nos modulos que queremos criar.
	 *
	 * @param array $config a configuração que vai ser passada para a classe pai.
	 * 
	 * @return void
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
		foreach ($this->_modulos as $modulo)
		{
			$this->load->model($modulo.'_model');
		}
	}

	/**
	 * Instala a migracao.
	 *
	 * @return void
	 */
	public function up()
	{
		$this->_cria_tabelas();
		$this->_add_registros();
	}
	/**
	 * remove a migracao.
	 *
	 * @return void
	 */
	public function down()
	{
		$this->_remove_tabelas();
	}
	/**
	 * cria as tabelas
	 *
	 * @return void
	 */
	private function _cria_tabelas()
	{
		foreach ($this->_modulos as $modulo)
		{
			$this->{$modulo.'_model'}->cria_tabela();
		}
		$this->_cria_tabela_sessoes();
	}
	/**
	 * remove as tabelas
	 *
	 * @return void
	 */
	private function _remove_tabelas()
	{
		$this->_modulos = array_reverse($this->_modulos);
		foreach ($this->_modulos as $modulo)
		{
			$this->dbforge->drop_table($this->{$modulo.'_model'}->tabela);
		}
		$this->dbforge->drop_table('admins_sessoes');
	}
	/**
	 * cria as tabelas
	 *
	 * @return void
	 */
	private function _add_registros()
	{
		$this->_add_registros_menus();
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
	 * adiciona os menus padrão
	 *
	 * @return integer
	 */
	private function _add_registros_menus()
	{
		$data = array();
		foreach ($this->_modulos as $modulo)
		{
			$data[] = array(
				'titulo'  => $this->{$modulo.'_model'}->titulo,
				'link' => $modulo,
				'ativo' => 'S'
			);
		}
		$data[] = array(
			'titulo'  => 'Logout',
			'link' => 'login/logout',
			'ativo' => 'S'
		);
		foreach ($data as $dados)
		{
			$this->db->insert('admins_menus', $dados);
		}
		return $this->db->insert_id();
	}
}

/* End of file 001_cria_estrutura_inicial.php */
/* Location: ./migrations/001_cria_estrutura_inicial.php */