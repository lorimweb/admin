<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Esta classe que persiste os dados dos Banneres no banco de dados
 *
 * @category  Site
 * @package   Models
 * @author    Gihovani Filipp Pereira Demétrio <gihovani@gmail.com>
 * @copyright 2014 Quantity LTDA
 * @license   http://gg2.com.br/license.html GG2
 * @version   Release: 1.0
 * @link      http://gg2.com.br
 */
class Banneres_model extends MY_Model {
	/**
	 * Construtor que inicializa a classe pai MY_Model
	 * e configura o nome da tabela principal e das colunas da tabela.
	 *
	 * @return void
	 */
	public function __construct ()
	{
		parent::__construct();
		$this->dimensoes = '540x185';
		$this->caminho = 'assets/img/banneres/';
		$this->pasta = realpath(APPPATH.'../').'/'.$this->caminho;
		$this->url = base_url($this->caminho);

		$this->titulo = 'Banneres';
		$this->tabela = 'banneres';
		$this->colunas = '`id`, `imagem`,`dt_registro` as dt_tmp,`nome`,`link`,`ordem`,`ativo`,DATE_FORMAT(`dt_registro`, "%d/%m/%Y") as `dt_registro`, CONCAT("<img src=\"'.$this->url.'/",imagem,"\" alt=\"",nome,"\" width=\"100\" class=\"img-responsive\" />") img';
	}
	/**
	 * cria a tabela dos banneres
	 *
	 * @return integer
	 */
	public function adicionar_tabela()
	{
		if ( ! empty($this->pasta) && ! is_dir($this->pasta)) mkdir($this->pasta, 0777);
		$sql = 'CREATE TABLE IF NOT EXISTS `'.$this->tabela.'` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `dt_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
		  `imagem` varchar(100) NOT NULL,
		  `nome` varchar(100) NOT NULL,
		  `link` varchar(255) NULL,
		  `ordem` int(11) NULL,
		  `ativo` set(\'S\',\'N\') NOT NULL,
		  PRIMARY KEY (`id`)
		)';
		$this->db->query($sql);
		$ret = $this->_add_registros();
		return $ret;
	}
	/**
	 * adiciona o banner padrão
	 *
	 * @return integer
	 */
	private function _add_registros()
	{
		$data = array(
			array(
				'imagem' => '02.jpg',
				'nome' => 'Banner Amarelo',
				'link' => base_url(),
				'ordem' => 1,
				'ativo' => 'S'
			),
			array(
				'imagem' => '03.jpg',
				'nome' => 'Banner Vermelho',
				'link' => base_url(),
				'ordem' => 2,
				'ativo' => 'S'
			),
		);
		foreach ($data as $tmp)
		{
			$this->adicionar($tmp);
		}
		return 2;
	}
}

/* End of file banneres_model.php */
/* Location: ./models/banneres_model.php */