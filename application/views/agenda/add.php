
	<?php echo form_open_multipart('', array('autocomplete'=>'off')); ?>
		<header class="modal-header">
			<h4 class="modal-title">Agregar fecha</h4>
		</header>

		<div class="modal-body pt-3">
			<?php if($this->is_admin): ?>
				<div class="form-group">
					<label>Paciente</label>
					<select name="paciente_id" class="custom-select select2" required>
						<option value="">Seleccione una opción</option>
						<?php foreach($this->pacientes_list as $paciente): ?>
							<?php $selected = $paciente->id==$paciente_id ? 'selected' : ''; ?>
							<option value="<?php echo $paciente->id; ?>" <?php echo $selected; ?>><?php echo $paciente->first_name; ?> <?php echo $paciente->last_name; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			<?php endif; ?>
			<?php if($this->is_admin): ?>
				<div class="form-group">
					<label>Profesional</label>
					<select id="profesional_id" name="profesional_id" class="custom-select" onchange="getFechasDisponibles(this.value);">
						<option value="">Seleccione una opción</option>
						<?php foreach($this->profesionales_list as $profesional): ?>
							<?php if($profesional->active): ?>
							<option value="<?php echo $profesional->id; ?>">
								<?php echo $profesional->first_name; ?> <?php echo $profesional->last_name; ?>
							</option>
							<?php endif; ?>
						<?php endforeach; ?>
					</select>
				</div>
			<?php endif; ?>
			<div class="form-row">
				<div class="form-group col-6">
					<label>Fecha</label>
					<input type="text" id="fecha_turno" class="form-control datepicker" name="fecha" required>
				</div>
				<div class="form-group col-6">
					<label>Hora</label>
					<select name="hora" id="horario_turno" class="custom-select" required>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label>Observaciones</label>
				<textarea rows="2" class="form-control" name="comentarios"></textarea>
			</div>
		</div>

		<footer class="modal-footer text-right border-0">
			<button type="submit" class="btn btn-primary btn-submit">Guardar</button>
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
					}
				});
			}
		}
	</script>