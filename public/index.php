<?php
require_once __DIR__ . '/../vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
$twig = new \Twig\Environment($loader, [
    'cache' => false,
]);

$path = $_SERVER['PATH_INFO'] ?? $_SERVER['REQUEST_URI'] ?? '/';
$path = strtok($path, '?');

// Minimal routing: map path to template name
switch ($path) {
    case '/':
    case '/index.php':
    case '/landing':
        echo $twig->render('landing.twig');
        break;
    case '/login':
        echo $twig->render('login.twig');
        break;
    case '/signup':
        echo $twig->render('signup.twig');
        break;
    case '/dashboard':
        echo $twig->render('dashboard.twig');
        break;
    case '/tickets':
        echo $twig->render('tickets.twig');
        break;
    default:
        http_response_code(404);
        echo $twig->render('404.twig', ['path' => $path]);
        break;
}
