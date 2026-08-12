<?php
$requestUri = strtok($_SERVER['REQUEST_URI'], '?');
$autorizado = false;

foreach ($_SESSION['controlLinksUsuario'] as $link) {
    if ($requestUri === $link || strpos($requestUri, $link . '/') === 0) {
        $autorizado = true;
        break;
    }
}

if (!$autorizado) {
    session_unset();
    session_destroy();
    if (headers_sent()) {
        return "<script>window.location.href=" . RUTA_URL . "</script>";
    } else {
        redireccionar('/login');
    }
}
?>