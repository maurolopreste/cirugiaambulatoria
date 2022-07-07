<?php $this->load->view('header'); ?>

	<div class="py-4 px-3 px-md-4">
		<?php $this->load->view('alert'); ?>
		
		<div class="mb-3 mb-md-4 d-flex justify-content-between">
			<div class="h3 mb-0">¡Hola, <?php echo $this->login->first_name; ?>! Te damos la bienvenida</div>
		</div>

		<?php if($this->is_admin): ?>
		<div class="row">
			<div class="col-md-6 col-xl-4 mb-3 mb-xl-4">
				<div class="card flex-row align-items-center p-3 p-md-4">
					<div class="icon icon-border-home rounded-circle mr-3">
						<i class="fas fa-calendar text-primary"></i>
					</div>
					<div>
						<a href="<?php echo site_url('/agenda'); ?>">
							<h4 class="mb-0 extra-bold text-primary">Agenda</h4>
							<h6 class="mb-0 text-muted">Citas programadas</h6>
						</a>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-xl-4 mb-3 mb-xl-4">
				<div class="card flex-row align-items-center p-3 p-md-4">
					<div class="icon icon-border-home rounded-circle mr-3">
						<i class="fas fa-user text-primary"></i>
					</div>
					<div>
						<a href="<?php echo site_url('/paciente'); ?>">
							<h4 class="mb-0 extra-bold text-primary"><?php echo $count_pacientes; ?> Pacientes</h4>
							<h6 class="mb-0 text-muted">Cantidad de pacientes</h6>
						</a>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-xl-4 mb-3 mb-xl-4">
				<div class="card flex-row align-items-center p-3 p-md-4">
					<div class="icon icon-border-home rounded-circle mr-3">
						<i class="fas fa-briefcase text-primary"></i>
					</div>
					<div>
						<a href="<?php echo site_url('/profesional'); ?>">
							<h4 class="mb-0 extra-bold text-primary"><?php echo $count_profesionales; ?> Profesionales</h4>
							<h6 class="mb-0 text-muted">Cantidad de profesionales</h6>
						</a>
					</div>
				</div>
			</div>
		</div>
		<?php endif; ?>

		<?php if($this->is_admin): ?>
		<div class="row">
			<div class="col-lg-12">
				<div class="card mb-3 mb-md-4">
					<div class="card-header d-flex align-items-center pb-0">
						<div class="icon icon-border-home rounded-circle mr-3">
							<i class="fas fa-calendar text-primary"></i>
						</div>
						<h4 class="mb-0 extra-bold text-primary">Turnos agendados para hoy</h4>
					</div>
					<div class="card-body pb-0">
						<?php if(!empty($turnos)): ?>
							<div class="table-responsive-xl">
								<table class="table text-nowrap mb-0">
									<thead>
										<tr>
											<th class="font-weight-semi-bold border-top-0 py-2">Fecha</th>
											<th class="font-weight-semi-bold border-top-0 py-2">Hora</th>
											<th class="font-weight-semi-bold border-top-0 py-2">Paciente</th>
											<th class="font-weight-semi-bold border-top-0 py-2">Profesional</th>
											<th class="font-weight-semi-bold border-top-0 py-2"></th>
										</tr>
									</thead>
									<tbody>
									<?php foreach($turnos as $row): ?>
										<tr>
											<td class="py-2">
												<?php echo strftime('%e de %B', strtotime($row->fecha)); ?><br>
											</td>
											<td class="py-2">
												<?php echo date('H:i', strtotime($row->fecha)); ?>
											</td>
											<td class="py-2">
												<a href="<?php echo site_url('/paciente/edit/'.$row->paciente_id); ?>"><?php echo $row->nombre_paciente; ?></a>
											</td>
											<td class="py-2"><?php echo $row->nombre_profesional; ?></td>
											<td class="py-2 text-right">
												<?php $mensaje_whatsapp = 'Hola ' . $row->nombre_paciente . "\n\n" . 'Te recordamos un turno pendiente en Cirugía Ambulatoria con *' . $row->nombre_profesional . '*, el día *' . strftime('%A %e/%m a las %H:%M hs', strtotime($row->fecha)) . '.*' . "\n\n" . '_Dirección: Av. J.B. Justo y Paso de los Andes, Ciudad de Mendoza_'; ?>
												<a href="https://api.whatsapp.com/send/?phone=<?php echo $row->telefono_paciente; ?>&text=<?php echo urlencode($mensaje_whatsapp); ?>&app_absent=0" class="btn btn-success text-white btn-sm" target="_blank">
													Enviar por WhatsApp <i class="fab fa-whatsapp"></i>
												</a>
												<a href="<?php echo site_url('/agenda/edit/'.$row->id); ?>" class="btn btn-info text-white btn-sm" data-toggle="modal" data-target="#editModal">
													Modificar turno
												</a>
											</td>
										</tr>
									<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						<?php else: ?>
							<div class="alert alert-warning">No hay turnos agendados para hoy</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
		<?php endif; ?>

	</div>

<?php $this->load->view('footer'); ?>