#!/usr/bin/env python3
"""Pruebas de integración HTTP: autorización, CSRF/cookies y XSS. Ver run.sh."""
from common import *

# ---------------------------------------------------------------- cookies y CSRF
c = Client(); s, h, body = c.get('/login')
cookies = h.get_all('Set-Cookie') or []
sess0 = [x for x in cookies if x.startswith('ci_session')]
check('CSRF: la cookie csrf es HttpOnly', any(x.startswith('csrf_cookie_name') and 'httponly' in x.lower() for x in cookies), cookies)
check('CSRF: /login incluye <meta name="csrf-token">', re.search(r'<meta name="csrf-token" content="[0-9a-f]{32}"', body))
check('CSRF: formulario de login trae campo oculto csrf_test_name', 'name="csrf_test_name"' in body)
s, h, b = c.post('/login', {'email': USERS['admin'], 'password': PASS}, token=False)
check('CSRF: POST /login SIN token es rechazado (403)', s == 403, s)
s, h, b = c.post('/login', {'email': USERS['admin'], 'password': PASS})
check('CSRF: POST /login CON token inicia sesión (redirige a /dashboard)', s in (302, 303, 307) and '/dashboard' in h.get('Location', ''), (s, h.get('Location')))
sess = sess0
check('Cookies: ci_session es HttpOnly', sess and all('httponly' in x.lower() for x in sess), sess)
# (cookie_secure es sólo-HTTPS: en http://127.0.0.1 debe quedar sin Secure)
check('Cookies: sin flag Secure en HTTP local (condicional a HTTPS)', sess and not any('secure' in x.lower() for x in sess), sess)
s, h, body = c.get('/dashboard')
m = re.search(r'<meta name="csrf-token" content="([0-9a-f]+)"', body)
check('CSRF: el meta token coincide con la cookie', m and m.group(1) == c.cookie('csrf_cookie_name'))

# ---------------------------------------------------------------- CSRF en acciones AJAX (admin)
adm = login('admin')
s, h, b = adm.post('/paciente/delete_evolutivo/2/5', {}, ajax=True, token=False)
check('CSRF: POST AJAX sin token es rechazado y NO borra', s == 403 and sql('select count(*) from evolutivo where id=2') == '1', s)
s, h, b = adm.post('/paciente/delete_evolutivo/2/5', {}, ajax=True)
check('CSRF: POST AJAX con token (como hace main.js) funciona', s == 200 and b.strip() == 'ok' and sql('select count(*) from evolutivo where id=2') == '0', (s, b[:60]))
s, h, b = adm.get('/profesional/index_dias_habilitados/2', ajax=True)
check('CSRF: formulario <form> escrito a mano (días habilitados) trae el token', b.count('name="csrf_test_name"') >= 1 and 'class="formDiaHorario"' in b)

# ---------------------------------------------------------------- AUTORIZACIÓN: paciente (pac1 = id 4)
p = login('pac1')
check('AUTZ paciente: /paciente denegado', denied(p.get('/paciente')))
r = p.get('/paciente/edit/5'); check('AUTZ paciente: /paciente/edit/<otro> denegado y sin datos', denied(r) and 'Pac2' not in r[2])
r = p.post('/paciente/send_historiaclinica/1', {'email': 'x@x.test', 'nombre': 'x'})
check('AUTZ paciente: NO puede enviar historia clínica ajena por email', denied(r), (r[0], r[1].get('Location')))
check('AUTZ paciente: /agenda denegado', denied(p.get('/agenda')))
check('AUTZ paciente: /agenda/edit/2 denegado', denied(p.get('/agenda/edit/2')))
r = p.post('/cita/edit/2', {'estado_cita': '3', 'observacion_profesional': 'hack'})
check('AUTZ paciente: no puede modificar turno ajeno (/cita/edit)', denied(r) and sql('select estado_cita from agenda where id=2') == '1')
r = p.post('/cita/edit/1', {'estado_cita': '3', 'observacion_profesional': 'hack'})
check('AUTZ paciente: no puede modificar ni su propio turno (estado/observación)', denied(r) and sql('select estado_cita from agenda where id=1') == '1')
check('AUTZ paciente: link de pago de turno ajeno denegado', denied(p.get('/cita/generar_link_pago/2')))
check('AUTZ paciente: /prescripcion/view/<ajena> denegado', denied(p.get('/prescripcion/view/2')))
r = p.get('/prescripcion/view/1'); check('AUTZ paciente: /prescripcion/view/<propia> permitido', r[0] == 200 and 'STUB' in r[2], r[0])
check('AUTZ paciente: /payment/success de turno ajeno denegado', denied(p.get('/payment/success?external_reference=2')))
r = p.get('/payment/success?external_reference=1'); check('AUTZ paciente: /payment/success de turno propio permitido', r[0] == 200, r[0])
before = sql('select count(*) from prescripcion')
r = p.post('/prescripcion/add/4', {'fecha': '2030-01-01', 'comentarios': 'x'})
check('AUTZ paciente: no puede crear prescripciones', denied(r) and sql('select count(*) from prescripcion') == before)
r = p.post('/paciente/delete_evolutivo/1/5', {}, ajax=True)
check('AUTZ paciente: no puede borrar evolutivos', r[0] == 403 and sql('select count(*) from evolutivo where id=1') == '1', r[0])

