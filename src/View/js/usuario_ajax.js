const BASE_DATAVIEW = '../../View/dataview/';

function MostrarAlerta(tipo, msg) {
    const icones = { success: 'check-circle', danger: 'times-circle', warning: 'exclamation-triangle', info: 'info-circle' };
    const icone = icones[tipo] || 'info-circle';
    const html = `
        <div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
            <i class="fas fa-${icone} me-2"></i>${msg}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>`;
    $('#alertBox').html(html);
    setTimeout(() => $('.alert').alert('close'), 4000);
}

function MostrarLoad() { 
    const el = document.getElementById('loadingOverlay');
    if (el) el.style.display = 'flex';
}
function EsconderLoad() { 
    const el = document.getElementById('loadingOverlay');
    if (el) el.style.display = 'none';
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

function ValidarEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

function ValidarFormLogin() {
    LimparErros();
    let valido = true;
    const email = document.getElementById('email').value.trim();
    const senha = document.getElementById('senha').value.trim();

    if (!email) {
        MostrarErro('email', 'Informe o e-mail.');
        valido = false;
    } else if (!ValidarEmail(email)) {
        MostrarErro('email', 'E-mail inválido.');
        valido = false;
    }

    if (!senha) {
        MostrarErro('senha', 'Informe a senha.');
        valido = false;
    } else if (senha.length < 4) {
        MostrarErro('senha', 'Senha muito curta.');
        valido = false;
    }

    return valido;
}

// Mostrar/esconder senha
document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.getElementById('toggleSenha');
    if (toggle) {
        toggle.addEventListener('click', function () {
            const senha = document.getElementById('senha');
            const icon = this.querySelector('i');
            if (senha.type === 'password') {
                senha.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                senha.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    }

    // Enter para logar
    document.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') Logar();
    });
});

function Logar() {
    if (!ValidarFormLogin()) return;

    const email = document.getElementById('email').value.trim();
    const senha = document.getElementById('senha').value.trim();

    MostrarLoad();
    $.ajax({
        type: 'post',
        url: BASE_DATAVIEW + 'usuario_dataview.php',
        data: { btn_login: 'ajx', email, senha },
        success: function (ret) {
            if (ret.status === 1) {
                sessionStorage.setItem('usuario', JSON.stringify({ nome: email.split('@')[0] }));
                window.location.href = 'listar_produto.php';
            } else {
                MostrarAlerta('danger', 'E-mail ou senha incorretos.');
            }
        },
        error: function () {
            MostrarAlerta('danger', 'Erro de conexão. Tente novamente.');
        },
        complete: function () { EsconderLoad(); }
    });
}

function Logout() {
    sessionStorage.clear();
    window.location.href = 'login.php';
}