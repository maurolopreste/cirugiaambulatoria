<?php if(!empty($rows)): ?>
	<div class="table-responsive-xl">
		<table class="table text-nowrap mb-0">
			<thead>
				<tr>
					<th class="font-weight-semi-bold border-top-0 py-2">Fecha</th>
					<th class="font-weight-semi-bold border-top-0 py-2">Facturado</th>
					<th class="font-weight-semi-bold border-top-0 py-2"></th>
				</tr>
			</thead>
			<tbody>
			<?php foreach($rows as $evolutivo): ?>
				<tr>
					<td><?php echo date('d-m-Y', strtotime($evolutivo->fecha)); ?></td>
					<td>
						<!-- <div class="custom-control custom-switch">
							<input type="checkbox" name="facturado" class="custom-control-input" id="customSwitch1" value="1">
							<label class="custom-control-label" for="customSwitch1"></label>
						</div> -->
						<input type="checkbox" <?php if($evolutivo->facturado) echo 'checked'; ?> disabled>
					</td>
					<td class="py-3 text-right">
						<?php if($this->is_admin || $evolutivo->profesional_id==$this->login->id): ?>
						<div class="position-relative">
							<a class="link-dark d-inline-block" href="<?php echo html_escape(site_url('/paciente/edit_evolutivo/'.$evolutivo->id)); ?>/<?php echo html_escape($paciente_id); ?>" data-toggle="modal" data-target="#editModal">
								<i class="gd-pencil icon-text"></i>
							</a>
							<a class="link-dark d-inline-block" href="<?php echo html_escape(site_url('/paciente/delete_evolutivo/'.$evolutivo->id)); ?>/<?php echo html_escape($paciente_id); ?>" onclick="return confirm('Atención: Se eliminá este informe', this)">
								<i class="gd-trash icon-text"></i>
							</a>
						</div>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<a href="<?php echo html_escape(site_url('/paciente/add_evolutivo/'.$rows[0]->paciente_id)); ?>" data-toggle="modal" data-target="#addModal" class="btn btn-primary">Agregar evolutivo</a>
<?php else: ?>
	<div class="alert alert-warning">El paciente no tiene informes evolutivos</div>
	<a href="<?php echo html_escape(site_url('/paciente/add_evolutivo/'.$paciente_id)); ?>" data-toggle="modal" data-target="#addModal" class="btn btn-primary">Agregar evolutivo</a>
<?php endif; ?>