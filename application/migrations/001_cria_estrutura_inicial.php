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
		'admins_grupos',
		'admins_menus',
		'banneres',
		'configuracoes',
		'contatos',
		'noticias',
		'modulos_acoes',
		'modulos',
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
			$this->{$modulo.'_model'}->adicionar_tabela();
		}
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
			$this->{$modulo . '_model'}->remover_tabela();
		}
	}
	/**
	 * cria as tabelas
	 *
	 * @return void
	 */
	private function _add_registros()
	{
		$data = array();
		foreach ($this->_modulos as $modulo)
		{
			$data[] = array(
				'titulo'  => $this->{$modulo . '_model'}->titulo,
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
			$this->admins_menus_model->adicionar($dados);
			$dados['nome'] = $dados['link'];
			$dados['descricao'] = $dados['titulo'];
			unset($dados['link'], $dados['titulo']);
			$this->modulos_model->adicionar($dados);
		}
		$sql = 'INSERT INTO admins_grupos_permissoes (acao_id, grupo_id) (SELECT id, 1 FROM modulos_acoes)';
		$this->db->query($sql);
		return count($data);
	}
}

/* End of file 001_cria_estrutura_inicial.php */
/* Location: ./migrations/001_cria_estrutura_inicial.php */