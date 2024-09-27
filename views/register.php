<!-- /views/register.php -->
<?php
include_once "../components/header.php";
include_once "../config/database.php";

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);  // Hash da senha

    // Conexão com o banco
    $database = new Database();
    $conn = $database->getConnection();

    // Insere o novo funcionário
    $query = "INSERT INTO employees (name, email, password) VALUES (:name, :email, :password)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Funcionário registrado com sucesso!</div>";
    } else {
        echo "<div class='alert alert-danger'>Não foi possível registrar o funcionário.</div>";
    }
}
?>

<h2>Registrar um Novo Funcionário</h2>
<form method="POST" action="register.php" class="form-group">
    <div class="mb-3">
        <label for="name" class="form-label">Nome</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Senha</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Registrar</button>
</form>

<?php include_once "../components/footer.php"; ?>

