<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Esta classe que persiste os dados dos Usuários no banco de dados
 *
 * @category  Site
 * @package   Models
 * @author    Gihovani Filipp Pereira Demétrio <gihovani@gmail.com>
 * @copyright 2014 Quantity LTDA
 * @license   http://gg2.com.br/license.html GG2
 * @version   1.0
 * @link      http://gg2.com.br
 */
class Admins_model extends MY_Model 
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
		$this->tabela = 'admins';
		$this->colunas = 'id, nome, login, ativo, senha';
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
		$filtro = array('login' => $login, 'senha' => $senha);
		$this->colunas = 'id, nome';
		$ret = $this->lista($filtro);
		if (isset($ret['itens'][0])) 
		{
			$ret = $ret['itens'][0];
			$ret->permissoes = array();
			$ret->menu = array();
		}
		return $ret;
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
		return sha1($senha.$login);
	}
}

/* End of file admins_model.php */
/* Location: ./models/admins_model.php */