# Modelo relacional propuesto

- **usuarios** 1:N **ventas**: un usuario vendedor registra varias ventas.
- **clientes** 1:N **ventas**: un cliente puede realizar varias compras.
- **ventas** 1:N **detalle_venta** y **productos** 1:N **detalle_venta**.
- **ventas** 1:N **pagos**: permite registrar uno o más pagos asociados a una venta.
- **categorias** 1:N **productos**.
- **proveedores** 1:N **productos** (proveedor principal opcional por producto).
- **productos** 1:1 **inventario**.
- **clientes** 1:N **pedidos**; **pedidos** 1:N **detalle_pedido**; **productos** 1:N **detalle_pedido**.
- **usuarios** 1:0..1 **empleados**: cuenta de acceso opcional vinculada a una ficha de empleado.

El script `database.sql` contiene las claves foráneas y las restricciones básicas.
