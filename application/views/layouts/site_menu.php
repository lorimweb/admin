<?php echo $cabecalho; ?>
<div class="container-fluid">
	<div class="col-md-2">
		<?php $menu = menu_lateral(); ?>
		<?php if (count($menu)) : ?>
		<fieldset>
			<legend>Menu</legend>
			<ul class="nav nav-pills nav-stacked">
				<?php foreach ($menu as $tmp) : ?>
				<li <?php echo menu_ativo($tmp->link);?>><a href="<?php echo site_url($tmp->link);?>"><?php echo $tmp->titulo;?></a></li>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>
		</fieldset>
	</div>
	<div class="col-md-10" role="main">
	<!-- Getting started ================================================== -->
		<div class="bs-docs-section">
			<?php echo $conteudo; ?>
		</div>
	</div>
</div>
<?php echo $rodape;

/* End of file site_menu.php */
/* Location: ./views/layouts/site_menu.php */