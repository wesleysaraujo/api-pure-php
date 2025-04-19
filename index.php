<?php

require('classes/ApiController.php');

header('Content-Type: application/json; charset=utf-8');

//Permitir requisições de qualquer origem
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');

// Tratar solicitações OPTIONS(pre-flight)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Função responsável pelo retorno de dados em formato JSON
function responseJson($data, $statusCode = 200) {
    http_response_code($statusCode);
    echo json_encode($data);
    exit();
}

// Pegar URI e Método da Requisição
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Remover prefixo da base URL
$basePathUri = explode('/api', $requestUri);
$endpoint = isset($basePathUri[1]) ? $basePathUri[1] : '/';

// Pegar os Parametros de URL
$params = [];
$pathSegments = explode('/',  trim($endpoint, '/'));
$resource = array_shift($pathSegments);
$id = count($pathSegments) > 0 ? array_shift($pathSegments) : null;

// Pegar dados da requisição
$rawData = json_decode(file_get_contents('php://input'), true);

$api = new ApiController();

// Rotas
switch ($resource) {
    case 'users':
       switch ($requestMethod) {
            case 'GET':
                $api->getUsers($id);
                break;
            case 'POST':
                $api->createUser($rawData);
                break;
            case 'PUT':
                $api->updateUser($id, $rawData);
                break;
            case 'DELETE':
                $api->deleteUser($id);
                break;
            default:
                responseJson(
                    [
                        'error' => 'Método não permitido.'
                    ],
                    405
                );
        }
        break;
    case '':
    case '/':
        responseJson(
            [
                'message' => 'API Pure PHP',
                'version' => '1.0.0',
            ]
        );
        break;
    default:
        $api->notFound();
}
