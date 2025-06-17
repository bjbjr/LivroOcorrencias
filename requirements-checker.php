<?php
// Verifica requisitos do sistema
$requirements = [
    'PHP >= 8.0' => version_compare(PHP_VERSION, '8.0.0', '>='),
    'PDO Extension' => extension_loaded('pdo'),
    'PDO MySQL Driver' => extension_loaded('pdo_mysql'),
    'Fileinfo Extension' => extension_loaded('fileinfo'),
    'GD Library' => extension_loaded('gd'),
    'allow_url_fopen' => ini_get('allow_url_fopen'),
    'Write Permissions' => is_writable(__DIR__ . '/../storage')
];

// Verifica XAMPP
$xamppPath = 'C:\\xampp';
$isXamppInstalled = file_exists($xamppPath . '\\htdocs') && 
                    file_exists($xamppPath . '\\php\\php.ini');

// Retorna resultados para o instalador
header('Content-Type: application/json');
echo json_encode([
    'requirements' => $requirements,
    'xampp_installed' => $isXamppInstalled,
    'xampp_path' => $xamppPath
]);