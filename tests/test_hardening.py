#!/usr/bin/env python3
"""Pruebas de endurecimiento: login, tokens de recuperación, sesión, contraseñas, uploads, secretos y entorno."""
from common import *
import hashlib, glob as _glob

def flash_text(html):
    m = re.search(r'role="alert">(.*?)</div>', html, re.S)
    return re.sub(r'<[^>]+>|\s+', ' ', m.group(1)).strip() if m else ''
def clear_rate():
    for f in _glob.glob(os.path.join(TMPD, 'ci_rate_*')): os.remove(f)
def mk_token(ttl=3600):
    return format(int(time.time()) + ttl, '08x') + os.urandom(16).hex()
def set_token(uid, token, stored=None):
    sql(f"update user set rp_token='{stored if stored is not None else hashlib.sha256(token.encode()).hexdigest()}' where id={uid}")
def fresh():
    c = Client(); c.get('/login'); return c

# ------------------------------------------------------------------ LOGIN
clear_rate()
c1 = fresh(); c1.post('/login', {'email': USERS['pac1'], 'password': 'mal'}); m1 = flash_text(c1.get('/login')[2])
c2 = fresh(); c2.post('/login', {'email': 'noexiste@t.test', 'password': 'mal'}); m2 = flash_text(c2.get('/login')[2])
check('LOGIN: contraseña incorrecta y usuario inexistente dan el mismo mensaje', m1 and m1 == m2, (m1, m2))

for ref, name in [('//evil.example', '//host'), ('http://evil.example/x', 'URL absoluta'), ('javascript:alert(1)', 'javascript:'), ('../../etc', '..')]:
    c = fresh(); s, h, b = c.post('/login?referrer=' + urllib.parse.quote(ref, safe=''), {'email': USERS['pac1'], 'password': PASS})
    loc = h.get('Location', '') + h.get('Refresh', '')
    check(f'LOGIN: referrer malicioso ({name}) no redirige afuera', 'evil' not in loc and 'javascript' not in loc and '/dashboard' in loc, (s, loc))
c = fresh(); s, h, b = c.post('/login?referrer=cita', {'email': USERS['pac1'], 'password': PASS})
check('LOGIN: referrer interno legítimo sí se respeta', 'cita' in (h.get('Location', '') + h.get('Refresh', '')), (s, dict(h)))

c = fresh(); sid0 = c.cookie('ci_session'); c.post('/login', {'email': USERS['pac1'], 'password': PASS}); c.get('/dashboard')
check('SESIÓN: el ID de sesión cambia al iniciar sesión (anti fijación)', sid0 and c.cookie('ci_session') and sid0 != c.cookie('ci_session'))
sfs = _glob.glob(os.path.join(TMPD, 'ci_session*' + c.cookie('ci_session'))); data = open(sfs[0], 'rb').read() if sfs else b''   # con sess_match_ip el nombre lleva md5(ip) antes del ID
check('SESIÓN: la sesión guarda al usuario pero sin hash de contraseña ni token', b'pac1@t.test' in data and b'$2y$' not in data and b'rp_token' not in data, data[:120])
c.get('/logout'); s, h, b = c.get('/dashboard')
check('SESIÓN: /logout destruye la sesión', s in (302, 303, 307) and 'login' in h.get('Location', ''), (s, h.get('Location')))

# baja y cambio de rol se aplican en la misma sesión
c = login('prof2'); sql('update user set active=NULL where id=3'); s, h, b = c.get('/dashboard'); sql('update user set active=1 where id=3')
check('SESIÓN: un usuario desactivado pierde el acceso en el acto', s in (302, 303, 307) and 'login' in h.get('Location', ''), (s, h.get('Location')))
c = login('prof1'); sql('update user set group_id=3 where id=2'); r = c.get('/agenda'); sql('update user set group_id=2 where id=2')
check('SESIÓN: un cambio de rol se aplica sin volver a iniciar sesión', denied(r), r[0])
c = login('prof1'); sql('update user set deleted_at=now() where id=2'); s, h, b = c.get('/dashboard'); sql('update user set deleted_at=NULL where id=2')
check('SESIÓN: un usuario eliminado pierde el acceso en el acto', s in (302, 303, 307) and 'login' in h.get('Location', ''), (s, h.get('Location')))

# fuerza bruta (se limpia el contador al final para no afectar el resto)
clear_rate(); c = fresh()
for _ in range(5): c.post('/login', {'email': USERS['pac2'], 'password': 'incorrecta'})
c.post('/login', {'email': USERS['pac2'], 'password': PASS}); msg = flash_text(c.get('/login')[2]); s, h, b = c.get('/dashboard')
check('FUERZA BRUTA: tras 5 fallos, ni la clave correcta entra durante la ventana', s in (302, 303, 307) and 'login' in h.get('Location', ''), (s, h.get('Location')))
check('FUERZA BRUTA: se informa el bloqueo al usuario', 'intentos' in msg.lower(), msg)
clear_rate()
c = fresh(); c.post('/login', {'email': USERS['pac2'], 'password': PASS}); check('FUERZA BRUTA: al limpiar el contador vuelve a funcionar', c.get('/dashboard')[0] == 200)

