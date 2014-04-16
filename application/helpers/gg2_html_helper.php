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
if ( ! function_exists('menu_lateral'))
{
	/**
	 * Função que retorna os menus laterais do painel administrativo
	 *
	 * @return array
	 */
	function menu_lateral()
	{
		$ci =& get_instance();
		$ci->load->model('admins_menus_model');
		$itens = $ci->admins_menus_model->lista(array('ativo' => 'S'));

		return $itens['itens'];
	}
}
if ( ! function_exists('menu_ativo'))
{
	/**
	 * Função que retorna se é o modulo que esta sendo acessado
	 *
	 * @param string $menu o nome do modulo acessado que queremos pesquisar
	 * 
	 * @return string
	 */
	function menu_ativo($menu = '')
	{
		$ci =& get_instance();
		return ($ci->router->class === $menu) ? 'class="active"' : '';
	}
}

if ( ! function_exists('monta_campos_form'))
{
	/**
	 * Função que retorna o formulário html a partir de uma configuracao 
	 *
	 * @param array   $campos os campos do formulario
	 * @param array   $dados  os dados que queremos preencher o formulario
	 * @param boolean $edit   é um campo editavel ou não
	 * 
	 * @return string
	 */
	function monta_campos_form($campos = array(), $dados = NULL, $edit = TRUE)
	{
		$html = '';
		if (count($campos))
		{
			$dados = isset($dados) ? $dados : new stdClass();
			foreach ($campos as $campo)
			{
				$value = set_value($campo['field'], isset($dados->{$campo['field']}) ? $dados->{$campo['field']} : '');
				$dados->{$campo['field']} = isset($dados->{$campo['field']}) ? $dados->{$campo['field']} : '';
				$campo['extra_div'] = isset($campo['extra_div']) ? $campo['extra_div'] : '';
				$campo['field'] = isset($campo['field']) ? $campo['field'] : '';
				$campo['extra_campo'] = isset($campo['extra_campo']) ? $campo['extra_campo'] : '';
				$campo['tipo'] = isset($campo['tipo']) ? $campo['tipo'] : 'text';
				$campo['selecionado'] = ! empty($value) ? $value : (isset($campo['selecionado']) ? $campo['selecionado'] : '');
				$requerido = strstr($campo['rules'], 'required');

				if ( ! $edit) $campo['extra_campo'] .= ($campo['tipo'] === 'textarea') ? ' readonly="readonly"' :  ' disabled="disabled"';
				if ($requerido) $campo['extra_campo'] .= ' required="required"';

				if ($campo['tipo'] !== 'hidden')
				{
					$html .= PHP_EOL.'<div '.$campo['extra_div'].'>';
					$html .= PHP_EOL.'<label for="gg2-campo-'.$campo['field'].'">'.$campo['label'].($requerido ? '<span class="gg2-requerido">*</span>' : '').'</label>';
				}
				switch ($campo['tipo'])
				{
					case 'radio':
						if (isset($campo['itens']) && count($campo['itens']))
						{
							$html .= PHP_EOL.'<div class="divInput">';
							foreach ($campo['itens'] AS $id => $item)
							{
								$html .= PHP_EOL.'<div '.$campo['extra_campo'].'><input type="radio" name="'.$campo['field'].'" id="gg2-campo-'.$campo['field'].$id.'" value="'.$id.'" title="'.$item.'" '.(($dados->{$campo['field']} === $id) ? 'checked="checked"' : '').' /> <label for="gg2-campo-'.$campo['field'].$id.'">'.$item.'</label></div>';
							}
							$html .= PHP_EOL.'</div>';
						}
						break;
					case 'select':
						$html .= PHP_EOL.'<select name="'.$campo['field'].'" id="gg2-campo-'.$campo['field'].'" title="'.$campo['label'].'" '.$campo['extra_campo'].'>';
						$html .= PHP_EOL.gera_select_option($campo['itens'], $campo['selecionado'], 'Selecione...');
						$html .= PHP_EOL.'</select>';
						break;
					case 'textarea':
						$html .= PHP_EOL.'<textarea name="'.$campo['field'].'" id="gg2-campo-'.$campo['field'].'" placeholder="'.$campo['label'].'" '.$campo['extra_campo'].'>'. $value.'</textarea>';
						break;
					case 'file':
						$html .= PHP_EOL.'<input type="file" name="'.$campo['field'].'" id="gg2-campo-'.$campo['field'].'" placeholder="'.$campo['label'].'" '.$campo['extra_campo'].' data-value="'.$dados->{$campo['field']}.'" />';
						break;
					case 'date':
						if (strstr($campo['extra_campo'], 'class="')) $campo['extra_campo'] = str_replace('class="', 'class="gg2-campo-date ', $campo['extra_campo']);
						else $campo['extra_campo'] .= ' class="gg2-campo-date"';
						$html .= PHP_EOL.'<input type="text" name="'.$campo['field'].'" id="gg2-campo-'.$campo['field'].'" placeholder="'.$campo['label'].'" '.$campo['extra_campo'].' value="'. $value.'" data-mask="00/00/0000" />';
						break;
					case 'datetime':
						if (strstr($campo['extra_campo'], 'class="')) $campo['extra_campo'] = str_replace('class="', 'class="gg2-campo-datetime ', $campo['extra_campo']);
						else $campo['extra_campo'] .= ' class="gg2-campo-datetime"';
						$html .= PHP_EOL.'<input type="text" name="'.$campo['field'].'" id="gg2-campo-'.$campo['field'].'" placeholder="'.$campo['label'].'" '.$campo['extra_campo'].' value="'. $value.'" data-mask="00/00/0000 (00:00)" />';
						break;
					case 'money':
						if (strstr($campo['extra_campo'], 'class="')) $campo['extra_campo'] = str_replace('class="', 'class="gg2-campo-money ', $campo['extra_campo']);
						else $campo['extra_campo'] .= ' class="gg2-campo-money"';
						$html .= PHP_EOL.'<input type="text" name="'.$campo['field'].'" id="gg2-campo-'.$campo['field'].'" placeholder="'.$campo['label'].'" '.$campo['extra_campo'].' value="'. $value.'" data-mask="#.##0,00" data-mask-reverse="true" data-mask-maxlength="false" />';
						break;
					case 'number':
						if (strstr($campo['extra_campo'], 'class="')) $campo['extra_campo'] = str_replace('class="', 'class="gg2-campo-number ', $campo['extra_campo']);
						else $campo['extra_campo'] .= ' class="gg2-campo-number"';
						$html .= PHP_EOL.'<input type="text" name="'.$campo['field'].'" id="gg2-campo-'.$campo['field'].'" placeholder="'.$campo['label'].'" '.$campo['extra_campo'].' value="'. $value.'" data-mask="0#" data-mask-maxlength="false" />';
						break;
					case 'phone':
						if (strstr($campo['extra_campo'], 'class="')) $campo['extra_campo'] = str_replace('class="', 'class="gg2-campo-phone ', $campo['extra_campo']);
						else $campo['extra_campo'] .= ' class="gg2-campo-phone"';
						$html .= PHP_EOL.'<input type="text" name="'.$campo['field'].'" id="gg2-campo-'.$campo['field'].'" placeholder="'.$campo['label'].'" '.$campo['extra_campo'].' value="'. $value.'" data-mask="(##) ####-####9" />';
						break;
					case 'cep':
						if (strstr($campo['extra_campo'], 'class="')) $campo['extra_campo'] = str_replace('class="', 'class="gg2-campo-cep ', $campo['extra_campo']);
						else $campo['extra_campo'] .= ' class="gg2-campo-cep"';
						$html .= PHP_EOL.'<input type="text" name="'.$campo['field'].'" id="gg2-campo-'.$campo['field'].'" placeholder="'.$campo['label'].'" '.$campo['extra_campo'].' value="'. $value.'" data-mask="#####-###" />';
						break;
					default:
						$html .= PHP_EOL.'<input type="'.$campo['tipo'].'" name="'.$campo['field'].'" id="gg2-campo-'.$campo['field'].'" placeholder="'.$campo['label'].'" '.$campo['extra_campo'].' value="'. $value.'" />';
						break;
				}
				if ($campo['tipo'] !== 'hidden')
					$html .= PHP_EOL.'</div>';
			}
		}
		return $html;
	}
}
if ( ! function_exists('gera_select_option'))
{
	/**
	 * Função que os options a partir de uma configuracao
	 *
	 * @param array  $dados       os valores do campo
	 * @param string $selecionado o valor selecionado
	 * @param string $vazio       o valor do campo vazio
	 * 
	 * @return string
	 */
	function gera_select_option($dados, $selecionado = '', $vazio = '')
	{
		$option = '';
		if ( ! empty($vazio))
			$option = '<option value="">'.$vazio.'</option>'.PHP_EOL;

		if (count($dados))
		{
			foreach ($dados as $value)
			{
				$option .= '<option value="'.$value->id.'"'.(($selecionado === $value->id) ? ' selected="selected"' : '' ).'>'.$value->nome.'</option>'.PHP_EOL;
			}
		}
		return $option;
	}
}

