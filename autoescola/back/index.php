<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Alunos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <center>
        <h1>Cadastro de Aluno</h1>
        <br>
        <form id="formAluno">
            <label for="nome">Nome completo:</label><br>
            <input type="text" id="nome" placeholder="Insira o nome" required /><br><br>

            <label for="email">E-mail:</label><br>
            <input type="email" id="email" placeholder="Insira o email" required /><br><br>

            <label for="telefone">Telefone:</label><br>
            <input type="text" id="telefone" placeholder="(00) 00000-0000" /><br><br>

            <label for="data_nascimento">Data de Nascimento:</label><br>
            <input type="date" id="data_nascimento" /><br><br>

            <button type="submit">Cadastrar Aluno</button><br><br>
        </form>

        <hr>

        <h2>Alunos Cadastrados</h2>
        <ul id="listaAlunos"></ul>
    </center>

    <script>
        const url = 'http://localhost/autoescola/back/index.php?rota=alunos';

        function carregarAlunos() {
            fetch(url)
                .then(resposta => resposta.json())
                .then(alunos => {
                    const lista = document.getElementById('listaAlunos');
                    lista.innerHTML = '';
                    alunos.forEach(aluno => {
                        lista.innerHTML += `<li><strong>${aluno.nome}</strong> - ${aluno.email}</li>`;
                    });
                })
                .catch(erro => console.error('Erro ao carregar alunos:', erro));
        }

        document.getElementById('formAluno').addEventListener('submit', function(e) {
            e.preventDefault();

            const dados = {
                nome: document.getElementById('nome').value,
                email: document.getElementById('email').value,
                telefone: document.getElementById('telefone').value,
                data_nascimento: document.getElementById('data_nascimento').value
            };

            fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(dados)
            })
            .then(resposta => resposta.json())
            .then(resultado => {
                alert(resultado.mensagem || resultado.erro);
                if (resultado.mensagem) {
                    document.getElementById('formAluno').reset();
                    carregarAlunos();
                }
            })
            .catch(erro => alert('Erro na requisição.'));
        });

        carregarAlunos();
    </script>
</body>
</html>
