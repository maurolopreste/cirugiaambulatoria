
	<?php echo form_open('', ['id'=>'addMensajeForm']); ?>
		<header class="modal-header">
			<h4 class="modal-title">Agregar mensaje</h4>
		</header>

		<div class="modal-body pt-3">
			<div class="form-group">
				<textarea class="form-control" name="mensaje" required></textarea>
			</div>
		</div>

		<footer class="modal-footer text-right border-0">
			<button type="submit" class="btn btn-primary btn-submit">Guardar</button>
		</footer>
	</form>