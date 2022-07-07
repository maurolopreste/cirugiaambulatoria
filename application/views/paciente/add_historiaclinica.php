
	<?php echo form_open('', ['id'=>'addHistoriaClinicaForm']); ?>
		<header class="modal-header">
			<h4 class="modal-title">Agregar historia clínica</h4>
		</header>

		<div class="modal-body pt-3">
			<div class="form-group">
				<label>Fecha accidente</label>
				<input type="date" class="form-control" name="fecha_accidente" value="<?php echo date('Y-m-d'); ?>" required>
			</div>
			<div class="form-group">
				<label>Mecanismo</label>
				<input type="text" class="form-control" name="mecanismo">
			</div>
			<div class="form-group">
				<label>Antecedentes</label>
				<input type="text" class="form-control" name="antecedentes">
			</div>
			<div class="form-group">
				<label>Examen físico</label>
				<input type="text" class="form-control" name="examen_fisico">
			</div>
			<div class="form-group">
				<label>Antecedentes patológicos</label>
				<input type="text" class="form-control" name="antecedentes_patologicos">
			</div>
			<div class="form-group">
				<label>Estudios solicitados</label>
				<input type="text" class="form-control" name="estudios_solicitados">
			</div>
			<div class="form-group">
				<label>Tratamiento</label>
				<textarea rows="2" class="form-control" name="tratamiento"></textarea>
			</div>
			<div class="form-group mb-4">
				<label for="ControlFile1">Documentación Adjunta (opcional)</label>
				<input type="file" name="userfile" class="form-control-file" id="ControlFile1">
			</div>
		</div>

		<footer class="modal-footer text-right border-0">
			<button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
			<button type="submit" class="btn btn-primary btn-submit">Guardar</button>
		</footer>
	</form>