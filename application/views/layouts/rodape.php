<?php echo mostra_popup(); ?>
<?php if (count($js)): ?>
<?php foreach ($js as $v): ?>
	<script src="<?php echo $v; ?>"></script>  
<?php endforeach; ?>
<?php endif; ?>
</body>
</html>
