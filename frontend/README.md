# Frontend para API PHP

Este é um frontend simples em HTML, CSS e JavaScript para consumir a API PHP que criamos anteriormente.

## Funcionalidades

- Listar todos os usuários
- Adicionar novo usuário
- Editar usuário existente
- Excluir usuário
- Notificações de sucesso/erro

## Como usar

1. Salve o arquivo `index.html` em um diretório separado do backend
2. Inicie a API PHP com Docker conforme as instruções anteriores
3. Abra o arquivo `index.html` em um navegador

## Configuração

Por padrão, o frontend está configurado para acessar a API em `http://localhost:8080/api`. Se sua API estiver em outro endereço, você precisará ajustar a constante `API_URL` no início do script JavaScript:

```javascript
const API_URL = 'http://seu-endereco/api';
```

## Possíveis melhorias

1. **Autenticação**: Adicionar login/logout e proteção para operações
2. **Paginação**: Para lidar com grandes volumes de dados
3. **Validação de formulários**: Melhorar a validação no lado do cliente
4. **Design responsivo**: Melhorar adaptação para dispositivos móveis
5. **Framework**: Migrar para um framework como React, Vue.js ou Angular para aplicações mais complexas

## Estrutura técnica

O frontend utiliza:
- HTML5 para estrutura
- CSS3 para estilização
- JavaScript (ES6+) para interação
- Fetch API para comunicação com o backend
- Design de interface simples e intuitivo

## Notas importantes

- Este frontend funciona com CORS habilitado na API
- Para uso em produção, considere servir este frontend através de um servidor web como Nginx ou Apache
- Em caso de problemas de CORS, verifique se os cabeçalhos estão configurados corretamente no backend