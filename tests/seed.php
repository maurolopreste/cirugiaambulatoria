<?php
// Genera el SQL de datos de prueba (ids fijos). Uso: php seed.php > seed.sql
$h = password_hash('Test1234!', PASSWORD_DEFAULT);
$x = "<script>alert(1)</script>\"'";
$u = function($id,$g,$fn,$ln,$em) use ($h) {
  return "($id,$g,'$fn','$ln','$em','$h','1',1,1,'2024-01-01 00:00:00')";
};
echo "INSERT INTO `group` (id,name) VALUES (1,'Administrador'),(2,'Profesional'),(3,'Paciente');\n";
echo "INSERT INTO config (meta_key,meta_value) VALUES ('site_title','Test'),('primary_color','#336699'),('admin_email','admin@example.test'),('telefono','000'),('logo','');\n";
echo "INSERT INTO `user` (id,group_id,first_name,last_name,email,password,dni,active,tyc,created_at) VALUES\n";
echo implode(",\n", [
  $u(1,1,'Ada','Admin','admin@t.test'),
  $u(2,2,'Pablo','Prof1','prof1@t.test'),
  $u(3,2,'Paula','Prof2','prof2@t.test'),
  $u(4,3,'Pedro','Pac1','pac1@t.test'),
  $u(5,3,'Pilar','Pac2','pac2@t.test'),
  "(6,3,'" . addslashes($x) . "','Xss','xss@t.test','$h','2',1,1,'2024-01-01 00:00:00')",
]) . ";\n";
$now = date('Y-m-d H:i:s'); $fut = date('Y-m-d 10:00:00', strtotime('+3 days'));
echo "INSERT INTO valor_consulta (id,nombre,valor) VALUES (1,'Consulta',1000),(2,'Control',500);\n";
echo "INSERT INTO profesional_valor (profesional_id,valor_consulta) VALUES (2,1);\n";
echo "INSERT INTO dias_habilitados (profesional_id,daysOfWeekDisabled,hora_desde,hora_hasta) VALUES (2,'0,6','9:00','18:00');\n";
echo "INSERT INTO agenda (id,paciente_id,profesional_id,fecha,valor_consulta,estado_cita,comentarios,created_at) VALUES\n";
echo "(1,4,2,'$fut',1,'1','orig','$now'),(2,5,3,'$fut',1,'1','orig','$now'),(3,6,2,'$fut',1,'1','orig','$now');\n";
echo "INSERT INTO prescripcion (id,paciente_id,profesional_id,fecha,comentarios,created_at) VALUES (1,4,2,'$now','p1','$now'),(2,5,3,'$now','p2','$now');\n";
echo "INSERT INTO historiaclinica (id,paciente_id,profesional_id,created_at) VALUES (1,5,3,'$now');\n";
echo "INSERT INTO evolutivo (id,paciente_id,profesional_id,fecha,informe,created_at) VALUES (1,5,3,'$now','<img src=x onerror=alert(1)>\\nlinea2','$now'),(2,5,3,'$now','ok','$now');\n";
