<?php
try {
    $pdo = new PDO('sqlite:banco.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //tabela de usuários
    $pdo->exec("CREATE TABLE IF NOT EXISTS usuarios (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        login TEXT NOT NULL,
        senha TEXT NOT NULL,
        tipo TEXT NOT NULL
    )");

    //tabela de reservas
    $pdo->exec("CREATE TABLE IF NOT EXISTS reservas (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nome TEXT NOT NULL,
        sala TEXT NOT NULL,
        data TEXT NOT NULL,
        horaInicio TEXT NOT NULL,
        horaFim TEXT NOT NULL,
        status TEXT DEFAULT 'ativa'
    )");

    //dados de teste
    $stmt = $pdo->query("SELECT COUNT(*) FROM usuarios");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO usuarios (login, senha, tipo) VALUES 
            ('admin', '1234', 'admin'),
            ('cliente', '1234', 'cliente')
        ");
        
        //inserindo uma reserva de teste
        $hoje = date('Y-m-d'); // Pega a data de hoje
        $pdo->exec("INSERT INTO reservas (nome, sala, data, horaInicio, horaFim, status) VALUES 
            ('admin', 'Sala 1', '$hoje', '08:00', '10:00', 'ativa')
        ");
        
        echo "Banco recriado com sucesso com a tabela RESERVAS!";
    }

} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>