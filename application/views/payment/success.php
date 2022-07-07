<?php $this->load->view('login/header'); ?>

	<!-- Page content -->
	<div class="card mb-3 mb-md-4">
		<div class="card-body text-center">
			<h4 class="card-title text-center">Tu pago fue realizado con éxito.<br>¡Te esperamos en nuestro consultorio online!</h4>

			<a href="<?php echo site_url('dashboard'); ?>" class="btn btn-primary">Volver a la página principal</a>
		</div>
	</div>

<?php $this->load->view('login/footer'); ?>