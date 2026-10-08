<?php
require_once __DIR__ . '/../includes/bootstrap.php';
verify_csrf();
$page = $_GET['page'] ?? (empty($_SESSION['user']) ? 'login' : 'dashboard');
$error = '';
if ($page === 'logout') { $_SESSION = []; session_destroy(); header('Location: index.php?page=login'); exit; }
if ($page === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = db()->prepare('SELECT id_usuario,nombre_usuario,password_hash,rol FROM usuarios WHERE nombre_usuario=? AND activo=1');
    $stmt->execute([trim($_POST['usuario'] ?? '')]); $user = $stmt->fetch();
    if ($user && password_verify($_POST['password'] ?? '', $user['password_hash'])) {
        session_regenerate_id(true); $_SESSION['user']=['id'=>(int)$user['id_usuario'],'nombre'=>$user['nombre_usuario'],'rol'=>$user['rol']];
        header('Location: index.php?page=dashboard'); exit;
    } $error='Usuario o contraseña incorrectos.';
}
if ($page !== 'login') require_login();
if ($page === 'users') require_admin();
if ($page === 'product-save' && $_SERVER['REQUEST_METHOD']==='POST') { require_login(); require_once __DIR__.'/../modules/products.php'; save_product(); }
if ($page === 'client-save' && $_SERVER['REQUEST_METHOD']==='POST') { require_login(); require_once __DIR__.'/../modules/clients.php'; save_client(); }
if ($page === 'provider-save' && $_SERVER['REQUEST_METHOD']==='POST') { require_admin(); require_once __DIR__.'/../modules/providers.php'; save_provider(); }
if ($page === 'sale-save' && $_SERVER['REQUEST_METHOD']==='POST') { require_login(); require_once __DIR__.'/../modules/sales.php'; save_sale(); }
function active_link(string $page,string $target): string { return $page===$target?'active':''; }
?><!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>FREY STUDIO</title><link rel="stylesheet" href="assets/style.css"></head><body>
<?php if($page==='login'): ?><main class="login-wrap"><section class="login-card"><div class="brand-mark">FS</div><p class="eyebrow">TIENDA DE MAQUILLAJE</p><h1>FREY STUDIO</h1><p class="muted">Sistema de gestión</p><?php if($error):?><div class="alert"><?=e($error)?></div><?php endif;?><form method="post"><input type="hidden" name="csrf" value="<?=e(csrf_token())?>"><label>Usuario<input name="usuario" required autocomplete="username"></label><label>Contraseña<input type="password" name="password" required autocomplete="current-password"></label><button class="btn primary full">Ingresar</button></form><small>Demo local: admin / Admin123!</small></section></main>
<?php else: ?><div class="app"><aside class="sidebar"><div class="brand"><span class="brand-mark small">FS</span><div><strong>FREY STUDIO</strong><small>Gestión de tienda</small></div></div><nav><a class="<?=active_link($page,'dashboard')?>" href="index.php?page=dashboard">⌂ Panel principal</a><a class="<?=active_link($page,'products')?>" href="index.php?page=products">▦ Productos</a><a class="<?=active_link($page,'clients')?>" href="index.php?page=clients">♙ Clientes</a><a class="<?=active_link($page,'providers')?>" href="index.php?page=providers">◇ Proveedores</a><a class="<?=active_link($page,'sales')?>" href="index.php?page=sales">＄ Ventas</a><?php if(($_SESSION['user']['rol']??'')==='admin'):?><a href="index.php?page=users">⚙ Usuarios</a><?php endif;?></nav><div class="sidebar-bottom"><small>Sesión: <?=e($_SESSION['user']['nombre']??'')?></small><a href="index.php?page=logout">Cerrar sesión ↗</a></div></aside><main class="main"><header class="topbar"><div><p class="eyebrow">PANEL DE CONTROL</p><h1><?=e(match($page){'products'=>'Productos','clients'=>'Clientes','providers'=>'Proveedores','sales'=>'Ventas','users'=>'Usuarios',default=>'Resumen general'})?></h1></div><span class="role-pill"><?=e($_SESSION['user']['rol']??'')?></span></header>
<?php switch($page){case 'products':require __DIR__.'/../modules/products.php';render_products();break;case 'clients':require __DIR__.'/../modules/clients.php';render_clients();break;case 'providers':require __DIR__.'/../modules/providers.php';render_providers();break;case 'sales':require __DIR__.'/../modules/sales.php';render_sales();break;case 'users':$users=db()->query('SELECT nombre_usuario,rol,activo FROM usuarios ORDER BY id_usuario')->fetchAll();echo '<section class="panel"><h2>Usuarios del sistema</h2><table><tr><th>Usuario</th><th>Rol</th><th>Estado</th></tr>';foreach($users as $u)echo '<tr><td>'.e($u['nombre_usuario']).'</td><td>'.e($u['rol']).'</td><td>'.($u['activo']?'Activo':'Inactivo').'</td></tr>';echo '</table></section>';break;default:require __DIR__.'/../modules/dashboard.php';render_dashboard();}?></main></div><?php endif;?></body></html>
