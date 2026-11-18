<?php
$alert = $this->session->flashdata('alert');
if($alert):
	$alert_class = $alert['error'] ? 'danger' : 'success';
	$alert_icon = $alert['error'] ? 'times' : 'check';
?>
<div class="alert alert-<?php echo html_escape($alert_class); ?> alert-left-bordered border-<?php echo html_escape($alert_class); ?> d-flex align-items-center p-md-4 mb-4" role="alert">
	<p class="mb-0">
		<i class="fa fa-<?php echo html_escape($alert_icon); ?> mr-2"></i>
		<?php echo html_escape(strip_tags($alert['msg'])); ?>
	</p>
</div>
<?php endif; ?>