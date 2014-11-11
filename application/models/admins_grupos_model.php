<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Esta classe que persiste os dados dos Grupos dos Administradores do Painel administrativo no banco de dados
 *
 * @category  Site
 * @package   Models
 * @author    Gihovani Filipp Pereira Demétrio <gihovani@gmail.com>
 * @copyright 2014 Quantity LTDA
 * @license   http://gg2.com.br/license.html GG2
 * @version   Release: 1.0
 * @link      http://gg2.com.br
 */
class Admins_grupos_model extends MY_Model {
	/**
	 * Construtor que inicializa a classe pai MY_Model
	 * e configura o nome da tabela principal e das colunas da tabela.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->titulo = 'Admins -> Grupos';
		$this->tabela = 'admins_grupos';
		$this->colunas = 'id, nome, ativo';
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
		$colunas = 'id, nome';
		$ret = $this->itens($this->tabela, $colunas, $filtro, 'nome', 'asc');
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
		  `ativo` set(\'S\',\'N\') NOT NULL,
		  PRIMARY KEY (`id`),
		  UNIQUE KEY `nome` (`nome`)
		) ENGINE = MyISAM';
		$this->db->query($sql);
		$this->_adicionar_tabela_grupos_permissoes();
		$ret = $this->_registros();
		return $ret;
	}
	/**
	 * cria a tabela de relacionamento dos grupos x permissoes
	 *
	 * @return integer
	 */
	private function _adicionar_tabela_grupos_permissoes()
	{
		$sql = 'CREATE TABLE IF NOT EXISTS `'.$this->tabela.'_permissoes` (
		  `acao_id` int(11) NOT NULL,
		  `grupo_id` int(11) NOT NULL,
		  PRIMARY KEY (`acao_id`,`grupo_id`)
		) ENGINE = MyISAM';
		$this->db->query($sql);
		return $this->db->affected_rows();
	}
	/**
	 * adiciona o admin padrão
	 *
	 * @return integer
	 */
	private function _registros()
	{
		$data = array(
			'id' => '1',
			'nome'  => 'Administradores',
			'ativo' => 'S'
		);
		return $this->adicionar($data);
	}
	/**
	 * Função que lista os permissoes ativos do grupo
	 *
	 * @param integer $grupo_id o identificador do grupo
	 *
	 * @return array
	 */
	public function lista_permissoes($grupo_id)
	{
		$tmp = $this->itens($this->tabela.'_permissoes', 'GROUP_CONCAT(acao_id) as ids', array('grupo_id' => $grupo_id));
		if (isset($tmp['itens'][0]))
		{
			$ret = explode(',', $tmp['itens'][0]->ids);
		}
		else
		{
			$ret = array();
		}
		return $ret;
	}
	/**
	 * Função que remove os permissoes do grupo
	 *
	 * @param integer $grupo_id o identificador do grupo
	 *
	 * @return integer
	 */
	public function excluir_permissoes($grupo_id)
	{
		$this->db->delete($this->tabela.'_permissoes', array('grupo_id' => $grupo_id));
		return $this->db->affected_rows();
	}
	/**
	 * Função que adiciona o permissoes para o grupo
	 *
	 * @param integer $acao_id  o identificador da permissao
	 * @param integer $grupo_id o identificador do grupo
	 *
	 * @return integer
	 */
	public function adicionar_permissoes($acao_id, $grupo_id)
	{
		$data = array();
		$data['acao_id'] = $acao_id;
		$data['grupo_id'] = $grupo_id;
		return $this->db->insert($this->tabela.'_permissoes', $data);
	}
	/**
	 * exclui a tabela
	 *
	 * @return void
	 */
	public function remover_tabela()
	{
		$sql = 'DROP TABLE IF EXISTS `'.$this->tabela.'_permissoes'.'`';
		$this->db->query($sql);
		$sql = 'DROP TABLE IF EXISTS `'.$this->tabela.'`';
		$this->db->query($sql);
	}
}

/* End of file admins_grupos_model.php */
/* Location: ./models/admins_grupos_model.php */