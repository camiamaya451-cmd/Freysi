<?php
function save_client(): void {
    $name=trim($_POST['nombres']??''); if($name===''){header('Location: index.php?page=clients&error=1');exit;}
    $s=db()->prepare('INSERT INTO clientes(nombres,apellidos,dni,telefono,correo) VALUES(?,?,?,?,?)'); $s->execute([$name,trim($_POST['apellidos']??''),trim($_POST['dni']??'')?:null,trim($_POST['telefono']??''),trim($_POST['correo']??'')]); header('Location: index.php?page=clients&saved=1'); exit;
}
function render_clients(): void {
    if(($_SESSION['user']['rol']??'')==='admin'||($_SESSION['user']['rol']??'')==='vendedor') echo '<section class="panel"><h2>Registrar cliente</h2><form class="form-grid" method="post" action="index.php?page=client-save"><input type="hidden" name="csrf" value="'.e(csrf_token()).'"><label>Nombres<input name="nombres" required></label><label>Apellidos<input name="apellidos"></label><label>DNI<input name="dni" maxlength="15"></label><label>Teléfono<input name="telefono"></label><label class="wide">Correo electrónico<input type="email" name="correo"></label><div class="wide"><button class="btn primary">Guardar cliente</button></div></form></section>';
    $rows=db()->query('SELECT c.*,COUNT(v.id_venta) compras FROM clientes c LEFT JOIN ventas v ON v.id_cliente=c.id_cliente GROUP BY c.id_cliente ORDER BY c.id_cliente DESC')->fetchAll(); echo '<section class="panel"><h2>Clientes registrados</h2><table><tr><th>Cliente</th><th>DNI</th><th>Teléfono</th><th>Correo</th><th>Compras</th></tr>'; foreach($rows as $r) echo '<tr><td>'.e(trim($r['nombres'].' '.$r['apellidos'])).'</td><td>'.e($r['dni']??'—').'</td><td>'.e($r['telefono']??'—').'</td><td>'.e($r['correo']??'—').'</td><td>'.(int)$r['compras'].'</td></tr>'; echo '</table></section>';
}
