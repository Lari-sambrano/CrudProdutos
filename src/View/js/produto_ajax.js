const BASE_DV = '../../View/dataview/';
let produtoIdExcluir = null;
let todosProdutos = [];

// ========================
// UTILITÁRIOS
// ========================
function MostrarLoad() { document.getElementById('loadingOverlay').style.display = 'flex'; }
function EsconderLoad() { document.getElementById('loadingOverlay').style.display = 'none'; }

function MostrarAlerta(tipo, msg) {
    const icones = { success: 'check-circle', danger: 'times-circle', warning: 'exclamation-triangle' };
    const html = `
        <div class="alert alert-${tipo} alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-${icones[tipo]} me-2"></i>${msg}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>`;
    $('#alertBox').html(html);
    setTimeout(() => { const a = document.querySelector('.alert'); if (a) bootstrap.Alert.getOrCreateInstance(a).close(); }, 4000);
}

function LimparErros() {
    document.querySelectorAll('.error-msg').forEach(el => el.textContent = '');
    document.querySelectorAll('.field-error').forEach(el => el.classList.remove('field-error'));
}

function MostrarErro(campo, msg) {
    const input = document.getElementById(campo);
    const error = document.getElementById(campo + 'Error');
    if (input) input.classList.add('field-error');
    if (error) error.textContent = msg;
}

function FormatarMoeda(valor) {
    return parseFloat(valor).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
}

// Máscara de preço
document.addEventListener('DOMContentLoaded', function () {
    const precoInput = document.getElementById('preco');
    if (precoInput) {
        precoInput.addEventListener('input', function () {
            let v = this.value.replace(/\D/g, '');
            v = (parseInt(v) / 100).toFixed(2);
            this.value = v.replace('.', ',');
        });
    }
    ListarProdutos();
});

// ========================
// VALIDAÇÃO DO FORMULÁRIO
// ========================
function ValidarFormProduto() {
    LimparErros();
    let valido = true;

    const nome = document.getElementById('nome').value.trim();
    const preco = document.getElementById('preco').value.trim();
    const quantidade = document.getElementById('quantidade').value.trim();

    if (!nome) {
        MostrarErro('nome', 'O nome do produto é obrigatório.');
        valido = false;
    } else if (nome.length < 3) {
        MostrarErro('nome', 'Nome deve ter pelo menos 3 caracteres.');
        valido = false;
    }

    if (!preco || preco === '0,00' || preco === '') {
        MostrarErro('preco', 'Informe um preço válido.');
        valido = false;
    } else {
        const precoNum = parseFloat(preco.replace(',', '.'));
        if (isNaN(precoNum) || precoNum <= 0) {
            MostrarErro('preco', 'O preço deve ser maior que zero.');
            valido = false;
        }
    }

    if (quantidade === '') {
        MostrarErro('quantidade', 'Informe a quantidade.');
        valido = false;
    } else if (parseInt(quantidade) < 0) {
        MostrarErro('quantidade', 'Quantidade não pode ser negativa.');
        valido = false;
    }

    return valido;
}

// ========================
// LISTAR
// ========================
function ListarProdutos() {
    MostrarLoad();
    $.ajax({
        type: 'post',
        url: BASE_DV + 'produto_dataview.php',
        data: { listar: 'ajx' },
        success: function (dados) {
            todosProdutos = dados;
            RenderizarTabela(dados);
        },
        error: function () { MostrarAlerta('danger', 'Erro ao carregar produtos.'); },
        complete: function () { EsconderLoad(); }
    });
}

function RenderizarTabela(produtos) {
    const tbody = document.getElementById('tabelaProdutos');
    const contador = document.getElementById('contadorProdutos');

    contador.textContent = `${produtos.length} produto(s) encontrado(s)`;

    if (produtos.length === 0) {
        tbody.innerHTML = `
            <tr><td colspan="7">
                <div class="empty-state">
                    <i class="fas fa-box-open d-block"></i>
                    <p class="mb-0">Nenhum produto encontrado.</p>
                </div>
            </td></tr>`;
        return;
    }

    tbody.innerHTML = produtos.map((p, i) => `
        <tr>
            <td><span class="badge bg-secondary">${i + 1}</span></td>
            <td><strong>${p.nome}</strong></td>
            <td><span class="text-muted small">${p.descricao || '—'}</span></td>
            <td class="preco-col">${FormatarMoeda(p.preco)}</td>
            <td>
                <span class="badge ${parseInt(p.quantidade) <= 5 ? 'bg-warning text-dark' : 'bg-light text-dark'} border">
                    ${p.quantidade} un.
                </span>
            </td>
            <td><span class="badge-status-ativo"><i class="fas fa-circle me-1" style="font-size:0.5rem"></i>Ativo</span></td>
            <td class="text-center">
                <button class="btn btn-sm btn-outline-primary me-1" onclick="AbrirModalEditar(${p.id})" title="Editar">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-outline-danger" onclick="AbrirModalExcluir(${p.id}, '${p.nome.replace(/'/g, "\\'")}')" title="Excluir">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>`).join('');
}

