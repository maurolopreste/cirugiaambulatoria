<?php if(!empty($rows)): ?>
	<div class="table-responsive-xl">
		<table class="table text-nowrap mb-0">
			<thead>
				<tr>
					<th class="font-weight-semi-bold border-top-0 py-2">Fecha</th>
					<th class="font-weight-semi-bold border-top-0 py-2">Atendido por</th>
					<th class="font-weight-semi-bold border-top-0 py-2">Estado</th>
					<th class="font-weight-semi-bold border-top-0 py-2">Link Consulta</th>
					<th class="font-weight-semi-bold border-top-0 py-2"></th>
				</tr>
			</thead>
			<tbody>
			<?php foreach($rows as $row): ?>
				<tr>
					<td><?php echo date('d-m-Y', strtotime($row->fecha)); ?></td>
					<td><?php echo html_escape($row->profesional); ?></td>
					<td>
						<?php
						switch($row->estado_cita)
						{
							case 2: echo '<div class="badge badge-success">Completado</div>'; break;
							case 3: echo '<div class="badge badge-warning">Cancelado</div>'; break;
							case 4: echo '<div class="badge badge-danger">Ausente</div>'; break;
							default: echo '<div class="badge badge-info">Pendiente</div>'; break;
						}
						?>
					</td>
					<td>
						<?php if($row->estado_cita==1 && $row->link_video): ?>
						<a href="https://meet.jit.si/<?php echo html_escape($row->link_video); ?>" target="_blank">
							Abrir <span class="gd-share"></span>
						</a>
						<?php else: ?>
						<span class="text-muted">No Disponible</span>
						<?php endif; ?>
					</td>
					<td class="py-3 text-right">
						<?php if($this->is_admin): ?>
						<div class="position-relative">
							<a class="link-dark d-inline-block" href="<?php echo html_escape(site_url('/cita/edit/'.$row->id)); ?>/<?php echo html_escape($paciente_id); ?>" data-toggle="modal" data-target="#editModal">
								<i class="gd-pencil icon-text"></i>
							</a>
							<a class="link-dark d-inline-block" href="<?php echo html_escape(site_url('/cita/delete/'.$row->id)); ?>" onclick="return confirm('Atención: Se eliminá esta fila', this)">
								<i class="gd-trash icon-text"></i>
							</a>
						</div>
						<?php endif; ?>
						<?php if($this->login->id==$row->profesional_id): ?>
						<div class="position-relative">
							<a class="link-dark d-inline-block" href="<?php echo html_escape(site_url('/cita/edit/'.$row->id)); ?>" data-toggle="modal" data-target="#editModal">
								<i class="gd-pencil icon-text"></i>
							</a>
						</div>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
<?php else: ?>
	<div class="alert alert-warning">Este paciente no tiene citas</div>
<?php endif; ?>