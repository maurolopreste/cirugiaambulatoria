<?php $this->load->view('header'); ?>
	
	<div class="py-4 px-3 px-md-4">
		<div class="card mb-3 mb-md-4">
			<div class="card-body">
				<?php $this->load->view('alert'); ?>
				
				<nav class="d-none d-md-block" aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item">
							<a href="<?php echo site_url('/profesional'); ?>">Profesionales</a>
						</li>
						<li class="breadcrumb-item active" aria-current="page">Agregar profesional</li>
					</ol>
				</nav>
				
				<div class="mb-5 d-flex justify-content-between">
					<div class="h3 mb-0">Agregar profesional</div>
				</div>
				
				<?php echo form_open(''); ?>
					<div class="row">
						<div class="col-md-6">
							<div class="form-row">
								<div class="form-group col-md-6">
									<label>Nombre</label>
									<input type="text" class="form-control" name="first_name" required>
								</div>
								<div class="form-group col-md-6">
									<label>Apellido</label>
									<input type="text" class="form-control" name="last_name" required>
								</div>
							</div>

							<div class="form-group">
								<label>DNI</label>
								<input type="text" class="form-control" name="dni">
							</div>

							<div class="form-group">
								<label>Especialidad</label>
								<input type="text" class="form-control" name="especialidad">
							</div>

							<div class="form-group">
								<label>Teléfono</label>
								<input type="text" class="form-control" name="telefono">
							</div>

							<hr/>
							<h4 class="mb-4"><strong>Datos de ingreso</strong></h4>

							<div class="form-group">
								<label>Email</label>
								<input type="email" class="form-control" name="email" required>
							</div>

							<div class="form-group">
								<label>Contraseña</label>
								<input type="text" class="form-control" name="password" required>
							</div>
						</div>
					</div>
					<hr>
					<button type="submit" class="btn btn-primary mt-4">Guardar</button>
				</form>
			</div>
		</div>
	</div>

<?php $this->load->view('footer'); ?>