<?php if (isset($imagem)): ?>
<?php 
list($current_width, $current_height) = getimagesize($imagem);
$proporcao = ceil((($current_width*100)/600)/100);
//$proporcao = 1;
?>
<?php echo form_open($action, array('id' => 'gg2-form', 'role' => 'form')); ?>
<input title="Imagem" type="hidden" name="imagem" id="imagem" value="<?php echo $imagem;?>" required="required">
<input title="Selecione a área do recorte" type="hidden" name="x1" id="x1">
<input type="hidden" name="y1" id="y1">
<input type="hidden" name="x2" id="x2">
<input type="hidden" name="y2" id="y2">
<input type="hidden" name="largura" id="w" value="<?php echo $tamanho['largura']; ?>">
<input type="hidden" name="altura" id="h" value="<?php echo $tamanho['altura']; ?>">
<?php 
//manter proporcao
$current_width 	= ceil($current_width/$proporcao);
$current_height = ceil($current_height/$proporcao);
$tamanho['largura'] = ceil($tamanho['largura']/$proporcao);
$tamanho['altura'] 	= ceil($tamanho['altura']/$proporcao);
?>
	<fieldset>
    	<legend>Imagem -> Recortar</legend>
        <?php echo isset($validacao) ? $validacao : ''; ?>
        <table class="table">
		<thead>
			<tr>
				<th>Original</th>
				<th>Preview</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td valign="top">
					<img src="<?php echo $imagem_url.'?'.mktime(); ?>" id="img-principal" alt="Selecione o espaço da imagem" width="<?php echo $current_width;?>" height="<?php echo $current_height; ?>" class="imgareaselect" data-thumb="img-thumbnail" />
				</td>
				<td valign="top">
					<div style="position:relative; overflow:hidden; margin: 0; width:<?php echo $tamanho['largura']; ?>px; height:<?php echo $tamanho['altura']; ?>px;">
						<img id="img-thumbnail" src="<?php echo $imagem_url.'?'.mktime(); ?>" style="position: relative; width: <?php echo $tamanho['largura']; ?>px; height: <?php echo $tamanho['altura']; ?>px; margin-left: 0px; margin-top: 0px;" alt="Previsualização da imagem" />
					</div>
				</td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="2">
					<?php echo isset($botoes) ? $botoes : ''; ?>
        			<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-disk"></i> Salvar</button>	
				</td>
			</tr>
		</tfoot>
		</table>
	</fieldset>
<?php form_close() ?>
<script type="text/javascript">
var w = <?php echo $tamanho['largura']; ?>, 
	h = <?php echo $tamanho['altura']; ?>,
	_width = <?php echo $current_width; ?>,
	_height = <?php echo $current_height; ?>,
	_proporcao = <?php echo $proporcao;?>;
</script>
<?php else: ?>
<p>Imagem não encontrada.</p>
<?php endif; ?>