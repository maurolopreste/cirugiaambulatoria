<?php $this->load->view('email/layout_top'); ?>

<h2>Se ha registrado una nueva cita en la agenda online.</h2>

<p>Fecha: <?php echo $agenda->fecha; ?><br>
Comentario: <?php echo $agenda->comentarios; ?></p>

<p>A continuación los datos del paciente:</p>

<p>Nombre: <?php echo $paciente->nombre_completo; ?><br>
Teléfono: <?php echo $paciente->telefono; ?><br>
Email: <?php echo $paciente->email; ?></p>

<?php $this->load->view('email/layout_bottom'); ?>