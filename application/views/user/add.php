<?php $this->load->view('header'); ?>
	
	<div class="py-4 px-3 px-md-4">
		<div class="card mb-3 mb-md-4">
			<div class="card-body">
				<?php $this->load->view('alert'); ?>
				
				<nav class="d-none d-md-block" aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item">
							<a href="<?php echo site_url('/user'); ?>">Usuarios</a>
						</li>
						<li class="breadcrumb-item active" aria-current="page">Agregar usuario</li>
					</ol>
				</nav>
				
				<div class="mb-5 d-flex justify-content-between">
					<div class="h3 mb-0">Agregar usuario</div>
				</div>
				
				<?php echo form_open(''); ?>
					<div class="row">
						<div class="col-md-6">
							<?php if($this->is_admin): ?>
								<div class="form-group">
									<label>Perfil</label>
									<select name="group_id" class="custom-select" onchange="return change_perfil(this.value)" required>
										<option value="">Seleccione una opción</option>
										<?php foreach($this->group_list as $group): ?>
											<option value="<?php echo $group->id; ?>"><?php echo $group->name; ?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="form-group">
									<label>Productor asignado</label>
									<select name="productor_id" class="custom-select select2">
										<option value="">Seleccione una opción</option>
										<?php foreach($this->productores_list as $productor): ?>
											<option value="<?php echo $productor->id; ?>"><?php echo $productor->first_name; ?> <?php echo $productor->last_name; ?> (<?php echo $productor->email; ?>)</option>
										<?php endforeach; ?>
									</select>
								</div>
							<?php endif; ?>

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

							<div class="form-group productor_content">
								<label>Matricula (sólo productores)</label>
								<input type="text" class="form-control" name="matricula">
							</div>

							<div class="cliente_content">
								<div class="form-group">
									<label>DNI</label>
									<input type="text" class="form-control" name="dni">
								</div>

								<div class="form-row">
									<div class="form-group col-md-6">
										<label>Compañía</label>
										<select name="empresa_id" class="custom-select select2">
											<option value="">Seleccione una opción</option>
											<?php foreach($this->empresas_list as $empresa): ?>
												<option value="<?php echo $empresa->id; ?>"><?php echo $empresa->nombre; ?></option>
											<?php endforeach; ?>
										</select>
									</div>
									<div class="form-group col-md-6">
										<label>N&deg; póliza</label>
										<input type="text" class="form-control" name="nro_poliza">
									</div>
								</div>

								<div class="form-group">
									<label>Dirección</label>
									<input type="text" class="form-control" name="direccion">
								</div>

								<div class="form-group">
									<label>Teléfono</label>
									<input type="text" class="form-control" name="telefono">
								</div>
							</div>

							<hr/>
							<h4 class="mb-4"><strong>Datos de ingreso</strong></h4>

							<div class="form-group">
								<label>Email</label>
								<input type="email" class="form-control" name="email" required>
							</div>

							<div class="form-group">
								<label>Contraseña</label>
								<input type="text" class="form-control" name="password">
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