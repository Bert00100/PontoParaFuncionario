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

// Verificar se o funcionário já registrou entrada hoje
$query = "SELECT * FROM attendance WHERE employee_id = :employee_id AND date = :today";
$stmt = $conn->prepare($query);
$stmt->bindParam(':employee_id', $employee_id);
$stmt->bindParam(':today', $today);
$stmt->execute();
$attendance = $stmt->fetch(PDO::FETCH_ASSOC);

$registro_feito = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selected_action = $_POST['action']; // Ação selecionada no dropdown

    // Registrar Entrada
    if ($selected_action == 'clock_in') {
        if (!$attendance) {
            $query = "INSERT INTO attendance (employee_id, clock_in, date) VALUES (:employee_id, NOW(), :date)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':employee_id', $employee_id);
            $stmt->bindParam(':date', $today);
            $stmt->execute();
            echo "<div class='alert alert-success'>Entrada registrada com sucesso!</div>";
            $registro_feito = true;
        } else {
            echo "<div class='alert alert-warning'>Entrada já foi registrada hoje.</div>";
        }
    }
    // Registrar Saída para Almoço
    elseif ($selected_action == 'clock_in_lunch_start') {
        if ($attendance && !$attendance['clock_in_lunch_start']) {
            $query = "UPDATE attendance SET clock_in_lunch_start = NOW() WHERE id = :id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':id', $attendance['id']);
            $stmt->execute();
            echo "<div class='alert alert-success'>Saída para almoço registrada com sucesso!</div>";
            $registro_feito = true;
        } else {
            echo "<div class='alert alert-warning'>Saída para almoço já foi registrada ou não há entrada registrada.</div>";
        }
    }
    // Registrar Volta do Almoço
    elseif ($selected_action == 'clock_in_lunch_end') {
        if ($attendance && $attendance['clock_in_lunch_start'] && !$attendance['clock_in_lunch_end']) {
            $query = "UPDATE attendance SET clock_in_lunch_end = NOW() WHERE id = :id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':id', $attendance['id']);
            $stmt->execute();
            echo "<div class='alert alert-success'>Volta do almoço registrada com sucesso!</div>";
            $registro_feito = true;
        } else {
            echo "<div class='alert alert-warning'>Volta do almoço já foi registrada ou não há saída para almoço registrada.</div>";
        }
    }
    // Registrar Saída
    elseif ($selected_action == 'clock_out') {
        if ($attendance && !$attendance['clock_out']) {
            $query = "UPDATE attendance SET clock_out = NOW() WHERE id = :id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':id', $attendance['id']);
            $stmt->execute();
            echo "<div class='alert alert-success'>Saída registrada com sucesso!</div>";
            $registro_feito = true;
        } else {
            echo "<div class='alert alert-warning'>Saída já foi registrada ou não há entrada registrada.</div>";
        }
    }

    // Se um registro foi feito, recarregar a página uma única vez
    if ($registro_feito) {
        echo "<script>window.location.href = 'attendance.php';</script>";
    }
}
?>

<div class="container mt-5 text-center">
    <h2>Registro de Ponto</h2>
    <form method="POST" action="attendance.php" class="form-group mx-auto" style="max-width: 400px;">
        <div class="mb-3">
            <label for="action" class="form-label">Selecione a ação</label>
            <select name="action" class="form-control" required>
                <option value="clock_in">Registrar Entrada</option>
                <option value="clock_in_lunch_start">Registrar Saída para Almoço</option>
                <option value="clock_in_lunch_end">Registrar Volta do Almoço</option>
                <option value="clock_out">Registrar Saída</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary w-100">Registrar</button>
    </form>

    <!-- Exibir relógio no meio da tela -->
    <div id="clock" class="mt-4" style="font-size: 2rem;"></div>

    <?php if ($attendance): ?>
        <p>Entrada registrada às: <?php echo $attendance['clock_in']; ?></p>
        <?php if ($attendance['clock_in_lunch_start']): ?>
            <p>Saída para almoço registrada às: <?php echo $attendance['clock_in_lunch_start']; ?></p>
        <?php endif; ?>
        <?php if ($attendance['clock_in_lunch_end']): ?>
            <p>Volta do almoço registrada às: <?php echo $attendance['clock_in_lunch_end']; ?></p>
        <?php endif; ?>
        <?php if ($attendance['clock_out']): ?>
            <p>Saída registrada às: <?php echo $attendance['clock_out']; ?></p>
        <?php endif; ?>
    <?php endif; ?>
</div>

<script>
    // Atualizar relógio a cada segundo
    setInterval(function() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        document.getElementById('clock').innerText = timeString;
    }, 1000);
</script>

<?php include_once "../components/footer.php"; ?>
