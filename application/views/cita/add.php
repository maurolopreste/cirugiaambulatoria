
	<?php echo form_open('', ['id'=>'addCitaForm']); ?>
		<header class="modal-header">
			<h4 class="modal-title">Agregar cita</h4>
		</header>

		<div class="modal-body pt-3">
			<?php if($this->is_admin): ?>
				<div class="form-group">
					<label>Profesional</label>
					<select name="profesional_id" class="custom-select select2" required>
						<option value="">Seleccione una opción</option>
						<?php foreach($this->profesionales_list as $profesional): ?>
							<option value="<?php echo $profesional->id; ?>"><?php echo $profesional->first_name; ?> <?php echo $profesional->last_name; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			<?php endif; ?>
			<div class="form-group">
				<label>Fecha</label>
				<input type="date" class="form-control" name="fecha" value="<?php echo date('Y-m-d'); ?>" required>
			</div>
			<div class="form-group">
				<label>Diagnóstico</label>
				<textarea rows="3" class="form-control" name="diagnostico"></textarea>
			</div>
			<div class="form-group">
				<label>Indicaciones de tratamiento</label>
				<textarea rows="3" class="form-control" name="indicaciones"></textarea>
			</div>
		</div>

		<footer class="modal-footer text-right border-0">
			<button type="submit" class="btn btn-primary btn-submit">Guardar</button>
		</footer>
	</form>