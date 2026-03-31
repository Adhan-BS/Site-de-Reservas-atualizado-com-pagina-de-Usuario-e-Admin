# 📅 RoomSync - Sistema de Reservas de Salas

Um sistema web completo para gerenciamento e reserva de salas em diferentes horários. O projeto simula uma interface visual de grade (estilo reserva de assentos), onde os usuários podem visualizar a disponibilidade, agendar horários e gerenciar suas reservas ativas.

## ✨ Funcionalidades

* **Grade Dinâmica Visual:** Interface em blocos (Verde/Vermelho) que atualiza em tempo real a disponibilidade das salas selecionadas.
* **Controle de Acesso (RBAC):** * **Administradores:** Podem ver o nome de quem fez qualquer reserva e têm privilégios para cancelar qualquer agendamento.
  * **Clientes:** Podem reservar horários livres, mas só enxergam as próprias informações. Reservas de terceiros aparecem como "Ocupado (Privado)" para garantir a privacidade. Só podem cancelar as próprias reservas.
* **Validação Anti-Conflito:** O backend em PHP impede que dois usuários reservem a mesma sala no mesmo horário.
* **API RESTful:** Comunicação fluida entre o Front-end e o Banco de Dados sem precisar recarregar a página (usando Vanilla JavaScript `fetch`).
* **Banco de Dados Portátil:** Utiliza SQLite, dispensando a instalação de servidores de banco de dados pesados.

## 🛠️ Tecnologias Utilizadas

* **Front-end:** HTML5, CSS3, Bootstrap 5 e Vanilla JavaScript (ES6).
* **Back-end:** PHP 8+ (API para processamento das rotas GET, POST e PUT).
* **Banco de Dados:** SQLite (via PDO).

## 🚀 Como executar este projeto localmente

1. Certifique-se de ter um servidor local instalado (como o **XAMPP** ou **WAMP**).
2. Clone este repositório para a pasta pública do seu servidor (ex: `C:\xampp\htdocs\roomsync`).
3. Inicie o servidor **Apache**.
4. Abra o navegador e acesse o arquivo de setup para gerar o banco de dados pela primeira vez: (se não consguir rode diretamente o setup_banco.php no navegador depois feche e va para o login)
5. **Usuários de teste:  Admin: `admin` / Senha: `1234`
* Cliente: `cliente` / Senha: `1234`

👥 Equipe de Desenvolvimento Projeto desenvolvido pelos alunos do 3º período de Ciência da Computação:

Adhan Borges de Souza

Elano Serrão

Gabriel Farias

Izabel Cristina Martins dos Santos

Luigi Gabriel Lopes dos Santos

Nicolas Alegre Ferreira Melo

Pietro
