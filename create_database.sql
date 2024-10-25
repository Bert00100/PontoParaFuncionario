-- Apagar o banco de dados existente se já houver um com o nome "attendance_db"
DROP DATABASE IF EXISTS attendance_db;

-- Criar o banco de dados "attendance_db"
CREATE DATABASE attendance_db;

-- Selecionar o banco de dados recém-criado para uso
USE attendance_db;

-- Criar a tabela de funcionários para armazenar informações básicas dos funcionários
CREATE TABLE IF NOT EXISTS employees (
    id INT AUTO_INCREMENT PRIMARY KEY,  -- ID único para cada funcionário
    name VARCHAR(100) NOT NULL,  -- Nome do funcionário (não pode ser vazio)
    email VARCHAR(100) UNIQUE NOT NULL,  -- Email do funcionário (único e não pode ser vazio)
    password VARCHAR(255) NOT NULL,  -- Senha do funcionário, normalmente criptografada
    role VARCHAR(50),  -- Cargo do funcionário, como 'admin' ou 'employee'
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP  -- Data e hora de quando o funcionário foi criado
);

-- Criar a tabela de registros de ponto, onde cada marcação de entrada e saída será armazenada
CREATE TABLE IF NOT EXISTS attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,  -- ID único para cada registro de ponto
    employee_id INT,  -- ID do funcionário (referência para a tabela "employees")
    clock_in TIMESTAMP NULL,  -- Data e hora da entrada do funcionário
    clock_in_lunch_start TIMESTAMP NULL,  -- Data e hora de início do almoço
    clock_in_lunch_end TIMESTAMP NULL,  -- Data e hora de volta do almoço
    clock_out TIMESTAMP NULL,  -- Data e hora de saída do funcionário
    date DATE NOT NULL,  -- Data em que os registros foram feitos
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Data e hora de criação do registro
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE  -- Relaciona com a tabela "employees" e apaga os registros se o funcionário for removido
);

-- Criar a tabela para guardar as horas extras
CREATE TABLE IF NOT EXISTS hora_extra (
    id INT AUTO_INCREMENT PRIMARY KEY,  -- ID único para cada registro de hora extra
    employee_id INT,  -- Referência ao funcionário (chave estrangeira para "employees")
    clock_in TIMESTAMP NULL,  -- Registro de entrada para horas extras
    clock_out TIMESTAMP NULL,  -- Registro de saída para horas extras
    date DATE NOT NULL,  -- Data do registro de hora extra
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Data e hora de criação do registro
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE  -- Relaciona com a tabela "employees" e apaga os registros se o funcionário for removido
);

-- Criar a tabela para guardar declarações, como atrasos, e o motivo
CREATE TABLE IF NOT EXISTS declaration (
    id INT AUTO_INCREMENT PRIMARY KEY,  -- ID único para cada declaração
    employee_id INT NOT NULL,  -- Referência ao funcionário (chave estrangeira para "employees")
    declaration_date_start DATE NOT NULL,  -- Data de início do atraso/declaração
    declaration_date_end DATE NOT NULL,  -- Data final do atraso/declaração
    entry_time TIME NOT NULL,  -- Hora de entrada no período de atraso
    exit_time TIME NOT NULL,  -- Hora de saída no período de atraso
    reason TEXT NOT NULL,  -- Motivo do atraso ou outra declaração
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Data e hora de criação da declaração
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE  -- Relaciona com a tabela "employees" e apaga os registros se o funcionário for removido
);

-- Criar um índice na coluna "date" da tabela "attendance" para melhorar a performance em buscas pela data
CREATE INDEX idx_attendance_date ON attendance(date);

-- Inserir alguns funcionários de exemplo na tabela "employees"
INSERT INTO employees (name, email, password, role) VALUES
('João Silva', 'joao.silva@example.com', '$2y$10$e./7Esjm/5KcazUl36Uzoe9N5kTrphIb9kzPlxK9vA3JxG.AfFYeO', 'employee'),
('Maria Santos', 'maria.santos@example.com', '$2y$10$e./7Esjm/5KcazUl36Uzoe9N5kTrphIb9kzPlxK9vA3JxG.AfFYeO', 'employee'),
('Admin', 'admin@example.com', '$2y$10$e./7Esjm/5KcazUl36Uzoe9N5kTrphIb9kzPlxK9vA3JxG.AfFYeO', 'admin');

-- Inserir alguns registros de ponto de exemplo para João Silva (funcionário com ID 1)
INSERT INTO attendance (employee_id, clock_in, clock_in_lunch_start, clock_in_lunch_end, clock_out, date) VALUES
(1, '2024-09-20 08:00:00', '2024-09-20 12:00:00', '2024-09-20 13:00:00', '2024-09-20 17:00:00', '2024-09-20'),
(1, '2024-09-21 08:05:00', '2024-09-21 12:10:00', '2024-09-21 13:05:00', '2024-09-21 17:05:00', '2024-09-21');

-- Inserir alguns registros de ponto de exemplo para Maria Santos (funcionária com ID 2)
INSERT INTO attendance (employee_id, clock_in, clock_in_lunch_start, clock_in_lunch_end, clock_out, date) VALUES
(2, '2024-09-20 08:10:00', '2024-09-20 12:05:00', '2024-09-20 13:10:00', '2024-09-20 17:15:00', '2024-09-20'),
(2, '2024-09-21 08:00:00', '2024-09-21 12:00:00', '2024-09-21 13:00:00', '2024-09-21 17:00:00', '2024-09-21');

-- Inserir registros de ponto onde o funcionário ainda não finalizou o expediente
INSERT INTO attendance (employee_id, clock_in, date) VALUES
(1, '2024-09-22 08:00:00', '2024-09-22'),
(2, '2024-09-22 08:05:00', '2024-09-22');

-- Inserir um administrador de exemplo
INSERT INTO employees (name, email, password, role) 
VALUES ('Admin User', 'admin@example.com', 'admin123', 'admin');

-- Visualizar todos os funcionários inseridos na tabela
SELECT * FROM employees;

-- Visualizar todos os registros da tabela de declarações
SELECT * FROM declaration;

-- Excluir todos os funcionários da tabela "employees"
DELETE FROM employees;

-- Apagar a tabela de declarações (se você quiser recriá-la ou limpá-la)
DROP TABLE declaration;

