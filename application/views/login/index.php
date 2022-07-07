<?php $this->load->view('login/header'); ?>

	<div class="card">
		<div class="card-body">
			<h4 class="card-title"><?php echo lang('Ingreso al sistema'); ?></h4>

			<?php echo form_open(); ?>
				<div class="form-group">
					<label for="email"><?php echo lang('Email'); ?></label>
					<input id="email" type="email" class="form-control" name="email" required autofocus>
				</div>

				<div class="form-group">
					<label for="password"><?php echo lang('Contraseña'); ?></label>
					<input id="password" type="password" class="form-control" name="password" required>
					<div class="text-right">
						<a href="<?php echo site_url('/login/forgotpassword'); ?>" class="small">
							<?php echo lang('¿Olvidaste tu contraseña?'); ?>
						</a>
					</div>
				</div>

				<div class="form-group no-margin">
					<button type="submit" class="btn btn-primary btn-block"><?php echo lang('Ingresar'); ?></button>
				</div>
				
				<!-- <div class="text-center mt-3 small">
					<?php echo lang('¿No tienes una cuenta?'); ?> <a href="<?php echo site_url('/register'); ?>"><?php echo lang('Regístrate'); ?></a>
				</div> -->
			</form>
		</div>
	</div>

<?php $this->load->view('login/footer'); ?>
					