<div class="modal fade gg2-popup" id="gg2-popup" tabindex="-1" role="dialog" aria-labelledby="gg2-popup-titulo" aria-hidden="true">
  <div class="modal-dialog gg2-popup-<?php echo $popup['class']; ?>">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="gg2-popup-titulo"><?php echo $popup['titulo'] ?></h4>
      </div>
      <div class="modal-body" id="gg2-popup-conteudo">
        <?php echo $popup['mensagem'] ?>
      </div>
      <?php if (isset($popup['botoes']) && count($popup['botoes']) > 0): ?>
      <div class="modal-footer" id="gg2-popup-rodape">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        <?php foreach($popup['botoes'] as $b):?>
          <?php echo $b; ?>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->