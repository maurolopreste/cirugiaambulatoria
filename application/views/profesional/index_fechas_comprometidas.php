<?php if(!empty($rows)): ?>
	<div class="table-responsive-xl">
		<table class="table text-nowrap mb-0">
			<thead>
				<tr>
					<th class="font-weight-semi-bold border-top-0 py-2">Motivo</th>
					<th class="font-weight-semi-bold border-top-0 py-2">Fecha Desde</th>
					<th class="font-weight-semi-bold border-top-0 py-2">Fecha Hasta</th>
					<th class="font-weight-semi-bold border-top-0 py-2"></th>
				</tr>
			</thead>
			<tbody>
			<?php foreach($rows as $fecha): ?>
				<tr>
					<td><?php echo html_escape($fecha->motivo); ?></td>
					<td><?php echo date('d-m-Y', strtotime($fecha->fecha_desde)); ?></td>
					<td><?php echo date('d-m-Y', strtotime($fecha->fecha_hasta)); ?></td>
					<td class="py-3 text-right">
						<div class="position-relative">
							<a class="link-dark d-inline-block" href="<?php echo html_escape(site_url('/profesional/delete_fecha_comprometida/'.$fecha->id)); ?>" onclick="return confirm('Atención: Se eliminá esta fila', this)">
								<i class="gd-trash icon-text"></i>
							</a>
						</div>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
<?php else: ?>
	<div class="alert alert-warning">El profesional no tiene fechas comprometidas</div>
<?php endif; ?>