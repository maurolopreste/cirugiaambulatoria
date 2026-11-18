<?php $this->load->view('header_static'); ?>

	<div class="p-3">
		<nav class="d-none d-md-block hidden-print" aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">
					<a href="<?php echo site_url('paciente/edit/'.$row->id); ?>" class="link-muted">&lt; Volver</a>
				</li>
			</ol>
		</nav>

		<div class="card mb-3" style="max-width: 1200px">
			<div class="card-body">
				<div class="hidden-print">
					<?php $this->load->view('alert'); ?>
				</div>

				<h1 class="h2 mb-5">Constancia de <strong>Parte Médico de Ingreso</strong></h1>
			
				<div class="row">
					<div class="col-sm-12">
						<?php echo form_open('', ['id'=>'formIngreso']); ?>
							<div class="form-row mb-3">
								<div class="form-group col-sm-4">
									<label>ART</label>
									<select name="art_id" class="custom-select select2" data-select="<?php echo $form->art_id; ?>">
										<option value="">Buscar por empresa o cuit</option>
										<?php foreach($this->art_list as $art): ?>
											<option value="<?php echo $art->id; ?>"><?php echo $art->nombre; ?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="form-group col-sm-4">
									<label>NRO SINIESTRO</label>
									<input type="text" class="form-control form-control-line" name="nro_siniestro" value="<?php echo $form->nro_siniestro; ?>">
								</div>
							</div>
							<div class="form-row mb-3">
								<div class="form-group col-sm-4">
									<label>OBRA SOCIAL</label>
									<select name="obra_social" class="custom-select select2" data-select="<?php echo $row->obra_social; ?>">
										<option value="">Selecciona una opción</option>
										<?php foreach($this->obra_social_list as $obra_social): ?>
											<option value="<?php echo $obra_social->id; ?>"><?php echo $obra_social->nombre; ?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="form-group col-sm-4">
									<label>NRO AFILIADO</label>
									<input type="text" class="form-control form-control-line" name="nro_afiliado" value="<?php echo $row->nro_afiliado; ?>">
								</div>
							</div>

							<div class="mb-3">
								<label class="d-inline mr-3"><strong>Tipo de contingencia:</strong></label>
								
								<?php $options = ['Accidente de Trabajo', 'Accidente in itinere', 'Enfermedad Profesional', 'Intercurrencia', 'Sin especificar']; ?>
								<?php foreach($options as $k=>$option): ?>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="contingencia" id="contingencia<?php echo $k; ?>" value="<?php echo $option; ?>" <?php if($form->contingencia==$option) echo 'checked'; ?>>
										<label class="form-check-label" for="contingencia<?php echo $k; ?>"><?php echo $option; ?></label>
									</div>
								<?php endforeach; ?>
							</div>

							<div class="h5 font-weight-bold mb-3 bg-dark text-white p-2">DATOS DEL TRABAJADOR</div>

							<div class="form-row mb-3">
								<div class="form-group col-sm-4">
									<label>Apellido</label>
									<input type="text" class="form-control form-control-line" name="last_name" value="<?php echo $row->last_name; ?>">
								</div>
								<div class="form-group col-sm-4">
									<label>Nombre</label>
									<input type="text" class="form-control form-control-line" name="first_name" value="<?php echo $row->first_name; ?>">
								</div>
								<div class="form-group col-sm-2">
									<label>CUIL/DNI</label>
									<input type="text" class="form-control form-control-line" name="dni" value="<?php echo $row->dni; ?>">
								</div>
								<div class="col-sm-2">
									<label class="d-inline mr-3">Sexo:</label>
									<?php $options = ['M', 'F']; ?>
									<?php foreach($options as $k=>$option): ?>
										<div class="form-check form-check-inline">
											<input class="form-check-input" type="radio" name="sexo" id="sexo<?php echo $k; ?>" value="<?php echo $option; ?>" <?php if($row->sexo==$option) echo 'checked'; ?>>
											<label class="form-check-label" for="sexo<?php echo $k; ?>"><?php echo $option; ?></label>
										</div>
									<?php endforeach; ?>
								</div>
							</div>

							<div class="form-row mb-3">
								<div class="col-sm-3">
									<label>Fecha de Nacimiento</label>
									<input type="date" class="form-control form-control-line" name="fecha_nac" value="<?php echo $row->fecha_nac; ?>">
								</div>
								<div class="form-group col-sm-7">
									<label>Calle</label>
									<input type="text" class="form-control form-control-line" name="calle" value="<?php echo $form->calle; ?>">
								</div>
								<div class="form-group col-sm-2">
									<label>Nro</label>
									<input type="text" class="form-control form-control-line" name="calle_nro" value="<?php echo $form->calle_nro; ?>">
								</div>
							</div>

							<div class="form-row mb-3">
								<div class="form-group col-sm-1">
									<label>Piso</label>
									<input type="text" class="form-control form-control-line" name="piso" value="<?php echo $form->piso; ?>">
								</div>
								<div class="form-group col-sm-1">
									<label>Depto</label>
									<input type="text" class="form-control form-control-line" name="depto" value="<?php echo $form->depto; ?>">
								</div>
								<div class="form-group col-sm-4">
									<label>Localidad</label>
									<input type="text" class="form-control form-control-line" name="localidad" value="<?php echo $form->localidad; ?>">
								</div>
								<div class="form-group col-sm-4">
									<label>Provincia</label>
									<select class="form-control form-control-line" name="provincia" data-select="<?php echo $form->provincia; ?>">
										<option value="14">Mendoza</option>
										<option disabled>----</option>
										<?php foreach($this->provincias_list as $provincia): ?>
											<option value="<?php echo $provincia->id; ?>"><?php echo $provincia->nombre; ?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="form-group col-sm-2">
									<label>CP</label>
									<input type="text" class="form-control form-control-line" name="cp" value="<?php echo $form->cp; ?>">
								</div>
							</div>

							<div class="form-row mb-3">
								<div class="form-group col-sm-4">
									<label>Tel. Fijo</label>
									<input type="text" class="form-control form-control-line" name="tel_fijo" value="<?php echo $form->tel_fijo; ?>">
								</div>
								<div class="form-group col-sm-4">
									<label>Tel. Móvil</label>
									<input type="text" class="form-control form-control-line" name="tel_movil" value="<?php echo $form->tel_movil; ?>">
								</div>
								<div class="form-group col-sm-4">
									<label>Tel. Alternativo</label>
									<input type="text" class="form-control form-control-line" name="tel_alt" value="<?php echo $form->tel_alt; ?>">
								</div>
							</div>

							<div class="form-group mb-5">
								<label>E-mail</label>
								<input type="email" class="form-control form-control-line" name="email" value="<?php echo $row->email; ?>">
							</div>

							<div class="h5 font-weight-bold mb-3 bg-dark text-white p-2">DATOS DEL EMPLEADOR</div>

							<div class="form-row mb-3">
								<div class="form-group col-sm-8">
									<label>Nombre de la Empresa</label>
									<input type="text" class="form-control form-control-line" name="empleador_nombre" value="<?php echo $form->empleador_nombre; ?>">
								</div>
								<div class="form-group col-sm-4">
									<label>CUIT</label>
									<input type="text" class="form-control form-control-line" name="empleador_cuit" value="<?php echo $form->empleador_cuit; ?>">
								</div>
							</div>

							<div class="form-row mb-3">
								<div class="form-group col-sm-8">
									<label>Domicilio completo</label>
									<input type="text" class="form-control form-control-line" name="empleador_domicilio" value="<?php echo $form->empleador_domicilio; ?>">
								</div>
								<div class="form-group col-sm-4">
									<label>Tel</label>
									<input type="text" class="form-control form-control-line" name="empleador_tel" value="<?php echo $form->empleador_tel; ?>">
								</div>
							</div>

							<div class="form-row mb-5">
								<div class="form-group col-sm-4">
									<label>Nombre del contacto</label>
									<input type="text" class="form-control form-control-line" name="empleador_contacto" value="<?php echo $form->empleador_contacto; ?>">
								</div>
								<div class="form-group col-sm-4">
									<label>Celular</label>
									<input type="text" class="form-control form-control-line" name="empleador_celular" value="<?php echo $form->empleador_celular; ?>">
								</div>
								<div class="form-group col-sm-4">
									<label>E-mail</label>
									<input type="email" class="form-control form-control-line" name="empleador_email" value="<?php echo $form->empleador_email; ?>">
								</div>
							</div>

							<div class="h5 font-weight-bold mb-3 bg-dark text-white p-2">DATOS DEL PRESTADOR</div>

							<div class="form-group">
								<label>Nombre del Establecimiento Asistencial</label>
								<input type="text" class="form-control form-control-line" name="prestador_nombre" value="<?php echo $form->prestador_nombre; ?>">
							</div>

							<div class="form-row mb-3">
								<div class="form-group col-sm-2">
									<label>CUIT</label>
									<input type="text" class="form-control form-control-line" name="prestador_cuit" value="<?php echo $form->prestador_cuit; ?>">
								</div>
								<div class="form-group col-sm-4">
									<label>Calle</label>
									<input type="text" class="form-control form-control-line" name="prestador_calle" value="<?php echo $form->prestador_calle; ?>">
								</div>
								<div class="form-group col-sm-1">
									<label>Nro</label>
									<input type="text" class="form-control form-control-line" name="prestador_calle_nro" value="<?php echo $form->prestador_calle_nro; ?>">
								</div>
								<div class="form-group col-sm-1">
									<label>Piso</label>
									<input type="text" class="form-control form-control-line" name="prestador_piso" value="<?php echo $form->prestador_piso; ?>">
								</div>
								<div class="form-group col-sm-1">
									<label>Depto</label>
									<input type="text" class="form-control form-control-line" name="prestador_depto" value="<?php echo $form->prestador_depto; ?>">
								</div>
								<div class="form-group col-sm-3">
									<label>Localidad</label>
									<input type="text" class="form-control form-control-line" name="prestador_localidad" value="<?php echo $form->prestador_localidad; ?>">
								</div>
							</div>

							<div class="form-row mb-3">
								<div class="form-group col-sm-4">
									<label>Provincia</label>
									<select class="form-control form-control-line" name="prestador_provincia">
										<option value="14">Mendoza</option>
										<option disabled>----</option>
										<?php foreach($this->provincias_list as $provincia): ?>
											<option value="<?php echo $provincia->id; ?>"><?php echo $provincia->nombre; ?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="form-group col-sm-2">
									<label>CP</label>
									<input type="text" class="form-control form-control-line" name="prestador_cp" value="<?php echo $form->prestador_cp; ?>">
								</div>
								<div class="form-group col-sm-3">
									<label>Tel</label>
									<input type="text" class="form-control form-control-line" name="prestador_tel" value="<?php echo $form->prestador_tel; ?>">
								</div>
								<div class="form-group col-sm-3">
									<label>Fax</label>
									<input type="text" class="form-control form-control-line" name="prestador_fax" value="<?php echo $form->prestador_fax; ?>">
								</div>
							</div>

							<div class="form-group mb-5">
								<label>E-mail</label>
								<input type="email" class="form-control form-control-line" name="prestador_email" value="<?php echo $form->prestador_email; ?>">
							</div>

							<div class="h5 font-weight-bold mb-3 bg-dark text-white p-2">DESCRIPCIÓN DEL MOTIVO DE CONSULTA</div>

							<div class="mb-5">
								<?php $options = ['Accidente de Trabajo', 'Accidente in itinere', 'Enfermedad Profesional', 'Intercurrencia', 'Sin especificar']; ?>
								<?php foreach($options as $k=>$option): ?>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="motivo_consulta" id="motivo_consulta<?php echo $k; ?>" value="<?php echo $option; ?>" <?php if($form->motivo_consulta==$option) echo 'checked'; ?>>
										<label class="form-check-label" for="motivo_consulta<?php echo $k; ?>"><?php echo $option; ?></label>
									</div>
								<?php endforeach; ?>
							</div>

							<div class="form-row mb-3">
								<div class="form-group col-sm-5">
									<label>Fecha del Accidente / Primera manifestación invalidante</label>
									<input type="date" class="form-control form-control-line" name="fecha_accidente" value="<?php echo $form->fecha_accidente ? date('Y-m-d', strtotime($form->fecha_accidente)) : ''; ?>">
								</div>
								<div class="form-group col-sm-2">
									<label>Hora</label>
									<input type="text" class="form-control form-control-line" name="hora_accidente" value="<?php echo $form->fecha_accidente ? date('H:i', strtotime($form->fecha_accidente)) : ''; ?>">
								</div>
							</div>

							<div class="form-row mb-3">
								<div class="form-group col-sm-5">
									<label>Fecha de inicio de la inasistencia laboral</label>
									<input type="date" class="form-control form-control-line" name="fecha_inasistencia" value="<?php echo $form->fecha_inasistencia ? date('Y-m-d', strtotime($form->fecha_inasistencia)) : ''; ?>">
								</div>
								<div class="form-group col-sm-2">
									<label>Hora</label>
									<input type="text" class="form-control form-control-line" name="hora_inasistencia" value="<?php echo $form->fecha_inasistencia ? date('H:i', strtotime($form->fecha_inasistencia)) : ''; ?>">
								</div>
							</div>

							<div class="form-row mb-3">
								<div class="form-group col-sm-5">
								<label>Fecha de primera atención médica</label>
									<input type="date" class="form-control form-control-line" name="fecha_primera_atencion" value="<?php echo $form->fecha_primera_atencion ? date('Y-m-d', strtotime($form->fecha_primera_atencion)) : ''; ?>">
								</div>
								<div class="form-group col-sm-2">
									<label>Hora</label>
									<input type="text" class="form-control form-control-line" name="hora_primera_atencion" value="<?php echo $form->fecha_primera_atencion ? date('H:i', strtotime($form->fecha_primera_atencion)) : ''; ?>">
								</div>
							</div>

							<div class="form-group">
								<label>Descripción del motivo de consulta</label>
								<textarea class="form-control form-control-line" name="descripcion_motivo_consulta" rows="2"><?php echo $form->descripcion_motivo_consulta; ?></textarea>
							</div>

							<div class="form-group">
								<label>Diagnóstico</label>
								<textarea class="form-control form-control-line" name="diagnostico" rows="2"><?php echo $form->diagnostico; ?></textarea>
							</div>

							<div class="form-group mb-4">
								<label>Indicaciones / Tratamiento</label>
								<textarea class="form-control form-control-line" name="indicaciones" rows="2"><?php echo $form->indicaciones; ?></textarea>
							</div>

							<div class="row mb-3">
								<div class="col-sm-6">
									<label class="d-inline mr-3">Corresponde baja laboral:</label>
									
									<?php $options = ['Si', 'No']; ?>
									<?php foreach($options as $k=>$option): ?>
										<div class="form-check form-check-inline">
											<input class="form-check-input" type="radio" name="baja_laboral" id="baja_laboral<?php echo $k; ?>" value="<?php echo $option; ?>" <?php if($form->baja_laboral==$option) echo 'checked'; ?>>
											<label class="form-check-label" for="baja_laboral<?php echo $k; ?>"><?php echo $option; ?></label>
										</div>
									<?php endforeach; ?>
								</div>
								<div class="form-group col-sm-6">
									<label>Fecha probable de alta (en caso de ser posible)</label>
									<input type="date" class="form-control form-control-line" name="fecha_probable_alta" value="<?php echo $form->fecha_probable_alta ? date('Y-m-d', strtotime($form->fecha_probable_alta)) : ''; ?>">
								</div>
							</div>

							<div class="row mb-3">
								<div class="col-sm-6">
									<div class="form-row">
										<div class="form-group col-sm-8">
											<label>Fecha de próxima revisión</label>
											<input type="date" class="form-control form-control-line" name="fecha_proxima_revision" value="<?php echo $form->fecha_proxima_revision ? date('Y-m-d', strtotime($form->fecha_proxima_revision)) : ''; ?>">
										</div>
										<div class="form-group col-sm-4">
											<label>Hora</label>
											<input type="text" class="form-control form-control-line" name="hora_proxima_revision" value="<?php echo $form->fecha_proxima_revision ? date('H:i', strtotime($form->fecha_proxima_revision)) : ''; ?>">
										</div>
									</div>
								</div>
								<div class="form-group col-sm-6">
									<label>Fecha de retorno al trabajo</label>
									<input type="date" class="form-control form-control-line" name="fecha_retorno" value="<?php echo $form->fecha_retorno ? date('Y-m-d', strtotime($form->fecha_retorno)) : ''; ?>">
								</div>
							</div>

							<div style="height: 110px"></div><!-- espaciador -->
							
							<div class="form-row text-center">
								<div class="form-group col-sm-4">
									<input type="text" class="form-control form-control-line text-center" value="Mendoza, <?php echo date('d/m/Y', strtotime($form->created_at)); ?>">
									<label>Lugar y Fecha de la Asistencia Médica</label>
								</div>
								<div class="form-group col-sm-4">
									<input type="text" class="form-control form-control-line bg-white" disabled>
									<label>Firma y Aclaración del Trabajador</label>
								</div>
								<div class="form-group col-sm-4">
									<input type="text" class="form-control form-control-line bg-white" disabled>
									<label>Firma y Sello Médico c/ N° de Matricula</label>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>

		<div class="row hidden-print">
			<div class="col-sm-12">
				<button type="submit" form="formIngreso" class="btn btn-primary btn-lg">Guardar cambios</button>
				<button type="button" class="btn btn-info btn-lg" onclick="window.print()">Imprimir Página</button>
			</div>
		</div>
	</div>

	<?php $this->load->view('footer_static'); ?>