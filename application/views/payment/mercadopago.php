<?php $this->load->view('login/header'); ?>

	<!-- Page content -->
	<div class="card mb-3 mb-md-4">
		<div class="card-body text-center">
			<h4 class="card-title">Selecciona el método de pago</h4>

			<a href="<?php echo html_escape($MP_link); ?>" class="btn btn-success">Pagar con Mercado Pago</a><br>
			<a href="<?php echo site_url('/cita?pagar_despues=1'); ?>" class="btn btn-success">Pagar con otro medio de pago</a>
		</div>
	</div>

<?php $this->load->view('login/footer'); ?>