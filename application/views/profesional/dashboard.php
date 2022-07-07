<?php $this->load->view('header'); ?>

	<div class="p-4">
		<?php $this->load->view('alert'); ?>

		<div class="mb-5 d-flex justify-content-center">
			<div class="h3 mb-0">¡Hola, <?php echo $this->login->first_name; ?>! Te damos la bienvenida</div>
		</div>

		<div class="row justify-content-center">
			<div class="col-lg-8">
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
											<th class="font-weight-semi-bold border-top-0 py-2">Paciente</th>
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
											<td class="py-2">
												<?php echo $row->paciente; ?> |
												<a href="<?php echo site_url('paciente/edit/'.$row->paciente_id); ?>" class="text-primary">Editar Información</a>
											</td>
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