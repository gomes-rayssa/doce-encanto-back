<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include 'components/header-adm.php'; ?>
    <?php include 'components/sidebar.php'; ?>
    
    <main class="main-content">
        <div class="dashboard-header">
            <h1>Configurações</h1>
        </div>

        <!-- Administradores -->
        <div class="chart-card" style="margin-bottom: 2rem;">
            <div class="section-header">
                <h2>Administradores</h2>
                <button class="btn-primary" onclick="openAdminModal()">
                    <i class="fas fa-plus"></i> Novo Administrador
                </button>
            </div>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Email</th>
                            <th>Data de Criação</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>admin@doces.com</td>
                            <td>01/01/2024</td>
                            <td>
                                <span style="color: var(--text-light); font-size: 0.875rem;">Admin Principal</span>
                            </td>
                        </tr>
                        <tr>
                            <td>gerente@doces.com</td>
                            <td>15/02/2024</td>
                            <td>
                                <a href="#" onclick="deleteAdmin(2)" class="btn-icon"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Configurações do Site -->
        <div class="chart-card" style="margin-bottom: 2rem;">
            <h2>Configurações do Site</h2>
            <form id="siteConfigForm">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Nome da Loja</label>
                        <input type="text" name="store_name" value="Doces Artesanais">
                    </div>
                    <div class="form-group">
                        <label>Email de Contato</label>
                        <input type="email" name="contact_email" value="contato@doces.com">
                    </div>
                    <div class="form-group">
                        <label>Telefone</label>
                        <input type="tel" name="phone" value="(11) 3456-7890">
                    </div>
                    <div class="form-group">
                        <label>Taxa de Entrega (R$)</label>
                        <input type="number" name="delivery_fee" value="10.00" step="0.01">
                    </div>
                </div>
                <div class="form-group">
                    <label>Endereço da Loja</label>
                    <textarea name="store_address">Rua das Flores, 123 - Centro - São Paulo/SP - CEP: 12345-678</textarea>
                </div>
                <button type="submit" class="btn-primary">Salvar Configurações</button>
            </form>
        </div>

        <!-- Promoções -->
        <div class="chart-card">
            <div class="section-header">
                <h2>Promoções Ativas</h2>
                <button class="btn-primary" onclick="openPromotionModal()">
                    <i class="fas fa-plus"></i> Nova Promoção
                </button>
            </div>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nome da Promoção</th>
                            <th>Desconto</th>
                            <th>Válida Até</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Black Friday</td>
                            <td>30%</td>
                            <td>30/11/2024</td>
                            <td><span class="badge badge-approved">Ativa</span></td>
                            <td>
                                <a href="#" onclick="editPromotion(1)" class="btn-icon"><i class="fas fa-edit"></i></a>
                                <a href="#" onclick="deletePromotion(1)" class="btn-icon"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Modal Administrador -->
    <div id="adminModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Novo Administrador</h2>
                <button class="modal-close" onclick="closeAdminModal()">&times;</button>
            </div>
            <form id="adminForm">
                <div class="form-group">
                    <label>Email do Administrador *</label>
                    <input type="email" name="admin_email" required>
                </div>
                <div style="padding: 1rem; background-color: var(--light-bg); border-radius: 8px; margin: 1rem 0;">
                    <p style="margin: 0; color: var(--text-light); font-size: 0.875rem;">
                        <i class="fas fa-info-circle"></i> A senha padrão será: <strong>Doce2025@</strong>
                    </p>
                </div>
                <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem;">
                    <button type="button" class="btn-secondary" onclick="closeAdminModal()">Cancelar</button>
                    <button type="submit" class="btn-primary">Criar Administrador</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Promoção -->
    <div id="promotionModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Nova Promoção</h2>
                <button class="modal-close" onclick="closePromotionModal()">&times;</button>
            </div>
            <form id="promotionForm">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Nome da Promoção *</label>
                        <input type="text" name="promotion_name" required>
                    </div>
                    <div class="form-group">
                        <label>Desconto (%) *</label>
                        <input type="number" name="discount" min="0" max="100" required>
                    </div>
                    <div class="form-group">
                        <label>Data de Início *</label>
                        <input type="date" name="start_date" required>
                    </div>
                    <div class="form-group">
                        <label>Data de Término *</label>
                        <input type="date" name="end_date" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Descrição</label>
                    <textarea name="description"></textarea>
                </div>
                <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem;">
                    <button type="button" class="btn-secondary" onclick="closePromotionModal()">Cancelar</button>
                    <button type="submit" class="btn-primary">Criar Promoção</button>
                </div>
            </form>
        </div>
    </div>

    <script src="scripts/configuracoes.js"></script>
</body>
</html>
