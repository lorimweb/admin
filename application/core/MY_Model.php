<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Esta classe é para pre configurar os models
 * 
 * @category  GG2_Admin
 * @package   Models
 * @author    Gihovani Filipp Pereira Demétrio <gihovani@gmail.com>
 * @copyright 2012-2014 GG2 Soluções
 * @license   http://gg2.com.br/license.html GG2 Soluções
 * @version   1.0
 * @link      http://gg2.com.br
 */
class MY_Model extends CI_Model
{
	/**
	 * Nome da tabela principal do model.
	 * 
	 * @var string
	 */
	public $tabela;
	/**
	 * Nome das colunas da tabela principal.
	 * 
	 * @var string
	 */
	public $colunas = '*';
	/**
	 * Nome da chave primaria da tabela.
	 * 
	 * @var string
	 */
	public $id = 'id';
	/**
	 * Esta variavel serve para debugar o sql.
	 * 
	 * @var boolean
	 */
	public $mostra_sql = FALSE;
	
	/**
	 * Construtor que inicializa a classe pai CI_Model
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}
	/**
	 * Função que retorna a lista com base na busca do BD
	 * 
	 * @param array   $filtro     os valores a serem buscados
	 * @param string  $sort_by    coluna de referencia para ordenação
	 * @param string  $sort_order criterio de ordem 'asc' ascendente e 'desc' decrescente'
	 * @param integer $offset     posicao dos itens retornados
	 * @param integer $limit      quantidade de itens retornados
	 * @param array   $extra      algum parametro extra como having ou group by etc.
	 * 
	 * @return array
	 */
	public function lista($filtro = NULL, $sort_by = 1, $sort_order = 'asc', $offset = 0, $limit = NULL, $extra = array())
	{
		return $this->itens($this->tabela, $this->colunas, $filtro, $sort_by, $sort_order, $offset, $limit, $extra);
	}
	/**
	 * Função que pega a quantidade de registro a partir de um filtro
	 *
	 * @param array $filtro o filtro dos campos que queremos
	 * 
	 * @return object
	 */
	public function num_lista($filtro = NULL)
	{
		return $this->num_itens($this->tabela, $filtro);
	}
	/**
	 * Função que pega um registro no BD a partir da sua chave primaria
	 *
	 * @param string $id o valor do id
	 * 
	 * @return object
	 */
	public function id($id)
	{
		return $this->registro($this->id, $id);
	} 
	/**
	 * Função que pega mostra o ultimo comando sql
	 *
	 * @return void
	 */
	private function _mostra_sql() 
	{
		if ($this->mostra_sql) 
		{
			$tmp =& get_instance();
			$tmp->output->append_output('<pre>' . $this->db->last_query() . '</pre>');
		}
	}
	/**
	 * Função que pega um registro no BD
	 *
	 * @param string $campo nome do campo da tabela
	 * @param string $valor valor que buscamos no campo da tabela
	 * 
	 * @return object
	 */
	public function registro($campo, $valor)
	{
		$tmp = $this->itens($this->tabela, $this->colunas, array($campo => $valor));
		return (isset($tmp['itens'][0])) ? $tmp['itens'][0] : FALSE;
	}
	/**
	 * Função que adiciona registros ao BD
	 *
	 * @param array $data campos e valores
	 * 
	 * @return integer
	 */
	public function adicionar($data = array())
	{
		$this->db->insert($this->tabela, $data);
		$this->_mostra_sql(); 
		return $this->db->insert_id();
	}
	/**
	 * Função que carrega alterações nos registros do BD
	 * 
	 * @param array $data   campos e valores a serem alterados
	 * @param array $filtro o filtro dos campos que queremos
	 * 
	 * @return integer
	 */
	public function editar($data = array(),$filtro = array())
	{
		$this->db->update($this->tabela, $data, $filtro);
		$this->_mostra_sql();
		return $this->db->affected_rows();
	}
	/**
	 * Função que exclui registros do BD
	 * 
	 * @param array $filtro identificação dos registros excluidos
	 * 
	 * @return linhas afetadas
	 */
	public function excluir($filtro)
	{
		$this->db->delete($this->tabela, $filtro);
		$this->_mostra_sql();
		return $this->db->affected_rows();
	}
	/**
	 * Função que retorna a quantidade de itens de uma busca
	 * 
	 * @param array $tabelas lista de tabelas 
	 * @param array $filtros os valores a serem buscados
	 * 
	 * @return int
	 */
	public function num_itens($tabelas, $filtros = NULL)
	{
		$this->db->select('COUNT(*) as qtde', FALSE);
		$this->_tabelas($tabelas);
		$this->_filtros($filtros);
		$tmp = $this->db->get()->result();
		$this->_mostra_sql();

		return (isset($tmp[0]->qtde)) ? $tmp[0]->qtde : 0;
	}
	/**
	 * Função que retorna a lista com base na busca do BD
	 * 
	 * @param array   $tabelas    lista de tabelas 
	 * @param string  $colunas    string com as colunas
	 * @param array   $filtros    os valores a serem buscados
	 * @param string  $sort_by    coluna de referencia para ordenação
	 * @param string  $sort_order criterio de ordem 'asc' ascendente e 'desc' decrescente'
	 * @param integer $offset     posicao dos itens retornados
	 * @param integer $limite     quantidade de itens retornados
	 * @param array   $extras     algum parametro extra como having ou group by etc.
	 * 
	 * @return array
	 */
	public function itens($tabelas, $colunas, $filtros = NULL, $sort_by = NULL, $sort_order = NULL, $offset = NULL, $limite = NULL, $extras = NULL)
	{
		$this->db->select('SQL_CALC_FOUND_ROWS '.$colunas, FALSE);
		$this->_tabelas($tabelas);
		$this->_filtros($filtros);
		$this->_extras($extras);

		if ( ! empty($sort_by))
		{
			$sort_order = (strtolower($sort_order) === 'desc') ? 'desc' : 'asc';
			$this->db->order_by($sort_by, $sort_order, FALSE);
		}
		
		if ( ! empty($limite) && isset($offset)) 
			$this->db->limit($limite, $offset);
		
		$query = $this->db->get()->result();
		$this->_mostra_sql();

		if (isset($query[0])) 
		{
			$ret['itens'] = $query;
			$n_linhas = $this->db->query('SELECT FOUND_ROWS() as count')->result();
			$ret['num_itens'] = $n_linhas[0]->count;
		} 
		else
		{
			$ret['itens'] = array();
			$ret['num_itens'] = 0;
		}
		return $ret;
	}
	/**
	 * Função que configura a lista de filtros
	 * 
	 * @param array $filtros as configuracoes dos filtros a serem buscados
	 * 
	 * @return void
	 */
	private function _filtros($filtros)
	{
		if (isset($filtros) && ! empty($filtros))
		{
			if (is_array($filtros) && isset($filtros[0]))
			{
				foreach ($filtros as $filtro)
				{
					if (isset($filtro['funcao_ci']))
						$this->db->{$filtro['funcao_ci']}($filtro['campo'], $filtro['valor']);
					else
						$this->db->where($filtro);
				}
			}
			else
			{
				$this->db->where($filtros);
			}
		}
	}
	/**
	 * Função que configura a lista de tabelas
	 * 
	 * @param array $tabelas as configuracoes das tabelas
	 * 
	 * @return void
	 */
	private function _tabelas($tabelas)
	{
		if (is_array($tabelas))
		{
			$this->db->from($tabelas[0]['nome']);
			unset($tabelas[0]);
			foreach ($tabelas as $tabela)
			{
				$tabela['tipo'] = isset($tabela['tipo']) ? $tabela['tipo'] : 'inner';
				$this->db->join($tabela['nome'], $tabela['where'], $tabela['tipo']);
			}
		}
		else
		{
			$this->db->from($tabelas);
		}
	}
	/**
	 * Função que configura os comandos extras
	 * 
	 * @param array $extras as configuracoes extras da consulta
	 * 
	 * @return void
	 */
	private function _extras($extras = array())
	{
		if (count($extras))
		{
			foreach ($extras as $comando => $valor) 
			{
				$this->db->{$comando}($valor); 
			}
		}
	}
}

/* End of file MY_Model.php */
/* Location: ./core/MY_Model.php */