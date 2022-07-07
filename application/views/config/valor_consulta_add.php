
	<header class="modal-header">
		<h4 class="modal-title">Agregar nuevo valor</h4>
	</header>

	<?php echo form_open('/config/valor_consulta'); ?>
		<div class="modal-body pt-3">
			<div class="form-group">
				<input type="text" class="form-control form-control-icon-img" name="nombre" placeholder="Ej: Consulta Psicológica">
			</div>
			<div class="form-group">
				<input type="text" class="form-control form-control-icon-img" name="valor" placeholder="1500">
			</div>
		</div>

		<footer class="modal-footer text-right border-0">
			<button type="submit" class="btn btn-primary">Guardar</button>
		</footer>
	</form>