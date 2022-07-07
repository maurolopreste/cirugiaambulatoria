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

							<?php if($this->is_admin): ?>
								<div class="form-group">
									<label>Perfil</label>
									<select name="group_id" class="custom-select" data-select="<?php echo $user->group_id; ?>" onchange="return change_perfil(this.value)" required>
										<option value="">Seleccione una opción</option>
										<?php foreach($this->group_list as $group): ?>
											<option value="<?php echo $group->id; ?>"><?php echo $group->name; ?></option>
										<?php endforeach; ?>
									</select>
								</div>

								<div class="form-group cliente_content">
									<label>Productor asignado</label>
									<select name="productor_id" class="custom-select select2" data-select="<?php echo $user->productor_id; ?>">
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
									<input type="text" class="form-control" name="first_name" value="<?php echo $user->first_name; ?>" required>
								</div>
								<div class="form-group col-md-6">
									<label>Apellido</label>
									<input type="text" class="form-control" name="last_name" value="<?php echo $user->last_name; ?>" required>
								</div>
							</div>

							<div class="form-group productor_content">
								<label>Matricula (sólo usuario productor)</label>
								<input type="text" class="form-control" name="matricula" value="<?php echo $user->matricula; ?>">
							</div>

							<div class="cliente_content" style="<?php if($user->group_id!=4) echo 'display:none';//cliente ?>">
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
							</div>

							<?php if($this->is_admin): ?>
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
							<?php endif; ?>

							<hr class="my-5" />
							
							<button type="submit" class="btn btn-primary">Guardar cambios</button>
						</form>
					</div>
				</div>
			</div>
			<div class="col-md-6 cliente_content">
				<div class="card mb-3 mb-md-4">
					<div class="card-body">
						<div class="mb-5 d-flex justify-content-between">
							<div class="h3 mb-0">Detalle de los seguros</div>
							<a href="<?php echo site_url('/user/add_seguro/'.$user->id); ?>" data-toggle="modal" data-target="#addModal" class="btn btn-primary btn-sm">Agregar</a>
						</div>

						<div id="index-seguros" data-user-id="<?php echo $user->id; ?>">
							<div id="index-seguros" data-user-id="<?php echo $user->id; ?>">
								<p class="text-muted">El cliente no tiene seguros</p>
							</div>
						</div>
					</div>
				</div>

				<div class="card mb-3 mb-md-4">
					<div class="card-body">
						<div class="mb-5 d-flex justify-content-between">
							<div class="h3 mb-0">Historial de mensajes</div>
							<a href="<?php echo site_url('/user/add_mensaje/'.$user->id); ?>" data-toggle="modal" data-target="#addModal" class="btn btn-primary btn-sm">Agregar</a>
						</div>

						<div id="index-mensajes" data-user-id="<?php echo $user->id; ?>">
							<p class="text-muted">No hay mensajes para este cliente</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		var user_group_id = '<?php echo $user->group_id; ?>';
	</script>

<?php $this->load->view('footer'); ?>