
	<?php echo form_open_multipart('', array('autocomplete'=>'off')); ?>
		<header class="modal-header">
			<h4 class="modal-title">Modificar pago: <?php echo strftime('%e de %B', strtotime($row->fecha)); ?></h4>
		</header>

		<div class="modal-body pt-3">
			<div class="form-group">
				<label>Paciente</label>
				<input type="text" class="form-control" value="<?php echo $row->nombre_paciente; ?>" readonly>
			</div>
			<div class="form-group">
				<label>Tipo de consulta</label>
				<select name="valor_consulta" class="custom-select">
					<option value="">Seleccione una opción</option>
					<?php foreach($this->valor_consulta_list as $v): ?>
						<option value="<?php echo $v->id; ?>" <?php if($row->valor_consulta==$v->id) echo 'selected'; ?>><?php echo $v->nombre; ?> ($<?php echo $v->valor; ?>)</option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="form-row">
				<div class="form-group col-6">
					<label>Método de pago</label>
					<select name="payment_method" class="custom-select">
						<option value="">Seleccione una opción</option>
						<?php foreach($this->metodopago_list as $m): ?>
							<option value="<?php echo $m->id; ?>" <?php if($row->payment_method==$m->id) echo 'selected'; ?>><?php echo $m->nombre; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="form-group col-6">
					<label>Estado del pago</label>
					<select name="payment_status" class="custom-select">
						<option value="0">Pendiente</option>
						<option value="1" <?php if($row->payment_status=='1') echo 'selected'; ?>>Completado</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label>Adjuntar comprobante</label>
				<input type="file" name="userfile">
			</div>
			<div class="form-group">
				<label>Observaciones</label>
				<textarea rows="2" class="form-control" name="comentarios"><?php echo $row->comentarios; ?></textarea>
			</div>
		</div>

		<footer class="modal-footer text-right border-0">
			<?php if(isset($row->file)): ?>
				<a href="<?php echo site_url('/uploads/'.$row->file); ?>" class="btn btn-outline-primary mr-auto" target="_blank">
					Descargar Comprobante <i class="gd-download ml-2"></i>
				</a>
			<?php endif; ?>
			<button type="submit" class="btn btn-primary btn-submit">Guardar</button>
		</footer>
	</form>