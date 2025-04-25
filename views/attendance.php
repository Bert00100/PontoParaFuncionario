<?php
session_start();
include_once "../components/header.php";
include_once "../config/database.php";

// Verificar se o usuário está logado
if (!isset($_SESSION['employee_id'])) {
    header("Location: login.php");
    exit();
}

$database = new Database();
$conn = $database->getConnection();
$employee_id = $_SESSION['employee_id'];
$today = date('Y-m-d');

// Obter informações do funcionário (apenas nome)
$employee_query = "SELECT name FROM employees WHERE id = :employee_id";
$employee_stmt = $conn->prepare($employee_query);
$employee_stmt->bindParam(':employee_id', $employee_id);
$employee_stmt->execute();
$employee = $employee_stmt->fetch(PDO::FETCH_ASSOC);

// Verificar registros de ponto
$query = "SELECT * FROM attendance WHERE employee_id = :employee_id AND date = :today ORDER BY date DESC, id DESC LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bindParam(':employee_id', $employee_id);
$stmt->bindParam(':today', $today);
$stmt->execute();
$attendance = $stmt->fetch(PDO::FETCH_ASSOC);

$registro_feito = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selected_action = $_POST['action'];
    
    // Registrar Entrada
    if ($selected_action == 'clock_in' && (!$attendance || !$attendance['clock_in'])) {
        if (!$attendance) {
            $query = "INSERT INTO attendance (employee_id, clock_in, date) VALUES (:employee_id, NOW(), :date)";
        } else {
            $query = "UPDATE attendance SET clock_in = NOW() WHERE id = :id";
        }
        $stmt = $conn->prepare($query);
        if (!$attendance) {
            $stmt->bindParam(':employee_id', $employee_id);
            $stmt->bindParam(':date', $today);
        } else {
            $stmt->bindParam(':id', $attendance['id']);
        }
        $stmt->execute();
        $registro_feito = true;
    }
    // Registrar Saída para Almoço
    elseif ($selected_action == 'clock_in_lunch_start' && $attendance && !$attendance['clock_in_lunch_start']) {
        $query = "UPDATE attendance SET clock_in_lunch_start = NOW() WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $attendance['id']);
        $stmt->execute();
        $registro_feito = true;
    }
    // Registrar Volta do Almoço
    elseif ($selected_action == 'clock_in_lunch_end' && $attendance && $attendance['clock_in_lunch_start'] && !$attendance['clock_in_lunch_end']) {
        $query = "UPDATE attendance SET clock_in_lunch_end = NOW() WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $attendance['id']);
        $stmt->execute();
        $registro_feito = true;
    }
    // Registrar Saída
    elseif ($selected_action == 'clock_out' && $attendance && !$attendance['clock_out']) {
        $query = "UPDATE attendance SET clock_out = NOW() WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $attendance['id']);
        $stmt->execute();
        $registro_feito = true;
    }

    if ($registro_feito) {
        $_SESSION['success_message'] = "Registro de " . [
            'clock_in' => 'entrada',
            'clock_in_lunch_start' => 'saída para almoço',
            'clock_in_lunch_end' => 'volta do almoço',
            'clock_out' => 'saída'
        ][$selected_action] . " realizado com sucesso!";
        header("Location: attendance.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Não foi possível registrar esta ação. Verifique se você já realizou os passos anteriores.";
    }
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Cabeçalho -->
            <div class="text-center mb-5">
                <h1 class="display-5 fw-bold text-primary mb-3">Registro de Ponto</h1>
                <div class="d-flex justify-content-center align-items-center mb-4">
                    <div class="me-3">
                        <i class="fas fa-user-circle fa-3x text-secondary"></i>
                    </div>
                    <div class="text-start">
                        <h5 class="mb-1"><?php echo htmlspecialchars($employee['name']); ?></h5>
                        <p class="text-muted mb-0"><?php echo date('d/m/Y'); ?></p>
                    </div>
                </div>
            </div>

            <!-- Mensagens -->
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['success_message']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['error_message']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>

            <!-- Cartões de Ação -->
            <div class="row g-4 mb-5">
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm <?php echo ($attendance && $attendance['clock_in']) ? 'border-success' : ''; ?>">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <div class="icon-circle <?php echo ($attendance && $attendance['clock_in']) ? 'bg-success text-white' : 'bg-light text-secondary'; ?>">
                                    <i class="fas fa-sign-in-alt"></i>
                                </div>
                            </div>
                            <h5 class="card-title">Entrada</h5>
                            <p class="card-text text-muted small">
                                <?php echo ($attendance && $attendance['clock_in']) ? date('H:i', strtotime($attendance['clock_in'])) : 'Não registrado'; ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm <?php echo ($attendance && $attendance['clock_in_lunch_start']) ? 'border-success' : ''; ?>">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <div class="icon-circle <?php echo ($attendance && $attendance['clock_in_lunch_start']) ? 'bg-success text-white' : 'bg-light text-secondary'; ?>">
                                    <i class="fas fa-utensils"></i>
                                </div>
                            </div>
                            <h5 class="card-title">Saída Almoço</h5>
                            <p class="card-text text-muted small">
                                <?php echo ($attendance && $attendance['clock_in_lunch_start']) ? date('H:i', strtotime($attendance['clock_in_lunch_start'])) : 'Não registrado'; ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm <?php echo ($attendance && $attendance['clock_in_lunch_end']) ? 'border-success' : ''; ?>">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <div class="icon-circle <?php echo ($attendance && $attendance['clock_in_lunch_end']) ? 'bg-success text-white' : 'bg-light text-secondary'; ?>">
                                    <i class="fas fa-undo"></i>
                                </div>
                            </div>
                            <h5 class="card-title">Volta Almoço</h5>
                            <p class="card-text text-muted small">
                                <?php echo ($attendance && $attendance['clock_in_lunch_end']) ? date('H:i', strtotime($attendance['clock_in_lunch_end'])) : 'Não registrado'; ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm <?php echo ($attendance && $attendance['clock_out']) ? 'border-success' : ''; ?>">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <div class="icon-circle <?php echo ($attendance && $attendance['clock_out']) ? 'bg-success text-white' : 'bg-light text-secondary'; ?>">
                                    <i class="fas fa-sign-out-alt"></i>
                                </div>
                            </div>
                            <h5 class="card-title">Saída</h5>
                            <p class="card-text text-muted small">
                                <?php echo ($attendance && $attendance['clock_out']) ? date('H:i', strtotime($attendance['clock_out'])) : 'Não registrado'; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulário de Registro -->
            <div class="card border-0 shadow">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div id="clock" class="display-4 fw-bold text-primary mb-3"></div>
                        <p class="text-muted">Horário atual</p>
                    </div>

                    <form method="POST" action="attendance.php">
                        <div class="mb-4">
                            <label for="action" class="form-label fw-bold">Selecione a ação</label>
                            <select name="action" id="action" class="form-select form-select-lg" required>
                                <option value="" disabled selected>Selecione uma opção</option>
                                <option value="clock_in" <?php echo ($attendance && $attendance['clock_in']) ? 'disabled' : ''; ?>>Registrar Entrada</option>
                                <option value="clock_in_lunch_start" <?php echo (!$attendance || !$attendance['clock_in'] || ($attendance && $attendance['clock_in_lunch_start'])) ? 'disabled' : ''; ?>>Registrar Saída para Almoço</option>
                                <option value="clock_in_lunch_end" <?php echo (!$attendance || !$attendance['clock_in_lunch_start'] || ($attendance && $attendance['clock_in_lunch_end'])) ? 'disabled' : ''; ?>>Registrar Volta do Almoço</option>
                                <option value="clock_out" <?php echo (!$attendance || ($attendance && $attendance['clock_out'])) ? 'disabled' : ''; ?>>Registrar Saída</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100 py-3">
                            <i class="fas fa-fingerprint me-2"></i> Registrar Ponto
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Relógio em tempo real
    function updateClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('pt-BR', { 
            hour: '2-digit', 
            minute: '2-digit', 
            second: '2-digit',
            hour12: false
        });
        document.getElementById('clock').innerText = timeString;
    }
    setInterval(updateClock, 1000);
    updateClock();

    // Desabilitar opções inválidas
    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('action');
        select.addEventListener('change', function() {
            // Adicionar feedback visual
            if (this.value) {
                this.classList.remove('is-invalid');
            }
        });
    });
</script>

<?php include_once "../components/footer.php"; ?>