<?php $this->load->view('header'); ?>

	<div class="py-4 px-3 px-md-4">
		<?php $this->load->view('alert'); ?>
	
		<div class="mb-3 mb-md-4 d-flex justify-content-between">
			<div class="h3 mb-0">Agregar paciente</div>
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
									<select name="profesional_id" class="custom-select select2" data-selected="<?php echo html_escape($row->profesional_id); ?>">
										<option value="">Seleccione una opción</option>
										<?php foreach($this->profesionales_list as $profesional): ?>
											<?php if($profesional->active): ?>
											<option value="<?php echo html_escape($profesional->id); ?>"><?php echo html_escape($profesional->first_name); ?> <?php echo html_escape($profesional->last_name); ?></option>
											<?php endif; ?>
										<?php endforeach; ?>
									</select>
								</div>
							<?php endif;*/ ?>

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
								<label>Teléfono</label>
								<input type="text" class="form-control" name="telefono">
							</div>

							<?php if($this->is_admin): ?>
								<div class="form-group">
									<label>Email</label>
									<input type="email" class="form-control" name="email" required>
								</div>
							<?php endif; ?>

							<!-- <div class="mb-3">
								<label class="d-inline mr-3"><strong>Método de ingreso</strong></label>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="metodoingreso" id="metodoingreso0" value="obra-social" checked>
									<label class="form-check-label" for="metodoingreso0">Obra Social</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="metodoingreso" id="metodoingreso1" value="art">
									<label class="form-check-label" for="metodoingreso1">ART</label>
								</div>
							</div> -->

							<div class="form-row">
								<div class="form-group col-md-6">
									<label>Obra Social</label>
									<select name="obra_social" class="custom-select select2">
										<option value="">Seleccione una opción</option>
										<?php foreach($this->obra_social_list as $obra_social): ?>
											<option value="<?php echo html_escape($obra_social->id); ?>"><?php echo html_escape($obra_social->nombre); ?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="form-group col-md-6">
									<label>Nro Afiliado</label>
									<input type="text" class="form-control" name="nro_afiliado">
								</div>
							</div>

							<div class="form-row">
								<div class="form-group col-md-6">
									<label>ART</label>
									<select name="art_id" class="custom-select select2">
										<option value="">Seleccione una opción</option>
										<?php foreach($this->art_list as $art): ?>
											<option value="<?php echo html_escape($art->id); ?>"><?php echo html_escape($art->nombre); ?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="form-group col-md-6">
									<label>Nro Siniestro</label>
									<input type="text" class="form-control" name="nro_siniestro">
								</div>
							</div>

							<?php /*if($this->is_admin): ?>
								<hr class="my-4" />
								<h5 class="h5 text-primary text-uppercase font-weight-semi-bold mb-4">Datos de ingreso</h4>

								<div class="form-group">
									<label>Email</label>
									<input type="email" class="form-control" name="email" required>
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
		</div>
	</div>

<?php $this->load->view('footer'); ?>