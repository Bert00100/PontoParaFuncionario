<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['employee_id'])) {
    header("Location: login.php");
    exit();
}

// Inclui o cabeçalho correto com base no cargo do usuário
include_once "../config/database.php";
include_once (isset($_SESSION['employee_role']) && $_SESSION['employee_role'] === 'admin') ? "../components/header.php" : "../components.header2.php";

// Conectar ao banco de dados
$database = new Database();
$conn = $database->getConnection();

// Verifica o cargo do usuário
$employee_id = $_SESSION['employee_id'];
$employee_role = $_SESSION['employee_role'];

// Para administradores, mostrar um seletor de funcionário
if ($employee_role === 'admin') {
    $selected_employee_id = isset($_POST['selected_employee']) ? $_POST['selected_employee'] : $employee_id;

    // Buscar todos os funcionários para o administrador selecionar
    $stmt_employees = $conn->prepare("SELECT id, name FROM employees");
    $stmt_employees->execute();
    $employees = $stmt_employees->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Para funcionários comuns, exibir apenas o próprio histórico
    $selected_employee_id = $employee_id;
}

// Buscar o histórico de marcações de ponto do funcionário selecionado
$stmt = $conn->prepare("SELECT date, clock_in, clock_in_lunch_start, clock_in_lunch_end, clock_out 
                        FROM attendance 
                        WHERE employee_id = :employee_id 
                        ORDER BY date DESC");
$stmt->bindParam(':employee_id', $selected_employee_id);
$stmt->execute();
$attendances = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    /* Garantir que o conteúdo não quebre linha dentro da célula */
    .nowrap-cell {
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }
</style>

<div class="container mt-5">
    <h2 class="mb-4">Histórico de Pontos</h2>
    
    <?php if ($employee_role === 'admin'): ?>
        <form method="POST" action="history.php" class="form-inline mb-4">
            <div class="form-group me-2">
                <label for="selected_employee" class="me-2">Selecione um funcionário:</label>
                <select name="selected_employee" id="selected_employee" class="form-select">
                    <?php foreach ($employees as $employee): ?>
                        <option value="<?php echo $employee['id']; ?>" <?php if ($selected_employee_id == $employee['id']) echo 'selected'; ?>>
                            <?php echo $employee['name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Ver Histórico</button>
        </form>
    <?php else: ?>
        <p>Acompanhe suas marcações de ponto abaixo:</p>
    <?php endif; ?>

    <?php if (count($attendances) > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Data</th>
                        <th>Registros</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($attendances as $attendance): ?>
                        <tr>
                            <td><?php echo $attendance['date']; ?></td>
                            <td class="nowrap-cell">
                                <?php echo $attendance['clock_in'] ? "Entrada: " . $attendance['clock_in'] : '---'; ?>
                                <?php echo $attendance['clock_in_lunch_start'] ? " | Saída para Almoço: " . $attendance['clock_in_lunch_start'] : ''; ?>
                                <?php echo $attendance['clock_in_lunch_end'] ? " | Volta do Almoço: " . $attendance['clock_in_lunch_end'] : ''; ?>
                                <?php echo $attendance['clock_out'] ? " | Saída: " . $attendance['clock_out'] : ''; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">Nenhum registro de ponto encontrado.</div>
    <?php endif; ?>
</div>

<?php include_once "../components/footer.php"; ?>
