<?php $this->load->view('login/header'); ?>

	<div class="card">
		<div class="card-body">
			<h4 class="card-title"><?php echo lang('Cambiar contraseña'); ?></h4>

			<?php echo form_open(); ?>
				<div class="form-group">
					<label for="email"><?php echo lang('Email'); ?></label>
					<input id="email" type="email" class="form-control" name="email" value="<?php echo $user->email; ?>" required>
				</div>

				<div class="form-group">
					<label for="password"><?php echo lang('Contraseña'); ?></label>
					<input id="password" type="password" class="form-control" name="password" required autofocus>
				</div>
				
				<div class="form-group">
					<label for="password-confirm"><?php echo lang('Repite contraseña'); ?></label>
					<input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
				</div>

				<div class="form-group no-margin">
					<button type="submit" class="btn btn-primary btn-block"><?php echo lang('Enviar'); ?></button>
				</div>
			</form>
		</div>
	</div>

<?php $this->load->view('login/footer'); ?>