
	<?php echo form_open_multipart('', ['id'=>'addSeguroForm']); ?>
		<header class="modal-header">
			<h4 class="modal-title">Agregar seguro</h4>
		</header>

		<div class="modal-body pt-3">
			<div class="form-group">
				<label>Tipo</label>
				<select name="poliza_tipo_id" class="custom-select select2" required>
					<option value="">Seleccione una opción</option>
					<?php foreach($this->tipo_seguro_list as $tipo): ?>
						<option value="<?php echo $tipo->id; ?>"><?php echo $tipo->nombre; ?></option>
					<?php endforeach; ?>
				</select>
			</div>

			<div class="form-group">
				<label>Compañía</label>
				<select name="empresa_id" class="custom-select select2" required>
					<option value="">Seleccione una opción</option>
					<?php foreach($this->empresas_list as $empresa): ?>
						<option value="<?php echo $empresa->id; ?>"><?php echo $empresa->nombre; ?></option>
					<?php endforeach; ?>
				</select>
			</div>

			<div class="form-group mb-4">
				<label for="ControlFile1">Documentación</label>
				<input type="file" name="userfile" class="form-control-file" id="ControlFile1">
			</div>

			<div class="form-group">
				<label>Detalle de la póliza</label>
				<input type="text" class="form-control" name="detalle" required>
			</div>

			<div class="form-row">
				<div class="form-group col-md-6">
					<label>N&deg; póliza</label>
					<input type="text" class="form-control" name="nro_poliza" required>
				</div>

				<div class="form-group col-md-6">
					<label>Vencimiento</label>
					<input type="date" class="form-control" name="vencimiento">
				</div>
			</div>
		</div>

		<footer class="modal-footer text-right border-0">
			<button type="submit" class="btn btn-primary btn-submit">Guardar</button>
		</footer>
	</form>