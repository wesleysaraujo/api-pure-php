# API REST com PHP Puro

Uma implementação simples de API REST em PHP puro (sem frameworks) com Docker e MySQL.

## Estrutura do Projeto

- `index.php`: Arquivo principal da API que gerencia rotas e requisições
- `Database.php`: Implementação Singleton para conexão com banco de dados
- `.htaccess`: Configurações para URLs amigáveis
- `database-example.sql`: Script para criação inicial do banco de dados
- `Dockerfile`: Configuração para construir a imagem Docker da API
- `docker-compose.yml`: Orquestração dos serviços (API, MySQL e PHPMyAdmin)

## Executando com Docker

### Pré-requisitos

- Docker
- Docker Compose

### Passos para Execução

1. Clone o repositório:
   ```bash
   git clone [seu-repositorio]
   cd [seu-repositorio]
   ```

2. Inicie os containers:
   ```bash
   docker-compose up -d
   ```

3. Acesse a API:
    - API: http://localhost:8080/api/
    - PHPMyAdmin: http://localhost:8081/ (usuário: apiuser, senha: apipassword)

### Endpoints Disponíveis

- `GET /api/`: Informações sobre a API
- `GET /api/users`: Listar todos os usuários
- `GET /api/users/{id}`: Obter usuário específico
- `POST /api/users`: Criar novo usuário
- `PUT /api/users/{id}`: Atualizar usuário existente
- `DELETE /api/users/{id}`: Remover usuário

## Exemplo de Uso

### Criar um Usuário
```bash
curl -X POST \
  http://localhost:8080/api/users \
  -H 'Content-Type: application/json' \
  -d '{
    "name": "Usuário Teste",
    "email": "teste@example.com"
}'
```

### Listar Usuários
```bash
curl -X GET http://localhost:8080/api/users
```

## Desenvolvimento

Para modificar a API, você pode editar os arquivos diretamente - as alterações serão refletidas automaticamente graças ao volume montado no container.

## Considerações de Segurança em Produção

- Altere as senhas definidas no `docker-compose.yml`
- Configure HTTPS para transmissão segura
- Implemente autenticação (como JWT)
- Adicione rate limiting para prevenir abusos
- Considere usar ferramentas como Docker Secrets para gerenciar credenciais