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
	 * Caminho onde estão salvas as imagens.
	 * 
	 * @var string
	 */
	public $caminho = 'assets/img/banneres/';
	/**
	 * Pasta do servidor onde estão salvas as imagens.
	 * 
	 * @var string
	 */
	public $pasta = NULL;
	/**
	 * URL base onde estão salvas as imagens.
	 * 
	 * @var string
	 */
	public $url = NULL;
	/**
	 * As dimensões padrão da imagem.
	 * 
	 * @var string
	 */
	public $dimensoes = '540x185';
	/**
	 * Construtor que inicializa a classe pai MY_Model
	 * e configura o nome da tabela principal e das colunas da tabela.
	 *
	 * @return void
	 */
	public function __construct ()
	{
		parent::__construct();
		$this->pasta = realpath(APPPATH.'../').'/'.$this->caminho;
		$this->url = site_url($this->caminho);

		$this->titulo = 'Banneres';
		$this->tabela = 'banneres';
		$this->colunas = '`imagem`,`dt_registro` as dt_tmp,`nome`,`link`,`ordem`,`ativo`,DATE_FORMAT(`dt_registro`, "%d/%m/%Y") as `dt_registro`, `id`, CONCAT("<img src=\"'.$this->url.'/",imagem,"\" alt=\"",nome,"\" width=\"100\" class=\"img-responsive\" />") img';
	}
	/**
	 * Função que gera o link para fazer o crop da imagem.
	 *
	 * @param integer $banner_id O identificador do banner.
	 * 
	 * @return string
	 */
	public function imagem_link_recortar($banner_id)
	{
		$link = '';
		$dados = $this->id($banner_id);
		if ( ! empty($dados->imagem) && is_file($this->pasta . $dados->imagem))
		{
			$link = $this->tabela;
			$imagem = $this->caminho . $dados->imagem;
			list($largura, $altura) = explode('x', $this->dimensoes);
			$link = site_url('imagens/recortar/?arquivo='.$imagem.'&altura='.$altura.'&largura='.$largura.'&retorno='.$link);
		}
		return $link;
	}

	/**
	 * cria a tabela dos banneres
	 *
	 * @return integer
	 */
	public function cria_tabela()
	{
		if ( ! is_dir($this->pasta)) mkdir($this->pasta, 0777);
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
		return $this->db->affected_rows();
	}
}

/* End of file banneres_model.php */
/* Location: ./models/banneres_model.php */