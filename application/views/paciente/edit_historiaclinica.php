
	<?php echo form_open('', ['id'=>'editHistoriaClinicaForm']); ?>
		<header class="modal-header">
			<h4 class="modal-title">Editar historia clínica</h4>
		</header>

		<div class="modal-body pt-3">
			<div class="form-group">
				<label>Fecha accidente</label>
				<input type="date" class="form-control" name="fecha_accidente" value="<?php echo date('Y-m-d', strtotime($row->fecha_accidente)); ?>" required>
			</div>
			<div class="form-group">
				<label>Mecanismo</label>
				<input type="text" class="form-control" name="mecanismo" value="<?php echo html_escape($row->mecanismo); ?>">
			</div>
			<div class="form-group">
				<label>Antecedentes</label>
				<input type="text" class="form-control" name="antecedentes" value="<?php echo html_escape($row->antecedentes); ?>">
			</div>
			<div class="form-group">
				<label>Examen físico</label>
				<input type="text" class="form-control" name="examen_fisico" value="<?php echo html_escape($row->examen_fisico); ?>">
			</div>
			<div class="form-group">
				<label>Antecedentes patológicos</label>
				<input type="text" class="form-control" name="antecedentes_patologicos" value="<?php echo html_escape($row->antecedentes_patologicos); ?>">
			</div>
			<div class="form-group">
				<label>Estudios solicitados</label>
				<input type="text" class="form-control" name="estudios_solicitados" value="<?php echo html_escape($row->estudios_solicitados); ?>">
			</div>
			<div class="form-group">
				<label>Tratamiento</label>
				<textarea rows="2" class="form-control" name="tratamiento"><?php echo html_escape($row->tratamiento); ?></textarea>
			</div>
			<div class="form-group mb-4">
				<label for="ControlFile1">Documentación Adjunta (opcional)</label>
				<input type="file" name="userfile" class="form-control-file" id="ControlFile1">
				<?php if(!empty($row->file)): ?>
				<div class="alert alert-warning mt-2 p-2">Archivo subido: <a href="<?php echo html_escape(site_url('/uploads/'.$row->file)); ?>" class="alert-link" target="_blank"><?php echo html_escape($row->file); ?></a></div>
				<?php endif; ?>
			</div>
		</div>

		<footer class="modal-footer text-right border-0">
			<button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
			<button type="submit" class="btn btn-primary btn-submit">Guardar</button>
		</footer>
	</form>