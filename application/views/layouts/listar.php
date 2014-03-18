<?php if (isset($filtro)): ?>
<fieldset class="row-fluid">
	<legend><?php echo (isset($titulo)) ? $titulo : 'Pesquisar' ?></legend>
	<?php print $filtro;?>
</fieldset>
<?php endif; ?>

<?php if (empty($paginacao)): ?>
<h3>Mostrando os Registros: <?php echo $num_itens; ?></h3>
<?php endif; ?>

<div class="row-fluid">
<?php if ( ! empty($listagem)): ?>
	
	<?php if (!empty($paginacao)) : ?>
	<div class="gg2_paginacao clearfix">
		<h3 class="registros pull-left">
			<strong>Mostrando os Registros</strong>: <?php echo $num_itens; ?>
		</h3> 
		<ul class="pagination pull-right"><?php echo $paginacao; ?></ul>
	</div>
	<?php endif; ?>

	<?php print $listagem; ?>
	
	<?php if (!empty($paginacao)) : ?>
	<div class="gg2_paginacao clearfix">
		<h3 class="registros pull-left">
			<strong>Mostrando os Registros</strong>: <?php echo $num_itens; ?>
		</h3> 
		<ul class="pagination pull-right"><?php echo $paginacao; ?></ul>
	</div>
	<?php endif; ?>

<?php else: ?> 
	<p class="error txt-center">Nenhum resultado encontrado.</p>
<?php endif; ?>
</div>

<script type="text/javascript">
<?php 
if (isset($acoes) && count($acoes)):
	foreach ($acoes AS $acao => $link): 
?>
	var ACAO_<?php echo strtoupper($acao)?> = '<?php echo site_url($link);?>/';
<?php 
	endforeach;
endif; ?>
</script>