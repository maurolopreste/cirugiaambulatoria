<?php $this->load->view('header'); ?>

	<div class="py-4 px-3 px-md-4">
		<div class="card mb-3 mb-md-4">
			<div class="card-body">
				<?php $this->load->view('alert'); ?>
				
				<nav class="d-none d-md-block" aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item">
							<a href="#">Usuarios</a>
						</li>
						<li class="breadcrumb-item active" aria-current="page">Todos los usuarios</li>
					</ol>
				</nav>
				
				<div class="mb-5 d-flex justify-content-between">
					<div class="h3 mb-0">Usuarios</div>
					<a href="<?php echo site_url('/user/add'); ?>" class="btn btn-primary btn-sm">Agregar nuevo</a>
				</div>

				<?php if(!empty($rows)): ?>
					<div class="table-responsive-xl">
						<table class="table text-nowrap mb-0">
							<thead>
								<tr>
									<!-- <th class="font-weight-semi-bold border-top-0 py-2"><input type="checkbox" onclick="toggleAll(this)"></th> -->
									<th class="font-weight-semi-bold border-top-0 py-2">Nombre</th>
									<th class="font-weight-semi-bold border-top-0 py-2">Email</th>
									<th class="font-weight-semi-bold border-top-0 py-2">Perfil</th>
									<th class="font-weight-semi-bold border-top-0 py-2">Acciones</th>
								</tr>
							</thead>
							<tbody>
							<?php foreach($rows as $row): ?>
								<tr>
									<!-- <td class="py-3">
										<input type="checkbox" name="ids[]" value="<?php echo $row->id; ?>">
									</td> -->
									<td>
										<a href="<?php echo site_url('/user/edit/'.$row->id); ?>">
											<?php echo $row->first_name; ?>
											<?php echo $row->last_name; ?>
										</a>
									</td>
									<td><?php echo $row->email; ?></td>
									<td><?php echo $row->perfil; ?></td>
									<td class="py-3 text-right">
										<div class="position-relative">
											<a id="dropDown<?php echo $row->id; ?>Invoker" class="link-dark d-flex" href="#" aria-controls="dropDown<?php echo $row->id; ?>" aria-haspopup="true" aria-expanded="false" data-unfold-target="#dropDown<?php echo $row->id; ?>" data-unfold-event="click" data-unfold-type="css-animation" data-unfold-duration="300" data-unfold-animation-in="fadeIn" data-unfold-animation-out="fadeOut">
												<i class="gd-more-alt icon-text"></i>
											</a>
											<ul id="dropDown<?php echo $row->id; ?>" class="unfold unfold-light unfold-top unfold-right position-absolute py-3 mt-1 unfold-css-animation unfold-hidden fadeOut" aria-labelledby="dropDown<?php echo $row->id; ?>Invoker" style="min-width: 150px; animation-duration: 300ms; right: 0px;">
												<li class="unfold-item">
													<a class="unfold-link media align-items-center text-nowrap" href="<?php echo site_url('/user/edit/'.$row->id); ?>">
														<i class="gd-pencil unfold-item-icon mr-3"></i>
														<span class="media-body">Editar</span>
													</a>
												</li>
												<li class="unfold-item">
													<a class="unfold-link media align-items-center text-nowrap text-danger" href="<?php echo site_url('/user/delete/'.$row->id); ?>" onclick="return confirm('Este usuario se enviará a la papelera', this)">
														<i class="gd-trash unfold-item-icon mr-3"></i>
														<span class="media-body">Papelera</span>
													</a>
												</li>
											</ul>
										</div>
									</td>
								</tr>
							<?php endforeach; ?>
							</tbody>
						</table>
						
					</div>
				<?php else: ?>
					<div class="pt-10 pb-12 text-center">
						<i class="gd-package display-1"></i>
						<h4 class="m-5">No hay usuarios para mostrar</h4>
						<a href="<?php echo site_url('/user/add'); ?>" class="btn btn-primary">Crear usuario</a>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		function set_featured(el, id, value) {
			$(el).hide().siblings('a').show();
			$.get('<?php echo site_url('/user/set_featured/'); ?>'+id+'/'+value);
		}
	</script>

<?php $this->load->view('footer'); ?>