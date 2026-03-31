<?php
session_start();
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: login.php");
    exit;
}

//guarda as informações do usuario logado em variaveis para o JS usar
$papel = $_SESSION['usuario_papel'];
$nomeUsuario = $_SESSION['usuario_nome'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>RoomSync - Reservas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>

        /*estilo extra para os bloqos*/
        .bloco-horario { height: 100px; display: flex; flex-direction: column; justify-content: center; align-items: center; border-radius: 10px; cursor: pointer; transition: 0.3s; }
        .bloco-livre { background-color: #198754; color: white; border: 2px solid #146c43; }
        .bloco-livre:hover { background-color: #146c43; transform: scale(1.05); }
        .bloco-ocupado { background-color: #dc3545; color: white; border: 2px solid #b02a37; cursor: not-allowed; opacity: 0.8; }
        /* Estilo base do quadrado */
.bloco-horario {
    padding: 15px;
    margin-bottom: 15px;
    border-radius: 8px;
    border: 1px solid #ccc;
    text-align: center;
    cursor: pointer;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: transform 0.2s;
}

.bloco-horario:hover {
    transform: scale(1.02);
}

/* Verde para livre */
.bloco-livre {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
}

/* Vermelho para ocupado */
.bloco-ocupado {
    background-color: #f8d7da;
    border-color: #f5c6cb;
    color: #721c24;
    cursor: not-allowed;
    opacity: 0.8;
}
    </style>

</head>
<body class="bg-light">
    <script>
        const usuarioAtual = "<?= $nomeUsuario ?>";
        const papelAtual = "<?= $papel ?>";
    </script>

    <nav class="navbar navbar-dark bg-primary mb-4">
        <div class="container d-flex justify-content-between">
            <span class="navbar-brand mb-0 h1">RoomSync - Olá, <?= ucfirst($nomeUsuario) ?> (<?= ucfirst($papel) ?>)</span>
            <a href="logout.php" class="btn btn-sm btn-outline-light">Sair</a>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-md-7 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">Buscar Disponibilidade da Sala</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Selecione a Sala</label>
                                <select class="form-select" id="salaBusca" onchange="montarGradeCinema()">
                                    <option value="Sala 101">Sala 101</option>
                                    <option value="Laboratório A">Laboratório A</option>
                                    <option value="Auditório">Auditório</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Data</label>
                                <input type="date" class="form-control" id="dataBusca" value="<?= date('Y-m-d') ?>" onchange="montarGradeCinema()">
                            </div>
                        </div>
                        
                        <hr>
                        <h6 class="text-center mb-3">Grade de Horários</h6>
                        <div class="row g-2" id="gradeCinema">
                            <p class="text-center text-muted">Carregando horários...</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><?= $papel === 'admin' ? 'Painel de Administração' : 'Minhas Reservas Ativas' ?></h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group" id="listaReservas">
                            <li class="list-group-item text-muted text-center">Carregando...</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="app.js"></script>
</body>
</html>
<script src="app.js?v=<?= time() ?>"></script>