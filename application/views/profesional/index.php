<?php $this->load->view('header'); ?>

	<div class="py-4 px-3 px-md-4">
		<div class="card mb-3 mb-md-4">
			<div class="card-body">
				<?php $this->load->view('alert'); ?>
				
				<nav class="d-none d-md-block" aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item">
							<a href="#">Profesionales</a>
						</li>
						<li class="breadcrumb-item active" aria-current="page">Todos los profesionales</li>
					</ol>
				</nav>
				
				<div class="mb-5 d-flex justify-content-between">
					<div class="h3 mb-0">Profesionales</div>
					<a href="<?php echo site_url('/profesional/add'); ?>" class="btn btn-primary">Agregar nuevo</a>
				</div>

				<form action="<?php echo site_url('/profesional'); ?>" method="get">
					<div class="input-group mb-5">
						<button type="submit" class="input-group-append btn">
							<i class="gd-search icon-text icon-text-sm"></i>
						</button>
						<input type="text" name="q" class="form-control form-control-icon-text" placeholder="Buscar" value="<?php echo $this->input->get('q'); ?>">
					</div>
				</form>

				<?php if(!empty($rows)): ?>
					<div class="table-responsive-xl">
						<table class="table text-nowrap mb-0">
							<thead>
								<tr>
									<!-- <th class="font-weight-semi-bold border-top-0 py-2"><input type="checkbox" onclick="toggleAll(this)"></th> -->
									<th class="font-weight-semi-bold border-top-0 py-2">Nombre</th>
									<th class="font-weight-semi-bold border-top-0 py-2">Especialidad</th>
									<th class="font-weight-semi-bold border-top-0 py-2">Email</th>
									<th class="font-weight-semi-bold border-top-0 py-2">Estado</th>
									<th class="font-weight-semi-bold border-top-0 py-2 text-right">Acciones</th>
								</tr>
							</thead>
							<tbody>
							<?php foreach($rows as $row): ?>
								<tr>
									<!-- <td class="py-3">
										<input type="checkbox" name="ids[]" value="<?php echo $row->id; ?>">
									</td> -->
									<td>
										<a href="<?php echo site_url('/profesional/edit/'.$row->id); ?>">
											<?php echo $row->first_name; ?>
											<?php echo $row->last_name; ?>
										</a>
									</td>
									<td><?php echo $row->especialidad; ?></td>
									<td><?php echo $row->email; ?></td>
									<td>
										<?php if($row->active): ?>
										<div class="badge badge-success">Activo</div>
										<?php else: ?>
										<div class="badge badge-danger">Inactivo</div>
										<?php endif; ?>
									</td>
									<td class="py-3 text-right">
										<div class="position-relative">
											<a class="link-dark d-inline-block" href="<?php echo site_url('/profesional/edit/'.$row->id); ?>">
												<i class="gd-pencil icon-text"></i>
											</a>
											<?php if($this->is_admin && $row->active): ?>
												<a class="link-dark d-inline-block ml-1" href="#" onclick="deshabilitar(<?php echo $row->id; ?>)" title="Deshabilitar">
													<i class="gd-unlink icon-text"></i>
												</a>
											<?php endif; ?>
											<?php if($this->is_admin && !$row->active): ?>
												<a class="link-dark d-inline-block ml-1" href="#" onclick="activar(<?php echo $row->id; ?>)" title="Activar">
													<i class="gd-check-box icon-text"></i>
												</a>
											<?php endif; ?>
											<?php if($this->is_admin): ?>
												<a class="link-dark d-inline-block ml-1" href="<?php echo site_url('/profesional/delete/'.$row->id); ?>" onclick="return confirm('Atención: Se eliminá este profesional', this)">
													<i class="gd-trash icon-text"></i>
												</a>
											<?php endif; ?>
										</div>
									</td>
								</tr>
							<?php endforeach; ?>
							</tbody>
						</table>
						
					</div>
				<?php else: ?>
					<div class="pt-10 pb-12 text-center">
						<i class="gd-briefcase display-1"></i>
						<h4 class="m-5">No hay profesionales para mostrar</h4>
						<!-- <a href="<?php echo site_url('/profesional/add'); ?>" class="btn btn-primary">Crear profesional</a> -->
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		function deshabilitar(id) {
			var modal = $('#confirmModal');
			var href = '<?php echo site_url('/profesional/deshabilitar/'); ?>'+id;
			modal.find('.h5').text('El profesional se deshabilitará');
			modal.find('.btn-confirm').attr('href', href);
			modal.modal('show');
		}
		function activar(id) {
			var modal = $('#confirmModal');
			var href = '<?php echo site_url('/profesional/activar/'); ?>'+id;
			modal.find('.h5').text('El profesional se habilitará para consultas');
			modal.find('.btn-confirm').attr('href', href);
			modal.modal('show');
		}
	</script>

<?php $this->load->view('footer'); ?>