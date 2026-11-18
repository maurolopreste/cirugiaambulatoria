#!/usr/bin/env bash
# Pruebas automatizadas de seguridad (autorización, CSRF/cookies, XSS).
# Usa una base APARTE (cirugiaambulatoria_test) y NO toca cirugiaambulatoria_ci.
# Requisitos: MySQL local (root/root), PHP 7.4 (Herd), python3, node.
set -euo pipefail
ROOT="$(cd "$(dirname "$0")/.." && pwd)"
T="$ROOT/tests"
PHP="${PHP74:-$HOME/Library/Application Support/Herd/bin/php74}"
MYSQL="${MYSQL_BIN:-/usr/local/mysql/bin}"
PORT="${PORT:-8089}"
WORK="$(mktemp -d)"            # docroot temporal de pruebas (index.php generado + uploads)
DB=cirugiaambulatoria_test
export MYSQL_PWD=root
MY="$MYSQL/mysql -uroot"
ADDED=()

cleanup() {
  [ -n "${CLEANED:-}" ] && return; CLEANED=1
  # lang() agrega al vuelo las traducciones faltantes en application/language: se restauran
  [ -d "$WORK/lang_backup" ] && cp -R "$WORK/lang_backup/." "$ROOT/application/language/"
  [ -n "${SERVER_PID:-}" ] && kill "$SERVER_PID" 2>/dev/null || true
  for f in "${ADDED[@]:-}"; do [ -n "$f" ] && rm -f "$ROOT/$f"; done
  for f in "${ADDED[@]:-}"; do [ -n "$f" ] && rmdir -p "$(dirname "$ROOT/$f")" 2>/dev/null || true; done
  rm -rf "$WORK"
}
trap cleanup EXIT
trap 'exit 143' INT TERM

cp -R "$ROOT/application/language" "$WORK/lang_backup"

# 1) Base de pruebas: estructura clonada + datos de ejemplo
$MY -e "DROP DATABASE IF EXISTS $DB; CREATE DATABASE $DB CHARACTER SET latin1;"
$MY "$DB" < "$T/schema.sql"
"$PHP" "$T/seed.php" | $MY "$DB"

# 2) Stubs de lo que falta en el repo (modelo/vistas de prescripciones): sólo si no existen
( cd "$T/fixtures" && find application -type f ) | while read -r f; do
  [ -e "$ROOT/$f" ] || echo "$f"
done > "$WORK/added.txt"
while read -r f; do
  [ -z "$f" ] && continue
  mkdir -p "$(dirname "$ROOT/$f")"; cp "$T/fixtures/$f" "$ROOT/$f"; ADDED+=("$f")
done < "$WORK/added.txt"

# 3) index.php de pruebas: misma app, otra base y otra URL
mkdir -p "$WORK/uploads"
sed -e "s#define('BASE_URL', 'http://localhost/cirugiaambulatoria');#define('BASE_URL', 'http://127.0.0.1:$PORT');#" \
    -e "s#'cirugiaambulatoria_ci'#'$DB'#" \
    -e "s#\$system_path = 'system';#\$system_path = '$ROOT/system';#" \
    -e "s#\$application_folder = 'application';#\$application_folder = '$ROOT/application';#" \
    "$ROOT/index.php" > "$WORK/index.php"
ln -s "$ROOT/assets" "$WORK/assets"; ln -s "$ROOT/favicon.png" "$WORK/favicon.png"
cat > "$WORK/router.php" <<'PHPR'
<?php
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if ($path !== '/' && is_file(__DIR__ . $path) && $path !== '/index.php' && $path !== '/router.php') return false; // estáticos
$_SERVER['SCRIPT_NAME'] = '/index.php';
require __DIR__ . '/index.php';
PHPR

# 4) Servidor (PHP 7.4, como producción)
"$PHP" -S 127.0.0.1:$PORT -t "$WORK" "$WORK/router.php" > "$WORK/server.log" 2>&1 &
SERVER_PID=$!
for i in $(seq 1 30); do curl -s -o /dev/null "http://127.0.0.1:$PORT/login" && break; sleep 0.3; done

# 4b) Modo manual: SERVE=1 ./tests/run.sh  -> deja la app corriendo para probarla en el navegador
if [ "${SERVE:-}" = "1" ]; then
  echo; echo "App de prueba en  http://127.0.0.1:$PORT   (Ctrl+C para detener)"
  echo "Usuarios (clave Test1234!):  admin@t.test | prof1@t.test | prof2@t.test | pac1@t.test | pac2@t.test | xss@t.test"
  wait "$SERVER_PID"; exit 0
fi

# 5) Pruebas
RC=0
BASE="http://127.0.0.1:$PORT" MYSQL_CLI="$MYSQL/mysql" DB=$DB ROOT="$ROOT" python3 "$T/test_security.py" || RC=$?
node "$T/test_csrf_prefilter.js" || RC=$?
cp "$WORK/server.log" "${SERVER_LOG_OUT:-/dev/null}" 2>/dev/null || true
exit $RC
