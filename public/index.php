<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Services\CryptoManager;

// Load environment variables if .env exists
if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
}

// Basic Routing
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$basePath = dirname($_SERVER['SCRIPT_NAME']);
// Normalize URI
if (strpos($requestUri, $basePath) === 0) {
    if ($basePath === '/') {
        $requestUri = substr($requestUri, 1);
    }
    else {
        $requestUri = substr($requestUri, strlen($basePath));
    }
}
$requestUri = trim($requestUri, '/');

// App Context
$websiteData = [
    'name' => $_ENV['APP_NAME'] ?? 'Project Eagle',
    'title' => $_ENV['APP_NAME'] ?? 'Project Eagle',
    'description' => 'A free online service providing hash and string algorithms.',
    'url' => 'http://' . $_SERVER['HTTP_HOST'] . $basePath . '/'
];

$algorithms = CryptoManager::getAlgorithms();
$websiteData['algorithms'] = $algorithms;
$websiteData['count'] = count($algorithms);

// Select a few random algorithms for "More algorithms"
if (count($algorithms) > 0) {
    $randKeys = (array)array_rand($algorithms, min(4, count($algorithms)));
    $websiteData['more'] = $randKeys;
}

// Route: /
if ($requestUri === '' || $requestUri === '/') {
    $websiteData['title'] = 'Home';
    $websiteData['description'] = $websiteData['name'] . ' is a free online cryptographic suite.';
    $groupedAlgorithms = CryptoManager::groupAlgorithmsByType();

    require __DIR__ . '/../views/home.php';
    exit;
}

// Route: /algorithm/{slug}
if (preg_match('#^algorithm/([^/]+)$#', $requestUri, $matches)) {
    $slug = $matches[1];
    $algorithm = CryptoManager::getAlgorithmBySlug($slug);

    if ($algorithm) {
        $websiteData['title'] = $algorithm['name'];
        $websiteData['description'] = 'Use ' . $algorithm['name'] . ' algorithm on your strings.';
        $response = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $response = CryptoManager::executeAlgorithm($algorithm, $_POST);
        }

        require __DIR__ . '/../views/algorithm.php';
        exit;
    }
}

// 404
http_response_code(404);
echo "404 Not Found";