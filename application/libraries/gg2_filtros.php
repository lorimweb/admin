<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Esta classe é uma biblioteca responsável por:
 * - gerar uma tabela com um formulário em html
 * - gerar os parametros sql para a consulta de acordo com o formulario
 * 
 * Podemos configurar os itens do formulário.
 *
 * @category  GG2_Admin
 * @package   Libraries
 * @author    Gihovani Filipp Pereira Demétrio <gihovani@gmail.com>
 * @copyright 2012-2014 GG2 Soluções
 * @license   http://gg2.com.br/license.html GG2 Soluções
 * @version   Release: 1.0
 * @link      http://gg2.com.br
 */
class Gg2_filtros
{
	/**
	 * Os itens do formulario.
	 * 
	 * @var array
	 */
	private $_itens;
	/**
	 * Os valores dos itens do formulario.
	 * 
	 * @var array
	 */
	private $_valores;
	/**
	 * A url base do formulario.
	 * 
	 * @var string
	 */
	private $_url;
	/**
	 * O numero de colunas do formulario.
	 * 
	 * @var integer
	 */
	private $_colunas;
	/**
	 * Os botões adicionais do formulario.
	 * 
	 * @var string
	 */
	private $_botoes;

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
			$this->init($config['itens'], $config['valores'], $config['url'], $config['colunas'], $config['botoes']);
	}

	/**
	 * Função que retorna o html do formulário
	 * 
	 * @return String
	 */
	public function formulario_html()
	{
		if ($this->qtd_itens)
		{
			$hidden = '';
			$formulario = '<input type="hidden" name="gg2-parametros_url" id="gg2-parametros_url" value="'.$this->parametros_url().'" />' . PHP_EOL;
			$formulario .= '<form name="gg2-filtros" method="get" action="'.$this->_url.'">' . PHP_EOL;
			$formulario .= '<table cellpadding="0" cellspacing="0" width="100%" class="gg2-filtros table">' . PHP_EOL;
			$formulario .= '<thead>' . PHP_EOL;
			$formulario .= '<tr>';
			$qt_hidden = 0;
			for ($i = 0; $i < $this->qtd_itens; $i++)
			{
				$valor = isset($this->_itens[$i]['valor']) ? $this->_itens[$i]['valor'] : '';
				$valor_campo = (isset($this->_valores[$this->_itens[$i]['nome']])
					&& ( ! empty($this->_valores[$this->_itens[$i]['nome']])
						OR $this->_valores[$this->_itens[$i]['nome']] === '0')
					) ? $this->_valores[$this->_itens[$i]['nome']] : $valor;

				if ($this->_itens[$i]['tipo'] !== 'hidden')
				{
					$formulario .= '<td width="' . intval(100 / $this->_colunas) . '%"' .
						( ! empty($this->_itens[$i]['colspan']) ? ' colspan="' . $this->_itens[$i]['colspan'] . '"' : '') .
						'>' . PHP_EOL;
					$formulario .= '<div class="form-group">' . PHP_EOL;
					$formulario .= '<label for="gg2-f-'.$this->_itens[$i]['nome'].'" title="'.$this->_itens[$i]['descricao'].'">';
					$formulario .= $this->_itens[$i]['descricao'] . PHP_EOL;
					$formulario .= ':</label>' . PHP_EOL;
					$formulario .= $this->_campo_item($this->_itens[$i], $valor_campo);
					$formulario .= '</div></td>';
					if ((($i + 1) - $qt_hidden) % $this->_colunas === 0)
					{
						$formulario .= PHP_EOL . '</tr><tr>';
					}
				}
				else
				{
					$qt_hidden++;
					$hidden .= $this->_campo_item($this->_itens[$i], $valor_campo);
				}
			}
			$formulario .= '</tr>' . PHP_EOL;
			$formulario .= '</thead>' . PHP_EOL;
			$formulario .= '<tfoot>' . PHP_EOL;
			$formulario .= '<tr>' . PHP_EOL;
			$formulario .= '<td colspan="'.$this->_colunas.'">' . PHP_EOL;
			$formulario .= ' <button type="submit" class="btn btn-primary"> Filtrar</button>' . PHP_EOL;
			$formulario .= ' <a class="btn btn-default" id="gg2-filtros_limpar" href="'.$this->_url.'"> Limpar Filtros</a>' . PHP_EOL;
			$formulario .= $this->_botoes;
			$formulario .= '</td>' . PHP_EOL;
			$formulario .= '</tr>' . PHP_EOL;
			$formulario .= '</tfoot>' . PHP_EOL;
			$formulario .= '</table>' . PHP_EOL;
			$formulario .= $hidden;
			$formulario .= '</form>' . PHP_EOL;
			$formulario = str_replace('<tr></tr>', '', $formulario);
		}
		else
		{
			$formulario = '';
		}
		return $formulario;
	}
	/**
	 * Função que retorna os filtro em sql 
	 * 
	 * @return String
	 */
	public function parametros_sql()
	{
		if ($this->qtd_itens)
		{
			foreach ($this->_itens as $item)
			{
				$valor = ( ! empty($this->_valores[$item['nome']]) ? $this->_valores[$item['nome']] : '');
				if (gettype($valor) === 'array')
				{
					$valor = implode(',', $valor);
				}
				if ( ! empty($valor) OR $valor === '0')
				{
					switch ($item['tipo'])
					{
						case 'select':
							foreach ($item['itens'] as $opcoes)
							{
								if (($opcoes->id === $valor) && ( ! empty($opcoes->where)))
									$parametro = str_replace('[valor]', $valor, $opcoes->where);
							}
							if (empty($parametro))
								$parametro = str_replace('[valor]', $valor, $item['where']);

							break;
						case 'hidden':
							$parametro = $item['where'];
							break;
						default:
							if (empty($valor)) $valor = '%';
							$parametro = str_replace('[valor]', $valor, $item['where']);
							break;
					}
					$parametros[] = $parametro;
				}
				unset($parametro);
			}
			$parametros = '(' . ( ! empty($parametros) ? @implode(' AND ', $parametros) : '1') . ')';
		}
		else
		{
			$parametros = '(1)';
		}
		return $parametros;
	}
	/**
	 * Função que retorna os filtro estilo get url
	 * 
	 * @return String
	 */
	public function parametros_url()
	{
		$parametros = '';
		if ($this->_valores)
		{
			foreach ($this->_valores as $id => $valor)
			{
				if (gettype($valor) === 'array')
					$valor = implode(',', $valor);

				$parametros .= '&gg2-f['.$id.']='.$valor;
			}
		}
		return $parametros;
	}
	/**
	 * Função que retorna os filtro estilo codeigniter
	 * 
	 * @return Array
	 */
	public function parametros_ci_where()
	{
		$parametros = array();
		if ($this->qtd_itens)
		{
			foreach ($this->_itens as $item)
			{
				$valor = ( ! empty($item['ci_where']['valor']) ? $item['ci_where']['valor'] : (isset($this->_valores[$item['nome']]) ? $this->_valores[$item['nome']] : ''));
				if ( ! empty($valor) OR $valor === '0')
				{
					$item['ci_where']['valor'] = (is_string($valor) && strtolower($valor) === 'null') ? NULL : $valor;
					$parametros[] = $item['ci_where'];
				}
			}
		}
		return $parametros;
	}
	/**
	 * Função que retorna o campo do filtro
	 * 
	 * @param array  $item        configuracao do campo
	 * @param string $selecionado valor do campo
	 * 
	 * @return String
	 */
	private function _campo_item($item, $selecionado)
	{
		if (gettype($selecionado) === 'array')
			$selecionado = implode(',', $selecionado);

		$campo = '';
		$item['extra'] = isset($item['extra']) ? $item['extra'] : '';
		$item['tipo'] = isset($item['tipo']) ? $item['tipo'] : 'text';
		switch ($item['tipo'])
		{
			case 'select':
				$campo .= '<select name="gg2-f['.$item['nome'].']" id="gg2-f-'.$item['nome'].'" '.$item['extra'].'>' . PHP_EOL;
				$campo .= '<option value="">Selecione...</option>' . PHP_EOL;
				foreach ($item['itens'] as $opcao)
				{
					$campo .= '<option value="'.$opcao->id.'"'.(($opcao->id === $selecionado) ? ' selected' : '').' title="'.$opcao->nome.'">';
					$campo .= $opcao->nome;
					$campo .= '</option>' . PHP_EOL;
				}
				$campo .= '</select>' . PHP_EOL;
				break;
			case 'date':
				if (strstr($selecionado, '-'))
				{
					$selecionado = explode(' ', $selecionado);
					$selecionado = implode('/', array_reverse(explode('-', $selecionado[0])));
				}
				if(strstr($item['extra'], 'class="'))
					$item['extra'] = str_replace('class="', 'class="gg2-date ', $item['extra']);
				else
					$item['extra'] .= ' class="gg2-date"';

				$campo .= '<input type="text" name="gg2-f['.$item['nome'].']" id="gg2-f-'.$item['nome'].'" value="'.$selecionado.'" '.$item['extra'].' />' . PHP_EOL;
				break;
			case 'datetime':
				if (strstr($selecionado, '-'))
				{
					$selecionado = explode(' ', $selecionado);
					$selecionado = implode('/', array_reverse(explode('-', $selecionado[0]))) . ' ' .$selecionado[1];
				}
				$campo .= '<input type="'.$item['tipo'].'" name="gg2-f['.$item['nome'].']" id="gg2-f-'.$item['nome'].'" value="'.$selecionado.'" '.$item['extra'].' />' . PHP_EOL;
				break;
			default:
				$campo .= '<input type="'.$item['tipo'].'" name="gg2-f['.$item['nome'].']" id="gg2-f-'.$item['nome'].'" value="'.$selecionado.'" '.$item['extra'].' />' . PHP_EOL;
				break;
		}
		return $campo;
	}
	/**
	 * Função de inicializacao da biblioteca
	 *
	 * @param array  $itens   os campos do filtro
	 * @param array  $valores o valor dos campos
	 * @param string $url     a url base do formulario
	 * @param int    $colunas o numero de colunas
	 * @param string $botoes  os botões adicionais do formulario
	 * 
	 * @return gg2-Filtros
	 */
	public function init($itens, $valores, $url, $colunas = 3, $botoes = '')
	{
		$this->_itens = $itens;
		$this->_valores = $valores;
		$this->_url = $url;
		$this->_colunas = ! empty($colunas) ? intval($colunas) : 3;
		$this->_botoes = ! empty($botoes) ? $botoes : '';
		$this->qtd_itens = sizeof($this->_itens);

		return $this;
	}
}

/* End of file gg2_filtros.php */
/* Location: ./libraries/gg2_filtros.php */