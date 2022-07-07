<?php $this->load->view('login/header'); ?>

	<div class="card">
		<div class="card-body">
			<h4 class="card-title"><?php echo lang('Registro'); ?></h4>

			<?php echo form_open(); ?>
				<div class="row">
					<div class="col-md-6 form-group">
						<label for="first_name"><?php echo lang('Nombre'); ?></label>
						<input id="first_name" type="text" class="form-control" name="first_name" required autofocus>
					</div>
					<div class="col-md-6 form-group">
						<label for="last_name"><?php echo lang('Apellido'); ?></label>
						<input id="last_name" type="text" class="form-control" name="last_name" required>
					</div>
				</div>

				<div class="form-group">
					<label for="email"><?php echo lang('Email'); ?></label>
					<input id="email" type="email" class="form-control" name="email" required>
				</div>

				<div class="form-group">
					<label for="password"><?php echo lang('Contraseña'); ?></label>
					<input id="password" type="password" class="form-control" name="password" required>
				</div>
				
				<div class="form-group">
					<label for="password-confirm"><?php echo lang('Repite contraseña'); ?></label>
					<input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
				</div>

				<div class="form-group no-margin">
					<button type="submit" class="btn btn-primary btn-block"><?php echo lang('Enviar'); ?></button>
				</div>
				
				<div class="text-center mt-3 small">
					<?php echo lang('¿Ya tienes una cuenta?'); ?> <a href="<?php echo site_url('/login'); ?>"><?php echo lang('Ingresa'); ?></a>
				</div>
			</form>
		</div>
	</div>

<?php $this->load->view('login/footer'); ?>
					