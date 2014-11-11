<div class="col-md-12 lista-opcoes-selecionaveis">
	<h2>Permissões</h2>
<?php if (isset($permissoes) && count($permissoes)): ?>
	<?php $modulo = '';?>
	<ul class="clearfix list-unstyled">
	<?php foreach ($permissoes as $p):?>

		<?php if ($modulo != $p->modulo): $modulo = $p->modulo; ?>
	</ul>
	<hr />
	<h3><?php echo $modulo;?></h3>
	<ul class="clearfix list-unstyled">
		<?php endif;?>

		<?php $ativo = in_array($p->id, $permissoes_ativos); ?>
		<li class="col-md-3<?php if($ativo) echo ' bg-success'; ?>">
			<input type="checkbox" name="permissoes[<?php echo $p->id ?>]" id="permissoes_<?php echo $p->id ?>" value="<?php echo $p->id ?>" <?php if($ativo) echo 'checked="checked"'; ?> <?php if ($desabilitado) echo 'disabled="disabled"'?> /> 
			<label for="permissoes_<?php echo $p->id ?>"><?php echo $p->descricao ?></label>
		</li>
	<?php endforeach;?>
	</ul>
<?php endif;?>
</div>