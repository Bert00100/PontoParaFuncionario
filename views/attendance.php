<!-- /views/attendance.php -->
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
$today = date('Y-m-d');

// Verificar se o funcionário já bateu o ponto de entrada hoje
$query = "SELECT * FROM attendance WHERE employee_id = :employee_id AND date = :today";
$stmt = $conn->prepare($query);
$stmt->bindParam(':employee_id', $employee_id);
$stmt->bindParam(':today', $today);
$stmt->execute();
$attendance = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar qual marcação está sendo feita
    if (!$attendance) {
        // Registrar o horário de entrada
        $query = "INSERT INTO attendance (employee_id, clock_in, date) VALUES (:employee_id, NOW(), :date)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':employee_id', $employee_id);
        $stmt->bindParam(':date', $today);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Entrada registrada com sucesso!</div>";
        } else {
            echo "<div class='alert alert-danger'>Erro ao registrar a entrada.</div>";
        }
    } elseif ($attendance && !$attendance['clock_in_lunch_start']) {
        // Registrar a saída para o almoço
        $query = "UPDATE attendance SET clock_in_lunch_start = NOW() WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $attendance['id']);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Saída para almoço registrada com sucesso!</div>";
        } else {
            echo "<div class='alert alert-danger'>Erro ao registrar a saída para almoço.</div>";
        }
    } elseif ($attendance && !$attendance['clock_in_lunch_end']) {
        // Registrar a volta do almoço
        $query = "UPDATE attendance SET clock_in_lunch_end = NOW() WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $attendance['id']);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Volta do almoço registrada com sucesso!</div>";
        } else {
            echo "<div class='alert alert-danger'>Erro ao registrar a volta do almoço.</div>";
        }
    } elseif ($attendance && !$attendance['clock_out']) {
        // Registrar a saída
        $query = "UPDATE attendance SET clock_out = NOW() WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $attendance['id']);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Saída registrada com sucesso!</div>";
        } else {
            echo "<div class='alert alert-danger'>Erro ao registrar a saída.</div>";
        }
    }
}

// Atualizar os dados de registro após a submissão do formulário
$stmt->execute();
$attendance = $stmt->fetch(PDO::FETCH_ASSOC);

// Exibir o formulário de registro de ponto
?>

<div class="container mt-5">
    <h2>Registro de Ponto</h2>
    <?php if (!$attendance): ?>
        <form method="POST" action="attendance.php" class="form-group">
            <button type="submit" class="btn btn-primary">Registrar Entrada</button>
        </form>
    <?php elseif ($attendance && !$attendance['clock_in_lunch_start']): ?>
        <form method="POST" action="attendance.php" class="form-group">
            <button type="submit" class="btn btn-warning">Registrar Saída para Almoço</button>
        </form>
        <p>Entrada registrada às: <?php echo $attendance['clock_in']; ?></p>
    <?php elseif ($attendance && !$attendance['clock_in_lunch_end']): ?>
        <form method="POST" action="attendance.php" class="form-group">
            <button type="submit" class="btn btn-warning">Registrar Volta do Almoço</button>
        </form>
        <p>Saída para almoço registrada às: <?php echo $attendance['clock_in_lunch_start']; ?></p>
    <?php elseif ($attendance && !$attendance['clock_out']): ?>
        <form method="POST" action="attendance.php" class="form-group">
            <button type="submit" class="btn btn-danger">Registrar Saída</button>
        </form>
        <p>Volta do almoço registrada às: <?php echo $attendance['clock_in_lunch_end']; ?></p>
    <?php else: ?>
        <p>Todos os horários registrados hoje. Entrada: <?php echo $attendance['clock_in']; ?>, Saída para almoço: <?php echo $attendance['clock_in_lunch_start']; ?>, Volta do almoço: <?php echo $attendance['clock_in_lunch_end']; ?>, Saída: <?php echo $attendance['clock_out']; ?></p>
    <?php endif; ?>
</div>

<?php
// Exibir o histórico de ponto do usuário logado
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

