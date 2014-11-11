<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Esta classe que persiste os dados das Ações dos Módulos do Painel administrativo no banco de dados
 *
 * @category  Site
 * @package   Models
 * @author    Gihovani Filipp Pereira Demétrio <gihovani@gmail.com>
 * @copyright 2014 Quantity LTDA
 * @license   http://gg2.com.br/license.html GG2
 * @version   Release: 1.0
 * @link      http://gg2.com.br
 */
class Modulos_acoes_model extends MY_Model {
	/**
	 * Construtor que inicializa a classe pai MY_Model
	 * e configura o nome da tabela principal e das colunas da tabela.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->titulo = 'Módulos -> Ações';
		$this->tabela = 'modulos_acoes';
		$this->colunas = 'id, modulo_id, nome, descricao, ativo';
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
			array('nome' => 'modulos b', 'where' => 'a.modulo_id = b.id', 'tipo' => 'inner'),
		);
		$colunas = 'a.id, 
			b.descricao modulo,
			a.nome, 
			a.descricao, 
			a.ativo,
			a.modulo_id';
		$ret = $this->itens($tabelas, $colunas, $filtro, $ordenar_por, $ordenar_sentido, $offset, $limite, $extra);
		return $ret;
	}
	/**
	 * retorna um array com os nomes dos grupos
	 *
	 * @param array $filtro o filtro do sql
	 *
	 * @return array
	 */
	public function options($filtro = NULL)
	{
		$colunas = 'a.id, a.descricao nome';
		$ret = $this->lista($filtro, 'b.nome', 'asc');
		return $ret['itens'];
	}
	/**
	 * cria a tabela dos grupos
	 *
	 * @return integer
	 */
	public function adicionar_tabela()
	{
		$sql = 'CREATE TABLE IF NOT EXISTS `'.$this->tabela.'` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `nome` varchar(30),
		  `descricao` varchar(100),
		  `ativo` set(\'S\',\'N\') NOT NULL,
		  `modulo_id` int(11) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE = MyISAM';
		$this->db->query($sql);
		return $this->db->affected_rows();
	}
}

/* End of file modulos_acoes_model.php */
/* Location: ./models/modulos_acoes_model.php */