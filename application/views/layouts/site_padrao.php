<?php echo $cabecalho; ?>
	<div class="navbar-wrapper">
		<div class="container">

			<div class="navbar navbar-inverse navbar-static-top" role="navigation">
				<div class="container">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<a class="navbar-brand" href="<?php echo site_url('site/index');?>"><?php echo NM_EMPRESA; ?></a>
					</div>
					<div class="navbar-collapse collapse">
						<ul class="nav navbar-nav">
							<li class="active"><a href="<?php echo site_url('site/index');?>">Página Inicial</a></li>
							<li class="dropdown">
								<a href="<?php echo site_url('site/paginas/apresentacao');?>" class="dropdown-toggle" data-toggle="dropdown">Páginas <b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li><a href="<?php echo site_url('site/paginas/apresentacao');?>">Apresentação</a></li>
									<li><a href="<?php echo site_url('site/paginas/quem-somos');?>">Quem Somos</a></li>
									<li><a href="<?php echo site_url('site/paginas/nossos-objetivos');?>">Nossos Objetivos</a></li>
									<li class="divider"></li>
									<li class="dropdown-header">Para Você</li>
									<li><a href="<?php echo site_url('site/paginas/beneficios');?>">Benefícios</a></li>
									<li><a href="<?php echo site_url('site/paginas/estatuto');?>">Estatuto</a></li>
								</ul>
							</li>
							<li><a href="<?php echo site_url('site/noticias');?>">Notícias</a></li>
							<li><a href="<?php echo site_url('site/contato');?>">Contato</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php echo $conteudo; ?>
	<!-- FOOTER -->
    <footer class="container">
		<p class="pull-right"><a href="#">Ir para o Topo</a></p>
		<p>&copy; 2014 <?php echo NM_EMPRESA;?>, Inc. &middot; <a href="#">Privacy</a> &middot; <a href="#">Terms</a></p>
    </footer>
<?php
echo $rodape;

/* End of file site.php */
/* Location: ./views/layouts/site.php */