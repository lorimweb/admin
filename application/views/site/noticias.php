<div class="container">
	<div class="col-sm-12 blog-main">
		<?php if (isset($dados) && ! empty($dados)) : ?>
		<div class="blog-post">
			<h2 class="blog-post-title"><?php echo $dados->titulo; ?></h2>
			<p class="blog-post-meta"><?php echo $dados->dt_registro; ?> - <?php echo $dados->chamada; ?></p>
			<div><?php echo $dados->conteudo; ?></div>
		</div>
		<hr>
		<h3>Outras Notícias</h3>
		<?php else: ?>
		<h2>Notícias</h2>
		<?php endif; ?>
		<?php foreach ($noticias as $n): ?>
			<div class="media clicavel">
				<a class="pull-left" href="<?php echo site_url('site/noticias/'.$n->slug); ?>">
					<img src="<?php echo base_url('img.php?src=assets/img/noticias/'.$n->imagem.'&zc=1'); ?>" alt="<?php echo $n->titulo ?>" class="media-object" width="64">
				</a>
				<div class="media-body">
					<h4 class="media-heading"><?php echo $n->dt_registro ?> - <?php echo $n->titulo ?></h4>
					<p><?php echo $n->chamada ?></p>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>