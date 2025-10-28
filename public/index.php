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
        // If it's an API POST to /login, handle below; otherwise render page
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // accept JSON body or form-encoded
            $body = file_get_contents('php://input');
            $data = [];
            if ($body)
                $data = json_decode($body, true) ?? [];
            $email = $data['email'] ?? $_POST['email'] ?? null;
            $password = $data['password'] ?? $_POST['password'] ?? null;
            header('Content-Type: application/json');
            if (!$email || !$password) {
                echo json_encode(['ok' => false, 'reason' => 'invalid']);
                exit;
            }
            $dataFile = __DIR__ . '/../data/users.json';
            if (!file_exists(dirname($dataFile)))
                mkdir(dirname($dataFile), 0755, true);
            $users = [];
            if (file_exists($dataFile)) {
                $users = json_decode(file_get_contents($dataFile), true) ?? [];
            }
            $matched = null;
            foreach ($users as $u) {
                if ($u['email'] === $email && $u['password'] === $password) {
                    $matched = $u;
                    break;
                }
            }
            if (!$matched) {
                echo json_encode(['ok' => false]);
                exit;
            }
            echo json_encode(['ok' => true, 'user' => ['id' => $matched['id'], 'email' => $matched['email'], 'name' => $matched['name']]]);
            exit;
        }
        echo $twig->render('login.twig');
        break;
    case '/signup':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $body = file_get_contents('php://input');
            $data = [];
            if ($body)
                $data = json_decode($body, true) ?? [];
            $email = $data['email'] ?? $_POST['email'] ?? null;
            $password = $data['password'] ?? $_POST['password'] ?? null;
            $name = $data['name'] ?? $_POST['name'] ?? null;
            header('Content-Type: application/json');
            if (!$email || !$password || !$name) {
                echo json_encode(['ok' => false, 'reason' => 'invalid']);
                exit;
            }
            $dataFile = __DIR__ . '/../data/users.json';
            if (!file_exists(dirname($dataFile)))
                mkdir(dirname($dataFile), 0755, true);
            $users = [];
            if (file_exists($dataFile)) {
                $users = json_decode(file_get_contents($dataFile), true) ?? [];
            }
            foreach ($users as $u) {
                if ($u['email'] === $email) {
                    echo json_encode(['ok' => false, 'reason' => 'exists']);
                    exit;
                }
            }
            $id = bin2hex(random_bytes(6));
            $newUser = ['id' => $id, 'email' => $email, 'password' => $password, 'name' => $name];
            $users[] = $newUser;
            file_put_contents($dataFile, json_encode($users, JSON_PRETTY_PRINT));
            echo json_encode(['ok' => true, 'user' => ['id' => $id, 'email' => $email, 'name' => $name]]);
            exit;
        }
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
