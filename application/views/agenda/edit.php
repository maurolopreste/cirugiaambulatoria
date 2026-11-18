
	<?php echo form_open('', array('autocomplete'=>'off')); ?>
		<header class="modal-header">
			<h4 class="modal-title">Editar turno</h4>
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
							<option value="<?php echo html_escape($paciente->id); ?>" <?php echo html_escape($selected); ?>><?php echo html_escape($paciente->first_name); ?> <?php echo html_escape($paciente->last_name); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="form-group">
					<label>Profesional</label>
					<select name="profesional_id" class="custom-select select2" required>
						<option value="">Seleccione una opción</option>
						<?php foreach($this->profesionales_list as $profesional): ?>
							<?php if(!$profesional->active) continue; ?>
							<?php $selected = $row->profesional_id==$profesional->id ? 'selected' : ''; ?>
							<option value="<?php echo html_escape($profesional->id); ?>" <?php echo html_escape($selected); ?>>
								<?php echo html_escape($profesional->first_name); ?> <?php echo html_escape($profesional->last_name); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>
			<?php endif; ?>
			
			<?php if($this->is_admin): ?>
				<div class="form-row">
					<div class="form-group col-6">
						<label>Fecha</label>
						<input type="text" id="fecha_turno" class="form-control datepicker" name="fecha" value="<?php echo date('Y-m-d', strtotime($row->fecha)); ?>" required>
					</div>
					<div class="form-group col-6">
						<label>Hora</label>
						<!-- <select name="hora" id="horario_turno" class="custom-select" required>
						</select> -->
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
			<?php endif; ?>
			
			<?php /*if($this->is_admin): ?>
				<div class="form-row">
					<div class="form-group col-6">
						<label>Método de pago</label>
						<select name="payment_method" class="custom-select">
							<option value="">Seleccione una opción</option>
							<?php foreach($this->metodopago_list as $m): ?>
								<option value="<?php echo html_escape($m->id); ?>" <?php if($row->payment_method==$m->id) echo 'selected'; ?>><?php echo html_escape($m->nombre); ?></option>
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
			<?php endif;*/ ?>

			<?php if($this->is_admin && !empty($row->file)): ?>
				<div class="alert alert-warning mt-2 p-2">Archivo subido: <a href="<?php echo html_escape(site_url('archivo/ver/'.rawurlencode($row->file))); ?>" class="alert-link" target="_blank"><?php echo html_escape($row->file); ?></a></div>
			<?php endif; ?>

			<?php if($this->is_admin): ?>
				<div class="form-group">
					<label>Observaciones</label>
					<textarea rows="2" class="form-control" name="comentarios"><?php echo html_escape($row->comentarios); ?></textarea>
				</div>
			<?php else: ?>
				<div class="form-group">
					<label>Observación del profesional</label>
					<textarea rows="4" class="form-control" name="observacion_profesional"><?php echo html_escape($row->observacion_profesional); ?></textarea>
				</div>
			<?php endif; ?>
		</div>

		<footer class="modal-footer text-right border-0">
			<?php if($this->is_admin && !empty($row->file)): ?>
				<a href="<?php echo html_escape(site_url('archivo/ver/'.rawurlencode($row->file))); ?>" class="btn btn-outline-primary mr-auto" target="_blank">
					Descargar Comprobante <i class="gd-download ml-2"></i>
				</a>
			<?php endif; ?>
			
			<?php if($this->is_admin && $row->estado_cita!=3): ?>
				<button type="button" class="btn btn-danger" data-title="El turno se cancelará. Le enviaremos una notificación al paciente." onclick="cancelarTurno(<?php echo html_escape($row->id); ?>)">Cancelar turno</button>
			<?php endif; ?>
			
			<button type="submit" class="btn btn-primary btn-submit">Guardar cambios</button>
			<button type="button" class="btn btn-light" data-dismiss="modal">Cerrar</button>
		</footer>
	</form>

	<script type="text/javascript">
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
						$('#horario_turno').find('option[value="<?php echo date('H', strtotime($row->fecha)); ?>"').attr('selected', 'selected');
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
						$('#horario_turno_2').find('option[value="<?php echo date('i', strtotime($row->fecha)); ?>"').attr('selected', 'selected');
					}
				});
			}
		}

		function cancelarTurno(turno) {
			var modal = $('#deleteModal');
			var href = base_url+'agenda/cancelar_turno/'+turno;
			modal.find('.h5').text('El turno se cancelará. Le enviaremos una notificación al paciente.');
			modal.find('.btn-confirm').click(function(){
				window.location.href = href;
			});
			modal.modal('show');
		}

		getFechasDisponibles(<?php echo html_escape($row->profesional_id); ?>);
		getHorariosDisponibles(<?php echo date('Y-m-d', strtotime($row->fecha)); ?>);
	</script>