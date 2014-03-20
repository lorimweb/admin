<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Esta classe é para pre configurar os inputs
 * 
 * @category  GG2_Admin
 * @package   Models
 * @author    Gihovani Filipp Pereira Demétrio <gihovani@gmail.com>
 * @copyright 2012-2014 GG2 Soluções
 * @license   http://gg2.com.br/license.html GG2 Soluções
 * @version   Release: 1.0
 * @link      http://gg2.com.br
 */
class MY_Input extends CI_Input
{
	/**
	 * Função que retorna a lista com base na busca do BD
	 * 
	 * @param string  $prefix    o prefixo que queremos adicionar na chave dos campos
	 * @param array   $post      coluna de referencia para ordenação
	 * @param boolean $em_branco se valores em branco devem ser retornados
	 * @param boolean $xss_clean se queremos limpar tentativas de fraude
	 * 
	 * @return array
	 */
	public function post_to_array($prefix = '', $post = NULL, $em_branco = FALSE, $xss_clean = FALSE)
	{
		$ret = array();
		if ( ! isset($post))
			$post = $_POST;
		foreach ($post as $chave => $value)
		{
			if (is_array($value) && count($value))
			{
				$ret[$prefix.$chave] = $this->post_to_array('', $value, $em_branco, $xss_clean);
			}
			else
			{
				if ($xss_clean === TRUE)
					$tmp = $this->security->xss_clean($value);
				else 
					$tmp = $value;
				
				
				if ($em_branco)
				{
					$ret[$prefix.$chave] = $tmp;
				}
				else
				{
					if ( ! empty($tmp))
						$ret[$prefix.$chave] = $tmp;
					else 
						$ret[$prefix.$chave] = NULL;
				}
			}
		}
		
		return $ret;
	}
	/**
	 * Função que retorna a query string
	 * 
	 * @return string
	 */
	public function query_string()
	{
		return $_SERVER['QUERY_STRING'];
	}
}

/* End of file MY_Input.php */
/* Location: ./core/MY_Input.php */