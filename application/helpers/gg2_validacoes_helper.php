<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Neste arquivo ficam as funções helpers de html de dados
 * 
 * @category  GG2_Admin
 * @package   Helpers
 * @author    Gihovani Filipp Pereira Demétrio <gihovani@gmail.com>
 * @copyright 2012-2014 GG2 Soluções
 * @license   http://gg2.com.br/license.html GG2 Soluções
 * @version   Release: 1.0
 * @link      http://gg2.com.br
 */

if ( ! function_exists('mensagem_validacao'))
{
	/**
	 * Função que retorna o html da validação passando como parametro a mensagem que será mostrada caso esteja tudo ok na validação
	 *
	 * @param string $salvo Mensagem se estiver tudo ok
	 * 
	 * @return string
	 */
	function mensagem_validacao($salvo = NULL)
	{
		$ci =& get_instance();
		$ret = '';
		$flag = TRUE;
		$mensagem = $ci->form_validation->error_string('<li>', '</li>');
		if (empty($mensagem))
		{
			if ( ! empty($salvo))
			{
				$class = 'success';
				$mensagem = $salvo;
			}
			else
			{
				$class = '';
				$mensagem = '';
				$flag = FALSE;
			}
		}
		else
		{
			$class = 'danger';
			$mensagem = '<h4>Preencha os campos abaixo corretamente:</h4><ul>'.$mensagem.'</ul>';
		}
		if ($flag) $ret = sprintf('<div class="alert alert-%s"><button type="button" class="close" data-dismiss="alert">&times;</button>%s</div>', $class, $mensagem);
		return $ret;
	}
}

if ( ! function_exists('tem_permissao'))
{
	/**
	 * Função que retorna se o usuario da sessao tem permissao ou não naquele metodo
	 *
	 * @param string $classe o nome do modulo
	 * @param string $metodo o nome da acao do modulo
	 * 
	 * @return boolean
	 */
	function tem_permissao($classe, $metodo = NULL)
	{
		$ret = FALSE;
		$ci =& get_instance();

		$permissoes = $ci->session->userdata('permissoes');
		if ( ! empty($permissoes) && count($permissoes))
		{
			$metodo = ($metodo === 'index') ? '' : $metodo;

			if (isset($metodo) && ! empty($metodo))
			{
				$ret = isset($permissoes[$classe][$metodo]);
				if ( ! $ret && strstr($metodo, 'json'))
					$ret = $ci->input->is_ajax_request();
			}
			else
			{
				$ret = isset($permissoes[$classe]);
			}
		}

		return TRUE;
		// return $ret;
	}
}

if ( ! function_exists('user_logado'))
{
	/**
	 * Função que retorna o nome do usuario da sessao
	 * 
	 * @return string
	 */
	function user_logado()
	{
		$ci =& get_instance();
		return $ci->session->userdata('nome');
	}
}

if ( ! function_exists('regra_validacao'))
{
	/**
	 * Gera um array com os campos do formulário e adiciona automaticamente a validacao (set_rules) do codeigniter.
	 *
	 * @param array  $config     nome do campo
	 * @param string $descricao  label do campo
	 * @param string $regras     as regras do codeigniter ex: trim|required
	 * @param string $attr_div   atributos da div que vai ser o elemento pai do label e do campo
	 * @param string $attr_campo atributos do campo onde será selecionado ou escrito a informação
	 * @param string $tipo       tipo do campo por default é text
	 * @param array  $itens      quando é um select ou um radio este item deve ser preenchido com as opções
	 * 
	 * @return array	
	 */
	function regra_validacao($config, $descricao = '', $regras = '', $attr_div = '', $attr_campo = '', $tipo = 'text', $itens = array())
	{
		if (is_array($config))
		{
			extract($config);
		}
		else
		{
			$campo = $config;
		}
		if (empty($regras)) $regras = 'trim|required';
		if ( ! strstr($attr_campo, 'class='))
			$attr_campo .= ' class="form-control"';
		else
			$attr_campo = str_replace('class="', 'class="form-control ', $attr_campo);

		if ( ! strstr($attr_div, 'class='))
			$attr_div .= ' class="form-group col-md-12"';
		else
			$attr_div = str_replace('class="', 'class="form-group ', $attr_div);

		return array(
				'field' 		=> $campo,
				'label' 		=> $descricao,
				'rules' 		=> $regras,
				'tipo' 			=> $tipo,
				'itens' 		=> $itens,
				'extra_campo'   => $attr_campo,
				'extra_div'     => $attr_div
		);
	}
}

