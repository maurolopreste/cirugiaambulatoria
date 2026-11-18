<?php $this->load->view('header'); ?>

	<div class="py-4 px-3 px-md-4">
		<?php $this->load->view('alert'); ?>
	
		<div class="mb-3 mb-md-4 d-flex justify-content-between">
			<div class="h3 mb-0">Editar paciente: <?php echo $row->first_name; ?> <?php echo $row->last_name; ?></div>
			<a href="<?php echo site_url('/agenda/add/'.$row->id); ?>" class="btn btn-info" data-toggle="modal" data-target="#addModal">Agendar turno</a>
		</div>
	
		<div class="row">
			<div class="col-md-6 mb-3 mb-xl-4">
				<div class="card mb-3 mb-md-4">
					<div class="card-header">
						<h5 class="h5 text-primary text-uppercase font-weight-semi-bold mb-0">Información</h5>
					</div>
					<div class="card-body pt-0">
						<?php echo form_open(''); ?>
							<?php /*if($this->is_admin): ?>
								<div class="form-group">
									<label>Profesional encargado</label>
									<select name="profesional_id" class="custom-select select2" data-selected="<?php echo $row->profesional_id; ?>">
										<option value="">Seleccione una opción</option>
										<?php foreach($this->profesionales_list as $profesional): ?>
											<?php if($profesional->active): ?>
											<option value="<?php echo $profesional->id; ?>"><?php echo $profesional->first_name; ?> <?php echo $profesional->last_name; ?></option>
											<?php endif; ?>
										<?php endforeach; ?>
									</select>
								</div>
							<?php endif;*/ ?>

							<div class="form-row">
								<div class="form-group col-md-6">
									<label>Nombre</label>
									<input type="text" class="form-control" name="first_name" value="<?php echo $row->first_name; ?>" required>
								</div>
								<div class="form-group col-md-6">
									<label>Apellido</label>
									<input type="text" class="form-control" name="last_name" value="<?php echo $row->last_name; ?>" required>
								</div>
							</div>

							<div class="form-group">
								<label>DNI</label>
								<input type="text" class="form-control" name="dni" value="<?php echo $row->dni; ?>">
							</div>

							<div class="form-group">
								<label>Teléfono</label>
								<input type="text" class="form-control" name="telefono" value="<?php echo $row->telefono; ?>">
							</div>

							<?php if($this->is_admin): ?>
								<div class="form-group">
									<label>Email</label>
									<input type="email" class="form-control" name="email" value="<?php echo $row->email; ?>" required>
								</div>
							<?php endif; ?>

							<div class="form-row">
								<div class="form-group col-md-6">
									<label>Obra Social</label>
									<select name="obra_social" class="custom-select select2" data-selected="<?php echo $row->obra_social; ?>">
										<option value="">Seleccione una opción</option>
										<?php foreach($this->obra_social_list as $obra_social): ?>
											<option value="<?php echo $obra_social->id; ?>"><?php echo $obra_social->nombre; ?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="form-group col-md-6">
									<label>Nro Afiliado</label>
									<input type="text" class="form-control" name="nro_afiliado" value="<?php echo $row->nro_afiliado; ?>">
								</div>
							</div>

							<div class="form-row">
								<div class="form-group col-md-6">
									<label>ART</label>
									<select name="art_id" class="custom-select select2" data-selected="<?php echo $row->art_id; ?>">
										<option value="">Seleccione una opción</option>
										<?php foreach($this->art_list as $art): ?>
											<option value="<?php echo $art->id; ?>"><?php echo $art->nombre; ?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="form-group col-md-6">
									<label>Nro Siniestro</label>
									<input type="text" class="form-control" name="nro_siniestro" value="<?php echo $row->nro_siniestro; ?>">
								</div>
							</div>

							<?php /*if($this->is_admin): ?>
								<hr class="my-4" />
								<h5 class="h5 text-primary text-uppercase font-weight-semi-bold mb-4">Datos de ingreso</h4>

								<div class="form-group">
									<label>Email</label>
									<input type="email" class="form-control" name="email" value="<?php echo $row->email; ?>" required>
								</div>

								<div class="form-group">
									<label>Contraseña</label>
									<input type="text" class="form-control" name="password" placeholder="(Sin cambios)">
								</div>
							<?php endif;*/ ?>

							<button type="submit" class="btn btn-primary mt-4">Guardar</button>
						</div>
					</div>
				</form>
			</div>
			<div class="col-md-6 mb-3 mb-xl-4">
				<div class="card mb-3 mb-md-4">
					<div class="card-header d-flex align-items-center justify-content-between">
						<h5 class="h5 text-primary text-uppercase font-weight-semi-bold mb-0">Formularios</h5>
					</div>
					<div class="card-body pt-0 d-flex align-items-center justify-content-between">
						<a href="<?php echo site_url('paciente/formingreso/'.$row->id); ?>" class="btn btn-block btn-primary">Ingreso</a>
						<a href="<?php echo site_url('paciente/formalta/'.$row->id); ?>" class="btn btn-block btn-info mt-0 mx-3">Alta</a>
						<a href="<?php echo site_url('paciente/formreingreso/'.$row->id); ?>" class="btn btn-block btn-success mt-0">Reingreso</a>
					</div>
				</div>
				<div class="card mb-3 mb-md-4">
					<div class="card-header d-flex align-items-center justify-content-between">
						<h5 class="h5 text-primary text-uppercase font-weight-semi-bold mb-0">Historia clínica</h5>
					</div>
					<div id="index-historiaclinica" data-paciente-id="<?php echo $row->id; ?>" class="card-body pt-0">
						<div class="alert alert-warning">El paciente no tiene historia clínica</div>
						<a href="<?php echo site_url('/paciente/add_historiaclinica/'.$row->id); ?>" data-toggle="modal" data-target="#addModal" class="btn btn-primary">Agregar historia clínica</a>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php $this->load->view('footer'); ?>