# ------------------------------------------------------------------ TOKENS DE RECUPERACIÓN
clear_rate(); c = fresh()
c.post('/login/forgotpassword', {'email': USERS['pac1']}); a = flash_text(c.get('/login')[2])
tok_len = sql('select length(rp_token) from user where id=4')
c.post('/login/forgotpassword', {'email': 'noexiste@t.test'}); b_ = flash_text(c.get('/login')[2])
check('TOKEN: "olvidé mi clave" responde igual exista o no el email', a and a == b_, (a, b_))
check('TOKEN: en la base se guarda el hash SHA-256 (64 hex), no el token', tok_len == '64', tok_len)

orig = sql('select password from user where id=5')
t = mk_token(); set_token(5, t); c = fresh()
check('TOKEN: token válido muestra el formulario', c.get('/login/changepassword/' + t)[0] == 200)
t_exp = mk_token(-10); set_token(5, t_exp); s, h, b = c.get('/login/changepassword/' + t_exp)
check('TOKEN: token vencido es rechazado', s in (302, 303, 307) and 'login' in h.get('Location', ''), s)
t = mk_token(); set_token(5, t); forged = format(int(time.time()) + 10**6, '08x') + t[8:]
check('TOKEN: no se puede extender el vencimiento alterando el token', c.get('/login/changepassword/' + forged)[0] in (302, 303, 307))
t = mk_token(); set_token(5, t, stored=t)   # token en claro guardado (formato viejo / filtración de la tabla)
check('TOKEN: el hash guardado no sirve como token', c.get('/login/changepassword/' + hashlib.sha256(t.encode()).hexdigest()[:40])[0] in (302, 303, 307))
check('TOKEN: un token distinto de 40 hex es rechazado', c.get('/login/changepassword/abc')[0] in (302, 303, 307))
t = mk_token(); set_token(5, t)
c.post('/login/changepassword/' + t, {'email': USERS['pac5'] if 'pac5' in USERS else USERS['pac2'], 'password': 'corta', 'password_confirmation': 'corta'})
check('CLAVE: contraseña de menos de 8 caracteres rechazada', sql('select password from user where id=5') == orig)
c.post('/login/changepassword/' + t, {'email': USERS['pac2'], 'password': 'NuevaClave2026', 'password_confirmation': 'NuevaClave2026'})
check('CLAVE: contraseña válida se guarda y el token se consume', sql('select password from user where id=5') != orig and sql('select rp_token from user where id=5') == 'NULL')
check('TOKEN: reusar el token ya consumido falla', c.get('/login/changepassword/' + t)[0] in (302, 303, 307))
c2 = fresh(); c2.post('/login', {'email': USERS['pac2'], 'password': 'NuevaClave2026'})
check('CLAVE: se puede entrar con la contraseña nueva', c2.get('/dashboard')[0] == 200)
sql(f"update user set password='{orig}' where id=5")

n0 = sql('select count(*) from user'); c = fresh()
c.post('/register', {'first_name': 'A', 'last_name': 'B', 'email': 'nuevo@t.test', 'password': 'corta'})
c.post('/register', {'first_name': 'A', 'last_name': 'B', 'email': 'no-es-email', 'password': 'LargaYValida1'})
check('REGISTRO: rechaza contraseña corta y email inválido', sql('select count(*) from user') == n0)

# ------------------------------------------------------------------ PERFIL: cambio de contraseña
base = {'first_name': 'Pedro', 'last_name': 'Pac1', 'dni': '1', 'telefono': '1'}
h0 = sql('select password from user where id=4'); p = login('pac1')
p.post('/perfil', {**base, 'password': 'OtraClave2026'})
check('PERFIL: no cambia la contraseña sin indicar la actual', sql('select password from user where id=4') == h0)
p.post('/perfil', {**base, 'password': 'OtraClave2026', 'current_password': 'incorrecta'})
check('PERFIL: no cambia la contraseña con la actual incorrecta', sql('select password from user where id=4') == h0)
p.post('/perfil', {**base, 'password': 'corta', 'current_password': PASS})
check('PERFIL: no acepta una nueva de menos de 8 caracteres', sql('select password from user where id=4') == h0)
p.post('/perfil', {**base, 'password': 'OtraClave2026', 'current_password': PASS})
check('PERFIL: con la actual correcta y una nueva válida, cambia', sql('select password from user where id=4') != h0)
sql(f"update user set password='{h0}' where id=4")

