<div class="container">
<?php echo form_open('login/index', array('id' => 'gg2-form', 'role' => 'form', 'class' => "form-login")); ?>
  <h2 class="form-login-heading"><?php echo NM_EMPRESA; ?> - Sistema Administrativo</h2>
  <?php echo isset($validacao) ? $validacao : ''; ?>
  <input type="text" class="form-control" placeholder="Login" name="login" required="required" autofocus="autofocus">
  <input type="password" class="form-control" placeholder="Senha" name="senha" required="required">
  <button class="btn btn-lg btn-primary btn-block" type="submit">Entrar</button>
<?php echo form_close(); ?>
</div>