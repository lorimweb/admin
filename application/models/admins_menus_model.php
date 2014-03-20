<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Esta classe que persiste os dados dos menus do Painel administrativo no banco de dados
 *
 * @category  Site
 * @package   Models
 * @author    Gihovani Filipp Pereira Demétrio <gihovani@gmail.com>
 * @copyright 2014 Quantity LTDA
 * @license   http://gg2.com.br/license.html GG2
 * @version   Release: 1.0
 * @link      http://gg2.com.br
 */
class Admins_menus_model extends MY_Model 
{
	/**
	 * Construtor que inicializa a classe pai MY_Model
	 * e configura o nome da tabela principal e das colunas da tabela.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->titulo = 'Admins -> Menus';
		$this->tabela = 'admins_menus';
		$this->colunas = 'id, titulo, link, ativo';
	}
	/**
	 * cria a tabela dos menus
	 *
	 * @return integer
	 */
	public function cria_tabela()
	{
		$sql = 'CREATE TABLE IF NOT EXISTS `'.$this->tabela.'` (
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
}

/* End of file admins_menus_model.php */
/* Location: ./models/admins_menus_model.php */