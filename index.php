
<!-- /index.php -->
<?php
include_once "components/header.php";
?>

<div class="container mt-5">
    <h1 class="text-center">Bem-vindo ao Sistema de Ponto</h1>
    <p class="text-center">Gerencie suas marcações de ponto de forma fácil e rápida.</p>

    <div class="row text-center mt-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Registrar Novo Funcionário</h5>
                    <p class="card-text">Adicione novos funcionários ao sistema.</p>
                    <a href="views/register.php" class="btn btn-primary">Registrar Funcionário</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Bater Ponto</h5>
                    <p class="card-text">Registre suas marcações de entrada, saída para almoço, e final do expediente.</p>
                    <a href="views/attendance.php" class="btn btn-success">Registrar Ponto</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Ver Histórico de Pontos</h5>
                    <p class="card-text">Acompanhe o histórico de suas marcações de ponto.</p>
                    <a href="views/history.php" class="btn btn-info">Ver Histórico</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once "components/footer.php"; ?>

