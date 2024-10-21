<?php
session_start();
include_once "../components/header.php";
include_once "../config/database.php";

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Conexão com o banco de dados
    $database = new Database();
    $conn = $database->getConnection();

    // Verifica se o email existe no banco
    $query = "SELECT id, name, password, role FROM employees WHERE email = :email";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $stored_password = $row['password'];

        // Verifica se a senha está correta
        if ($password === $stored_password) {
            // Iniciar a sessão e armazenar o ID e o cargo do funcionário
            $_SESSION['employee_id'] = $row['id'];
            $_SESSION['employee_name'] = $row['name'];
            $_SESSION['employee_role'] = $row['role']; // Armazena o cargo na sessão

            // Redirecionar para a página de Attendance ou outra página principal
            header("Location: attendance.php");
            exit();
        } else {
            echo "<div class='alert alert-danger'>Senha inválida.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Email não encontrado.</div>";
    }
}
?>

<h2>Login</h2>
<form method="POST" action="login.php" class="form-group">
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Senha</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Entrar</button>
</form>

<?php include_once "../components/footer.php"; ?>
