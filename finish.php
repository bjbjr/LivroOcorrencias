<?php
// finish.php
exec('php ../artisan migrate --seed');
exec('php ../artisan storage:link');
exec('php ../artisan key:generate');
?>

<div class="text-center">
    <div class="alert alert-success">
        <h4><i class="bi bi-check-circle"></i> Instalação Concluída com Sucesso!</h4>
    </div>
    
    <div class="card mb-4">
        <div class="card-body">
            <h5>Dados de Acesso:</h5>
            <p><strong>URL:</strong> <a href="<?= $config['app_url'] ?>" target="_blank"><?= $config['app_url'] ?></a></p>
            <p><strong>Admin:</strong> <?= $config['admin_email'] ?></p>
            <p><strong>Senha:</strong> <?= $config['admin_password'] ?></p>
        </div>
    </div>
    
    <div class="alert alert-danger">
        <strong>Importante:</strong> Por segurança, renomeie ou remova a pasta <code>/install</code>
    </div>
    
    <a href="../" class="btn btn-lg btn-success">Acessar o Sistema</a>
</div>