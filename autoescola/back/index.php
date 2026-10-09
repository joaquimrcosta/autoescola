<?php
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit;
}

require "config.php";

$rota = $_GET["rota"] ?? "teste";

function teste() {
    echo json_encode(["mensagem" => "API Autoescola respondendo perfeitamente!"]);
}

function listarAlunos($pdo) {
    $stmt = $pdo->query("SELECT * FROM alunos ORDER BY id DESC");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

function criarAluno($pdo) {
    
    $dados = json_decode(file_get_contents("php://input"), true);

    $nome = $dados["nome"] ?? "";
    $email = $dados["email"] ?? "";
    $telefone = $dados["telefone"] ?? "";
    $data_nascimento = $dados["data_nascimento"] ?? "";

    if (empty($nome) || empty($email)) {
        http_response_code(400);
        echo json_encode(["erro" => "Nome e e-mail são obrigatórios."]);
        return;
    }

    try {
        $sql = "INSERT INTO alunos (nome, email, telefone, data_nascimento) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nome, $email, $telefone, $data_nascimento]);

        http_response_code(201);
        echo json_encode(["mensagem" => "Aluno cadastrado com sucesso!"]);
    } catch (PDOException $e) {
        
        if ($e->getCode() == "23000") {
            http_response_code(409);
            echo json_encode(["erro" => "Este e-mail já está cadastrado."]);
        } else {
            http_response_code(500);
            echo json_encode(["erro" => "Erro ao cadastrar aluno."]);
        }
    }
}

function listarInstrutores($pdo) {
    $stmt = $pdo->query("SELECT * FROM instrutores ORDER BY id DESC");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

function criarInstrutor($pdo) {
    $dados = json_decode(file_get_contents("php://input"), true);

    $nome = $dados["nome"] ?? "";
    $email = $dados["email"] ?? "";

    if (empty($nome) || empty($email)) {
        http_response_code(400);
        echo json_encode(["erro" => "Nome e e-mail são obrigatórios."]);
        return;
    }

    try {
        $sql = "INSERT INTO instrutores (nome, email) VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nome, $email]);

        http_response_code(201);
        echo json_encode(["mensagem" => "Instrutor cadastrado com sucesso!"]);
    } catch (PDOException $e) {
        if ($e->getCode() == "23000") {
            http_response_code(409);
            echo json_encode(["erro" => "Este e-mail já está cadastrado."]);
        } else {
            http_response_code(500);
            echo json_encode(["erro" => "Erro ao cadastrar instrutor."]);
        }
    }
}

$metodo = $_SERVER["REQUEST_METHOD"];

switch ($rota) {

    case "alunos":
        if ($metodo === "GET") {
            listarAlunos($pdo);
        } elseif ($metodo === "POST") {
            criarAluno($pdo);
        }
        break;

    case "instrutores":
        if ($metodo === "GET") {
            listarInstrutores($pdo);
        } elseif ($metodo === "POST") {
            criarInstrutor($pdo);
        }
        break;

    default:
        teste();
        break;
}