# FREY STUDIO — Sistema de gestión para tienda de maquillaje

Proyecto inicial en PHP 8 + MySQL/MariaDB, elaborado a partir del informe de análisis y diseño de FREY STUDIO. Incluye estructura real de código para que GitDiagram pueda reconocer módulos, clases y relaciones. Es una base funcional de inicio, no un sistema auditado para producción.

## Módulos incluidos
- Inicio de sesión y control de roles (`admin`, `vendedor`).
- Panel con indicadores básicos.
- Productos y categorías.
- Clientes y proveedores.
- Ventas con detalle, pago y actualización transaccional del inventario.
- Script SQL con tablas y datos de demostración.
- Diagrama de arquitectura en Mermaid.

## Requisitos
- PHP 8.1 o superior con extensiones PDO y PDO MySQL.
- MySQL 8 / MariaDB.
- XAMPP, WAMP, Laragon o servidor equivalente.

## Instalación local (XAMPP)
1. Copia la carpeta `frey_studio_github` a `C:\xampp\htdocs\frey_studio`.
2. En phpMyAdmin, importa `database.sql`.
3. Edita `config/database.php` y configura tus credenciales MySQL.
4. Abre `http://localhost/frey_studio/public/`.
5. Usuario inicial: `admin`; contraseña inicial: `Admin123!`.
6. Cambia la contraseña de demostración antes de usar datos reales.

## Subir a GitHub y usar GitDiagram
Sube todos los archivos y carpetas, no solo el documento Word. GitDiagram puede leer `README.md`, `database.sql`, los PHP y `docs/architecture.mmd`. Después vuelve a generar el diagrama desde el repositorio.

## Estructura
```text
public/                 Punto de entrada y estilos
config/database.php     Conexión PDO a MySQL
includes/               Autenticación, permisos y funciones comunes
modules/                Productos, clientes, proveedores y ventas
 database.sql           Esquema y datos de demostración
 docs/architecture.mmd  Diagrama de arquitectura Mermaid
```

## Importante
- La cuenta y los datos incluidos son solo para desarrollo local.
- Configura HTTPS, cambia claves, agrega respaldos y revisa permisos antes de publicar en internet.
- La aplicación no procesa pagos reales; registra el método y el estado del pago.
