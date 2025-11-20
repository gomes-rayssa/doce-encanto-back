<?php
session_start();
$pedidoId = $_GET['id'] ?? '1234';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Pedido #<?php echo $pedidoId; ?></title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include 'components/header-adm.php'; ?>
    <?php include 'components/sidebar.php'; ?>
    
    <main class="main-content">
        <div class="dashboard-header">
            <div>
                <a href="pedidos.php" style="color: var(--text-light); text-decoration: none; margin-bottom: 0.5rem; display: block;">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
                <h1>Pedido #<?php echo $pedidoId; ?></h1>
            </div>
            <button class="btn-primary" onclick="sendInvoice()">
                <i class="fas fa-file-invoice"></i> Enviar Nota Fiscal
            </button>
        </div>

        <div class="dashboard-grid" style="grid-template-columns: 2fr 1fr;">
            <!-- Itens do Pedido -->
            <div class="chart-card">
                <h2>Itens do Pedido</h2>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Produto</th>
                                <th>Quantidade</th>
                                <th>Preço Unit.</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Brigadeiro Gourmet</td>
                                <td>20</td>
                                <td>R$ 3,50</td>
                                <td>R$ 70,00</td>
                            </tr>
                            <tr>
                                <td>Beijinho</td>
                                <td>15</td>
                                <td>R$ 3,00</td>
                                <td>R$ 45,00</td>
                            </tr>
                            <tr>
                                <td>Cajuzinho</td>
                                <td>12</td>
                                <td>R$ 3,50</td>
                                <td>R$ 41,90</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" style="text-align: right; font-weight: bold;">Total:</td>
                                <td style="font-weight: bold; color: var(--primary-color);">R$ 156,90</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Status do Pedido -->
            <div class="chart-card">
                <h2>Status do Pedido</h2>
                <div class="form-group">
                    <label>Alterar Status</label>
                    <select id="orderStatus" onchange="updateStatus()">
                        <option value="novo" selected>Novo</option>
                        <option value="preparacao">Em Preparação</option>
                        <option value="enviado">Enviado</option>
                        <option value="entregue">Entregue</option>
                        <option value="cancelado">Cancelado</option>
                    </select>
                </div>
                <div style="margin-top: 1.5rem;">
                    <h3 style="font-size: 1rem; margin-bottom: 1rem;">Histórico de Status</h3>
                    <div style="border-left: 2px solid var(--border-color); padding-left: 1rem;">
                        <div style="margin-bottom: 1rem;">
                            <div style="font-weight: 600; color: var(--text-dark);">Novo</div>
                            <div style="font-size: 0.875rem; color: var(--text-light);">20/11/2024 14:30 - Admin</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="dashboard-grid">
            <!-- Informações de Pagamento -->
            <div class="chart-card">
                <h2>Informações de Pagamento</h2>
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div>
                        <strong>Método:</strong> Cartão de Crédito
                    </div>
                    <div>
                        <strong>Parcelamento:</strong> 3x de R$ 52,30
                    </div>
                    <div>
                        <strong>Status:</strong> <span class="badge badge-approved">Aprovado</span>
                    </div>
                </div>
            </div>

            <!-- Endereço de Entrega -->
            <div class="chart-card">
                <h2>Endereço de Entrega</h2>
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <div><strong>CEP:</strong> 12345-678</div>
                    <div><strong>Rua:</strong> Rua das Flores, 123</div>
                    <div><strong>Bairro:</strong> Centro</div>
                    <div><strong>Cidade:</strong> São Paulo - SP</div>
                    <div style="margin-top: 1rem;">
                        <strong>Contato:</strong> (11) 98765-4321
                    </div>
                </div>
            </div>

            <!-- Dados do Entregador -->
            <div class="chart-card">
                <h2>Dados do Entregador</h2>
                <div class="form-group">
                    <label>Selecionar Entregador</label>
                    <select>
                        <option value="">Selecione um entregador</option>
                        <option value="1">Carlos - Moto</option>
                        <option value="2">José - Carro</option>
                        <option value="3">Ana - Bicicleta</option>
                    </select>
                </div>
                <div id="deliveryInfo" style="margin-top: 1rem; display: none;">
                    <div><strong>Nome:</strong> Carlos Silva</div>
                    <div><strong>Veículo:</strong> Moto - ABC-1234</div>
                </div>
            </div>
        </div>
    </main>

    <script src="scripts/pedido-detalhe.js"></script>
</body>
</html>
