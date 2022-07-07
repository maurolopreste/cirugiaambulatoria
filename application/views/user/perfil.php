<?php $this->load->view('header'); ?>

	<div class="py-4 px-3 px-md-4">
		<div class="card mb-3 mb-md-4">
			<div class="card-body">
				<?php $this->load->view('alert'); ?>
				
				<nav class="d-none d-md-block" aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item">
							<a href="<?php echo site_url('/user'); ?>">Mi perfil</a>
						</li>
						<li class="breadcrumb-item active" aria-current="page">Editar perfil</li>
					</ol>
				</nav>

				<div class="mb-5 d-flex justify-content-between">
					<div class="h3 mb-0">Editar perfil</div>
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

					<div class="form-group">
						<label>DNI</label>
						<input type="text" class="form-control" name="dni" value="<?php echo $user->dni; ?>">
					</div>

					<div class="form-group">
						<label>Dirección</label>
						<input type="text" class="form-control" name="direccion" value="<?php echo $user->direccion; ?>">
					</div>

					<div class="form-group">
						<label>Teléfono</label>
						<input type="text" class="form-control" name="telefono" value="<?php echo $user->telefono; ?>">
					</div>

					<hr class="my-5" />

					<h4 class="mb-4"><strong>Cambiar contraseña</strong></h4>

					<div class="form-group">
						<label>Nueva Contraseña</label>
						<input type="text" class="form-control" name="password">
					</div>

					<hr class="my-5" />
					
					<button type="submit" class="btn btn-primary">Guardar cambios</button>
				</form>
			</div>
		</div>
	</div>

<?php $this->load->view('footer'); ?>