<?php
// database.php - Configuração do banco de dados
?>
<form id="database-form" class="needs-validation" novalidate>
    <h3>Configuração do Banco de Dados</h3>
    
    <div class="mb-3">
        <label class="form-label">Host do MySQL:</label>
        <input type="text" class="form-control" name="LivroOCC_BD" value="127.0.0.1" required>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Porta:</label>
        <input type="text" class="form-control" name="db_port" value="3306" required>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Nome do Banco:</label>
        <input type="text" class="form-control" name="db_name" required>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Usuário:</label>
        <input type="text" class="form-control" name="db_user" required>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Senha:</label>
        <input type="password" class="form-control" name="db_pass">
    </div>
    
    <div class="alert alert-warning">
        <strong>Atenção:</strong> O banco de dados será criado se não existir
    </div>
    
    <button type="submit" class="btn btn-primary">Testar Conexão & Continuar</button>
    
    <script>
    document.getElementById('database-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Simula teste de conexão
        fetch('test-database.php', {
            method: 'POST',
            body: new FormData(this)
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                nextStep();
            } else {
                alert('Erro na conexão: ' + data.error);
            }
        });
    });
    </script>
</form>