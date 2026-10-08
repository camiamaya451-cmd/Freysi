<?php
function render_dashboard(): void {
    $pdo = db();
    $stats = [
        'Productos registrados' => (int)$pdo->query('SELECT COUNT(*) FROM productos WHERE activo=1')->fetchColumn(),
        'Clientes' => (int)$pdo->query('SELECT COUNT(*) FROM clientes')->fetchColumn(),
        'Ventas registradas' => (int)$pdo->query('SELECT COUNT(*) FROM ventas')->fetchColumn(),
        'Stock bajo' => (int)$pdo->query('SELECT COUNT(*) FROM inventario WHERE stock_actual <= stock_minimo')->fetchColumn(),
    ];
    $total = (float)$pdo->query("SELECT COALESCE(SUM(total),0) FROM ventas WHERE DATE(fecha_venta)=CURDATE() AND estado='Finalizada'")->fetchColumn();
    echo '<section class="stats">'; foreach($stats as $label=>$value) echo '<article class="stat-card"><span>'.e($label).'</span><strong>'.number_format($value).'</strong><small>Datos actuales</small></article>'; echo '<article class="stat-card featured"><span>Ventas de hoy</span><strong>'.money($total).'</strong><small>Total de ventas finalizadas</small></article></section>';
    echo '<section class="panel"><div class="panel-head"><div><p class="eyebrow">OPERACIÓN</p><h2>Accesos rápidos</h2></div></div><div class="quick-grid"><a href="index.php?page=sales"><b>＋</b><span><strong>Registrar venta</strong><small>Crear una venta y descontar stock</small></span></a><a href="index.php?page=products"><b>▦</b><span><strong>Ver productos</strong><small>Consultar precios e inventario</small></span></a><a href="index.php?page=clients"><b>♙</b><span><strong>Gestionar clientes</strong><small>Registrar información de compradores</small></span></a></div></section>';
    $low=$pdo->query('SELECT p.nombre, i.stock_actual, i.stock_minimo FROM inventario i JOIN productos p ON p.id_producto=i.id_producto WHERE i.stock_actual<=i.stock_minimo ORDER BY i.stock_actual LIMIT 6')->fetchAll();
    echo '<section class="panel"><h2>Alertas de stock bajo</h2>'; if(!$low) echo '<p class="muted">No hay productos por debajo del stock mínimo.</p>'; else { echo '<table><tr><th>Producto</th><th>Stock actual</th><th>Stock mínimo</th></tr>'; foreach($low as $r) echo '<tr><td>'.e($r['nombre']).'</td><td><span class="warning">'.(int)$r['stock_actual'].'</span></td><td>'.(int)$r['stock_minimo'].'</td></tr>'; echo '</table>'; } echo '</section>';
}
