
	<?php echo form_open('', array('autocomplete'=>'off')); ?>
		<header class="modal-header">
			<h4 class="modal-title">Modificar cita</h4>
		</header>

		<div class="modal-body pt-3">
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
							<?php $selected = $row->profesional_id==$profesional->id ? 'selected' : ''; ?>
							<option value="<?php echo $profesional->id; ?>" <?php echo $selected; ?>>
								<?php echo $profesional->first_name; ?> <?php echo $profesional->last_name; ?>
								(Mat <?php echo $profesional->matricula; ?>)
							</option>
						<?php endforeach; ?>
					</select>
				</div>
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
			<?php endif; ?>
			
			<div class="form-group">
				<label>Estado de la cita</label>
				<select name="estado_cita" class="custom-select" required>
					<option value="1" <?php if($row->estado_cita==1) echo 'selected'; ?>>Pendiente</option>
					<option value="2" <?php if($row->estado_cita==2) echo 'selected'; ?>>Completado</option>
					<option value="3" <?php if($row->estado_cita==3) echo 'selected'; ?>>Cancelado</option>
					<option value="4" <?php if($row->estado_cita==4) echo 'selected'; ?>>Ausente</option>
				</select>
			</div>

			<?php if($this->is_admin): ?>
			<div class="form-group">
				<label>Observaciones (admin)</label>
				<textarea rows="2" class="form-control" name="comentarios"><?php echo $row->comentarios; ?></textarea>
			</div>
			<?php else: ?>
			<div class="form-group">
				<label>Observaciones del profesional</label>
				<textarea rows="3" class="form-control" name="observacion_profesional"><?php echo $row->observacion_profesional; ?></textarea>
			</div>
			<?php endif; ?>
		</div>

		<footer class="modal-footer text-right border-0">
			<button type="submit" class="btn btn-primary btn-submit">Guardar cambios</button>
			<button type="button" class="btn btn-light ml-2" data-dismiss="modal">Cerrar</button>
		</footer>
	</form>

	<script type="text/javascript">
		function getFechasDisponibles(profesional_id) {
			if(profesional_id) {
				var action = '/dashboard/get_fechas_disponibles/'+profesional_id;
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