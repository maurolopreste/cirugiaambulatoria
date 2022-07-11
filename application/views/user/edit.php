<?php $this->load->view('header'); ?>

	<div class="py-4 px-3 px-md-4">
		<?php $this->load->view('alert'); ?>
				
		<nav class="d-none d-md-block" aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">
					<a href="<?php echo site_url('/user'); ?>">Usuarios</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page">Editar usuario</li>
			</ol>
		</nav>

		<div class="row">
			<div class="col-md-6">
				<div class="card mb-3 mb-md-4">
					<div class="card-body">
						<div class="mb-5 d-flex justify-content-between">
							<div class="h3 mb-0">Editar usuario</div>
						</div>

						<?php echo form_open(''); ?>
							<div class="form-row">
								<div class="form-group col-md-6">
									<label>Nombre</label>
									<input type="text" class="form-control" name="first_name" value="<?php echo $user->first_name; ?>" required>
								</div>
								<div class="form-group col-md-6">
									<label>Apellido</label>
									<input type="text" class="form-control" name="last_name" value="<?php echo $user->last_name; ?>" required>
								</div>
							</div>

							<hr class="my-5" />

							<h4 class="mb-4"><strong>Datos de ingreso</strong></h4>

							<div class="form-group">
								<label>Email</label>
								<input type="email" class="form-control" name="email" value="<?php echo $user->email; ?>" required>
							</div>

							<div class="form-group">
								<label>Contraseña</label>
								<input type="text" class="form-control" placeholder="(Sin cambios)" name="password">
							</div>

							<hr class="my-5" />
							
							<button type="submit" class="btn btn-primary">Guardar cambios</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		var user_group_id = '1';
	</script>

<?php $this->load->view('footer'); ?>