# ------------------------------------------------------------------ UPLOADS
pdf = b'%PDF-1.4\n1 0 obj<<>>endobj\ntrailer<<>>\n%%EOF\n'
for name in ('abc123.pdf', 'Informe Pérez.pdf', 'huerfano.pdf'): open(os.path.join(UPLOADS, name), 'wb').write(pdf)
sql("update historiaclinica set file='abc123.pdf' where id=1")
sql("delete from evolutivo where id=10"); sql("insert into evolutivo (id,paciente_id,profesional_id,fecha,informe,file,created_at) values (10,5,3,now(),'x','Informe Pérez.pdf',now())")
anon = Client(); a_ = login('admin'); p1 = login('pac1'); p2 = login('pac2')
check('UPLOADS: acceso directo a /uploads/ bloqueado', anon.get('/uploads/abc123.pdf')[0] == 403)
check('UPLOADS: uploads/.htaccess niega todo', 'Require all denied' in open(ROOT + '/uploads/.htaccess').read())
r = anon.get('/archivo/ver/abc123.pdf'); check('UPLOADS: sin sesión no se entrega el archivo', denied(r) and '%PDF' not in r[2])
r = p1.get('/archivo/ver/abc123.pdf'); check('UPLOADS: otro paciente no puede ver el archivo', denied(r) and '%PDF' not in r[2], r[0])
r = p2.get('/archivo/ver/abc123.pdf'); check('UPLOADS: el paciente dueño sí lo ve (PDF, nosniff, CSP)', r[0] == 200 and r[1].get('Content-Type') == 'application/pdf' and r[1].get('X-Content-Type-Options') == 'nosniff' and 'sandbox' in r[1].get('Content-Security-Policy', ''), (r[0], r[1].get('Content-Type')))
check('UPLOADS: el admin lo ve', a_.get('/archivo/ver/abc123.pdf')[0] == 200)
check('UPLOADS: un profesional que NO es del paciente no lo ve', denied(login('prof1').get('/archivo/ver/abc123.pdf')))
check('UPLOADS: el profesional del paciente sí lo ve', login('prof2').get('/archivo/ver/abc123.pdf')[0] == 200)
r = a_.get('/archivo/ver/' + urllib.parse.quote('Informe Pérez.pdf')); check('UPLOADS: nombres con espacios y acentos (archivos viejos) funcionan', r[0] == 200, r[0])
check('UPLOADS: archivo en disco que no pertenece a ningún registro → 404', a_.get('/archivo/ver/huerfano.pdf')[0] == 404)
check('UPLOADS: path traversal → 404', all(a_.get('/archivo/ver/' + t)[0] in (404, 403, 400) for t in ('..%2F..%2Findex.php', '..%2f..%2fapplication%2fconfig%2fconfig.php', '%2e%2e%2findex.php')))
s, h, b = a_.get('/paciente/edit_historiaclinica/1/5', ajax=True)
check('UPLOADS: las vistas enlazan /archivo/ver/ y no /uploads/', 'archivo/ver/abc123.pdf' in b and '/uploads/' not in b)

hc = {k: 'x' for k in ('fecha_accidente', 'mecanismo', 'antecedentes', 'examen_fisico', 'antecedentes_patologicos', 'estudios_solicitados', 'tratamiento')}
hc['fecha_accidente'] = '2026-01-01'
a_.post_file('/paciente/add_historiaclinica/5', hc, 'mi informe.pdf', pdf, ctype='application/pdf')
nombre = sql('select file from historiaclinica order by id desc limit 1')
check('UPLOADS: el PDF subido se guarda con nombre aleatorio', re.fullmatch(r'[0-9a-f]{32}\.pdf', nombre) is not None, nombre)
n_h = sql('select count(*) from historiaclinica')
a_.post_file('/paciente/add_historiaclinica/5', hc, 'shell.php', b'<?php system($_GET["c"]); ?>', ctype='application/x-php')
nombre2 = sql('select ifnull(file,"") from historiaclinica order by id desc limit 1')
check('UPLOADS: un .php subido NO se guarda', not nombre2.endswith('.php') and not any(f.endswith('.php') for f in os.listdir(UPLOADS)), nombre2)
a_.post_file('/paciente/add_historiaclinica/5', hc, 'foto.png', b'<?php echo 1; ?>', ctype='image/png')
check('UPLOADS: un .php disfrazado de imagen NO se guarda', not any(open(os.path.join(UPLOADS, f), 'rb').read().startswith(b'<?php') for f in os.listdir(UPLOADS)))

open(os.path.join(UPLOADS, 'logo.png'), 'wb').write(b'\x89PNG\r\n\x1a\n' + b'0' * 20)
sql("update config set meta_value='logo.png' where meta_key='logo'")
r = Client().get('/archivo/logo'); check('LOGO: se entrega sin sesión (login) como imagen', r[0] == 200 and r[1].get('Content-Type') == 'image/png', r[0])
check('LOGO: el login usa /archivo/logo y no /uploads/', 'archivo/logo' in Client().get('/login')[2] and '/uploads/' not in Client().get('/login')[2])

