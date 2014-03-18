<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Neste arquivo ficam as funções helpers extras
 * algumas funções básicas para auxiliar na programação
 * 
 * @category  GG2_Admin
 * @package   Helpers
 * @author    Gihovani Filipp Pereira Demétrio <gihovani@gmail.com>
 * @copyright 2012-2014 GG2 Soluções
 * @license   http://gg2.com.br/license.html GG2 Soluções
 * @version   1.0
 * @link      http://gg2.com.br
 */

if( ! function_exists('pega_chave_array'))
{
	/**
	 * Função que retorna a partir de um array o nome do 
	 * campo na posicao passada como parametro
	 *
	 * @param array   $array   o array com todos os campos
	 * @param integer $posicao a posicao que queremos pegar
	 * 
	 * @return string
	 */
	function pega_chave_array($array = array(), $posicao = 0)
	{
		$ret = NULL;
		if (count($array))
		{
			$array = array_keys($array);
			if ($array[$posicao])
				$ret = $array[$posicao];
		}
		return $ret;
	}
}
if( ! function_exists('pega_extensao_arquivo'))
{
	/**
	 * Função que retorna a extensao de um arquivo
	 *
	 * @param string $arquivo o nome ou o caminho do arquivo
	 * 
	 * @return string
	 */
	function pega_extensao_arquivo($arquivo)
	{
		return str_replace('.', '', strtolower(substr($arquivo, -4)));
	}
}
if( ! function_exists('pega_id_youtube'))
{
	/**
	 * Função que retorna o id de um link do youtube
	 *
	 * @param string $link a url do youtube
	 * 
	 * @return string
	 */
	function pega_id_youtube($link)
	{
		$ret = FALSE;
		parse_str( parse_url( $link, PHP_URL_QUERY ), $tmp );
		if (isset($tmp['v']) && ! empty($tmp['v']))
		{
			$ret = $tmp['v'];
		}
		return $ret;
	}
}


if ( ! function_exists('unzip'))
{
	/**
	 * Função que descompacta um arquivo zip
	 *
	 * @param string $arquivo    o nome do arquivo
	 * @param string $nova_pasta a pasta onde queremos extrair os dados do arquivo
	 * @param string $caminho    o caminho do arquivo
	 * 
	 * @return boolean
	 */
	function unzip($arquivo, $nova_pasta, $caminho)
	{
		$ret = TRUE;
		if ( ! empty($arquivo) && ! empty($nova_pasta) && ! empty($caminho))
		{
			$arquivo_zip = new ZipArchive();
			$aberto = $arquivo_zip->open($caminho . $arquivo);
			if ($aberto === TRUE) 
			{
				$diretorio = $caminho . $nova_pasta;
				if ( ! is_dir($diretorio)) 
				{
					@mkdir($diretorio, 0755, TRUE) OR die('Erro para criar a pasta '.$diretorio);
				}
				$arquivo_zip->extractTo($diretorio);
				$arquivo_zip->close();
				$ret = TRUE;
			}
			else
			{
				$ret = FALSE;
			}
		}
		
		return $ret;
	}
}
if ( ! function_exists('pega_arquivos'))
{
	/**
	 * Função que retorna a lista de arquivos de um diretorio
	 *
	 * @param string $caminho o caminho da pasta 
	 * @param string $regex   a expressao para pegarmos os arquivos
	 * 
	 * @return array
	 */
	function pega_arquivos($caminho, $regex = '{*.jpg,*.JPG}')
	{
		return glob($caminho . $regex, GLOB_BRACE);
	}
}

if ( ! function_exists('envia_email'))
{
	/**
	 * Função que envia email
	 *
	 * @param string $para      o email da pessoa que vai receber
	 * @param string $assunto   o assunto do email
	 * @param string $mensagem  o conteudo
	 * @param string $anexo     o caminho do arquivo que queremos anexar
	 * @param string $remetente o email do remetente
	 * 
	 * @return boolean
	 */
	function envia_email($para, $assunto, $mensagem, $anexo = NULL, $remetente = EMAIL)
	{
		$ci =& get_instance();
		$ci->load->library('email', array('mailtype' => 'html'));
		$ci->email->set_newline('\r\n');
		$ci->email->clear();
		$ci->email->from($remetente);
		$ci->email->to($para);
		$ci->email->subject($assunto);
		$ci->email->message($mensagem);
			
		if (isset($anexo) && ! empty($anexo)) $ci->email->attach($anexo);
		return $ci->email->send();
	}
}

if ( ! function_exists('sim_nao'))
{
	/**
	 * Função que retorna um array com os itens sim e não
	 * essa função serve para não precisarmos recortar toda vez que quisermos criar
	 * um array sim e não
	 * 
	 * @return array
	 */
	function sim_nao()
	{
		$lista = array(
			(object)array('id' => 'S', 'nome' => 'Sim'),
			(object)array('id' => 'N', 'nome' => 'Não')
		);
		return $lista;
	}
}

/* End of file gg2_extra_helper.php */
/* Location: ./helpers/gg2_extra_helper.php */