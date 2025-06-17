<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Instalação - Livro de Ocorrências</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h2 class="text-center">Instalação do Sistema de Ocorrências</h2>
            </div>
            
            <div class="card-body" id="install-steps">
                <!-- Etapas serão carregadas via AJAX aqui -->
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Carregando...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Controle do fluxo de instalação
    const steps = [
        'welcome.php',
        'requirements.php',
        'database.php',
        'admin.php',
        'config.php',
        'finish.php'
    ];

    let currentStep = 0;

    function loadStep(stepIndex) {
        fetch(steps[stepIndex])
            .then(response => response.text())
            .then(data => {
                document.getElementById('install-steps').innerHTML = data;
            });
    }

    function nextStep() {
        currentStep++;
        if(currentStep < steps.length) {
            loadStep(currentStep);
        }
    }

    // Inicia o processo
    document.addEventListener('DOMContentLoaded', () => loadStep(0));
    </script>
</body>
</html>