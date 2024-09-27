-- Apagar o banco de dados se ele existir
DROP DATABASE IF EXISTS attendance_db;

-- Criar o banco de dados novamente
CREATE DATABASE attendance_db;

-- Usar o banco de dados recém-criado
USE attendance_db;

-- Criar a tabela de funcionários
CREATE TABLE IF NOT EXISTS employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,  -- Senha criptografada
    role VARCHAR(50),  -- Role pode ser 'admin' ou 'employee'
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Criar a tabela de registros de ponto (attendance)
CREATE TABLE IF NOT EXISTS attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT,
    clock_in TIMESTAMP NULL,  -- Registro de entrada
    clock_in_lunch_start TIMESTAMP NULL,  -- Saída para almoço
    clock_in_lunch_end TIMESTAMP NULL,  -- Volta do almoço
    clock_out TIMESTAMP NULL,  -- Registro de saída
    date DATE NOT NULL,  -- Data do registro
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE
);

-- Criar um índice para melhorar a performance em consultas pela data
CREATE INDEX idx_attendance_date ON attendance(date);

-- Inserir alguns funcionários de exemplo
INSERT INTO employees (name, email, password, role) VALUES
('João Silva', 'joao.silva@example.com', '$2y$10$e./7Esjm/5KcazUl36Uzoe9N5kTrphIb9kzPlxK9vA3JxG.AfFYeO', 'employee'),
('Maria Santos', 'maria.santos@example.com', '$2y$10$e./7Esjm/5KcazUl36Uzoe9N5kTrphIb9kzPlxK9vA3JxG.AfFYeO', 'employee'),
('Admin', 'admin@example.com', '$2y$10$e./7Esjm/5KcazUl36Uzoe9N5kTrphIb9kzPlxK9vA3JxG.AfFYeO', 'admin');

-- Inserir registros de ponto de exemplo para João Silva
INSERT INTO attendance (employee_id, clock_in, clock_in_lunch_start, clock_in_lunch_end, clock_out, date) VALUES
(1, '2024-09-20 08:00:00', '2024-09-20 12:00:00', '2024-09-20 13:00:00', '2024-09-20 17:00:00', '2024-09-20'),
(1, '2024-09-21 08:05:00', '2024-09-21 12:10:00', '2024-09-21 13:05:00', '2024-09-21 17:05:00', '2024-09-21');

-- Inserir registros de ponto de exemplo para Maria Santos
INSERT INTO attendance (employee_id, clock_in, clock_in_lunch_start, clock_in_lunch_end, clock_out, date) VALUES
(2, '2024-09-20 08:10:00', '2024-09-20 12:05:00', '2024-09-20 13:10:00', '2024-09-20 17:15:00', '2024-09-20'),
(2, '2024-09-21 08:00:00', '2024-09-21 12:00:00', '2024-09-21 13:00:00', '2024-09-21 17:00:00', '2024-09-21');

-- Exemplo de registros de ponto onde o funcionário ainda não finalizou o expediente
INSERT INTO attendance (employee_id, clock_in, date) VALUES
(1, '2024-09-22 08:00:00', '2024-09-22'),
(2, '2024-09-22 08:05:00', '2024-09-22');