if ( ! function_exists('mensagem_popup'))
{
	/**
	 * Função que seta na sessao os dados do popup modal do bootstrap a partir de uma configuracao
	 *
	 * @param array  $mensagem o conteudo html do popup
	 * @param string $titulo   o titulo da janela
	 * @param string $class    a classe css da janela
	 * @param array  $botoes   os botoes adicionais da janela
	 * 
	 * @return void
	 */
	function mensagem_popup($mensagem, $titulo = 'Informação', $class = 'primary', $botoes = array())
	{
		if (is_array($mensagem))
		{
			$titulo = isset($mensagem['titulo']) ? $mensagem['titulo'] : $titulo;
			$class = isset($mensagem['class']) ? $mensagem['class'] : $class;
			$botoes = isset($mensagem['botoes']) ? $mensagem['botoes'] : $botoes;
			$mensagem = isset($mensagem['mensagem']) ? $mensagem['mensagem'] : '';
		}
		if ( ! empty($mensagem))
		{
			$ci =& get_instance();
			$dados = array(
				'mensagem' => $mensagem,
				'titulo' => $titulo,
				'class' => $class,
				'botoes' => $botoes
			);
			$ci->session->set_userdata('mensagem_popup', $dados);
		}
	}
}

if ( ! function_exists('mostra_popup'))
{
	/**
	 * Função que mostra os dados do popup modal do bootstrap a partir da sessao
	 * 
	 * @return string
	 */
	function mostra_popup()
	{
		$ci =& get_instance();
		$popup = $ci->session->userdata('mensagem_popup');
		if ( ! empty($popup))
		{
			$ret = $ci->load->view('layouts/janela_modal', array('popup' => $popup), TRUE);
			$ci->session->unset_userdata('mensagem_popup');
		}
		else
		{
			$ret = '';
		}
		return $ret;
	}
}

