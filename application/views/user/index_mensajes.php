<?php if(!empty($rows)): ?>
	<div class="table-responsive-xl">
		<table class="table text-nowrap mb-0">
			<thead>
				<tr>
					<th class="font-weight-semi-bold border-top-0 py-2">Mensaje</th>
					<th class="font-weight-semi-bold border-top-0 py-2">Fecha</th>
					<th class="font-weight-semi-bold border-top-0 py-2"></th>
				</tr>
			</thead>
			<tbody>
			<?php foreach($rows as $mensaje): ?>
				<tr>
					<td class="py-3"><?php echo $mensaje->mensaje; ?></td>
					<td class="py-3"><?php echo $mensaje->fecha; ?></td>
					<td class="py-3 text-right">
						<a class="link-dark d-inline-block" href="<?php echo site_url('/user/delete_mensaje/'.$mensaje->id); ?>" onclick="return confirm('¿Eliminar mensaje?', this)">
							<i class="gd-trash icon-text"></i>
						</a>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
<?php endif; ?>