<?php $this->load->view('header'); ?>

	<div class="py-4 px-3 px-md-4">
		<div class="card mb-3 mb-md-4">
			<div class="card-body">
				<?php $this->load->view('alert'); ?>

				<?php if($this->input->get('pagar_despues')): ?>
					<div class="alert alert-warning alert-left-bordered d-flex align-items-center p-md-4 mb-4" role="alert">
						<p class="mb-0">
							<i class="fa fa-check mr-2"></i>
							Si querés pagar con otro medio de pago escribinos a <strong><?php echo html_escape(get_site_config('admin_email')); ?></strong>
						</p>
					</div>
				<?php endif; ?>
				
				<nav class="d-none d-md-block" aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item">
							<a href="#">Citas Agendadas</a>
						</li>
						<li class="breadcrumb-item active" aria-current="page">Todas las citas</li>
					</ol>
				</nav>
				
				<div class="mb-5 d-flex justify-content-between">
					<div class="h3 mb-0">Citas Agendadas</div>
				</div>

				<?php if(!empty($rows)): ?>
					<div class="table-responsive-xl">
						<table class="table text-nowrap mb-0">
							<thead>
								<tr>
									<!-- <th class="font-weight-semi-bold border-top-0 py-2"><input type="checkbox" onclick="toggleAll(this)"></th> -->
									<th class="font-weight-semi-bold border-top-0 py-2">Fecha</th>
									<th class="font-weight-semi-bold border-top-0 py-2">Hora</th>
									<th class="font-weight-semi-bold border-top-0 py-2">Profesional</th>
									<th class="font-weight-semi-bold border-top-0 py-2">Link Consulta</th>
									<th class="font-weight-semi-bold border-top-0 py-2" colspan="2">Estado</th>
								</tr>
							</thead>
							<tbody>
							<?php foreach($rows as $row): ?>
								<tr>
									<!-- <td class="py-3">
										<input type="checkbox" name="ids[]" value="<?php echo html_escape($row->id); ?>">
									</td> -->
									<td><?php echo strftime('%e de %B, %Y', strtotime($row->fecha)); ?></td>
									<td><?php echo date('H:i', strtotime($row->fecha)); ?></td>
									<td><?php echo html_escape($row->profesional); ?></td>
									<td>
										<?php if($row->link_video && $row->payment_status): ?>
											<a href="https://meet.jit.si/<?php echo html_escape($row->link_video); ?>" target="_blank">
												Abrir <span class="gd-share"></span>
											</a>
										<?php else: ?>
											<span class="text-muted">No Disponible</span>
										<?php endif; ?>
									</td>
									<td>
										<?php
										if(!$row->payment_status)
										{
											echo '<div class="badge badge-danger">Pendiente de Pago</div>';
										}
										else {
											switch($row->estado_cita)
											{
												case 2: echo '<div class="badge badge-success">Completada</div>'; break;
												default: echo '<div class="badge badge-info">Pendiente</div>'; break;
											}
										}
										?>
									</td>
									<td class="py-3 text-right">
										<?php if(!$row->payment_status): ?>
											<a href="<?php echo html_escape(site_url('/cita/generar_link_pago/'.$row->id)); ?>" target="_blank">
												Pagar ahora <span class="gd-credit-card ml-1" style="vertical-align: middle;"></span>
											</a>
										<?php endif; ?>
									</td>
								</tr>
							<?php endforeach; ?>
							</tbody>
						</table>
						<?php //$this->load->view('pagination', ['pagination'=>$pagination]); ?>
					</div>
				<?php else: ?>
					<div class="pt-10 pb-12 text-center">
						<i class="gd-calendar display-1"></i>
						<h4 class="m-5">No hay datos para mostrar</h4>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>

<?php $this->load->view('footer'); ?>