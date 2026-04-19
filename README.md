# 🛒 Sistema de Cadastro de Produtos

Sistema web completo para gerenciamento de produtos com autenticação de usuários, desenvolvido em PHP seguindo o padrão de arquitetura **MVC**.
Funcionalidades:
- 🔐 Login e logout com autenticação segura (bcrypt)
- 📦 Cadastro, edição e exclusão de produtos
- 🔍 Filtro e busca em tempo real na listagem
- ✅ Validação de formulários no front-end e no back-end
- ⚡ Comunicação assíncrona via Ajax (sem recarregar a página)
- 
Arquitetura
O projeto segue o padrão MVC + VO + SQL, com as responsabilidades bem separadas em camadas:

Navegador (JS/Ajax)
      ↓
dataview.php        ← recebe a requisição e retorna JSON
      ↓
Controller (CTRL)   ← valida as regras de negócio
      ↓
Model (MODEL)       ← executa as queries no banco via PDO
      ↓
MySQL               ← armazena os dados

Estrutura de Arquivos
├── index.php                  # Redireciona para o login
├── vendor/autoload.php        # Autoload do Composer
│
├── Config/
│   └── Conexao.php            # Conexão com o banco (Singleton)
│
├── VO/
│   ├── ProdutoVO.php          # Entidade Produto (getters/setters)
│   └── UsuarioVO.php          # Entidade Usuário (getters/setters)
│
├── Model/SQL/
│   ├── PRODUTO_SQL.php        # Queries SQL de produtos
│   └── USUARIO_SQL.php        # Queries SQL de usuários
│
├── Model/
│   ├── ProdutoMODEL.php       # Acesso ao banco — produtos
│   └── UsuarioMODEL.php       # Acesso ao banco — usuários
│
├── Controller/
│   ├── ProdutoCTRL.php        # Regras de negócio — produtos
│   └── UsuarioCTRL.php        # Regras de negócio — login/logout
│
├── View/dataview/
│   ├── produto_dataview.php   # Endpoint Ajax — produtos
│   └── usuario_dataview.php   # Endpoint Ajax — login/logout
│
└── View/js/
    ├── produto_ajax.js        # Front-end dos produtos (CRUD)
    └── usuario_ajax.js        # Front-end do login
Segurança

| Proteção | Como é aplicada |
|---|---|
| SQL Injection | PDO com `prepare()` e `bindValue()` |
| XSS | `htmlspecialchars()` nos setters do VO |
| Senha segura | `password_hash()` / `password_verify()` (bcrypt) |
| Acesso não autenticado | Verificação de `$_SESSION` no dataview |
| Validação dupla | Front-end (JS) + back-end (Controller) |

 Tecnologias

- PHP 8+ — back-end e lógica de negócio
- MySQL — banco de dados relacional
- PDO — acesso seguro ao banco
- Composer — autoload de classes (PSR-4)
- JavaScript / jQuery** — requisições Ajax e validações
- Bootstrap 5 — interface responsiva
- Font Awesome — ícones

Como executar

1. Clone o repositório
   ```bash
   git clone https://github.com/seu-usuario/seu-repositorio.git
   ```

2. Instale as dependências
   ```bash
   composer install
   ```

3. Importe o banco de dados e configure a conexão em `Conexao.php`
   ```php
   'mysql:host=localhost;dbname=db_produtos;charset=utf8'
   ```

4. Inicie o servidor e acesse `index.php`

Padrões de Projeto

- MVC — separação entre dados, lógica e apresentação
- Singleton — uma única instância de conexão com o banco
- Value Object (VO) — entidades encapsuladas com getters/setters
- Repository — queries SQL centralizadas em arquivos dedicados