# ------------------------------------------------------------------ BUGS CORREGIDOS
n = sql("select count(*) from user where group_id=3")
a_.post('/agenda/add', {'nuevopaciente[first_name]': 'Nuevo', 'nuevopaciente[last_name]': 'Paciente', 'nuevopaciente[email]': '', 'nuevopaciente[telefono]': '1', 'nuevopaciente[obra_social]': '', 'profesional_id': '2', 'fecha': '2030-01-01', 'hora': '10', 'hora_min': '00', 'comentarios': 'x'})
check('AGENDA: el "nuevo paciente" se crea como paciente (grupo 3), no como profesional', sql("select group_id from user where first_name='Nuevo' and last_name='Paciente' order by id desc limit 1") == '3')

# ------------------------------------------------------------------ SECRETOS Y ENTORNO
out = subprocess.run(['git', '-C', ROOT, 'grep', '--untracked', '-l', '-E', 'la64DUlofo|45Ops119|APP_USR-[0-9a-f]|c624024565957847', '--', '.', ':!tests'], capture_output=True, text=True).stdout.split()
check('SECRETOS: no quedan credenciales conocidas en el código actual', not out, out)
check('SECRETOS: secrets.php está ignorado por git', subprocess.run(['git', '-C', ROOT, 'check-ignore', '-q', 'application/config/secrets.php']).returncode == 0)
def raw(path, headers=None):
    op = urllib.request.build_opener(NoRedirect)
    try: r = op.open(urllib.request.Request(BASE + path, headers=headers or {})); return r.status, r.headers, r.read().decode('utf-8', 'replace')
    except urllib.error.HTTPError as e: return e.code, e.headers, e.read().decode('utf-8', 'replace')

PROD = 'https://gestion.cirugiaambulatoria.com.ar'
# producción por HTTP → redirección 301 a la URL canónica (nunca al Host recibido)
st, hd, body = raw('/login?x=1', {'Host': 'gestion.example.com'})
check('HTTPS: en producción, HTTP redirige (301) a la URL https canónica', st == 301 and hd.get('Location') == PROD + '/login?x=1', (st, hd.get('Location')))
st, hd, body = raw('//evil.example/x', {'Host': 'evil.example'})
check('HTTPS: la redirección no puede apuntar a otro dominio (open redirect)', st == 301 and hd.get('Location', '').startswith(PROD + '/'), hd.get('Location'))
check('HTTPS: el redirect no filtra errores ni detalles', not re.search(r'Severity|Warning|Notice|Deprecated|Database Error|Fatal error|cirugiaambulatoria_test', body))
# detrás de un proxy/CDN que ya terminó el TLS: no hay redirección y se envía HSTS
st, hd, body = raw('/login', {'Host': 'gestion.example.com', 'X-Forwarded-Proto': 'https'})
check('HTTPS: detrás de proxy HTTPS no redirige (evita bucles)', st != 301, st)
check('HTTPS: se envía Strict-Transport-Security (HSTS)', 'max-age=' in hd.get('Strict-Transport-Security', ''), hd.get('Strict-Transport-Security'))
check('ENTORNO: un host público se trata como PRODUCCIÓN (sin errores ni detalles de la base)', not re.search(r'Severity|Warning|Notice|Deprecated|Database Error|Fatal error|cirugiaambulatoria_test|root', body), (st, body[:150]))
# desarrollo: HTTP sin redirección ni HSTS
st, hd, body = raw('/login')
check('HTTPS: en desarrollo (localhost) no se fuerza HTTPS ni se envía HSTS', st == 200 and not hd.get('Strict-Transport-Security'), (st, hd.get('Strict-Transport-Security')))

# ------------------------------------------------------------------ SAMESITE y Secure
st, hd, body = raw('/login')
ck = hd.get_all('Set-Cookie') or []
ses = [x for x in ck if x.startswith('ci_session')]; csrf = [x for x in ck if x.startswith('csrf_cookie_name')]
check('SAMESITE: la cookie de sesión lleva SameSite=Lax', ses and all('samesite=lax' in x.lower() for x in ses), ses)
check('SAMESITE: la cookie CSRF lleva SameSite=Lax', csrf and all('samesite=lax' in x.lower() for x in csrf), csrf)
st, hd, body = raw('/login', {'X-Forwarded-Proto': 'https'})
ck = hd.get_all('Set-Cookie') or []
check('SECURE: con HTTPS (también detrás de proxy) las cookies llevan Secure', len(ck) >= 2 and all('secure' in x.lower() for x in ck), ck)

summary()
