
	<?php echo form_open('', array('autocomplete'=>'off')); ?>
		<header class="modal-header">
			<h4 class="modal-title">Agendar turno</h4>
		</header>

		<div class="modal-body pt-3">
			<div class="form-group">
				<label>Paciente</label>
				<?php if($paciente_id): ?>
				<select name="paciente_id" class="custom-select select2" disabled>
				<?php else: ?>
				<select name="paciente_id" class="custom-select select2" onchange="selectPaciente(this.value)" required>
					<option value="nuevo">-- Nuevo paciente --</option>
				<?php endif; ?>
					<?php foreach($this->pacientes_list as $paciente): ?>
						<?php $selected = $paciente->id==$paciente_id ? 'selected' : ''; ?>
						<option value="<?php echo html_escape($paciente->id); ?>" <?php echo html_escape($selected); ?>><?php echo html_escape($paciente->first_name); ?> <?php echo html_escape($paciente->last_name); ?> <?php echo html_escape($paciente->dni); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div id="nuevopaciente" style="<?php if($paciente_id) echo "display:none"; ?>">
				<div class="form-row">
					<div class="form-group col-6">
						<label>Nombre</label>
						<input type="text" class="form-control" name="nuevopaciente[first_name]">
					</div>
					<div class="form-group col-6">
						<label>Apellido</label>
						<input type="text" class="form-control" name="nuevopaciente[last_name]">
						</select>
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-6">
						<label>Email</label>
						<input type="email" class="form-control" name="nuevopaciente[email]">
					</div>
					<div class="form-group col-6">
						<label>Teléfono / WhatsApp</label>
						<input type="text" class="form-control" name="nuevopaciente[telefono]">
						</select>
					</div>
				</div>
				<div class="form-group">
					<label>Obra Social</label>
					<select id="obra_social" name="nuevopaciente[obra_social]" class="custom-select select2">
						<option value="">Seleccione una opción</option>
						<?php foreach($this->obrasocial_list as $obra_social): ?>
							<?php if(!$obra_social->active) continue; ?>
							<option value="<?php echo html_escape($obra_social->id); ?>"><?php echo html_escape($obra_social->nombre); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label>Profesional</label>
				<select id="profesional_id" name="profesional_id" class="custom-select select2" onchange="getFechasDisponibles(this.value);" required>
					<option value="">Seleccione una opción</option>
					<?php foreach($this->profesionales_list as $profesional): ?>
						<?php if(!$profesional->active) continue; ?>
						<option value="<?php echo html_escape($profesional->id); ?>">
							<?php echo html_escape($profesional->first_name); ?> <?php echo html_escape($profesional->last_name); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="form-row">
				<div class="form-group col-6">
					<label>Fecha</label>
					<input type="text" id="fecha_turno" class="form-control datepicker" name="fecha" required>
				</div>
				<div class="form-group col-6">
					<label>Hora</label>
					<div class="row">
						<div class="col-6">
							<select name="hora" id="horario_turno" class="custom-select" disabled onchange="getMinutosDisponibles(this.value);" required>
							</select>
						</div>
						<div class="col-6 pl-0">
							<select name="hora_min" id="horario_turno_2" class="custom-select" disabled required>
								<option value=""></option>
								<option value="0">00</option>
								<option value="10">10</option>
								<option value="20">20</option>
								<option value="30">30</option>
								<option value="40">40</option>
								<option value="50">50</option>
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label>Observaciones</label>
				<textarea rows="2" class="form-control" name="comentarios"></textarea>
			</div>
		</div>

		<footer class="modal-footer text-right border-0">
			<button type="submit" class="btn btn-primary btn-submit">Agendar turno</button>
		</footer>
	</form>

	<script type="text/javascript">
		function selectPaciente(paciente_id) {
			if(paciente_id=="nuevo") {
				$('#nuevopaciente').show();
			}
			else {
				$('#nuevopaciente').hide();
			}
		}

		function getFechasDisponibles(profesional_id) {
			if(profesional_id) {
				var action = base_url+'/dashboard/get_fechas_disponibles/'+profesional_id;
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
				var action = base_url+'/dashboard/get_horarios_disponibles';
				$.post(action, {fecha: fecha, profesional_id: profesional_id}, function(res){
					if(res) {
						$('#horario_turno').html(res).removeAttr('disabled');
						var hora  = $('#horario_turno').val();
						getMinutosDisponibles(hora, profesional_id);
					}
				});
			}
		}

		function getMinutosDisponibles(hora, profesional_id) {
			if(hora) {
				var action = base_url+'/dashboard/get_minutos_disponibles';
				var fecha = $('#fecha_turno').val();
				var profesional_id = $('#profesional_id').val();
				$.post(action, {fecha: fecha, hora: hora, profesional_id: profesional_id}, function(res){
					if(res) {
						$('#horario_turno_2').html(res).removeAttr('disabled');
					}
				});
			}
		}
	</script>