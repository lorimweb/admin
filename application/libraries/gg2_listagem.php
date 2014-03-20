<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Esta classe é uma biblioteca responsável por:
 * controlar as listagens
 * 
 * @category  GG2_Admin
 * @package   Libraries
 * @author    Gihovani Filipp Pereira Demétrio <gihovani@gmail.com>
 * @copyright 2012-2014 GG2 Soluções
 * @license   http://gg2.com.br/license.html GG2 Soluções
 * @version   Release: 1.0
 * @link      http://gg2.com.br
 */
class Gg2_listagem
{
	/**
	 * Configura se a linha eh selecionavel e qual o nome do campo.
	 * 
	 * @var array
	 */
	private $_selecionavel;
	/**
	 * Configura os cabecalhos da listagem.
	 * 
	 * @var array
	 */
	private $_cabecalhos;
	/**
	 * Configura o titulo da lista.
	 * 
	 * @var string
	 */
	private $_caption;
	/**
	 * Configura as linhas da listagem.
	 * 
	 * @var array
	 */
	private $_itens;
	/**
	 * Configura as larguras de cada coluna.
	 * 
	 * @var array
	 */
	private $_larguras;
	/**
	 * Configura os botões que vao aparecer em cada linha.
	 * 
	 * @var array
	 */
	private $_botoes;
	/**
	 * Numero de colunas.
	 * 
	 * @var integer
	 */
	private $_num_colunas;
	/**
	 * Url base da listagem.
	 * 
	 * @var string
	 */
	private $_url;
	/**
	 * Campo que queremos ordenar.
	 * 
	 * @var string
	 */
	private $_ordenar_por;
	/**
	 * Tipo da ordenacao asc = ascendente ou desc = descendente.
	 * 
	 * @var string
	 */
	private $_ordenar_sentido = 'desc';

	/**
	 * Construtor que inicializa a biblioteca de filtro
	 *
	 * @param array $config a configuração da bibilioteca
	 * 
	 * @return String
	 */
	public function __construct($config = NULL)
	{
		if (isset($config))
			$this->init($config);
	}

	/**
	 * Inicializa a classe com a configuracao passada como parametro
	 *
	 * @param array $config a configuracao da classe
	 *
	 * @return Gg2_listagem
	 */
	public function init($config)
	{
		foreach ($config AS $chave => $valor)
		{
			$this->{'_'.$chave} = $valor;
		}
		return $this;
	}

	/**
	 * Retorna o html da listagem
	 *
	 * @return string
	 */
	public function html()
	{
		$largura_total = ( ! empty($this->_larguras)) ? array_sum($this->_larguras) : '100%';
		$conteudo  = '<table width="' . $largura_total . '" class="gg2-listagem table table-striped">' . PHP_EOL;
		$conteudo .= $this->_caption();
		$conteudo .= $this->_cabecalho();
		$conteudo .= $this->_itens();
		$conteudo .= '</table>' . PHP_EOL;
		return $conteudo;
	}
	/**
	 * Retorna o xls da listagem
	 *
	 * @return string
	 */
	public function xls()
	{
		$ret = 'nenhum item encontrado';
		if (isset($this->_itens[0]))
		{
			if ( isset($this->_cabecalhos))
			{
				$cabecalhos = array_keys($this->_cabecalhos);
			}
			else
			{
				$cabecalhos = get_object_vars($this->_itens[0]);
				$cabecalhos = array_keys($cabecalhos);
				$this->_cabecalhos = $cabecalhos;
			}
			$delimitador = "\t";
			$ret = '"'.implode('"'.$delimitador.'"', $this->_cabecalhos).'"'."\r\n";
			unset($this->_cabecalhos);
			foreach ($this->_itens as $chave => $item)
			{
				$tmp = array();
				foreach ($cabecalhos as $chave)
				{
					$tmp[] = '"'.html_entity_decode(str_replace(array('"', "\n", "\r", "\t"), array("''", ' ', ' ', ' '), strip_tags($item->$chave)), ENT_NOQUOTES, 'UTF-8').'"';
				}
				$ret .= implode($delimitador, $tmp)."\r\n";
				unset($this->_itens[$chave], $tmp);
			}
		}
		return utf8_decode($ret);
	}