# validación de agendar_cita (sólo casos inválidos: el válido llamaría a MercadoPago/email)
n0 = sql('select count(*) from agenda'); fut = (__import__('datetime').date.today() + __import__('datetime').timedelta(days=5)).isoformat()
cases = {'profesional inexistente': {'profesional_id': '999', 'valor_consulta': '1', 'fecha': fut, 'hora': '10'},
         'profesional es un admin': {'profesional_id': '1', 'valor_consulta': '1', 'fecha': fut, 'hora': '10'},
         'valor de consulta ajeno al profesional': {'profesional_id': '2', 'valor_consulta': '2', 'fecha': fut, 'hora': '10'},
         'fecha en el pasado': {'profesional_id': '2', 'valor_consulta': '1', 'fecha': '2020-01-01', 'hora': '10'},
         'fecha con formato inválido': {'profesional_id': '2', 'valor_consulta': '1', 'fecha': "x'; DROP", 'hora': '10'},
         'hora inválida': {'profesional_id': '2', 'valor_consulta': '1', 'fecha': fut, 'hora': 'abc'}}
for name, d in cases.items():
    r = p.post('/dashboard/agendar_cita', d)
    check(f'VALID agendar_cita rechaza: {name}', sql('select count(*) from agenda') == n0 and r[0] in (302, 303, 307, 403), (r[0], sql('select count(*) from agenda'), n0))

# ---------------------------------------------------------------- AUTORIZACIÓN: profesional 1 (id 2)
f = login('prof1')
check('AUTZ profesional: sigue viendo /paciente (decisión de negocio pendiente)', f.get('/paciente')[0] == 200)
check('AUTZ profesional: /agenda/edit/<turno de otro profesional> denegado', denied(f.get('/agenda/edit/2')))
r = f.get('/agenda/edit/1'); check('AUTZ profesional: /agenda/edit/<su turno> permitido', r[0] == 200, r[0])
r = f.get('/agenda/completar/2'); check('AUTZ profesional: no completa turno ajeno', denied(r) and sql('select estado_cita from agenda where id=2') == '1')
r = f.post('/cita/edit/2', {'estado_cita': '4', 'observacion_profesional': 'x'})
check('AUTZ profesional: no modifica turno ajeno (/cita/edit)', denied(r) and sql('select estado_cita from agenda where id=2') == '1')
r = f.get('/agenda/completar/1'); check('AUTZ profesional: completa su propio turno', sql('select estado_cita from agenda where id=1') == '2', r[0])
check('AUTZ profesional: /prescripcion/edit/<de otro> denegado', denied(f.get('/prescripcion/edit/2')))
r = f.get('/prescripcion/edit/1'); check('AUTZ profesional: /prescripcion/edit/<propia> permitido', r[0] == 200, r[0])
r = f.post('/paciente/delete_evolutivo/1/5', {}, ajax=True)
check('AUTZ profesional: no borra evolutivo de otro profesional', r[0] == 403 and sql('select count(*) from evolutivo where id=1') == '1', r[0])
check('AUTZ profesional: /agenda/cancelar_turno sigue siendo sólo admin', denied(f.get('/agenda/cancelar_turno/1')))
check('AUTZ profesional: /pagos y /config siguen siendo sólo admin', denied(f.get('/pagos')) and denied(f.get('/config')))

f2 = login('prof2')
r = f2.post('/paciente/delete_evolutivo/1/5', {}, ajax=True)
check('AUTZ profesional: SÍ borra su propio evolutivo', r[0] == 200 and r[2].strip() == 'ok' and sql('select count(*) from evolutivo where id=1') == '0', (r[0], r[2][:50]))