// ========================
// FILTRO LOCAL
// ========================
function FiltrarTabela() {
    const busca = document.getElementById('campoBusca').value.toLowerCase();
    const campo = document.getElementById('filtroCampo').value;

    const filtrados = todosProdutos.filter(p => {
        if (campo === 'nome') return p.nome.toLowerCase().includes(busca);
        if (campo === 'descricao') return (p.descricao || '').toLowerCase().includes(busca);
        return p.nome.toLowerCase().includes(busca) || (p.descricao || '').toLowerCase().includes(busca);
    });

    RenderizarTabela(filtrados);
}

// ========================
// CADASTRAR
// ========================
function AbrirModalCadastrar() {
    LimparErros();
    document.getElementById('produtoId').value = '';
    document.getElementById('nome').value = '';
    document.getElementById('descricao').value = '';
    document.getElementById('preco').value = '';
    document.getElementById('quantidade').value = '';
    document.getElementById('modalTitulo').innerHTML = '<i class="fas fa-plus me-2"></i>Novo Produto';
    document.getElementById('btnSalvar').onclick = SalvarProduto;
    new bootstrap.Modal(document.getElementById('modalProduto')).show();
}

// ========================
// EDITAR
// ========================
function AbrirModalEditar(id) {
    LimparErros();
    MostrarLoad();
    $.ajax({
        type: 'post',
        url: BASE_DV + 'produto_dataview.php',
        data: { buscar_id: 'ajx', id },
        success: function (p) {
            if (!p || !p.id) { MostrarAlerta('danger', 'Produto não encontrado.'); return; }
            document.getElementById('produtoId').value = p.id;
            document.getElementById('nome').value = p.nome;
            document.getElementById('descricao').value = p.descricao || '';
            document.getElementById('preco').value = parseFloat(p.preco).toFixed(2).replace('.', ',');
            document.getElementById('quantidade').value = p.quantidade;
            document.getElementById('modalTitulo').innerHTML = '<i class="fas fa-edit me-2"></i>Editar Produto';
            document.getElementById('btnSalvar').onclick = SalvarProduto;
            new bootstrap.Modal(document.getElementById('modalProduto')).show();
        },
        error: function () { MostrarAlerta('danger', 'Erro ao buscar produto.'); },
        complete: function () { EsconderLoad(); }
    });
}

// ========================
// SALVAR (cadastrar ou editar)
// ========================
function SalvarProduto() {
    if (!ValidarFormProduto()) return;

    const id = document.getElementById('produtoId').value;
    const acao = id ? 'btn_alterar' : 'btn_cadastrar';

    const dados = {
        [acao]: 'ajx',
        nome: document.getElementById('nome').value.trim(),
        descricao: document.getElementById('descricao').value.trim(),
        preco: document.getElementById('preco').value.trim(),
        quantidade: document.getElementById('quantidade').value.trim()
    };
    if (id) dados.id = id;

    MostrarLoad();
    $.ajax({
        type: 'post',
        url: BASE_DV + 'produto_dataview.php',
        data: dados,
        success: function (ret) {
            if (ret.status === 1) {
                bootstrap.Modal.getInstance(document.getElementById('modalProduto')).hide();
                MostrarAlerta('success', id ? 'Produto alterado com sucesso!' : 'Produto cadastrado com sucesso!');
                ListarProdutos();
            } else {
                MostrarAlerta('danger', 'Erro ao salvar. Verifique os dados.');
            }
        },
        error: function () { MostrarAlerta('danger', 'Erro de conexão.'); },
        complete: function () { EsconderLoad(); }
    });
}

// ========================
// EXCLUIR
// ========================
function AbrirModalExcluir(id, nome) {
    produtoIdExcluir = id;
    document.getElementById('nomeProdutoExcluir').textContent = nome;
    new bootstrap.Modal(document.getElementById('modalExcluir')).show();
}

function ConfirmarExclusao() {
    if (!produtoIdExcluir) return;
    MostrarLoad();
    $.ajax({
        type: 'post',
        url: BASE_DV + 'produto_dataview.php',
        data: { excluir: 'ajx', id: produtoIdExcluir },
        success: function (ret) {
            bootstrap.Modal.getInstance(document.getElementById('modalExcluir')).hide();
            if (ret.status === 1) {
                MostrarAlerta('success', 'Produto excluído com sucesso!');
                ListarProdutos();
            } else {
                MostrarAlerta('danger', 'Erro ao excluir produto.');
            }
        },
        error: function () { MostrarAlerta('danger', 'Erro de conexão.'); },
        complete: function () { EsconderLoad(); produtoIdExcluir = null; }
    });
}