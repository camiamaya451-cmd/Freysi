<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../config/database.php';

function e(?string $value): string { return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8'); }
function require_login(): void {
    if (empty($_SESSION['user'])) { header('Location: index.php?page=login'); exit; }
}
function require_admin(): void {
    require_login();
    if (($_SESSION['user']['rol'] ?? '') !== 'admin') { http_response_code(403); exit('Acceso denegado: se requiere rol administrador.'); }
}
function csrf_token(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function verify_csrf(): void {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
        http_response_code(419); exit('Token de seguridad inválido. Recarga la página.');
    }
}
function money(float|string $value): string { return 'S/ ' . number_format((float)$value, 2); }