# ---------------------------------------------------------------- AUTORIZACIÓN: admin conserva acceso
check('AUTZ admin: /paciente 200', adm.get('/paciente')[0] == 200)
check('AUTZ admin: /agenda/edit/<cualquiera> 200', adm.get('/agenda/edit/2')[0] == 200)
check('AUTZ admin: /prescripcion/edit/<cualquiera> 200', adm.get('/prescripcion/edit/2')[0] == 200)
check('AUTZ admin: /payment/success de cualquier turno 200', adm.get('/payment/success?external_reference=2')[0] == 200)
r = adm.post('/cita/edit/2', {'estado_cita': '4', 'observacion_profesional': 'ok'}); check('AUTZ admin: /cita/edit guarda estado_cita (bug de columna corregido)', not denied(r) and sql('select estado_cita from agenda where id=2') == '4', r[0])
for pth in ('/dashboard', '/config', '/pagos', '/profesional', '/user'):
    check(f'AUTZ admin: GET {pth} 200', adm.get(pth)[0] == 200)

# ---------------------------------------------------------------- XSS (admin)
s, h, b = adm.get('/paciente')
check('XSS: nombre malicioso se muestra escapado en el listado', '&lt;script&gt;alert(1)&lt;/script&gt;' in b and '<script>alert(1)' not in b)
s, h, b = adm.get('/paciente?q=' + urllib.parse.quote('"><script>pwn()</script>'))
check('XSS reflejado: ?q= escapado en el input', '<script>pwn()' not in b and '&quot;&gt;&lt;script&gt;pwn()' in b)
for pth in ('/profesional?q=' + urllib.parse.quote('"><script>pwn()</script>'), '/pagos?q=' + urllib.parse.quote('"><script>pwn()</script>')):
    check(f'XSS reflejado: {pth.split("?")[0]} escapado', '<script>pwn()' not in adm.get(pth)[2])
s, h, b = adm.get('/agenda')
check('XSS: calendario de agenda escapa el nombre del paciente', '<script>alert(1)' not in b and '&lt;script&gt;alert(1)' in b)
s, h, b = adm.get('/paciente/index_historiaclinica/5', ajax=True)
check('XSS: informe de evolutivo con HTML se muestra escapado', '<img src=x' not in b and 'Pac2' not in b)
sql("update evolutivo set informe='<img src=x onerror=alert(1)>\\nlinea2' where id=2") if sql('select count(*) from evolutivo where id=2') == '1' else sql("insert into evolutivo (id,paciente_id,profesional_id,fecha,informe,created_at) values (3,5,3,now(),'<img src=x onerror=alert(1)>\\nlinea2',now())")
s, h, b = adm.get('/paciente/index_historiaclinica/5', ajax=True)
check('XSS: informe escapado y con saltos de línea (nl2br)', '<img src=x' not in b and '&lt;img src=x onerror=alert(1)&gt;' in b and '<br' in b, b[:200])
x = login('xss'); s, h, b = x.get('/dashboard')
check('XSS: el header escapa el nombre del usuario logueado', s == 200 and '<script>alert(1)' not in b)

# ---------------------------------------------------------------- Estáticos: cobertura del escape en vistas
raw = []
for f_ in glob.glob(ROOT + '/application/views/**/*.php', recursive=True):
    if '/errors/' in f_: continue
    for i, line in enumerate(open(f_, encoding='utf-8', errors='replace'), 1):
        for m in re.finditer(r'<\?(?:php\s+echo|=)\s+(\$[^;?]*)', line):
            e = m.group(1).strip()
            if e in ('$pagination', '$calendar'): continue
            raw.append(f'{os.path.relpath(f_, ROOT)}:{i}: {e}')
check('ESTÁTICO: ninguna vista imprime variables sin html_escape (salvo $pagination/$calendar)', not raw, raw[:5])

# ---------------------------------------------------------------- Humo: sin errores fatales por rol
for who, paths in {'admin': ['/dashboard', '/paciente', '/agenda', '/profesional', '/config', '/user'],
                   'prof1': ['/dashboard', '/paciente', '/agenda'], 'pac1': ['/dashboard', '/cita', '/perfil']}.items():
    cl = adm if who == 'admin' else (f if who == 'prof1' else p)
    for pth in paths:
        s, h, b = cl.get(pth)
        mm = re.search(r'Fatal error|Parse error|Uncaught|A PHP Error[^<]*</h4>\s*<p>[^<]*<p>[^<]*<p>[^<]*', b)
        check(f'HUMO {who}: {pth} responde sin error fatal', s == 200 and not mm, (s, mm.group(0)[:200] if mm else ''))

summary()
