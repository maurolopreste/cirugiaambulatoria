
	<?php echo form_open(''); ?>
		<header class="modal-header">
			<h4 class="modal-title">Enviar historia clínica</h4>
		</header>

		<div class="modal-body pt-3">
			<div class="form-group">
				<label>Nombre destinatario</label>
				<input type="text" class="form-control" name="nombre" required>
			</div>
			<div class="form-group">
				<label>Email destinatario</label>
				<input type="text" class="form-control" name="email" required>
			</div>
		</div>

		<footer class="modal-footer text-right border-0">
			<button type="submit" class="btn btn-primary btn-submit">Enviar</button>
		</footer>
	</form>