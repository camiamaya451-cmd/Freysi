<?php
function save_provider(): void {
    $name=trim($_POST['nombre']??''); if($name===''){header('Location: index.php?page=providers&error=1');exit;}
    $s=db()->prepare('INSERT INTO proveedores(nombre,telefono,correo,direccion) VALUES(?,?,?,?)'); $s->execute([$name,trim($_POST['telefono']??''),trim($_POST['correo']??''),trim($_POST['direccion']??'')]); header('Location: index.php?page=providers&saved=1'); exit;
}
function render_providers(): void {
    if(($_SESSION['user']['rol']??'')==='admin') echo '<section class="panel"><h2>Registrar proveedor</h2><form class="form-grid" method="post" action="index.php?page=provider-save"><input type="hidden" name="csrf" value="'.e(csrf_token()).'"><label>Nombre o empresa<input name="nombre" required></label><label>Teléfono<input name="telefono"></label><label>Correo<input type="email" name="correo"></label><label>Dirección<input name="direccion"></label><div class="wide"><button class="btn primary">Guardar proveedor</button></div></form></section>';
    $rows=db()->query('SELECT * FROM proveedores ORDER BY nombre')->fetchAll(); echo '<section class="panel"><h2>Proveedores</h2><table><tr><th>Nombre</th><th>Teléfono</th><th>Correo</th><th>Dirección</th></tr>'; foreach($rows as $r) echo '<tr><td>'.e($r['nombre']).'</td><td>'.e($r['telefono']??'—').'</td><td>'.e($r['correo']??'—').'</td><td>'.e($r['direccion']??'—').'</td></tr>'; echo '</table></section>';
}
