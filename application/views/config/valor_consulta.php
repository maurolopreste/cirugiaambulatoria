<?php $this->load->view('header'); ?>
	
	<div class="py-4 px-3 px-md-4">
		<div class="card mb-3 mb-md-4">
			<div class="card-body">
				<?php $this->load->view('alert'); ?>
				
				<nav class="d-none d-md-block" aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item">
							<a href="<?php echo site_url('/config'); ?>">Configuración</a>
						</li>
						<li class="breadcrumb-item active" aria-current="page">Editar configuración</li>
					</ol>
				</nav>
				
				<div class="mb-5 d-flex justify-content-between">
					<div class="h3 mb-0">Editar configuración: Valores</div>
					<a href="<?php echo site_url('/config/add_valor_consulta'); ?>" data-toggle="modal" data-target="#addModal" class="btn btn-primary">Agregar</a>
				</div>
				
				<div class="table-responsive-xl">
					<table class="table text-nowrap mb-0">
						<thead>
							<tr>
								<th class="font-weight-semi-bold border-top-0">Tipo de consulta</th>
								<th class="font-weight-semi-bold border-top-0">Valor</th>
								<th class="font-weight-semi-bold border-top-0 text-right">Acciones</th>
							</tr>
						</thead>
						<tbody>
							<?php if(!empty($rows)): ?>
								<?php foreach($rows as $row): ?>
									<tr>
										<td>
											<input type="text" class="form-control form-control-icon-img" id="input_<?php echo $row->id; ?>" name="nombre" value="<?php echo $row->nombre; ?>">
										</td>
										<td>
											<input type="text" class="form-control form-control-icon-img" id="valor_<?php echo $row->id; ?>" name="valor" value="<?php echo $row->valor; ?>">
										</td>
										<td class="pt-3 text-right">
											<button class="btn btn-sm btn-success text-white" onclick="edit(<?php echo $row->id; ?>)">Guardar</button>
											<a href="<?php echo site_url('/config/delete_valor_consulta/'.$row->id); ?>" class="btn btn-sm btn-danger text-white" onclick="return confirm('¿Eliminar este valor?', this)">Eliminar</button>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		function edit(id) {
			var nombre = $('#input_'+id).val();
			var valor = $('#valor_'+id).val();
			if(nombre) {
				var action = '<?php echo site_url('/config/valor_consulta/'); ?>'+id;
				$.post(action, {id: id, nombre: nombre, valor: valor}, function(){
					$('#input_'+id).parents('tr').addClass('alert-success');
					showNotification('Los datos se actualizaron correctamente.');
				});
			}
		}
	</script>

<?php $this->load->view('footer'); ?>