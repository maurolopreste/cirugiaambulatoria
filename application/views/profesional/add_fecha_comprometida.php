
	<?php echo form_open(''); ?>
		<header class="modal-header">
			<h4 class="modal-title">Agregar fecha comprometida</h4>
		</header>

		<div class="modal-body pt-3">
			<div class="form-row">
				<div class="form-group col-sm-6">
					<label>Fecha Desde</label>
					<input type="date" class="form-control" id="fecha_desde" name="fecha_desde" value="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d'); ?>" onchange="setFechaDesde(this.value)" required>
				</div>
				<div class="form-group col-sm-6">
					<label>Fecha Hasta</label>
					<input type="date" class="form-control" id="fecha_hasta" name="fecha_hasta" required>
				</div>
			</div>
			<div class="form-group">
				<label>Motivo</label>
				<textarea rows="2" class="form-control" name="motivo"></textarea>
			</div>
		</div>

		<footer class="modal-footer text-right border-0">
			<button type="submit" class="btn btn-primary btn-submit">Guardar</button>
		</footer>
	</form>

	<script type="text/javascript">
		function setFechaDesde(fecha) {
			$('#fecha_hasta').attr('min', fecha).val(fecha);
		}
	</script>