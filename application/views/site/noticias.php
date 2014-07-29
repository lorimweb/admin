<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/pt_BR/all.js#xfbml=1&appId=702363299835957";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div class="container">
	<div class="col-sm-12 blog-main">
		<?php if (isset($dados) && ! empty($dados)) : ?>
		<?php $link = site_url('site/noticias/'.$dados->slug); ?>
		<div class="blog-post">
			<h2 class="blog-post-title"><?php echo $dados->titulo; ?></h2>
			<p class="blog-post-meta"><?php echo $dados->dt_registro; ?> - <?php echo $dados->chamada; ?></p>
			<div class="col-md-12">
				<div class="pull-right">
					<div data-href="<?php echo $link ?>" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false" class="fb-like"></div>
				</div>
				<div class="pull-right">
					<a data-url="<?php echo $link ?>" data-text="<?php echo $dados->titulo; ?>" data-lang="pt" data-dnt="true" href="https://twitter.com/share" class="twitter-share-button">Tweetar</a>
				</div>
				<div class="pull-right">
					<!-- Place this tag where you want the +1 button to render. -->
					<div class="g-plusone" data-href="<?php echo $link ?>" data-size="medium"></div>
				</div>
			</div>
			<div><?php echo $dados->conteudo; ?></div>
		</div>
		<hr>
		<div class="fb-comments" data-href="<?php echo site_url('site/noticias/'.$dados->slug);?>" data-numposts="5" data-colorscheme="light"></div>
		<script>
		/* g+ */
		window.___gcfg = {lang: 'pt-BR'};

		(function() {
		var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
		po.src = 'https://apis.google.com/js/plusone.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
		})();

		/* twitter */
		!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');
		</script>
		<hr>
		<h3>Outras Notícias</h3>
		<?php else: ?>
		<h2>Notícias</h2>
		<?php endif; ?>

		<?php if (isset($noticias) && isset($noticias['itens']) && count($noticias['itens'])) : ?>
		<?php foreach ($noticias['itens'] as $n): ?>
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
		<?php if (isset($noticias['paginacao']) && ! empty($noticias['paginacao'])): ?>
		<div class="text-center">
			<ul class="pagination"><?php echo $noticias['paginacao']; ?></ul>
		</div>
		<?php endif; ?>
		<?php else: ?>
		<p class="text-warning">Nenhuma informação encontrada.</p>
		<?php endif;?>
	</div>
</div>