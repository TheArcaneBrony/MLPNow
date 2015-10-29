<?php include "../config.php"; header('Content-Type: text/javascript'); ?>
MLPNow.Pony = {
	shortNames: <?php echo json_encode($Pony->shortNames()); ?>,
	longNames: <?php echo json_encode($Pony->longNames()); ?>,
	colors: <?php echo json_encode($Pony->colors()); ?>,
	list: <?php echo json_encode($Pony->data()); ?>,
};