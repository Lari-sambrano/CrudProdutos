<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Gestão de Produtos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1e3a5f 0%, #2d6a9f 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            width: 100%;
            max-width: 420px;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            border: none;
        }
        .login-header {
            background: linear-gradient(135deg, #1e3a5f, #2d6a9f);
            border-radius: 16px 16px 0 0;
            padding: 2rem;
            text-align: center;
            color: white;
        }
        .login-header i { font-size: 2.5rem; margin-bottom: 0.5rem; }
        .login-header h4 { margin: 0; font-weight: 700; }
        .login-header p { margin: 0; opacity: 0.8; font-size: 0.9rem; }
        .login-body { padding: 2rem; }
        .form-control:focus { border-color: #2d6a9f; box-shadow: 0 0 0 0.2rem rgba(45,106,159,0.25); }
        .btn-login {
            background: linear-gradient(135deg, #1e3a5f, #2d6a9f);
            border: none;
            color: white;
            padding: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            border-radius: 8px;
        }
        .btn-login:hover { opacity: 0.9; color: white; }
        .input-group-text { background: #f8f9fa; border-right: none; }
        .form-control { border-left: none; }
        .alert { border-radius: 8px; font-size: 0.9rem; }
        .field-error { border-color: #dc3545 !important; }
        .error-msg { color: #dc3545; font-size: 0.8rem; margin-top: 4px; }
    </style>
</head>
<body>
    <div id="loadingOverlay" style="display:none; position:fixed; top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.4);z-index:9999;align-items:center;justify-content:center;">
    <div class="spinner-border text-light" style="width:3rem;height:3rem;"></div>
</div>
    <div class="card login-card">
        <div class="login-header">
            <i class="fas fa-boxes-stacked"></i>
            <h4>Gestão de Produtos</h4>
            <p>Faça login para continuar</p>
        </div>
        <div class="login-body">
            <div id="alertBox"></div>
            <div class="mb-3">
                <label class="form-label fw-semibold">E-mail</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope text-secondary"></i></span>
                    <input type="email" id="email" class="form-control" placeholder="seu@email.com">
                </div>
                <div id="emailError" class="error-msg"></div>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Senha</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock text-secondary"></i></span>
                    <input type="password" id="senha" class="form-control" placeholder="••••••••">
                    <button class="btn btn-outline-secondary" type="button" id="toggleSenha">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <div id="senhaError" class="error-msg"></div>
            </div>
            <button class="btn btn-login w-100" onclick="Logar()">
                <i class="fas fa-sign-in-alt me-2"></i>Entrar
            </button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="../js/usuario_ajax.js"></script>
</body>

</html>