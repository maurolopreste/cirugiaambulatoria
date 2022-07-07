<?php $this->load->view('header'); ?>

	<div class="p-4">
		<?php $this->load->view('alert'); ?>

		<div class="mb-5 d-flex justify-content-center">
			<div class="h3 mb-0">¡Hola, <?php echo $this->login->first_name; ?>! Te damos la bienvenida</div>
		</div>

		<div class="row justify-content-center">
			<div class="col-lg-5">
				<div class="card mb-3 mb-md-4">
					<div class="card-header d-flex align-items-center pb-0">
						<div class="icon icon-border-home rounded-circle mr-3">
							<i class="fas fa-calendar text-primary"></i>
						</div>
						<h4 class="mb-0 extra-bold text-primary">Agendar cita online</h4>
					</div>
					<div class="card-body">
						<div id="agenda_alert" class="alert alert-success" style="display: none">¡Gracias! Te contactaremos a la brevedad para continuar con el proceso.</div>
						<?php echo form_open('/dashboard/agendar_cita', array('autocomplete'=>'off')); ?>
							<p class="mb-4">Autogestioná tu turno con nuestros profesionales. Cuando lo hayas hecho recibirás un mail donde el profesional confirma la cita.</p>
							<div class="form-group">
								<label><strong>Tipo de cobertura</strong></label>
								<select name="tipo" class="custom-select mb-3" data-selected="<?php echo $user->cobertura; ?>" required>
									<option value="">Seleccione tipo de cobertura</option>
									<?php foreach($this->tipo_cobertura_list as $tipo): ?>
										<option value="<?php echo $tipo->id; ?>"><?php echo $tipo->nombre; ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="form-group">
								<label><strong>Especialidad</strong></label>
								<select name="profesional_id" class="custom-select mb-3" onchange="getValorConsultas(this.value);getFechasDisponibles(this.value)" required>
									<option value="">Seleccione un profesional</option>
									<?php foreach($this->profesionales_list as $profesional): ?>
										<?php if($profesional->active): ?>
										<option value="<?php echo $profesional->id; ?>"><?php echo $profesional->first_name; ?> <?php echo $profesional->last_name; ?> - <?php echo $profesional->especialidad; ?> (Mat. <?php echo $profesional->matricula; ?>)</option>
										<?php endif; ?>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="form-group">
								<label><strong>Tipo de consulta</strong></label>
								<select name="valor_consulta" id="valor_consulta" class="custom-select mb-3" required>
									<option value="">Seleccione una opción</option>
								</select>
							</div>
							<div class="form-row">
								<div class="form-group col-sm-6">
									<label><strong>Fecha</strong></label>
									<input type="text" id="fecha_turno" name="fecha" class="form-control datepicker" required>
								</div>
								<div class="form-group col-sm-6">
									<label><strong>Horario</strong></label>
									<select name="hora" id="horario_turno" class="custom-select mb-3" required>
									</select>
								</div>
							</div>
							<button type="submit" class="btn btn-primary px-4">Agendar</button>
						</form>
					</div>
				</div>
			</div>
			<div class="col-lg-5">
				<div class="card mb-3 mb-md-4">
					<div class="card-header d-flex align-items-center pb-0">
						<div class="icon icon-border-home rounded-circle mr-3">
							<i class="fas fa-calendar text-primary"></i>
						</div>
						<h4 class="mb-0 extra-bold text-primary">Citas Agendadas</h4>
					</div>
					<div class="card-body pb-0">
						<?php if(!empty($citas)): ?>
							<div class="table-responsive-xl">
								<table class="table text-nowrap mb-0">
									<thead>
										<tr>
											<!-- <th class="font-weight-semi-bold border-top-0 py-2"><input type="checkbox" onclick="toggleAll(this)"></th> -->
											<th class="font-weight-semi-bold border-top-0 py-2">Fecha</th>
											<th class="font-weight-semi-bold border-top-0 py-2">Atendido por</th>
											<th class="font-weight-semi-bold border-top-0 py-2">Link Consulta</th>
										</tr>
									</thead>
									<tbody>
									<?php foreach($citas as $row): ?>
										<tr>
											<td class="py-2">
												<?php echo strftime('%e de %B, %Y', strtotime($row->fecha)); ?><br>
												<strong>Hora:</strong> <?php echo date('H:i', strtotime($row->fecha)); ?>
											</td>
											<td class="py-2"><?php echo $row->profesional; ?></td>
											<td class="py-2">
												<?php if($row->link_video && $row->payment_status): ?>
													<a href="https://meet.jit.si/<?php echo $row->link_video; ?>" class="btn btn-primary btn-block text-white btn-sm" target="_blank">
														Abrir <span class="gd-share"></span>
													</a>
												<?php else: ?>
													<span class="text-muted">No Disponible</span>
												<?php endif; ?>
											</td>
										</tr>
										<?php if(!$row->payment_status): ?>
											<tr>
												<td class="py-0 text-center border-top-0" colspan="3">
													<div class="alert alert-info py-2">
														La consulta está pendiente de pago.
														<a href="<?php echo site_url('/cita/generar_link_pago/'.$row->id); ?>" class="alert-link" target="_blank">
															Puede pagar desde aquí.
														</a>
													</div>
												</td>
											</tr>
										<?php endif; ?>
									<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						<?php else: ?>
							<div class="alert alert-warning">No tiene citas agendadas</div>
						<?php endif; ?>
					</div>
				</div>
				<div class="card mb-3 mb-md-4">
					<div class="card-header d-flex align-items-center pb-0">
						<div class="icon icon-border-home rounded-circle mr-3">
							<i class="fas fa-phone text-primary"></i>
						</div>
						<h4 class="mb-0 extra-bold text-primary">Contactanos</h4>
					</div>
					<div class="card-body pb-0">
						<div class="h4 text-success"><?php echo get_site_config('telefono'); ?></div>
						<p class="mb-4">Lunes a viernes de 09:00 a 18:00<br>
						Sábados de 09:00 a 13:00</p>
						<p class="mb-4"><strong>Email:</strong> <?php echo get_site_config('admin_email'); ?></p>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		function getValorConsultas(profesional_id) {
			$('#fecha_turno').val('');
			$('#horario_turno').empty();

			if(profesional_id) {
				var action = '/dashboard/get_valores_consulta/'+profesional_id;
				$.post(action, null, function(res){
					if(res) {
						$('#valor_consulta').html(res);
					}
				});
			}
		}
		function getFechasDisponibles(profesional_id) {
			if(profesional_id) {
				//buscar días habilitados
				var action = '/dashboard/get_dias_habilitados/'+profesional_id;
				$.post(action, null, function(res){
					$('#fecha_turno').datepicker('setDaysOfWeekDisabled', res).on('changeDate', function(e) {
						var fecha = $('#fecha_turno').val();
						getHorariosDisponibles(fecha, profesional_id);
					});
				});
				//fechas comprometidas
				var action = '/dashboard/get_fechas_disponibles/'+profesional_id;
				$.post(action, null, function(res){
					$('#fecha_turno').datepicker('setDatesDisabled', res);
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

<?php $this->load->view('footer'); ?>