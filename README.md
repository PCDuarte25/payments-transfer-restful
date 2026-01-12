# Pagamentos e transferências - Backend

## Instalação

### Pré-requisitos

- Docker and Docker Compose instalados

### Configuração

1. **Clone o repositório**
   ```bash
   git clone https://github.com/PCDuarte25/payments-transfer-restful.git
   cd payments-transfer-restful
   ```

2. **Crie um arquivo `.env` baseado no `.env.example`**
    ```bash
    cp .env.example .env
    ```

3. **Configure as variáveis de banco de dados no `.env`**
    ```bash
    DB_CONNECTION=mysql
    DB_HOST=db
    DB_PORT=3306
    DB_DATABASE=payments
    DB_USERNAME=user
    DB_PASSWORD=password
    DB_ROOT_PASSWORD=root
    ```

### Execução do projeto
1. **Inicie os containers**
   ```bash
   docker-compose up -d --build
   ```

2. **Execute as migrations e as seeders**
   ```bash
   docker-compose exec web php artisan migrate --seed
   ```
   ps: Se a migration falhar, espere um pouco antes de rodar, pois o banco pode ainda não ter sido inicializado corretamente.

   As seeders irão criar 1 usuário comum e 1 usuário lojista, ambos com 500 unidades de saldo.

## API endpoints

A API estará disponível em `http://localhost:9000` e todas as requisições para os endpoints da API precisam do Header `Accept: application/json`

### Autenticação

* `POST /api/login`: Recebe os dados: `email` e `password` e retorna o usuário logado e o `token` de autenticação para API.
* `POST /api/logout`: Desloga o usuário que estava logado.

### Usuários

* `POST /api/v1/users`: Cria um novo usuário
```bash
{
  "full_name": "Fulano da Silva",
  "document": "123.456.789-01",
  "email": "fulano.silva@exemplo.com",
  "password": "SenhaSegura123",
  "user_type": "common"
}
```
* `PUT /api/v1/users`: Atualiza um usuário
```bash
{
  "full_name": "Sicrano da Silva",
  "document": "123.456.789-02",
  "email": "fulano.silva@exemplo.com",
  "password": "SenhaSegura123",
  "user_type": "common"
}
```
* `DELETE /api/v1/users/{user_id}`: Remove o usuário com o ID indicado no path

### Transações

* `POST /api/v1/transactions`: Cria uma nova transação entre 2 usuários
```bash
{
  "payer_id": 1,
  "recipient_id": 2,
  "amount": 50
}
```

### Envio de e-mails/sms

1. **Rode a queue**
   ```bash
   docker-compose exec web php artisan queue:work
   ```

2. **Efetue uma transação via endpoint que tenha recebido sucesso na autorização externa**
* `POST /api/v1/transactions`:
```bash
{
  "payer_id": 1,
  "recipient_id": 2,
  "amount": 50
}
```

Após isso sera criado um evento de pagamento concluído, e o listener irá capturar e enviar para a fila, que por fim irá rodar assíncronamente o pedido e emitir a notificação para o usuário que recebeu a transação.

### Testes unitários
* Para rodar os testes unitários execute o comando:
    ```bash
    docker compose exec -it web php artisan test tests/Unit/Application/UseCases/TransactionUseCases/Cases/CreateTransactionTest.php
    ```
