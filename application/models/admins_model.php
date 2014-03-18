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
}

/* End of file usuarios_model.php */
/* Location: ./models/usuarios_model.php */