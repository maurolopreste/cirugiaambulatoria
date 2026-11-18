"""Utilidades comunes de las pruebas HTTP (cliente con cookies, SQL, check)."""
import os, re, subprocess, sys, glob, json, time, urllib.request, urllib.parse, urllib.error, http.cookiejar

BASE = os.environ['BASE']; DB = os.environ['DB']; MYSQL = os.environ['MYSQL_CLI']; ROOT = os.environ['ROOT']
PASS = 'Test1234!'
UPLOADS = os.environ.get('UPLOADS', ''); TMPD = os.environ.get('TMPD', '/tmp')
USERS = {'admin': 'admin@t.test', 'prof1': 'prof1@t.test', 'prof2': 'prof2@t.test',
         'pac1': 'pac1@t.test', 'pac2': 'pac2@t.test', 'xss': 'xss@t.test'}

class NoRedirect(urllib.request.HTTPRedirectHandler):
    def redirect_request(self, *a, **k): return None

class Client:
    def __init__(self):
        self.jar = http.cookiejar.CookieJar()
        self.op = urllib.request.build_opener(urllib.request.HTTPCookieProcessor(self.jar), NoRedirect)
    def cookie(self, name):
        for c in self.jar:
            if c.name == name: return c.value
    def req(self, method, path, data=None, ajax=False, token=True):
        headers = {'X-Requested-With': 'XMLHttpRequest'} if ajax else {}
        body = None
        if method == 'POST':
            data = dict(data or {})
            if token and self.cookie('csrf_cookie_name'): data.setdefault('csrf_test_name', self.cookie('csrf_cookie_name'))
            body = urllib.parse.urlencode(data).encode()
        r = urllib.request.Request(BASE + path, data=body, headers=headers, method=method)
        try:
            resp = self.op.open(r); status = resp.status
        except urllib.error.HTTPError as e:
            resp = e; status = e.code
        txt = resp.read().decode('utf-8', 'replace')
        return status, resp.headers, txt
    def post_file(self, path, data, filename, content, field='userfile', ctype='application/octet-stream'):
        """POST multipart/form-data con un archivo (y el token CSRF)."""
        data = dict(data); data.setdefault('csrf_test_name', self.cookie('csrf_cookie_name'))
        bd = '----tests' + os.urandom(8).hex(); out = b''
        for k, v in data.items():
            out += f'--{bd}\r\nContent-Disposition: form-data; name="{k}"\r\n\r\n{v}\r\n'.encode()
        out += f'--{bd}\r\nContent-Disposition: form-data; name="{field}"; filename="{filename}"\r\nContent-Type: {ctype}\r\n\r\n'.encode() + content + f'\r\n--{bd}--\r\n'.encode()
        r = urllib.request.Request(BASE + path, data=out, headers={'Content-Type': 'multipart/form-data; boundary=' + bd}, method='POST')
        try: resp = self.op.open(r); status = resp.status
        except urllib.error.HTTPError as e: resp = e; status = e.code
        return status, resp.headers, resp.read().decode('utf-8', 'replace')
    def get(self, p, **k): return self.req('GET', p, **k)
    def post(self, p, d=None, **k): return self.req('POST', p, d, **k)

def login(who):
    c = Client(); c.get('/login')                      # obtiene cookie CSRF
    s, h, _ = c.post('/login', {'email': USERS[who], 'password': PASS})
    assert s in (302, 303, 307) and '/dashboard' in h.get('Location', ''), f'login {who} falló: {s} {h.get("Location")}'
    return c

def sql(q):
    out = subprocess.run([MYSQL, '-uroot', '-N', '-B', DB, '-e', q], capture_output=True, text=True,
                         env={**os.environ, 'MYSQL_PWD': 'root'}).stdout.strip()
    return out

def denied(r):
    s, h, _ = r
    loc = h.get('Location', '')
    return s == 403 or (s in (301, 302, 303, 307, 308) and re.search(r'/(dashboard|login)(\?|$)', loc) is not None)

results = []
def check(name, cond, extra=''):
    results.append((name, bool(cond), extra)); print(('PASS ' if cond else 'FAIL ') + name + (f'  [{extra}]' if (extra and not cond) else ''))


def summary():
    fails = [r for r in results if not r[1]]
    print(f'\nTotal: {len(results)} pruebas, {len(results) - len(fails)} ok, {len(fails)} fallas')
    sys.exit(1 if fails else 0)
