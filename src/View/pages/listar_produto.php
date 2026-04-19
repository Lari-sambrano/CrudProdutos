<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Produtos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f0f4f8; }
        .navbar-custom {
            background: linear-gradient(135deg, #1e3a5f, #2d6a9f);
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        .card { border: none; border-radius: 12px; box-shadow: 0 2px 15px rgba(0,0,0,0.08); }
        .card-header-custom {
            background: linear-gradient(135deg, #1e3a5f, #2d6a9f);
            color: white;
            border-radius: 12px 12px 0 0 !important;
            padding: 1.25rem 1.5rem;
        }
        .btn-novo {
            background: #28a745;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
        }
        .btn-novo:hover { background: #218838; color: white; }
        .table thead th {
            background: #f8f9fa;
            font-weight: 700;
            color: #495057;
            border-bottom: 2px solid #dee2e6;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .table tbody tr:hover { background: #f0f4f8; }
        .badge-status-ativo { background: #d4edda; color: #155724; padding: 4px 10px; border-radius: 20px; font-size: 0.8rem; }
        .preco-col { font-weight: 700; color: #1e3a5f; }
        .search-box { border-radius: 8px; border: 2px solid #dee2e6; }
        .search-box:focus { border-color: #2d6a9f; box-shadow: none; }
        .empty-state { text-align: center; padding: 3rem; color: #adb5bd; }
        .empty-state i { font-size: 3rem; margin-bottom: 1rem; }
        .modal-header-custom { background: linear-gradient(135deg, #1e3a5f, #2d6a9f); color: white; border-radius: 12px 12px 0 0; }
        .modal-content { border: none; border-radius: 12px; }
        .field-error { border-color: #dc3545 !important; }
        .error-msg { color: #dc3545; font-size: 0.8rem; margin-top: 4px; }
        #loadingOverlay {
            display: none;
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.4); z-index: 9999;
            align-items: center; justify-content: center;
        }
        .spinner-border { width: 3rem; height: 3rem; }
    </style>
</head>
<body>

<!-- Loading -->
<div id="loadingOverlay" style="display:none; position:fixed; top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.4);z-index:9999;align-items:center;justify-content:center;">
    <div class="spinner-border text-light"></div>
</div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-custom mb-4">
    <div class="container">
        <span class="navbar-brand text-white fw-bold"><i class="fas fa-boxes-stacked me-2"></i>Gestão de Produtos</span>
        <div class="ms-auto">
            <span class="text-white me-3"><i class="fas fa-user-circle me-1"></i><span id="nomeUsuario"></span></span>
            <button class="btn btn-outline-light btn-sm" onclick="Logout()">
                <i class="fas fa-sign-out-alt me-1"></i>Sair
            </button>
        </div>
    </div>
</nav>

<!-- Alertas -->
<div class="container">
    <div id="alertBox" class="mb-3"></div>

    <div class="card">
        <div class="card-header-custom d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Produtos Cadastrados</h5>
            <button class="btn btn-novo" onclick="AbrirModalCadastrar()">
                <i class="fas fa-plus me-1"></i>Novo Produto
            </button>
        </div>
        <div class="card-body">
            <!-- Busca -->
            <div class="row mb-3">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fas fa-search text-secondary"></i></span>
                        <input type="text" id="campoBusca" class="form-control search-box" placeholder="Buscar produto..." oninput="FiltrarTabela()">
                    </div>
                </div>
                <div class="col-md-3">
                    <select id="filtroCampo" class="form-select search-box" onchange="FiltrarTabela()">
                        <option value="todos">Filtrar por: Todos</option>
                        <option value="nome">Nome</option>
                        <option value="descricao">Descrição</option>
                    </select>
                </div>
                <div class="col-md-4 text-end">
                    <span class="text-muted small" id="contadorProdutos"></span>
                </div>
            </div>

            <!-- Tabela -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th>Preço</th>
                            <th>Quantidade</th>
                            <th>Status</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody id="tabelaProdutos"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cadastrar/Editar -->
<div class="modal fade" id="modalProduto" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title" id="modalTitulo"><i class="fas fa-box me-2"></i>Produto</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" id="produtoId">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Nome do Produto <span class="text-danger">*</span></label>
                        <input type="text" id="nome" class="form-control" placeholder="Ex: Notebook Dell Inspiron">
                        <div id="nomeError" class="error-msg"></div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Quantidade <span class="text-danger">*</span></label>
                        <input type="number" id="quantidade" class="form-control" placeholder="0" min="0">
                        <div id="quantidadeError" class="error-msg"></div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Preço (R$) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="text" id="preco" class="form-control" placeholder="0,00">
                        </div>
                        <div id="precoError" class="error-msg"></div>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Descrição</label>
                        <textarea id="descricao" class="form-control" rows="3" placeholder="Descreva o produto..."></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary fw-semibold px-4" id="btnSalvar" onclick="SalvarProduto()">
                    <i class="fas fa-save me-1"></i>Salvar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Confirmar Exclusão -->
<div class="modal fade" id="modalExcluir" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white border-0" style="border-radius:12px 12px 0 0">
                <h5 class="modal-title"><i class="fas fa-trash me-2"></i>Confirmar Exclusão</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4">
                <i class="fas fa-exclamation-triangle text-warning" style="font-size:3rem"></i>
                <p class="mt-3 mb-0">Tem certeza que deseja excluir o produto <strong id="nomeProdutoExcluir"></strong>?</p>
                <p class="text-muted small">Esta ação não pode ser desfeita.</p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger fw-semibold px-4" onclick="ConfirmarExclusao()">
                    <i class="fas fa-trash me-1"></i>Excluir
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="../js/usuario_ajax.js"></script>
<script src="../js/produto_ajax.js"></script>

<script>
    // Verifica sessão e carrega nome
    const sessao = JSON.parse(sessionStorage.getItem('usuario') || '{}');
    if (!sessao.nome) {
        window.location.href = 'login.php';
    } else {
        document.getElementById('nomeUsuario').textContent = sessao.nome;
        ListarProdutos();
    }
</script>
</body>
</html>