if ( ! function_exists('filtro_config'))
{
	/**
	 * Gera um array com os campos do formulário de filtro da listagem
	 *
	 * @param string $config       nome do campo
	 * @param string $descricao    label do campo
	 * @param string $operacao_sql pode ser uma string contendo as operacoes sql como like, =. >, <, ou pode ser um array com os comandos ci_where + where
	 * @param string $tipo         tipo do campo por default é text
	 * @param array  $itens        quando é um select ou um radio este item deve ser preenchido com as opções
	 * @param string $attr_div     atributos da div que vai ser o elemento pai do label e do campo
	 * @param string $valor        se tem um valor default no campo
	 * 
	 * @return array	
	 */
	function filtro_config($config, $descricao, $operacao_sql = '', $tipo = 'text', $itens = array(), $attr_div = 'class="form-control"', $valor = '[valor]')
	{
		if (is_array($config))
			extract($config);
		else
			$campo = $config;

		$id = str_replace('.', '-', $campo);
		if (is_array($operacao_sql))
		{
			$where = $operacao_sql['where'];
			unset($operacao_sql['where']);
			$ci_where = $operacao_sql;
		}
		else
		{
			$ci_where = array(
				'funcao_ci' => $operacao_sql,
				'campo'     => $campo,
				'valor'     => ''
			);
			if ($operacao_sql === 'like')
				$valor = '%'.$valor.'%';
			else
				$operacao_sql = '=';

			$where = sprintf('%s %s "%s"', $campo, $operacao_sql, $valor);
		}
		return array(
				'extra'     => $attr_div,
				'tipo'      => $tipo,
				'nome'      => $id,
				'descricao' => $descricao,
				'where'     => $where,
				'ci_where'  => $ci_where,
				'itens'		=> $itens
		);
	}
}

if ( ! function_exists('eh_cpf_valido'))
{
	/**
	 * Verifica se um cpf é válido
	 *
	 * @param string $numero numero do cpf
	 * 
	 * @return bool
	 */
	function eh_cpf_valido($numero)
	{
		$numero = str_pad(preg_replace('/[^0-9]/', '', $numero), 11, '0', STR_PAD_LEFT);
		// Verifica se nenhuma das sequências abaixo foi digitada, caso seja, retorna falso
		$invalidos = array(
			'00000000000',
			'11111111111',
			'22222222222',
			'33333333333',
			'44444444444',
			'55555555555',
			'66666666666',
			'77777777777',
			'88888888888',
			'99999999999'
		);
		if (strlen($numero) !== 11 OR in_array($numero, $invalidos))
		{
			return FALSE;
		}
		else
		{
			// Calcula os números para verificar se o numero é verdadeiro
			for ($t = 9; $t < 11; $t++)
			{
				for ($digito = 0, $posicao = 0; $posicao < $t; $posicao++)
				{
					$digito += $numero{$posicao} * (($t + 1) - $posicao);
				}

				$digito = ((10 * $digito) % 11) % 10;
				if (intval($numero{$posicao}) !== intval($digito))
					return FALSE;
			}

			return TRUE;
		}
	}
}

if ( ! function_exists('eh_cnpj_valido'))
{
	/**
	 * Verifica se um cnpj é válido
	 *
	 * @param string $numero numero do cnpj
	 * 
	 * @return bool
	 */
	function eh_cnpj_valido($numero)
	{
		$numero = str_pad(preg_replace('/[^0-9]/', '', $numero), 14, '0', STR_PAD_LEFT);
		$invalidos = array(
			'00000000000000',
			'11111111111111',
			'22222222222222',
			'33333333333333',
			'44444444444444',
			'55555555555555',
			'66666666666666',
			'77777777777777',
			'88888888888888',
			'99999999999999'
		);
		if (strlen($numero) !== 14 OR in_array($numero, $invalidos))
		{
			return FALSE;
		}
		else
		{
			for ($t = 12; $t < 14; $t++)
			{
				for ($digito = 0, $p = $t - 7, $posicao = 0; $posicao < $t; $posicao++)
				{
					$digito += $numero{$posicao} * $p;
					$p = ($p < 3) ? 9 : --$p;
				}
				$digito = ((10 * $digito) % 11) % 10;
				if (intval($numero{$posicao}) !== intval($digito))
					return FALSE;
			}

			return TRUE;
		}
	}
}

/* End of file gg2_validacoes_helper.php */
/* Location: ./helpers/gg2_validacoes_helper.php */