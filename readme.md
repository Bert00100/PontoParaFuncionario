O que o Script Faz:
Apagar e Recriar o Banco de Dados:

O banco de dados attendance_db será apagado e recriado.
Criar as Tabelas employees e attendance:

A tabela employees armazena os dados dos funcionários, e a tabela attendance armazena as marcações de ponto.
Inserir Funcionários de Exemplo:

Três funcionários são adicionados: João Silva, Maria Santos e um usuário Admin. As senhas estão criptografadas com o algoritmo bcrypt.
Inserir Registros de Ponto de Exemplo:

São inseridos registros de ponto para os dias 20 e 21 de setembro de 2024 para João Silva e Maria Santos, com todas as quatro marcações (entrada, saída para almoço, volta do almoço e saída).
Um registro para o dia 22 de setembro mostra funcionários que ainda não finalizaram o expediente (sem marcações de saída).
Senhas de Exemplo:
As senhas para os funcionários de exemplo são as mesmas e estão criptografadas:
Senha original: password123
Próximos Passos:
Execute o Script no MySQL: Rode esse script no seu banco de dados MySQL para apagar o banco atual, recriá-lo e adicionar os registros de exemplo.

Testar o Sistema: Faça o cadastro no seu codigo e apos isso tente fazer login e registrar o ponto

