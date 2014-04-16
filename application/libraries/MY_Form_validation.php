<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Esta classe é uma biblioteca responsável por fazer a validação dos formulários.
 * 
 * @category  GG2_Admin
 * @package   Libraries
 * @author    Gihovani Filipp Pereira Demétrio <gihovani@gmail.com>
 * @copyright 2012-2014 GG2 Soluções
 * @license   http://gg2.com.br/license.html GG2 Soluções
 * @version   Release: 1.0
 * @link      http://gg2.com.br
 */
class MY_Form_validation extends CI_Form_validation {
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Função que verifica se é um cpf ou um cnpj válido.
	 *
	 * @param string $str conteudo para ser validado.
	 *
	 * @return bool
	 */
	public function cpf_cnpj_valido($str)
	{
		$ret = FALSE;
		$this->set_message('cpf_cnpj_valido', 'O campo %s deve conter um número de documento válido.');
		$tamanho = strlen($str);
		if ($tamanho === 14)
			$ret = (bool) eh_cpf_valido($str);
		elseif ($tamanho === 18)
			$ret = (bool) eh_cnpj_valido($str);

		return $ret;
	}
	/**
	 * Função que verifica se é uma data válida
	 *
	 * @param string $str conteudo para ser validado.
	 *
	 * @return bool
	 */
	public function data_valido($str)
	{
		$ret = FALSE;
		$this->set_message('data_valido', 'O campo %s deve conter uma Data válida.');
		if (strlen($str) === 10)
		{
			$str = explode('/', $str);

			$ret = checkdate($str[1], $str[0], $str[2]);
		}
		return $ret;
	}
	/**
	 * Função que verifica se a data é maior que a idade passada como parametro.
	 *
	 * @param string  $str   conteudo para ser validado.
	 * @param integer $idade idade mínima
	 *
	 * @return bool
	 */
	public function idade_maior_valido($str, $idade)
	{
		$ret = FALSE;
		$maior = date('Y') - intval($idade);
		$this->set_message('idade_maior_valido', 'O campo %s deve conter uma Data menor que (31/12/'.$maior.').');
		if (strlen($str) === 10)
		{
			$str = explode('/', $str);

			$ret = ($str[2] <= $maior);
		}
		return $ret;
	}
}

/* End of file MY_Form_validation.php */
/* Location: ./libraries/MY_Form_validation.php */