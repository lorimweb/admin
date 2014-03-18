<div class="row-fluid">
<?php echo (isset($antes_form)) ? $antes_form : ''; ?>
<?php echo ($edit) ? form_open($action, array('id' => 'gg2-form', 'role' => 'form', 'enctype' => (isset($enctype)) ? $enctype : 'application/x-www-form-urlencoded')) : '' ; ?>
	<fieldset>
		<legend><?php echo isset($titulo) ? $titulo : 'Formulário'; ?></legend>
		<div id="#gg2_msg"><?php echo isset($validacao) ? $validacao : ''; ?></div>		
		<?php echo (isset($antes_campos)) ? $antes_campos : ''; ?>
		<?php echo (isset($campos)) ? monta_campos_form($campos, isset($dados) ? $dados : NULL, $edit) : ''; ?>
		<?php echo (isset($form_html)) ? $form_html : ''; ?>
		
		<div class="form-group col-md-12">
			<?php echo isset($botoes) ? $botoes : ''; ?>
			<!-- <button type="reset" class="btn">Restaurar</button> -->
			<?php if($edit): ?>
			<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-disk"></i> Salvar</button>
			<?php endif; ?>
		</div>
	</fieldset>
<?php echo ($edit) ? form_close() : '';?>
<?php echo (isset($depois_form)) ? $depois_form : ''; ?>
</div>