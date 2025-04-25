<!DOCTYPE html>
<html lang="pt-BR" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Ponto</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- CSS Personalizado -->
    <link rel="stylesheet" href="style/style.css">
</head>
<body class="d-flex flex-column h-100">

<?php include_once "components/header.php"; ?>

<main class="flex-shrink-0">
    <div class="container my-5">
        <div class="hero-section text-center py-5 mb-5 rounded-3" style="background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);">
            <h1 class="display-4 text-white fw-bold">Bem-vindo ao Sistema de Ponto</h1>
            <p class="lead text-white-50">Gerencie suas marcações de ponto de forma fácil e rápida.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body p-4 text-center">
                        <div class="icon-wrapper bg-primary bg-opacity-10 rounded-circle p-3 mb-3 mx-auto" style="width: 60px; height: 60px;">
                            <i class="fas fa-user-plus text-primary fs-4"></i>
                        </div>
                        <h5 class="card-title fw-bold">Registrar Novo Funcionário</h5>
                        <p class="card-text text-muted">Adicione novos funcionários ao sistema.</p>
                        <a href="views/register.php" class="btn btn-primary px-4 rounded-pill mt-2">Registrar Funcionário</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body p-4 text-center">
                        <div class="icon-wrapper bg-success bg-opacity-10 rounded-circle p-3 mb-3 mx-auto" style="width: 60px; height: 60px;">
                            <i class="fas fa-fingerprint text-success fs-4"></i>
                        </div>
                        <h5 class="card-title fw-bold">Bater Ponto</h5>
                        <p class="card-text text-muted">Registre suas marcações de entrada, saída para almoço, e final do expediente.</p>
                        <a href="views/attendance.php" class="btn btn-success px-4 rounded-pill mt-2">Registrar Ponto</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body p-4 text-center">
                        <div class="icon-wrapper bg-info bg-opacity-10 rounded-circle p-3 mb-3 mx-auto" style="width: 60px; height: 60px;">
                            <i class="fas fa-history text-info fs-4"></i>
                        </div>
                        <h5 class="card-title fw-bold">Ver Histórico de Pontos</h5>
                        <p class="card-text text-muted">Acompanhe o histórico de suas marcações de ponto.</p>
                        <a href="views/history.php" class="btn btn-info px-4 rounded-pill mt-2">Ver Histórico</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include_once "components/footer.php"; ?>

</body>
</html>