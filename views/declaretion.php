<?php
session_start();
include_once "../components/header.php";
include_once "../config/database.php";

// Verifica se o usuário está logado
if (!isset($_SESSION['employee_id'])) {
    echo "<div class='alert alert-danger'>Acesso negado. Por favor, faça login.</div>";
    include_once "../components/footer.php";
    exit();
}

// Conexão com o banco de dados
$database = new Database();
$conn = $database->getConnection();

// Verifica o papel do usuário
$employee_id = $_SESSION['employee_id'];
$role = $_SESSION['employee_role'];  // 'admin' ou 'employee'

// Se o usuário for admin, ele pode selecionar um funcionário para ver o histórico
$selected_employee_id = $employee_id;  // Valor padrão para employees
if ($role === 'admin' && isset($_POST['selected_employee_id'])) {
    $selected_employee_id = $_POST['selected_employee_id'];  // Recebe o ID do funcionário selecionado
}

// Processa o formulário de registro de atraso se enviado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register_declaration'])) {
    $declaration_date = $_POST['declaration_date'];
    $entry_time = $_POST['entry_time'];
    $exit_time = $_POST['exit_time'];
    $reason = $_POST['reason'];

    // Insere a declaração de atraso
    $query = "INSERT INTO declaration (employee_id, declaration_date, entry_time, exit_time, reason) 
              VALUES (:employee_id, :declaration_date, :entry_time, :exit_time, :reason)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':employee_id', $employee_id);
    $stmt->bindParam(':declaration_date', $declaration_date);
    $stmt->bindParam(':entry_time', $entry_time);
    $stmt->bindParam(':exit_time', $exit_time);
    $stmt->bindParam(':reason', $reason);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Atraso registrado com sucesso!</div>";
    } else {
        echo "<div class='alert alert-danger'>Não foi possível registrar o atraso.</div>";
    }
}

// Para Admin: Puxar a lista de funcionários para o dropdown
$employees = [];
if ($role === 'admin') {
    $query = "SELECT id, name FROM employees";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Consulta o histórico de declarações
$query = "SELECT id, declaration_date, entry_time, exit_time, reason 
          FROM declaration
          WHERE employee_id = :employee_id
          ORDER BY declaration_date DESC";
$stmt = $conn->prepare($query);
$stmt->bindParam(':employee_id', $selected_employee_id);
$stmt->execute();
$declarations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Registrar Atraso</h2>
<form method="POST" action="" class="form-group">
    <div class="mb-3">
        <label for="declaration_date" class="form-label">Data do Atraso</label>
        <input type="date" name="declaration_date" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="entry_time" class="form-label">Hora de Entrada</label>
        <input type="time" name="entry_time" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="exit_time" class="form-label">Hora de Saída</label>
        <input type="time" name="exit_time" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="reason" class="form-label">Motivo</label>
        <textarea name="reason" class="form-control" rows="4" required></textarea>
    </div>
    <button type="submit" name="register_declaration" class="btn btn-primary">Registrar Atraso</button>
</form>

<?php if ($role === 'admin'): ?>
<h2>Selecionar Funcionário</h2>
<form method="POST" action="" class="form-group">
    <div class="mb-3">
        <label for="selected_employee_id" class="form-label">Selecionar Funcionário</label>
        <select name="selected_employee_id" class="form-control" required>
            <?php foreach ($employees as $employee): ?>
                <option value="<?php echo $employee['id']; ?>" <?php echo $employee['id'] == $selected_employee_id ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($employee['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-secondary">Ver Histórico</button>
</form>
<?php endif; ?>

<h2>Histórico de Declarações</h2>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Data</th>
            <th>Hora de Entrada</th>
            <th>Hora de Saída</th>
            <th>Motivo</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($declarations as $declaration): ?>
        <tr>
            <td><?php echo htmlspecialchars($declaration['declaration_date']); ?></td>
            <td><?php echo htmlspecialchars($declaration['entry_time']); ?></td>
            <td><?php echo htmlspecialchars($declaration['exit_time']); ?></td>
            <td><?php echo htmlspecialchars($declaration['reason']); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include_once "../components/footer.php"; ?>
