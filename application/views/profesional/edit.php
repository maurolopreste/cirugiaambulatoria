<?php $this->load->view('header'); ?>

	<div class="py-4 px-3 px-md-4">
		<?php $this->load->view('alert'); ?>
	
		<div class="mb-3 mb-md-4 d-flex justify-content-between">
			<div class="h3 mb-0">Editar profesional: <?php echo html_escape($row->first_name); ?> <?php echo html_escape($row->last_name); ?></div>
		</div>
	
		<div class="row">
			<div class="col-md-6 mb-3 mb-xl-4">
				<div class="card mb-3 mb-md-4">
					<div class="card-header">
						<h5 class="h5 text-primary text-uppercase font-weight-semi-bold mb-0">Información</h5>
					</div>
					<div class="card-body pt-0">
						<?php echo form_open(''); ?>
							<div class="form-row">
								<div class="form-group col-md-6">
									<label>Nombre</label>
									<input type="text" class="form-control" name="first_name" value="<?php echo html_escape($row->first_name); ?>" required>
								</div>
								<div class="form-group col-md-6">
									<label>Apellido</label>
									<input type="text" class="form-control" name="last_name" value="<?php echo html_escape($row->last_name); ?>" required>
								</div>
							</div>

							<div class="form-group">
								<label>DNI</label>
								<input type="text" class="form-control" name="dni" value="<?php echo html_escape($row->dni); ?>">
							</div>

							<div class="form-group">
								<label>Especialidad</label>
								<input type="text" class="form-control" name="especialidad" value="<?php echo html_escape($row->especialidad); ?>">
							</div>

							<div class="form-group">
								<label>Teléfono</label>
								<input type="text" class="form-control" name="telefono" value="<?php echo html_escape($row->telefono); ?>">
							</div>

							<hr/>

							<h5 class="h5 text-primary text-uppercase font-weight-semi-bold mb-3">Datos de ingreso</h5>

							<div class="form-group">
								<label>Email</label>
								<input type="email" class="form-control" name="email" value="<?php echo html_escape($row->email); ?>" required>
							</div>

							<div class="form-group">
								<label>Contraseña</label>
								<input type="text" class="form-control" name="password" placeholder="(Sin cambios)">
							</div>
						
							<hr>
							<button type="submit" class="btn btn-primary mt-4">Guardar</button>
						</form>
					</div>
				</div>
			</div>
			<div class="col-md-6 mb-3 mb-xl-4">
				<div class="card mb-3 mb-md-4">
					<div class="card-header d-flex align-items-center justify-content-between">
						<h5 class="h5 text-primary text-uppercase font-weight-semi-bold mb-0">Días y horarios habilitados</h5>
					</div>
					<div class="card-body pt-0">
						<div id="index-dias-habilitados" data-profesional-id="<?php echo html_escape($row->id); ?>" class="mb-2"></div>
						<!-- <a href="#" class="text-primary btn-add-horario">+ Agregar horario</a> -->
					</div>
				</div>
				<div class="card mb-3 mb-md-4">
					<div class="card-header d-flex align-items-center justify-content-between">
						<h5 class="h5 text-primary text-uppercase font-weight-semi-bold mb-0">Fechas comprometidas</h5>
						<a href="<?php echo html_escape(site_url('/profesional/add_fecha_comprometida/'.$row->id)); ?>" data-toggle="modal" data-target="#addModal" class="btn btn-primary btn-sm">Agregar fecha</a>
					</div>
					<div id="index-fechas-comprometidas" data-profesional-id="<?php echo html_escape($row->id); ?>" class="card-body pt-0">
					</div>
				</div>
			</div>
		</div>
	</div>

<?php $this->load->view('footer'); ?>