<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Esta classe que controla o crop das imagens do site
 *
 * @category  Site
 * @package   Controllers
 * @author    Gihovani Filipp Pereira Demétrio <gihovani@gmail.com>
 * @copyright 2014 Quantity LTDA
 * @license   http://gg2.com.br/license.html GG2
 * @version   Release: 1.0
 * @link      http://gg2.com.br
 */
class Imagens extends MY_Controller {
	/**
	 * Controi a classe e inicializa a classe pai passado false para 
	 * não verificar se o admin está logado.
	 *
	 * @return void
	 *
	 */
	public function __construct()
	{
		parent::__construct(FALSE);
	}

	/**
	 * Função que mostra o formulário para recortar uma imagem
	 *
	 * @return void
	 */
	public function recortar()
	{
		$validacao = array(
			array('field' => 'imagem', 	'label' => 'Imagem',	'rules' => 'trim|required|min_length[5]'),
			array('field' => 'x1', 		'label' => 'X1',		'rules' => 'trim|required'),
			array('field' => 'x2', 		'label' => 'X2',		'rules' => 'trim|required'),
			array('field' => 'y1', 		'label' => 'Y1',		'rules' => 'trim|required'),
			array('field' => 'y2', 		'label' => 'Y2',		'rules' => 'trim|required'),
			array('field' => 'largura',	'label' => 'Largura',	'rules' => 'trim|required'),
			array('field' => 'altura', 	'label' => 'Altura',	'rules' => 'trim|required'),
		);

		$arquivo = $this->input->get('arquivo');
		$altura = intval($this->input->get('altura'));
		$largura = intval($this->input->get('largura'));
		$retorno = urldecode($this->input->get('retorno'));
		$caminho = realpath(APPPATH.'../'.$arquivo);

		if (is_file($caminho))
		{
			$data['imagem'] = $caminho;
			$data['imagem_url'] = base_url($arquivo);
		}
		$this->form_validation->set_rules($validacao);
		if ($this->form_validation->run())
		{
			$ret = $this->_imagem_crop();
			if (isset($ret['success']))
			{
				mensagem_popup($ret['success']);
				redirect($retorno);
				// print 1;
			}
			elseif(isset($ret['danger']))
			{
				mensagem_popup($ret['danger'], ': (', 'danger');
				// print 2;
			}
		}

		$data['tamanho'] = array('largura' => $largura, 'altura' => $altura);
		$data['botoes'] = '<button type="button" onclick="javascript:history.back(1)" class="btn btn-default"> <i class="glyphicon glyphicon-arrow-left"></i> Tela Anterior</button>'.PHP_EOL;
		$data['validacao'] = mensagem_validacao();
		$data['action'] = site_url('imagens/recortar/?arquivo='.$arquivo.'&altura='.$altura.'&largura='.$largura.'&retorno='.$retorno);
		$this->gg2_layouts
			->arquivos_extras(JS . 'imgareaselect/css/imgareaselect-animated.css')
			->arquivos_extras(JS . 'imgareaselect/scripts/jquery.imgareaselect.min.js')
			->arquivos_extras(JS . 'imgareaselect/scripts/recortar.js')
			->view($this->modulo.'/'.$this->acao, $data);
	}

	/**
	 * Função que recorta a imagem
	 *
	 * @return void
	 */
	private function _imagem_crop()
	{
		// Imagem original
		$imagem = $this->input->post('imagem');
		$extencao = pega_extensao_arquivo($imagem);
		// As coordenadas X e Y dentro da imagem original
		// recebidas pelo formulário
		$esquerda = $this->input->post('x1');
		$topo = $this->input->post('y1');
		$largura = $this->input->post('x2') - $esquerda;
		$altura = $this->input->post('y2') - $topo;

		// Este será o tamanho final da imagem
		$crop_width = $this->input->post('largura');
		$crop_height = $this->input->post('altura');

		if ( ! list($current_width, $current_height) = getimagesize($imagem))
			return array('danger' => 'tipo de imagem invalido');

		if ($extencao === 'jpeg')
			$extencao = 'jpg';
		switch ($extencao)
		{
			case 'bmp' :
				$current_image = imagecreatefromwbmp($imagem);
				break;
			case 'gif' :
				$current_image = imagecreatefromgif($imagem);
				break;
			case 'jpg' :
				$current_image = imagecreatefromjpeg($imagem);
				break;
			case 'png' :
				$current_image = imagecreatefrompng($imagem);
				break;
			default :
				return array('danger' => 'tipo de imagem invalido');
		}

		$nova_imagem = imagecreatetruecolor($crop_width, $crop_height);

		// preserve transparency
		if ($extencao === 'gif' OR $extencao === 'png')
		{
			imagecolortransparent($nova_imagem, imagecolorallocatealpha($nova_imagem, 0, 0, 0, 127));
			imagealphablending($nova_imagem, FALSE);
			imagesavealpha($nova_imagem, TRUE);
		}

		imagecopyresampled ($nova_imagem, $current_image, 0, 0, $esquerda, $topo, $crop_width, $crop_height, $largura, $altura);

		switch ($extencao)
		{
			case 'bmp':
				imagewbmp($nova_imagem, $imagem);
				break;
			case 'gif':
				imagegif($nova_imagem, $imagem);
				break;
			case 'jpg':
				imagejpeg($nova_imagem, $imagem, 100);
				break;
			case 'png':
				imagepng($nova_imagem, $imagem);
				break;
		}

		imagedestroy($current_image);
		imagedestroy($nova_imagem);

		return array('success' => 'A imagem foi recortada corretamente');
	}
}

/* End of file imagens.php */
/* Location: ./controllers/imagens.php */