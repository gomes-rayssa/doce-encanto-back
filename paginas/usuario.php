<?php
include 'header.php';

if (!isset($_SESSION['usuario_logado'])) {
    header('Location: login.php');
    exit;
}

$usuario = $_SESSION['usuario_data'] ?? [];
$endereco = $usuario['endereco'] ?? [];
?>

<link rel="stylesheet" href="usuario.css" />

<div id="notification" class="notification hidden">
    <span id="notification-message"></span>
</div>
<link rel="stylesheet" href="usuario.css" />
<link rel="stylesheet" href="admin.css" /> <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true): ?>
    <div class="admin-link-container container">
        <a href="admin.php" class="btn-admin-link">
            <i class="fas fa-shield-alt"></i>
            Acessar Painel Administrativo
        </a>
    </div>
<?php endif; ?>

<header class="header">
    <div class="container">
        <div class="header-content">
            <h1 class="header-title">Minha Conta</h1>
        </div>
    </div>
</header>

<main class="main">
    <div class="container">
        <div class="grid">
            <div class="main-column">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title-section">
                            <h2 class="card-title">
                                <i class="fas fa-user"></i>
                                Informações Pessoais
                            </h2>
                            <p class="card-description">
                                Gerencie suas informações de perfil
                            </p>
                        </div>
                        <div class="card-actions">
                            <a id="logout-btn" href="../logout.php" class="btn btn-outline btn-sm">
                                <i class="fas fa-sign-out-alt"></i>
                                Sair
                            </a>
                            <button id="edit-btn" class="btn btn-outline btn-sm">
                                <i class="fas fa-edit"></i>
                                Editar
                            </button>
                            <div id="edit-actions" class="edit-actions hidden">
                                <button id="cancel-btn" class="btn btn-outline btn-sm">
                                    <i class="fas fa-times"></i>
                                    Cancelar
                                </button>
                                <button id="save-btn" class="btn btn-primary btn-sm">
                                    <i class="fas fa-save"></i>
                                    Salvar
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="nome-completo">Nome Completo</label>
                                <input type="text" id="nome-completo" class="form-input" readonly
                                    value="<?php echo htmlspecialchars($usuario['nome'] ?? ''); ?>" />
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" class="form-input" readonly
                                    value="<?php echo htmlspecialchars($usuario['email'] ?? ''); ?>" />
                            </div>

                            <div class="form-group">
                                <label for="data-nascimento">Data de Nascimento</label>
                                <input type="date" id="data-nascimento" class="form-input" readonly
                                    value="<?php echo htmlspecialchars($usuario['dataNascimento'] ?? ''); ?>" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="card-title-section">
                            <h2 class="card-title">
                                <i class="fas fa-map-marker-alt"></i>
                                Endereço
                            </h2>
                            <p class="card-description">
                                Informações de entrega e cobrança
                            </p>
                        </div>
                        <div class="card-actions">
                            <button id="edit-address-btn" class="btn btn-outline btn-sm">
                                <i class="fas fa-edit"></i>
                                Editar Endereço
                            </button>
                            <div id="address-edit-actions" class="edit-actions hidden">
                                <button id="cancel-address-btn" class="btn btn-outline btn-sm">
                                    <i class="fas fa-times"></i>
                                    Cancelar
                                </button>
                                <button id="save-address-btn" class="btn btn-primary btn-sm">
                                    <i class="fas fa-save"></i>
                                    Salvar
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="form-grid address-grid">
                            <div class="form-group">
                                <label for="cep">CEP</label>
                                <input type="text" id="cep" class="form-input" placeholder="00000-000" readonly
                                    value="<?php echo htmlspecialchars($endereco['cep'] ?? ''); ?>" />
                            </div>
                            <div class="form-group span-2">
                                <label for="rua">Rua</label>
                                <input type="text" id="rua" class="form-input" readonly
                                    value="<?php echo htmlspecialchars($endereco['rua'] ?? ''); ?>" />
                            </div>
                            <div class="form-group">
                                <label for="numero">Número</label>
                                <input type="text" id="numero" class="form-input" readonly
                                    value="<?php echo htmlspecialchars($endereco['numero'] ?? ''); ?>" />
                            </div>
                            <div class="form-group">
                                <label for="bairro">Bairro</label>
                                <input type="text" id="bairro" class="form-input" readonly
                                    value="<?php echo htmlspecialchars($endereco['bairro'] ?? ''); ?>" />
                            </div>
                            <div class="form-group">
                                <label for="cidade">Cidade</label>
                                <input type="text" id="cidade" class="form-input" readonly
                                    value="<?php echo htmlspecialchars($endereco['cidade'] ?? ''); ?>" />
                            </div>
                            <div class="form-group">
                                <label for="estado">Estado</label>
                                <input type="text" id="estado" class="form-input" maxlength="2" readonly
                                    value="<?php echo htmlspecialchars($endereco['estado'] ?? ''); ?>" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card danger-card">
                    <div class="card-header">
                        <h2 class="card-title danger-title">Zona de Perigo</h2>
                    </div>
                    <div class="card-content">
                        <div class="danger-actions">
                            <button id="delete-btn" class="btn btn-danger">
                                <i class="fas fa-trash"></i>
                                Apagar Conta
                            </button>
                        </div>
                        <div class="delete-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            Esta ação não pode ser desfeita.
                        </div>
                    </div>
                </div>
            </div>

            <div class="sidebar">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Meus Pedidos</h2>
                    </div>
                    <div class="card-content">
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<div id="custom-popup" class="custom-popup hidden">
    <div class="popup-content">
        <p id="popup-message"></p>
        <div class="popup-actions">
            <button id="popup-yes-btn" class="btn btn-primary">Sim</button>
            <button id="popup-no-btn" class="btn btn-outline">Não</button>
        </div>
    </div>
</div>

<?php
include 'footer.php';
?>
<script src="usuario.js"></script>