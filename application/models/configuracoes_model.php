<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Esta classe que persiste os dados dos Configuracoes no banco de dados
 *
 * @category  Site
 * @package   Models
 * @author    Gihovani Filipp Pereira Demétrio <gihovani@gmail.com>
 * @copyright 2014 Quantity LTDA
 * @license   http://gg2.com.br/license.html GG2
 * @version   Release: 1.0
 * @link      http://gg2.com.br
 */
class Configuracoes_model extends MY_Model {
	/**
	 * As dimensões padrão da imagem.
	 * 
	 * @var string
	 */
	public $dimensoes_topo = '540x185';
	/**
	 * As dimensões padrão da imagem.
	 * 
	 * @var string
	 */
	public $dimensoes_lateral = '475x255';
	/**
	 * Construtor que inicializa a classe pai MY_Model
	 * e configura o nome da tabela principal e das colunas da tabela.
	 *
	 * @return void
	 */
	public function __construct ()
	{
		parent::__construct();
		$this->caminho = 'assets/img/configuracoes/';
		$this->pasta = realpath(APPPATH.'../').'/'.$this->caminho;
		$this->url = base_url($this->caminho);

		$this->titulo = 'Configurações';
		$this->tabela = 'configuracoes';
		$this->colunas = '`imagem_topo`,`link_topo`,`imagem_lateral`,`link_lateral`,`dt_registro` dt_tmp,`email`,`googlemaps`, DATE_FORMAT(`dt_registro`, "%d/%m/%Y") as `dt_registro`, `id`, CONCAT("<img src=\"'.$this->url.'/",imagem_topo,"\" alt=\"",imagem_topo,"\" width=\"100\" class=\"img-responsive\" />") img_topo, CONCAT("<img src=\"'.$this->url.'/",imagem_lateral,"\" alt=\"",imagem_lateral,"\" width=\"60\" class=\"img-responsive\" />") img_lateral';
	}
	/**
	 * Função que gera o link para fazer o crop da imagem.
	 *
	 * @param integer $config_id O identificador do banner.
	 * @param string  $tipo      O tipo da imagem (topo ou lateral)
	 * 
	 * @return string
	 */
	public function imagem_link_recortar($config_id, $tipo = 'topo')
	{
		$link = $this->tabela;
		$dados = $this->id($config_id);
		if ( ! empty($dados->{'imagem_'.$tipo}) && is_file($this->pasta . $dados->{'imagem_'.$tipo}))
		{
			$imagem = $this->caminho . $dados->{'imagem_'.$tipo};
			list($largura, $altura) = explode('x', $this->{'dimensoes_'.$tipo});
			$link = site_url('imagens/recortar/?arquivo='.$imagem.'&altura='.$altura.'&largura='.$largura.'&retorno='.$link);
		}
		return $link;
	}
	/**
	 * cria a tabela dos configuracoes
	 *
	 * @return integer
	 */
	public function cria_tabela()
	{
		if ( ! empty($this->pasta) && ! is_dir($this->pasta)) mkdir($this->pasta, 0777);
		$sql = 'CREATE TABLE IF NOT EXISTS `'.$this->tabela.'` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `dt_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
		  `imagem_topo` varchar(100) DEFAULT NULL,
		  `link_topo` varchar(200) DEFAULT NULL,
		  `imagem_lateral` varchar(100) DEFAULT NULL,
		  `link_lateral` varchar(200) DEFAULT NULL,
		  `email` varchar(100) NOT NULL,
		  `googlemaps` varchar(1000) DEFAULT NULL,
		  PRIMARY KEY (`id`)
		)';
		$this->db->query($sql);
		$ret = $this->_add_registros();
		return $ret;
	}
	/**
	 * adiciona da configuração padrão
	 *
	 * @return integer
	 */
	private function _add_registros()
	{
		$data = array(
			'imagem_topo' => 'banner-topo.jpg',
			'link_topo' => base_url(),
			'imagem_lateral' => 'midias-internas.jpg',
			'link_lateral' => base_url(),
			'email' => EMAIL
		);
		return $this->adicionar($data);
	}
}

/* End of file configuracoes_model.php */
/* Location: ./models/configuracoes_model.php */