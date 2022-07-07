
	<?php echo form_open('', ['id'=>'addEvolutivoForm']); ?>
		<header class="modal-header">
			<h4 class="modal-title">Agregar evolutivo</h4>
		</header>

		<div class="modal-body pt-3">
			<?php if($this->is_admin): ?>
				<div class="form-group">
					<label>Profesional</label>
					<select name="profesional_id" class="custom-select select2" required>
						<option value="">Seleccione una opción</option>
						<?php foreach($this->profesionales_list as $profesional): ?>
							<?php $selected = $row->profesional_id==$profesional->id ? 'selected' : ''; ?>
							<option value="<?php echo $profesional->id; ?>" <?php echo $selected; ?>><?php echo $profesional->first_name; ?> <?php echo $profesional->last_name; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			<?php endif; ?>
			<div class="form-group">
				<label>Fecha</label>
				<input type="date" class="form-control" name="fecha" value="<?php echo date('Y-m-d'); ?>" required>
			</div>
			<div class="form-group">
				<label>Informe</label>
				<textarea rows="5" class="form-control" name="informe" required></textarea>
			</div>
			<div class="form-group mb-4">
				<label for="ControlFile1">Documentación Adjunta (opcional)</label>
				<input type="file" name="userfile" class="form-control-file" id="ControlFile1">
			</div>
			<div class="form-group">
				<label>Próxima visita</label>
				<input type="date" class="form-control" name="proxima_visita">
			</div>
			<div class="form-group">
				<label>¿Paciente fue dado de alta?</label>
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="alta" id="inlineRadio1" value="1">
					<label class="form-check-label" for="inlineRadio1">Si</label>
				</div>
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="alta" id="inlineRadio2" value="0" checked>
					<label class="form-check-label" for="inlineRadio2">No</label>
				</div>
			</div>
		</div>

		<footer class="modal-footer text-right border-0">
			<button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
			<button type="submit" class="btn btn-primary btn-submit">Guardar</button>
		</footer>
	</form>