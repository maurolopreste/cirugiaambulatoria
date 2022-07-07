
	<?php echo form_open_multipart('', array('autocomplete'=>'off')); ?>
		<header class="modal-header">
			<h4 class="modal-title">Modificar fecha</h4>
		</header>

		<div class="modal-body pt-3">
			<?php if($row->estado_cita==3): ?>
				<div class="alert alert-danger">Este turno fue cancelado</div>
			<?php endif; ?>

			<?php if($this->is_admin): ?>
				<div class="form-group">
					<label>Paciente</label>
					<select name="paciente_id" class="custom-select select2" required>
						<option value="">Seleccione una opción</option>
						<?php foreach($this->pacientes_list as $paciente): ?>
							<?php $selected = $row->paciente_id==$paciente->id ? 'selected' : ''; ?>
							<option value="<?php echo $paciente->id; ?>" <?php echo $selected; ?>><?php echo $paciente->first_name; ?> <?php echo $paciente->last_name; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="form-group">
					<label>Profesional</label>
					<select name="profesional_id" class="custom-select select2">
						<option value="">Seleccione una opción</option>
						<?php foreach($this->profesionales_list as $profesional): ?>
							<?php if($profesional->active): ?>
							<?php $selected = $row->profesional_id==$profesional->id ? 'selected' : ''; ?>
							<option value="<?php echo $profesional->id; ?>" <?php echo $selected; ?>>
								<?php echo $profesional->first_name; ?> <?php echo $profesional->last_name; ?>
								(Mat <?php echo $profesional->matricula; ?>)
							</option>
							<?php endif; ?>
						<?php endforeach; ?>
					</select>
				</div>
			<?php endif; ?>
			
			<div class="form-row">
				<div class="form-group col-6">
					<label>Fecha</label>
					<input type="text" id="fecha_turno" class="form-control datepicker" name="fecha" value="<?php echo date('Y-m-d', strtotime($row->fecha)); ?>" required>
				</div>
				<div class="form-group col-6">
					<label>Hora</label>
					<select name="hora" id="horario_turno" class="custom-select" required>
					</select>
				</div>
			</div>
			
			<?php if($this->is_admin): ?>
				<div class="form-row">
					<div class="form-group col-6">
						<label>Método de pago</label>
						<select name="payment_method" class="custom-select">
							<option value="">Seleccione una opción</option>
							<?php foreach($this->metodopago_list as $m): ?>
								<option value="<?php echo $m->id; ?>" <?php if($row->payment_method==$m->id) echo 'selected'; ?>><?php echo $m->nombre; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="form-group col-6">
						<label>Estado del pago</label>
						<select name="payment_status" class="custom-select">
							<option value="0">Pendiente</option>
							<option value="1" <?php if($row->payment_status=='1') echo 'selected'; ?>>Completado</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label>Adjuntar comprobante</label>
					<input type="file" name="userfile">
				</div>
			<?php endif; ?>

			<?php if($this->is_admin && !empty($row->file)): ?>
				<div class="alert alert-warning mt-2 p-2">Archivo subido: <a href="<?php echo site_url('/uploads/'.$row->file); ?>" class="alert-link" target="_blank"><?php echo $row->file; ?></a></div>
			<?php endif; ?>

			<?php if($this->is_admin): ?>
				<div class="form-group">
					<label>Observaciones</label>
					<textarea rows="2" class="form-control" name="comentarios"><?php echo $row->comentarios; ?></textarea>
				</div>
			<?php else: ?>
				<div class="form-group">
					<label>Observación del profesional</label>
					<textarea rows="3" class="form-control" name="observacion_profesional"><?php echo $row->observacion_profesional; ?></textarea>
				</div>
			<?php endif; ?>
		</div>

		<footer class="modal-footer text-right border-0">
			<?php if($this->is_admin && !empty($row->file)): ?>
				<a href="<?php echo site_url('/uploads/'.$row->file); ?>" class="btn btn-outline-primary mr-auto" target="_blank">
					Descargar Comprobante <i class="gd-download ml-2"></i>
				</a>
			<?php endif; ?>
			
			<?php if($row->estado_cita!=3): ?>
				<a href="<?php echo site_url('/agenda/cancelar_turno/'.$row->id); ?>" class="btn btn-danger" onclick="return confirm('El turno se cancelará. Le enviaremos una notificación al paciente.', this)">Cancelar turno</a>
			<?php endif; ?>
			
			<button type="submit" class="btn btn-primary btn-submit">Guardar</button>
			<button type="button" class="btn btn-light" data-dismiss="modal">Cerrar</button>
		</footer>
	</form>

	<script type="text/javascript">
		function getFechasDisponibles(profesional_id) {
			if(profesional_id) {
				var action = '<?php echo site_url('/dashboard/get_fechas_disponibles/'); ?>'+profesional_id;
				$.post(action, null, function(res){
					$('#fecha_turno').datepicker('setDatesDisabled', res).on('changeDate', function(e) {
						var fecha = $('#fecha_turno').val();
						getHorariosDisponibles(fecha, profesional_id);
					});
				});
			}
		}

		function getHorariosDisponibles(fecha, profesional_id) {
			if(fecha) {
				var action = '/dashboard/get_horarios_disponibles';
				$.post(action, {fecha: fecha, profesional_id: profesional_id}, function(res){
					if(res) {
						$('#horario_turno').html(res);
						$('#horario_turno').find('option[value="<?php echo date('H:i', strtotime($row->fecha)); ?>"').attr('selected', 'selected');
					}
				});
			}
		}

		getFechasDisponibles(<?php echo $row->profesional_id; ?>);
		getHorariosDisponibles(<?php echo date('Y-m-d', strtotime($row->fecha)); ?>);
	</script>