	/**
	 * Retorna o titulo/caption da listagem
	 *
	 * @return string
	 */
	private function _caption()
	{
		$conteudo = ( ! empty($this->_caption)) ? '<caption>'.$this->_caption.'</caption>' . PHP_EOL : '';
		return $conteudo;
	}
	/**
	 * Retorna o cabecalho da listagem
	 *
	 * @return string
	 */
	private function _cabecalho()
	{
		$conteudo = '';
		if ( empty($this->_cabecalhos) && ! empty($this->_itens))
			$this->_cabecalhos = array_keys($this->_itens[0]);
		if (is_array($this->_cabecalhos))
		{
			$this->_num_colunas = sizeof($this->_cabecalhos);
			$conteudo = '<thead><tr>' . PHP_EOL;
			$this->_selecionavel['chave'] = isset($this->_selecionavel['chave']) ? $this->_selecionavel['chave'] : '';
			$this->_selecionavel['display'] = isset($this->_selecionavel['display']) ? $this->_selecionavel['display'] : '';
			if (isset($this->_cabecalhos[$this->_selecionavel['chave']]))
			{
				$input = '<input type="checkbox" style="display:'.$this->_selecionavel['display'].'" name="gg2-seleciona_todos" id="gg2-seleciona_todos" /> ';
			}
			else
			{
				$input = '';
			}
			$tmp = 1;
			foreach ($this->_cabecalhos AS $chave => $cabecalho)
			{
				$conteudo .= $this->_html_cabecalho($cabecalho, $chave, $tmp, $input);
				$input = '';
				$tmp++;
			}
			if ( isset($this->_botoes) && is_array($this->_botoes))
			{
				$this->_larguras['botoes'] = count($this->_botoes) * 25;
				$conteudo .= $this->_html_cabecalho(' ', 'botoes');
				$this->_num_colunas++;
			}
			$conteudo .= '</tr></thead>' . PHP_EOL;
		}
		return $conteudo;
	}
	/**
	 * Retorna as linhas da listagem
	 *
	 * @return string
	 */
	private function _itens()
	{
		$conteudo = '<tbody>' . PHP_EOL;
		$chaves = array_keys($this->_cabecalhos);
		$tamanho = sizeof($chaves);
		if (count($this->_itens))
		{
			foreach ($this->_itens as $item)
			{
				$conteudo .= '<tr>';
				$id = isset($item->{$this->_selecionavel['chave']}) ? $item->{$this->_selecionavel['chave']} : '';
				if ( ! empty($id))
				{
					$input = '<input type="checkbox" style="display:'.$this->_selecionavel['display'].'" name="gg2-selecionado['.$id.']" id="gg2-selecionado_'.$id.'" value="'.$id.'" /> ';
				}
				else
				{
					$input = '';
				}
				for ($i = 0; $i < $tamanho; $i++)
				{
					$valor = isset($item->$chaves[$i]) ? $item->$chaves[$i] : '';
					$conteudo .= $this->_html_item($input.$valor);
					$input = '';
				}
				if ( isset($this->_botoes) && is_array($this->_botoes))
				{
					$conteudo .= $this->_html_item(str_replace('[id]', $id, implode(' ', $this->_botoes)), 'class="acoes" data-id="'.$id.'"');
				}
				$conteudo .= '</tr>' . PHP_EOL;
			}
		}
		else
		{
			$conteudo .= '<tr><td class="warning" colspan="'.$this->_num_colunas.'">Nenhum registro disponível.</td></tr>' . PHP_EOL;
		}
		$conteudo .= '</tbody>' . PHP_EOL;
		return $conteudo;
	}
	/**
	 * Retorna o html da coluna do cabecalho
	 *
	 * @param string  $campo   o nome da coluna
	 * @param string  $chave   a chave da coluna (nome que vendo do banco de dados)
	 * @param integer $posicao a posicao da coluna
	 * @param string  $input   o campo input para selecionar a coluna
	 *
	 * @return string
	 */
	private function _html_cabecalho($campo, $chave = '', $posicao = 0, $input = '')
	{
		$link = '';
		$imagem = '';
		$sentido = 'asc';
		if (isset($this->_url) && ! empty($this->_url))
		{
			if ($this->_ordenar_por === $chave)
			{
				if ($this->_ordenar_sentido === 'asc') $sentido = 'desc';
				$imagem = ($sentido === 'asc') ? '<i class="glyphicon glyphicon-chevron-down"></i> ' : '<i class="glyphicon glyphicon-chevron-up"></i> ';
			}
			$link = str_replace(array('[sort_by]', '[sort_order]'), array($posicao, $sentido), $this->_url);
		}
		$largura = isset($this->_larguras[$chave]) ? $this->_larguras[$chave] : '';
		$conteudo = '<th'.(empty($largura) ? '' : ' width="'.$largura.'" ').'>';
		$conteudo .= ( ! empty($link)) ? ' <a href="'.$link.'" title="'.$campo.'">' . $input  . $imagem . $campo .'</a>' : $input . $campo;
		$conteudo .= '</th>' . PHP_EOL;
		return $conteudo;
	}
	/**
	 * Retorna o html da coluna da linha
	 *
	 * @param string $campo o nome da coluna
	 * @param string $extra os argumentos adicionais da coluna da linha
	 *
	 * @return string
	 */
	private function _html_item($campo, $extra = '')
	{
		return '<td '.$extra.'>' . $campo . '</td>' . PHP_EOL;
	}
}

/* End of file gg2_listagem.php */
/* Location: ./libraries/gg2_listagem.php */