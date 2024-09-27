<!-- /views/history.php -->
<?php
session_start();
include_once "../components/header.php";
include_once "../config/database.php";

// Verificar se o usuário está logado
if (!isset($_SESSION['employee_id'])) {
    header("Location: login.php");
    exit();
}

// Conectar ao banco de dados
$database = new Database();
$conn = $database->getConnection();

$employee_id = $_SESSION['employee_id'];

// Buscar o histórico de marcações de ponto do funcionário logado
$query = "SELECT date, clock_in, clock_in_lunch_start, clock_in_lunch_end, clock_out 
          FROM attendance 
          WHERE employee_id = :employee_id 
          ORDER BY date DESC";
$stmt = $conn->prepare($query);
$stmt->bindParam(':employee_id', $employee_id);
$stmt->execute();
$attendances = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
    <h2>Histórico de Pontos</h2>
    <p>Acompanhe suas marcações de ponto abaixo:</p>

    <?php if (count($attendances) > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Entrada</th>
                    <th>Saída para Almoço</th>
                    <th>Volta do Almoço</th>
                    <th>Saída</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($attendances as $attendance): ?>
                    <tr>
                        <td><?php echo $attendance['date']; ?></td>
                        <td><?php echo $attendance['clock_in'] ? $attendance['clock_in'] : '---'; ?></td>
                        <td><?php echo $attendance['clock_in_lunch_start'] ? $attendance['clock_in_lunch_start'] : '---'; ?></td>
                        <td><?php echo $attendance['clock_in_lunch_end'] ? $attendance['clock_in_lunch_end'] : '---'; ?></td>
                        <td><?php echo $attendance['clock_out'] ? $attendance['clock_out'] : '---'; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">Nenhum registro de ponto encontrado.</div>
    <?php endif; ?>
</div>

<?php include_once "../components/footer.php"; ?>

