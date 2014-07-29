<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Esta classe que persiste os dados dos contatos no banco de dados
 *
 * @category  Site
 * @package   Models
 * @author    Gihovani Filipp Pereira Demétrio <gihovani@gmail.com>
 * @copyright 2014 Quantity LTDA
 * @license   http://gg2.com.br/license.html GG2
 * @version   Release: 1.0
 * @link      http://gg2.com.br
 */
class Contatos_model extends MY_Model {
	/**
	 * Construtor que inicializa a classe pai MY_Model
	 * e configura o nome da tabela principal e das colunas da tabela.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->titulo = 'Contatos';
		$this->tabela = 'contatos';
		$this->colunas = '`id`, `dt_registro` as dt_tmp, `nome`, `email`, 
			`telefone1`, `telefone2`, `ativo`, `conteudo`,
            DATE_FORMAT(`dt_registro`, "%d/%m/%Y") as `dt_registro`';
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
		  `dt_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
		  `nome` varchar(100) NOT NULL,
		  `email` varchar(100) NOT NULL,
		  `conteudo` tinytext NOT NULL,
		  `telefone1` varchar(20) DEFAULT NULL,
		  `telefone2` varchar(20) DEFAULT NULL,
		  `ativo` set(\'S\',\'N\') NOT NULL,
		  PRIMARY KEY (`id`)
		)';
		$this->db->query($sql);
		return $this->db->affected_rows();
	}
}

/* End of file contatos_model.php */
/* Location: ./models/contatos_model.php */