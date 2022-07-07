<?php if(!empty($rows)): ?>
	<div class="table-responsive-xl">
		<table class="table text-nowrap mb-0">
			<thead>
				<tr>
					<th class="font-weight-semi-bold border-top-0 py-2">Tipo de Seguro</th>
					<th class="font-weight-semi-bold border-top-0 py-2">Compañia</th>
					<th class="font-weight-semi-bold border-top-0 py-2">Vencimiento</th>
					<th class="font-weight-semi-bold border-top-0 py-2">PDF</th>
					<th class="font-weight-semi-bold border-top-0 py-2 text-right"></th>
				</tr>
			</thead>
			<tbody>
			<?php foreach($rows as $seguro): ?>
				<tr>
					<td class="py-3"><?php echo $seguro->poliza_tipo; ?></td>
					<td class="py-3"><?php echo $seguro->empresa; ?></td>
					<td class="py-3">
						<span class="badge badge-success"><?php echo $seguro->vencimiento; ?></span>
					</td>
					<td class="py-3">
						<a class="link-dark d-inline-block" href="<?php echo site_url('/user/download_seguro/'.$seguro->code); ?>" title="Descargar póliza">
							<i class="gd-download icon-text"></i>
						</a>
					</td>
					<td class="py-3 text-right">
						<a class="link-dark d-inline-block" href="<?php echo site_url('/user/edit_seguro/'.$seguro->id); ?>/<?php echo $user_id; ?>" data-toggle="modal" data-target="#editModal">
							<i class="gd-pencil icon-text"></i>
						</a>
						<a class="link-dark d-inline-block" href="<?php echo site_url('/user/delete_seguro/'.$seguro->id); ?>" onclick="return confirm('Atención: Se eliminá esta fila', this)">
							<i class="gd-trash icon-text"></i>
						</a>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
<?php endif; ?>