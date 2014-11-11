<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Esta classe que persiste os dados dos Usuários no banco de dados
 *
 * @category  Site
 * @package   Models
 * @author    Gihovani Filipp Pereira Demétrio <gihovani@gmail.com>
 * @copyright 2014 Quantity LTDA
 * @license   http://gg2.com.br/license.html GG2
 * @version   Release: 1.0
 * @link      http://gg2.com.br
 */
class Admins_model extends MY_Model {
	/**
	 * Construtor que inicializa a classe pai MY_Model
	 * e configura o nome da tabela principal e das colunas da tabela.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->titulo = 'Administradores';
		$this->tabela = 'admins';
		$this->colunas = 'id, grupo_id, nome, login, ativo, senha';
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
			array('nome' => 'admins_grupos b', 'where' => 'a.grupo_id = b.id', 'tipo' => 'inner'),
		);
		$colunas = 'a.id, 
			b.nome grupo,
			a.nome, 
			a.login, 
			a.ativo,
			a.senha,
			a.grupo_id';
		$ret = $this->itens($tabelas, $colunas, $filtro, $ordenar_por, $ordenar_sentido, $offset, $limite, $extra);
		return $ret;
	}
	/**
	 * função que verifica o login e senha do admin.
	 *
	 * @param string  $login       o login do admin
	 * @param string  $senha       a senha do admin
	 * @param boolean $criptografa se é para verificar a senha criptografada.
	 *
	 * @return string
	 */
	public function login($login, $senha = '', $criptografa = TRUE)
	{
		if ($criptografa) $senha = $this->criptografa($login, $senha);
		$filtro = array('a.login' => $login, 'a.senha' => $senha);
		$this->colunas = 'a.id, a.nome';
		$ret = $this->lista($filtro);
		if (isset($ret['itens'][0]))
		{
			$ret = $ret['itens'][0];
			$ret->permissoes = $this->_permissoes($ret->id);
			$ret->menu = array();
		}
		return $ret;
	}
	/**
	 * função que pega as permissoes do admin.
	 *
	 * @param integer $id o identificador do admin
	 *
	 * @return array
	 */
	private function _permissoes($id)
	{
		$data = array();
		$tabelas = array(
			array('nome' => $this->tabela.' a'),
			array('nome' => 'admins_grupos_permissoes b', 'where' => 'a.grupo_id = b.grupo_id', 'tipo' => 'inner'),
			array('nome' => 'modulos_acoes c', 'where' => 'b.acao_id = c.id', 'tipo' => 'inner'),
			array('nome' => 'modulos d', 'where' => 'c.modulo_id = d.id', 'tipo' => 'inner'),
		);
		$colunas = 'd.nome modulo, c.nome acao';
		$ret = $this->itens($tabelas, $colunas, array('a.id' => $id));
		foreach ($ret['itens'] as $tmp)
		{
			$data[$tmp->modulo][$tmp->acao] = TRUE;
		}
		return $data;
	}
	/**
	 * função que criptografa a senha do admin.
	 *
	 * @param string $login o login do admin
	 * @param string $senha a senha do admin
	 *
	 * @return string
	 */
	public function criptografa($login, $senha)
	{
		return md5($senha.$login);
	}
	/**
	 * cria a tabela dos admins
	 *
	 * @return integer
	 */
	public function adicionar_tabela()
	{
		$sql = 'CREATE TABLE IF NOT EXISTS `'.$this->tabela.'` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `dt_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
		  `nome` varchar(100) NOT NULL,
		  `login` varchar(50) NOT NULL,
		  `senha` varchar(50) NOT NULL,
		  `ativo` set(\'S\',\'N\') NOT NULL,
		  `grupo_id` int(11) NOT NULL,
		  PRIMARY KEY (`id`), UNIQUE KEY `login` (`login`)
		) ENGINE = MyISAM';
		$this->db->query($sql);
		$ret = $this->_registros();
		$this->_adicionar_tabela_sessoes();
		return $ret;
	}
	/**
	 * cria a tabela da sessao
	 *
	 * @return integer
	 */
	private function _adicionar_tabela_sessoes()
	{
		$sql = 'CREATE TABLE IF NOT EXISTS `'.$this->tabela.'_sessoes` (
		  `id` char(32) NOT NULL,
		  `dh_inicio` datetime DEFAULT NULL,
		  `dh_termino` datetime DEFAULT NULL,
		  `url` tinytext,
		  `ip` varchar(15) DEFAULT NULL,
		  `admin_id` int(11) NOT NULL,
		  PRIMARY KEY (`id`)
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
			'grupo_id' => '1',
			'nome'  => 'Admin',
			'login' => 'admin',
			'senha' => 'f6fdffe48c908deb0f4c3bd36c032e72', //admin
			'ativo' => 'S'
		);
		return $this->adicionar($data);
	}
	/**
	 * exclui a tabela
	 *
	 * @return void
	 */
	public function remover_tabela()
	{
		$sql = 'DROP TABLE IF EXISTS `'.$this->tabela.'_sessoes'.'`';
		$this->db->query($sql);
		$sql = 'DROP TABLE IF EXISTS `'.$this->tabela.'`';
		$this->db->query($sql);
	}
}

/* End of file admins_model.php */
/* Location: ./models/admins_model.php */