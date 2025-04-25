# 🕒 Sistema de Relógio de Ponto Gratuito

Este projeto é um sistema simples de **relógio de ponto** que pode ser usado gratuitamente por pequenos negócios, com cadastro de funcionários, login seguro, registro de ponto, horas extras e declarações de justificativa.  
Desenvolvido como parte do Projeto Integrador Transdisciplinar em Análise e Desenvolvimento de Sistemas I.

---

## 🚀 O Que Este Script Faz

### 🔄 Apaga e Recria o Banco de Dados
- O banco `attendance_db` será excluído (caso exista) e recriado do zero.

### 🧱 Cria as Tabelas Necessárias

- `employees`: Armazena os dados dos funcionários e administradores.
- `attendance`: Registra as marcações de ponto (entrada, almoço e saída).
- `hora_extra`: Guarda as horas extras trabalhadas.
- `declaration`: Permite o registro de declarações como atrasos e seus motivos.

### 👨‍💼 Insere um Administrador de Exemplo
- Nome: Admin User  
- Email: admin@example.com  
- Senha: admin123  
- Cargo: admin

---

## 📌 Estrutura das Tabelas

### `employees`
- `id`: ID do funcionário (chave primária)
- `name`: Nome
- `email`: E-mail único
- `password`: Senha (criptografada recomendável)
- `role`: Cargo (ex: admin, employee)
- `created_at`: Data de criação do cadastro

### `attendance`
- `id`: ID do registro de ponto
- `employee_id`: Referência ao funcionário
- `clock_in`, `clock_in_lunch_start`, `clock_in_lunch_end`, `clock_out`: Horários registrados
- `date`: Data da marcação
- `created_at`: Data de criação do registro

### `hora_extra`
- `id`: ID do registro de hora extra
- `employee_id`: Referência ao funcionário
- `clock_in`, `clock_out`: Horários de início e fim da hora extra
- `date`: Data do registro
- `created_at`: Data de criação

### `declaration`
- `id`: ID da declaração
- `employee_id`: Referência ao funcionário
- `declaration_date_start`, `declaration_date_end`: Período da justificativa
- `entry_time`, `exit_time`: Horários da ocorrência
- `reason`: Motivo da declaração
- `created_at`: Data de criação

---

## ✅ Como Usar

1. **Instale o XAMPP (ou outro ambiente com PHP + MySQL).**
2. **Execute o script SQL em seu gerenciador de banco de dados (MySQL Workbench, DBeaver, phpMyAdmin, etc.):**
   - Ele irá apagar o banco atual `attendance_db` (se existir), recriá-lo e configurar toda a estrutura necessária.
3. **Configure o seu projeto PHP para conectar ao banco `attendance_db`.**
4. **Acesse via navegador (`localhost` ou endereço configurado).**
5. **Use o usuário Admin de exemplo para começar a cadastrar funcionários e gerenciar os pontos.**

---

## 🔧 Requisitos

- PHP 7.4 ou superior  
- MySQL 5.7 ou superior  
- Navegador moderno  
- Ambiente local (XAMPP, WAMP, Laragon, etc.)

---

## 🔒 Segurança Recomendada

- Utilize **bcrypt** ou outro algoritmo seguro para criptografar as senhas.
- Nunca armazene senhas em texto puro em produção.

---

## 📌 Próximos Passos

- Interface para marcação de ponto via QR Code ou código do funcionário.
- Geração de relatórios mensais (PDF/Excel).
- Sistema de permissões mais robusto.
- Modo mobile responsivo para tablets e celulares.

---

## 📜 Licença

Este projeto é de uso **livre e gratuito**.  
Sinta-se à vontade para usar em seu negócio local, adaptar ou contribuir com melhorias!

---

