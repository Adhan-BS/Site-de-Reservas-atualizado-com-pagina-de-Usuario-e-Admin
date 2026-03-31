<?php
try {
    $pdo = new PDO('sqlite:' . __DIR__ . '/banco.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    //tabela de reservas adm
    $pdo->exec("CREATE TABLE IF NOT EXISTS reservas (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nome TEXT,
        sala TEXT,
        data TEXT,
        horaInicio TEXT,
        horaFim TEXT,
        status TEXT
    )");

    //Tabela para usuario
    $pdo->exec("CREATE TABLE IF NOT EXISTS usuarios (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        login TEXT UNIQUE,
        senha TEXT,
        papel TEXT
    )");

    //cria usuários automaticamente se a tabela estiver vazia (teste)
    $stmt = $pdo->query("SELECT COUNT(*) FROM usuarios");
    if ($stmt->fetchColumn() == 0) {
        // Usuário Administrador
        $pdo->exec("INSERT INTO usuarios (login, senha, papel) VALUES ('admin', 'admin123', 'admin')");
        // Usuário Cliente (Aluno/Professor)
        $pdo->exec("INSERT INTO usuarios (login, senha, papel) VALUES ('aluno', 'senha123', 'cliente')");
    }

} catch (PDOException $e) {
    die("Erro de conexão com SQLite: " . $e->getMessage());
}
?>