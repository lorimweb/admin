<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Neste arquivo ficam as funções helpers de formatação de dados
 * 
 * @category  GG2_Admin
 * @package   Helpers
 * @author    Gihovani Filipp Pereira Demétrio <gihovani@gmail.com>
 * @copyright 2012-2014 GG2 Soluções
 * @license   http://gg2.com.br/license.html GG2 Soluções
 * @version   1.0
 * @link      http://gg2.com.br
 */

if( ! function_exists('slug'))
{
	/**
	 * Função que retorna uma string sem acentos e tudo em minusculo
	 * usado para as urls amigaveis
	 *
	 * @param string $string o texto que queremos converter
	 * 
	 * @return string
	 */
	function slug($string) 
	{
		if (is_string($string)) 
		{
			$string = strtolower(trim(utf8_decode($string)));

			$before = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr';
			$after  = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';           
			$string = strtr($string, utf8_decode($before), $after);
					
			$replace = array(
				'/[^a-z0-9.-]/'	=> '-',
				'/-+/'			=> '-',
				'/\-{2,}/'		=> ''
			);
			$string = preg_replace(array_keys($replace), array_values($replace), $string);
		}
		return $string;
	}
}

if( ! function_exists('formata_data_hora_mysql'))
{
	/**
	 * Função que retorna uma data hora (datetime) no formato do mysql
	 *
	 * @param string  $data  a data para convertermos
	 * @param boolean $agora se a data for invalida retorna a data e hora atual
	 * 
	 * @return datetime
	 */
	function formata_data_hora_mysql($data, $agora = TRUE) 
	{
		$data = explode(' ', $data);
		$data[0] = formata_data_mysql($data[0], FALSE);
		
		if($data[0])
		{
			if(strlen($data[1]) === 5)
				$data[1] .= ':00';
			$ret = implode(' ', $data);
		}
		else
		{
			$ret = ($agora) ? date('Y-m-d H:i:s') : NULL;
		}
		return $ret;
	}
}

if( ! function_exists('formata_data_mysql'))
{ 
	/**
	 * Função que retorna uma data (date) no formato do mysql
	 *
	 * @param string  $data  a data para convertermos
	 * @param boolean $agora se a data for invalida retorna a data atual
	 * 
	 * @return date
	 */
	function formata_data_mysql($data, $agora = TRUE)
	{
		$data = str_replace('_', '', $data);
				$ret = ($agora) ? date('Y-m-d') : NULL;
				if (strlen($data) === 10)
				{
					if (substr_count($data, '/') === 2)
					{
						$data = explode('/', $data);
						if (count($data) === 3)
							$ret = implode('-', array_reverse($data));
					}
					elseif (substr_count($data, '-') === 2)
					{
						$ret = $data;
					}
				}
		return $ret;
	}
}

/* End of file gg2_formatacoes_helper.php */
/* Location: ./helpers/gg2_formatacoes_helper.php */