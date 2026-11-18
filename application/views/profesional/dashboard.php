<?php $this->load->view('header'); ?>

	<meta http-equiv="refresh" content="10">

	<div class="p-4">
		<?php $this->load->view('alert'); ?>

		<div class="mb-5 d-flex justify-content-center">
			<div class="h3 mb-0">¡Hola, <?php echo $this->login->first_name; ?>! Te damos la bienvenida</div>
		</div>

		<div class="row justify-content-center">
			<div class="col-lg-8">
				<?php if($proxima_consulta): ?>
				<div class="card mb-3 mb-md-4 pb-3">
					<div class="card-header d-flex align-items-center pb-0">
						<div class="icon icon-border-home rounded-circle mr-3">
							<i class="fas fa-clock text-primary"></i>
						</div>
						<h4 class="mb-0 extra-bold text-primary">Próxima consulta</h4>
					</div>
					<div class="card-body pb-4 text-center">
						<h3 class="display-4"><?php echo $proxima_consulta->paciente; ?></h3>
						<h4>Turno: <?php echo date('H:i', strtotime($proxima_consulta->fecha)); ?></h4>
						<br>
						<a href="<?php echo site_url('paciente/formingreso/'.$proxima_consulta->paciente_id); ?>" class="btn btn-success text-white">Ver datos paciente</a>
						<a href="<?php echo site_url('agenda/completar/'.$proxima_consulta->id); ?>" class="btn btn-info text-white">Marcar como atendido</a>
					</div>
				</div>
				<?php endif; ?>

				<div class="card mb-3 mb-md-4">
					<div class="card-header d-flex align-items-center pb-0">
						<div class="icon icon-border-home rounded-circle mr-3">
							<i class="fas fa-calendar text-primary"></i>
						</div>
						<h4 class="mb-0 extra-bold text-primary">Turnos agendados para hoy</h4>
					</div>
					<div class="card-body pb-0">
						<?php if(!empty($citas)): ?>
							<div class="table-responsive-xl">
								<table class="table text-nowrap lead mb-0">
									<thead>
										<tr>
											<th class="font-weight-semi-bold border-top-0 py-2">Fecha</th>
											<th class="font-weight-semi-bold border-top-0 py-2">Hora</th>
											<th class="font-weight-semi-bold border-top-0 py-2">Nombre paciente</th>
										</tr>
									</thead>
									<tbody>
									<?php foreach($citas as $row): ?>
										<tr>
											<td class="py-2"><?php echo strftime('%e de %B, %Y', strtotime($row->fecha)); ?></td>
											<td class="py-2"><?php echo date('H:i', strtotime($row->fecha)); ?></td>
											<td class="py-2"><?php echo $row->paciente; ?></td>
										</tr>
									<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						<?php else: ?>
							<div class="alert alert-warning">No tiene citas agendadas</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php $this->load->view('footer'); ?>