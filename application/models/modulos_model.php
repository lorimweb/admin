<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Esta classe que persiste os dados dos Módulos do Painel administrativo no banco de dados
 *
 * @category  Site
 * @package   Models
 * @author    Gihovani Filipp Pereira Demétrio <gihovani@gmail.com>
 * @copyright 2014 Quantity LTDA
 * @license   http://gg2.com.br/license.html GG2
 * @version   Release: 1.0
 * @link      http://gg2.com.br
 */
class Modulos_model extends MY_Model {
	/**
	 * Construtor que inicializa a classe pai MY_Model
	 * e configura o nome da tabela principal e das colunas da tabela.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->titulo = 'Módulos';
		$this->tabela = 'modulos';
		$this->colunas = 'id, nome, descricao, ativo';
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
		$colunas = 'id, descricao nome';
		$ret = $this->itens($this->tabela, $colunas, $filtro, 'descricao', 'asc');
		return $ret['itens'];
	}
	/**
	 * Função que adiciona registros ao BD
	 *
	 * @param array $data campos e valores
	 * 
	 * @return integer
	 */
	public function adicionar($data = array())
	{
		$id = parent::adicionar($data);
		// adiciona as acoes padrao
		$this->load->model('modulos_acoes_model');
		$acoes_padrao = array(
			array('nome' => 'adicionar', 'descricao' => 'Adicionar', 'modulo_id' => $id),
			array('nome' => 'ver', 'descricao' => 'Ver Dados', 'modulo_id' => $id),
			array('nome' => 'editar', 'descricao' => 'Editar Dados', 'modulo_id' => $id),
			array('nome' => 'remover', 'descricao' => 'Remover Registro', 'modulo_id' => $id),
			array('nome' => 'listar', 'descricao' => 'Listar os Registros', 'modulo_id' => $id),
			array('nome' => 'exportar', 'descricao' => 'Exportar Dados', 'modulo_id' => $id)
		);
		foreach ($acoes_padrao as $acao)
		{
			$this->modulos_acoes_model->adicionar($acao);
		}
		// fim das acoes padrão
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
		  PRIMARY KEY (`id`),
		  UNIQUE KEY `nome` (`nome`)
		) ENGINE = MyISAM';
		return $this->db->query($sql);
	}
}

/* End of file modulos_model.php */
/* Location: ./models/modulos_model.php */