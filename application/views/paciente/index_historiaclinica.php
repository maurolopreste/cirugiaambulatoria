<?php if(!empty($rows)): ?>
	<div class="table-responsive-xl">
		<table class="table mb-0">
			<thead>
				<tr>
					<th class="font-weight-semi-bold border-top-0 py-2">Fecha</th>
					<th class="font-weight-semi-bold border-top-0 py-2">Informe</th>
					<th class="font-weight-semi-bold border-top-0 py-2">Próxima visita</th>
					<th class="font-weight-semi-bold border-top-0 py-2"></th>
				</tr>
			</thead>
			<tbody>
			<?php foreach($rows as $row): ?>
				<tr>
					<td>
						<?php if(!empty($row->fecha)): ?>
							<?php echo strftime('%e de %B, %Y', strtotime($row->fecha)); ?>
						<?php else: ?>
							<?php echo strftime('%e de %B, %Y', strtotime($row->created_at)); ?>
						<?php endif; ?>
					</td>
					<td>
						<?php if(!empty($row->informe)): ?>
							<?php echo nl2br(html_escape($row->informe)); ?>
						<?php else: ?>
							Ingreso historia clínica
						<?php endif; ?>
					</td>
					<td>
						<?php if(!empty($row->proxima_visita)): ?>
							<?php echo strftime('%e de %B', strtotime($row->proxima_visita)); ?>
						<?php else: ?>
							-
						<?php endif; ?>
					</td>
					<td class="py-3 text-right">
						<?php if($this->is_admin): ?>
						<div class="position-relative">
							<a class="link-dark d-inline-block" href="<?php echo html_escape(site_url('/paciente/edit_historiaclinica/'.$row->id)); ?>/<?php echo html_escape($paciente_id); ?>" data-toggle="modal" data-target="#editModal">
								<i class="gd-pencil icon-text"></i>
							</a>
							<a class="link-dark d-inline-block" href="<?php echo html_escape(site_url('/paciente/send_historiaclinica/'.$row->id)); ?>">
								<i class="gd-send icon-text"></i>
							</a>
							<a class="link-dark d-inline-block" href="<?php echo html_escape(site_url('/paciente/delete_historiaclinica/'.$row->id)); ?>" onclick="return confirm('Atención: Se eliminá este informe', this)">
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
	<a href="<?php echo html_escape(site_url('/paciente/add_evolutivo/'.$paciente_id)); ?>" data-toggle="modal" data-target="#addModal" class="btn btn-primary">Agregar informe evolutivo</a>
<?php else: ?>
	<div class="alert alert-warning">Este paciente no tiene historia clínica</div>
	<a href="<?php echo html_escape(site_url('/paciente/add_historiaclinica/'.$paciente_id)); ?>" data-toggle="modal" data-target="#addModal" class="btn btn-primary">Agregar historia clínica</a>
<?php endif; ?>