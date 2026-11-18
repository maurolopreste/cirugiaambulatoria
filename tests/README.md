# Tests de seguridad

Requisitos: MySQL local (root/root), PHP 7.4 de Herd, python3 y node. No toca `cirugiaambulatoria_ci`: usa una base aparte (`cirugiaambulatoria_test`).

- `./tests/run.sh` — recrea la base de pruebas, levanta la app con PHP 7.4 en `127.0.0.1:8089` y corre todo.
- `SERVE=1 ./tests/run.sh` — deja la app corriendo para probarla a mano (usuarios `admin@t.test`, `prof1@t.test`, `prof2@t.test`, `pac1@t.test`, `pac2@t.test`, `xss@t.test`; clave `Test1234!`).

Archivos: `schema.sql` (estructura clonada de la base local, sin datos), `seed.php` (datos de prueba), `test_security.py` (HTTP), `test_csrf_prefilter.js` (JS), `fixtures/` (stubs del módulo de prescripciones, que no está en el repo; se copian sólo si faltan y se borran al terminar).
