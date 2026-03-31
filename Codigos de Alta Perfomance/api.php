<?php
//pega a conexão com o banco SQLite
require_once 'conexao.php';

//formata para JSON
header('Content-Type: application/json');

// 1. INICIA A SESSÃO ANTES DE TUDO (Isso garante que o PHP consiga ler quem fez o login)
session_start();
$papelUsuario = $_SESSION['usuario_papel'] ?? 'cliente';
$usuarioLogado = $_SESSION['usuario_nome'] ?? '';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

// --- PARTE DO GET (LISTAR) ---
if ($method === 'GET') {
    $stmt = $pdo->query("SELECT * FROM reservas ORDER BY data, horaInicio");
    $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //regra de seguranca:
    if ($papelUsuario !== 'admin') {
        foreach ($reservas as &$res) {
            // Se a reserva NÃO for da pessoa logada, esconde o nome real
            if ($res['nome'] !== $usuarioLogado) {
                $res['nome'] = 'Ocupado (Privado)'; 
            }
        }
    }

    echo json_encode($reservas);
    exit;
}

// --- PARTE DO POST (CRIAR) ---
if ($method === 'POST') {
    $nome = trim($input['nome'] ?? '');
    $sala = $input['sala'];
    $data = $input['data'];
    $horaInicio = $input['horaInicio'];
    $horaFim = $input['horaFim'];

    // VALIDAÇÃO: Exigir nome
    if (empty($nome) || $nome === "undefined") {
        http_response_code(400);
        echo json_encode(['erro' => 'Erro: Nome do usuário é obrigatório para reservar!']);
        exit;
    }

    //regra de conflito
    $sqlConflito = "SELECT id FROM reservas 
                    WHERE sala = ? AND data = ? AND status = 'ativa' 
                    AND (horaInicio < ? AND horaFim > ?)";
    $stmt = $pdo->prepare($sqlConflito);
    //logistica de tempo
    $stmt->execute([$sala, $data, $horaFim, $horaInicio]);
    
    if ($stmt->fetch()) {
        http_response_code(409); //retorna erro de conflito
        echo json_encode(['erro' => 'Erro: Esta sala já está reservada nesse horário!']);
        exit;
    }

    //se a sala tiver livre salva no banco
    $sqlInsert = "INSERT INTO reservas (nome, sala, data, horaInicio, horaFim, status) 
                  VALUES (?, ?, ?, ?, ?, 'ativa')";
    $stmt = $pdo->prepare($sqlInsert);
    $stmt->execute([$nome, $sala, $data, $horaInicio, $horaFim]);
    
    http_response_code(201); //suceso yupiii!!!
    echo json_encode(['mensagem' => 'Reserva criada com sucesso']);
    exit;
}

// --- PARTE DO PUT (CANCELAR) ---
if ($method === 'PUT') {
    $id = $input['id'];

    if ($papelUsuario === 'admin') {
        // Admin tem poder total: cancela pelo ID e pronto
        $stmt = $pdo->prepare("UPDATE reservas SET status = 'cancelada' WHERE id = ?");
        $stmt->execute([$id]);
    } else {
        // Cliente só cancela se o ID bater E o nome da reserva for o DELE
        $stmt = $pdo->prepare("UPDATE reservas SET status = 'cancelada' WHERE id = ? AND nome = ?");
        $stmt->execute([$id, $usuarioLogado]);
    }
    
    echo json_encode(['mensagem' => 'Processado com sucesso']);
    exit;
}
?>