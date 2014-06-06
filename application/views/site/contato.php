<div class="container">
	<div class="col-md-12">
		<h1>FALE CONOSCO</h1>
		<div class="row">
			<?php echo isset($validacao) ? $validacao : ''; ?>
			<form role="form" method="post" action="<?php echo site_url('site/contato'); ?>">
			  <div class="form-group">
			    <label for="nome">NOME</label>
			    <input type="text" class="form-control" name="nome" id="nome" placeholder="Digite o seu Nome" value="<?php echo set_value('nome');?>">
			  </div>
			  <div class="form-group">
			    <label for="telefone1">TELEFONE FIXO</label>
			    <input type="text" class="form-control gg2-campo-tel" name="telefone1" id="telefone1" placeholder="(dd) Telefone" value="<?php echo set_value('telefone1');?>">
			  </div>
			  <div class="form-group">
			    <label for="telefone2">TELEFONE CELULAR</label>
			    <input type="text" class="form-control gg2-campo-tel" name="telefone2" id="telefone2" placeholder="(dd) Telefone" value="<?php echo set_value('telefone2');?>">
			  </div>
			  <div class="form-group">
			    <label for="email">E-MAIL</label>
			    <input type="email" class="form-control" name="email" id="email" placeholder="Digite o seu E-mail" value="<?php echo set_value('email');?>">
			  </div>
			  <div class="form-group">
			    <label for="assunto">CONTEÚDO:</label>
			    <textarea class="form-control" name="conteudo" id="conteudo" rows="5" placeholder="Digite o conteúdo"><?php echo set_value('conteudo');?></textarea>
			  </div>
			  <div class="form-group text-right">
			  	<button type="submit" class="btn btn-primary">Enviar</button>
			  </div>
			</form>
			<div class="clearfix"></div>
			<?php if( isset($config->googlemaps) && ! empty($config->googlemaps)):?>
			<hr class="linha-separadora">
			<div>
				<?php echo $config->googlemaps; ?>
			</div>
			<?php endif; ?>
		</div>
	</div>
</div>