if ( ! function_exists('mostra_banner'))
{
	/**
	 * Função que mostra os banneres em jpg ou em swf
	 *
	 * @param object $dados os dados do banner
	 * 
	 * @return string
	 */
	function mostra_banner($dados)
	{
		$ret = '';
		if (isset($dados->imagem) && ! empty($dados->imagem))
		{
			if (pega_extensao_arquivo($dados->imagem) !== 'swf')
			{
				$ret = '<img src="'. base_url('assets/img/'.$dados->imagem).'" alt="Banner" class="img-responsive">';
				if (isset($dados->link) && ! empty($dados->link))
				{
					$ret = '<a href="'.$dados->link.'">'.$ret.'</a>';
				}
			}
			else
			{
				$ret = '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="100%" height="185" id="Untitled-1" align="middle">
				<param name="movie" value="'. base_url('assets/img/'.$dados->imagem).'" />
				<param name="quality" value="high" />
				<param name="bgcolor" value="#ffffff" />
				<param name="play" value="true" />
				<param name="loop" value="true" />
				<param name="wmode" value="window" />
				<param name="scale" value="showall" />
				<param name="menu" value="true" />
				<param name="devicefont" value="false" />
				<param name="salign" value="" />
				<param name="allowScriptAccess" value="sameDomain" />
				<!--[if !IE]>-->
				<object type="application/x-shockwave-flash" data="'. base_url('assets/img/'.$dados->imagem).'" width="100%" height="185">
					<param name="movie" value="'. base_url('assets/img/'.$dados->imagem).'" />
					<param name="quality" value="high" />
					<param name="bgcolor" value="#ffffff" />
					<param name="play" value="true" />
					<param name="loop" value="true" />
					<param name="wmode" value="window" />
					<param name="scale" value="showall" />
					<param name="menu" value="true" />
					<param name="devicefont" value="false" />
					<param name="salign" value="" />
					<param name="allowScriptAccess" value="sameDomain" />
				<!--<![endif]-->
					<a href="http://www.adobe.com/go/getflash">
						<img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" />
					</a>
				<!--[if !IE]>-->
				</object>
				<!--<![endif]-->
			</object>';
			}
		}

		return $ret;
	}
}


/* End of file gg2_html_helper.php */
/* Location: ./helpers/gg2_html_helper.php */