#!/usr/bin/env bash
# Pruebas del sistema de migraciones. Variables: PHP, MY (cliente mysql), DB, WORK, ROOT.
FAILS=0; TOTAL=0
check() { TOTAL=$((TOTAL+1)); if [ "$2" = "1" ]; then echo "PASS $1"; else echo "FAIL $1  [$3]"; FAILS=$((FAILS+1)); fi; }
q() { $MY -N -B "$1" -e "$2" 2>/dev/null; }

# A) base vacía: la línea base crea el esquema y queda registrada la versión
T=$(q "$DB" "SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_SCHEMA='$DB'")
V=$(q "$DB" "SELECT version FROM migrations")
check "MIGRACIONES: en una base vacía se crean las 20 tablas + la tabla migrations" "$([ "$T" = 21 ] && echo 1 || echo 0)" "tablas=$T"
check "MIGRACIONES: queda registrada la versión 1" "$([ "$V" = 1 ] && echo 1 || echo 0)" "version=$V"

# B) idempotencia: volver a correr no cambia nada
OUT=$(CI_ENV=development "$PHP" "$WORK/index.php" migrate run 2>&1)
V2=$(q "$DB" "SELECT version FROM migrations")
check "MIGRACIONES: volver a ejecutar 'run' no cambia nada (idempotente)" "$([ "$V2" = 1 ] && echo "$OUT" | grep -q "sin cambios" && echo 1 || echo 0)" "$OUT"
OUT=$(CI_ENV=development "$PHP" "$WORK/index.php" migrate 2>&1)
check "MIGRACIONES: 'migrate' (estado) informa base al día y no modifica" "$(echo "$OUT" | grep -q "al día" && echo 1 || echo 0)" "$OUT"

# C) base que YA tiene el esquema (como producción): no se toca ningún dato ni tabla
LEG="${DB}_legacy"
$MY -e "DROP DATABASE IF EXISTS $LEG; CREATE DATABASE $LEG CHARACTER SET latin1;" 2>/dev/null
$MY "$LEG" < "$ROOT/application/migrations/sql/001_baseline.sql" 2>/dev/null
q "$LEG" "INSERT INTO config (meta_key, meta_value) VALUES ('marcador', 'no_tocar')"
sed "s#'$DB'#'$LEG'#" "$WORK/index.php" > "$WORK/index_legacy.php"
OUT=$(CI_ENV=development "$PHP" "$WORK/index_legacy.php" migrate run 2>&1)
M=$(q "$LEG" "SELECT meta_value FROM config WHERE meta_key='marcador'")
T=$(q "$LEG" "SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_SCHEMA='$LEG'")
V=$(q "$LEG" "SELECT version FROM migrations")
check "MIGRACIONES: en una base con esquema previo NO se modifica nada (dato intacto)" "$([ "$M" = no_tocar ] && echo 1 || echo 0)" "marcador=$M"
check "MIGRACIONES: ... pero se registra la versión 1 y sólo se agrega la tabla migrations" "$([ "$V" = 1 ] && [ "$T" = 21 ] && echo "$OUT" | grep -q "ya tiene el esquema" && echo 1 || echo 0)" "v=$V tablas=$T $OUT"
$MY -e "DROP DATABASE IF EXISTS $LEG;" 2>/dev/null

# C2) una migración nueva (temporal): pendiente -> run -> up() aplicado -> version 1 -> down() revierte
MIG="$ROOT/application/migrations/002_prueba_temporal.php"
trap 'rm -f "$MIG"' EXIT
cat > "$MIG" <<'PHPM'
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Prueba_temporal extends CI_Migration {
	public function up()   { $this->db->query("ALTER TABLE config ADD COLUMN _mig_prueba INT NULL"); }
	public function down() { $this->db->query("ALTER TABLE config DROP COLUMN _mig_prueba"); }
}
PHPM
OUT=$(CI_ENV=development "$PHP" "$WORK/index.php" migrate 2>&1)
check "MIGRACIONES: el estado muestra la migración nueva como PENDIENTE" "$(echo "$OUT" | grep -q "PENDIENTE.*002_prueba_temporal" && echo 1 || echo 0)" "$OUT"
COL=$(q "$DB" "SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA='$DB' AND TABLE_NAME='config' AND COLUMN_NAME='_mig_prueba'")
check "MIGRACIONES: consultar el estado NO aplica nada" "$([ "$COL" = 0 ] && echo 1 || echo 0)" "col=$COL"
CI_ENV=development "$PHP" "$WORK/index.php" migrate run >/dev/null 2>&1
COL=$(q "$DB" "SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA='$DB' AND TABLE_NAME='config' AND COLUMN_NAME='_mig_prueba'")
V=$(q "$DB" "SELECT version FROM migrations")
check "MIGRACIONES: 'run' aplica la migración nueva (columna creada, versión 2)" "$([ "$COL" = 1 ] && [ "$V" = 2 ] && echo 1 || echo 0)" "col=$COL v=$V"
CI_ENV=development "$PHP" "$WORK/index.php" migrate version 1 >/dev/null 2>&1
COL=$(q "$DB" "SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA='$DB' AND TABLE_NAME='config' AND COLUMN_NAME='_mig_prueba'")
V=$(q "$DB" "SELECT version FROM migrations")
check "MIGRACIONES: 'version 1' ejecuta down() (columna eliminada, versión 1)" "$([ "$COL" = 0 ] && [ "$V" = 1 ] && echo 1 || echo 0)" "col=$COL v=$V"
rm -f "$MIG"; trap - EXIT

# D) protecciones
OUT=$(env -u CI_ENV "$PHP" "$WORK/index.php" migrate 2>&1); RCX=$?
check "MIGRACIONES: por CLI sin CI_ENV se rechaza (no se opera contra una base sin saberlo)" "$([ $RCX -ne 0 ] && echo "$OUT" | grep -q CI_ENV && echo 1 || echo 0)" "rc=$RCX"

echo "Migraciones: $((TOTAL-FAILS)) ok, $FAILS fallas"
[ $FAILS -eq 0 ]
