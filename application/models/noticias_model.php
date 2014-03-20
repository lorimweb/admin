<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Esta classe que persiste os dados das Notícias no banco de dados
 *
 * @category  Site
 * @package   Models
 * @author    Gihovani Filipp Pereira Demétrio <gihovani@gmail.com>
 * @copyright 2014 Quantity LTDA
 * @license   http://gg2.com.br/license.html GG2
 * @version   Release: 1.0
 * @link      http://gg2.com.br
 */
class Noticias_model extends MY_Model
{
	/**
	 * Caminho onde estão salvas as imagens.
	 * 
	 * @var string
	 */
	public $caminho = 'assets/img/noticias/';
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
	public $dimensoes = '640x480';
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

		$this->titulo = 'Notícias';
		$this->tabela = 'noticias';
		$this->colunas = '`imagem`,`dt_registro` as dt_tmp,`titulo`,`slug`,`ordem`,`destaque`,`ativo`,`chamada`,`conteudo`,DATE_FORMAT(`dt_registro`, "%d/%m/%Y") as `dt_registro`, `id`, CONCAT("<img src=\"'.$this->url.'/",imagem,"\" alt=\"",titulo,"\" width=\"100\" class=\"img-responsive\" />") img';
	}
	/**
	 * Função que gera o link para fazer o crop da imagem.
	 *
	 * @param integer $noticia_id O identificador do noticia.
	 * 
	 * @return string
	 */
	public function imagem_link_recortar($noticia_id)
	{
		$link = '';
		$dados = $this->id($noticia_id);
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
	 * cria a tabela dos noticias
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
		  `titulo` varchar(100) NOT NULL,
		  `slug` varchar(255) NULL,
		  `chamada` tinytext NOT NULL,
		  `conteudo` text NOT NULL,
		  `ordem` int(11) NULL,
		  `destaque` set(\'S\',\'N\') NOT NULL,
		  `ativo` set(\'S\',\'N\') NOT NULL,
		  PRIMARY KEY (`id`)
		)';
		$this->db->query($sql);
		return $this->db->affected_rows();
	}
}

/* End of file noticias_model.php */
/* Location: ./models/noticias_model.php */