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
class Admins_menus_model extends MY_Model {
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
		$this->colunas = 'id, titulo, link, modulo_id, ativo';
	}
	/**
	 * Função que retorna a lista com base na busca do BD
	 * 
	 * @param array   $filtro          os valores a serem buscados
	 * @param string  $ordenar_por     coluna de referencia para ordenação
	 * @param string  $ordenar_sentido criterio de ordem 'asc' ascendente e 'desc' decrescente'
	 * @param integer $offset          posicao dos itens retornados
	 * @param integer $limite          quantidade de itens retornados
	 * @param array   $extra           algum parametro extra como having ou group by etc.
	 * 
	 * @return array
	 */
	public function lista($filtro = NULL, $ordenar_por = 1, $ordenar_sentido = 'asc', $offset = 0, $limite = NULL, $extra = array())
	{
		$tabelas = array(
			array('nome' => $this->tabela.' a'),
			array('nome' => 'modulos b', 'where' => 'a.modulo_id = b.id', 'tipo' => 'left'),
		);
		$colunas = 'a.id, 
			b.descricao modulo,
			a.titulo, 
			a.link, 
			a.ativo,
			a.modulo_id';
		$ret = $this->itens($tabelas, $colunas, $filtro, $ordenar_por, $ordenar_sentido, $offset, $limite, $extra);
		return $ret;
	}
	/**
	 * cria a tabela dos menus
	 *
	 * @return integer
	 */
	public function adicionar_tabela()
	{
		$sql = 'CREATE TABLE IF NOT EXISTS `'.$this->tabela.'` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `titulo` varchar(30),
		  `link` varchar(50) DEFAULT NULL,
		  `modulo_id` int(11) DEFAULT NULL,
		  `ativo` set(\'S\',\'N\') NOT NULL,
		  PRIMARY KEY (`id`),
		  UNIQUE KEY `titulo` (`titulo`)
		) ENGINE = MyISAM';
		$this->db->query($sql);
		return $this->db->affected_rows();
	}
}

/* End of file admins_menus_model.php */
/* Location: ./models/admins_menus_model.php */