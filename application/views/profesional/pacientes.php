<?php $this->load->view('header'); ?>

	<div class="py-4 px-3 px-md-4">
		<div class="card mb-3 mb-md-4">
			<div class="card-body">
				<?php $this->load->view('alert'); ?>
				
				<nav class="d-none d-md-block" aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item">
							<a href="#">Pacientes</a>
						</li>
						<li class="breadcrumb-item active" aria-current="page">Todos los pacientes</li>
					</ol>
				</nav>
				
				<div class="mb-5 d-flex justify-content-between">
					<div class="h3 mb-0">Pacientes</div>
					<?php if($this->is_admin): ?>
						<a href="<?php echo site_url('/paciente/add'); ?>" class="btn btn-primary btn-sm">Agregar nuevo</a>
					<?php endif; ?>
				</div>

				<form action="<?php echo site_url('/paciente'); ?>" method="get">
					<div class="input-group mb-5">
						<button type="submit" class="input-group-append btn">
							<i class="gd-search icon-text icon-text-sm"></i>
						</button>
						<input type="text" name="q" class="form-control form-control-icon-text" placeholder="Buscar" value="<?php echo html_escape($this->input->get('q')); ?>">
					</div>
				</form>

				<?php if(!empty($rows)): ?>
					<div class="table-responsive-xl">
						<table class="table text-nowrap mb-0">
							<thead>
								<tr>
									<!-- <th class="font-weight-semi-bold border-top-0 py-2"><input type="checkbox" onclick="toggleAll(this)"></th> -->
									<th class="font-weight-semi-bold border-top-0 py-2">Apellido</th>
									<th class="font-weight-semi-bold border-top-0 py-2">Nombre</th>
									<th class="font-weight-semi-bold border-top-0 py-2">DNI</th>
									<th class="font-weight-semi-bold border-top-0 py-2">Teléfono</th>
									<th class="font-weight-semi-bold border-top-0 py-2"></th>
								</tr>
							</thead>
							<tbody>
							<?php foreach($rows as $row): ?>
								<tr>
									<!-- <td class="py-3">
										<input type="checkbox" name="ids[]" value="<?php echo html_escape($row->id); ?>">
									</td> -->
									<td class="py-3"><?php echo html_escape($row->last_name); ?></td>
									<td class="py-3"><?php echo html_escape($row->first_name); ?></td>
									<td class="py-3"><?php echo html_escape($row->dni); ?></td>
									<td class="py-3"><?php echo html_escape($row->telefono); ?></td>
									<td align="right">
										<a class="btn btn-success btn-sm text-white text-nowrap" href="<?php echo html_escape(site_url('/paciente/edit/'.$row->id)); ?>">
											<i class="gd-pencil mr-2"></i> Editar
										</a>
									</td>
								</tr>
							<?php endforeach; ?>
							</tbody>
						</table>
						
					</div>
				<?php else: ?>
					<div class="pt-10 pb-12 text-center">
						<i class="gd-user display-1"></i>
						<h4 class="m-5">No hay pacientes para mostrar</h4>
						<!-- <a href="<?php echo site_url('/paciente/add'); ?>" class="btn btn-primary">Crear paciente</a> -->
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>

<?php $this->load->view('footer'); ?>