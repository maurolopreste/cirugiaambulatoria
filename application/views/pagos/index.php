<?php $this->load->view('header'); ?>

	<div class="py-4 px-3 px-md-4">
		<div class="card mb-3 mb-md-4">
			<div class="card-body">
				<?php $this->load->view('alert'); ?>
				
				<nav class="d-none d-md-block" aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item">
							<a href="#">Listado de Pagos</a>
						</li>
						<li class="breadcrumb-item active" aria-current="page">Todas los pagos</li>
					</ol>
				</nav>
				
				<div class="mb-5 d-flex justify-content-between">
					<div class="h3 mb-0">Listado de Pagos</div>
				</div>

				<form action="<?php echo site_url('/pagos'); ?>" method="get">
					<div class="input-group mb-5">
						<button type="submit" class="input-group-append btn">
							<i class="gd-search icon-text icon-text-sm"></i>
						</button>
						<input type="text" name="q" class="form-control form-control-icon-text" placeholder="Buscar por paciente o método de pago" value="<?php echo html_escape($this->input->get('q')); ?>">
					</div>
				</form>

				<?php if(!empty($rows)): ?>
					<div class="table-responsive-xl">
						<table class="table text-nowrap mb-0">
							<thead>
								<tr>
									<!-- <th class="font-weight-semi-bold border-top-0 py-2"><input type="checkbox" onclick="toggleAll(this)"></th> -->
									<th class="font-weight-semi-bold border-top-0 py-2">Fecha</th>
									<th class="font-weight-semi-bold border-top-0 py-2">Paciente</th>
									<th class="font-weight-semi-bold border-top-0 py-2">Tipo Consulta</th>
									<th class="font-weight-semi-bold border-top-0 py-2">Valor</th>
									<th class="font-weight-semi-bold border-top-0 py-2">Medio de pago</th>
									<th class="font-weight-semi-bold border-top-0 py-2">Estado</th>
									<th class="font-weight-semi-bold border-top-0 py-2 text-right">Acciones</th>
								</tr>
							</thead>
							<tbody>
							<?php foreach($rows as $row): ?>
								<tr>
									<!-- <td class="py-3">
										<input type="checkbox" name="ids[]" value="<?php echo html_escape($row->id); ?>">
									</td> -->
									<td><?php echo strftime('%e de %B', strtotime($row->fecha)); ?></td>
									<td><?php echo html_escape($row->paciente); ?></td>
									<td><?php echo html_escape($row->tipo_consulta); ?></td>
									<td><?php echo html_escape($row->valor); ?></td>
									<td><?php echo html_escape($row->metodo_pago); ?></td>
									<td>
										<?php
										switch($row->payment_status)
										{
											case 1: echo '<div class="badge badge-success">Pagado</div>'; break;
											default: echo '<div class="badge badge-warning">Pendiente</div>'; break;
										}
										?>
									</td>
									<td class="py-3 text-right">
										<div class="position-relative">
											<a class="link-dark d-inline-block mr-2" href="<?php echo html_escape(site_url('/pagos/edit/'.$row->id)); ?>" data-toggle="modal" data-target="#editModal">
												<i class="gd-eye icon-text"></i>
											</a>
											<!-- <a class="link-dark d-inline-block" href="<?php echo html_escape(site_url('/pagos/delete/'.$row->id)); ?>" onclick="return confirm('¿Eliminar este pago?', this)">
												<i class="gd-trash icon-text"></i>
											</a> -->
										</div>
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
						<!-- <a href="<?php echo site_url('/citas/add'); ?>" class="btn btn-primary">Crear póliza</a> -->
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>

<?php $this->load->view('footer'); ?>