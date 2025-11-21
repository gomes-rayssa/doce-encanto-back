<?php
session_start();

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['is_admin'] = true;
}
include 'db_config.php';

$produtos = [];
$sql = "SELECT id, nome, descricao, categoria, preco, estoque, imagem_url FROM produtos ORDER BY id ASC";
if ($result = $conn->query($sql)) {
    while ($row = $result->fetch_assoc()) {
        $row['status'] = ($row['estoque'] > 0) ? 'Em Estoque' : 'Esgotado';
        $row['badge_class'] = ($row['estoque'] > 0) ? 'badge-in-stock' : 'badge-out-of-stock';
        $produtos[] = $row;
    }
    $result->free();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Produtos</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <?php include 'components/header-adm.php'; ?>
    <?php include 'components/sidebar.php'; ?>

    <main class="main-content">
        <div class="dashboard-header">
            <h1>Produtos</h1>
            <button class="btn-primary" onclick="openProductModal()">
                <i class="fas fa-plus"></i> Novo Produto
            </button>
        </div>

        <div class="recent-orders">
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Imagem</th>
                            <th>Nome</th>
                            <th>Categoria</th>
                            <th>Preço</th>
                            <th>Estoque</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($produtos as $p): ?>
                            <tr>
                                <td>#<?php echo htmlspecialchars(str_pad($p['id'], 3, '0', STR_PAD_LEFT)); ?></td>
                                <td><img src="<?php echo htmlspecialchars($p['imagem_url'] ?? '/placeholder.svg?height=50&width=50'); ?>"
                                        alt="Produto" style="width: 50px; height: 50px; border-radius: 8px;"></td>
                                <td><?php echo htmlspecialchars($p['nome']); ?></td>
                                <td><?php echo htmlspecialchars($p['categoria']); ?></td>
                                <td>R$ <?php echo number_format($p['preco'], 2, ',', '.'); ?></td>
                                <td><?php echo htmlspecialchars($p['estoque']); ?></td>
                                <td><span
                                        class="badge <?php echo htmlspecialchars($p['badge_class']); ?>"><?php echo htmlspecialchars($p['status']); ?></span>
                                </td>
                                <td>
                                    <a href="#" onclick="editProduct(<?php echo $p['id']; ?>)" class="btn-icon"><i
                                            class="fas fa-edit"></i></a>
                                    <a href="#" onclick="deleteProduct(<?php echo $p['id']; ?>)" class="btn-icon"><i
                                            class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div id="productModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 id="modalTitle">Novo Produto</h2>
                    <button class="modal-close" onclick="closeProductModal()">&times;</button>
                </div>
                <form id="productForm">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Nome *</label>
                            <input type="text" name="nome" required>
                        </div>
                        <div class="form-group">
                            <label>Categoria *</label>
                            <select name="categoria" required>
                                <option value="">Selecione</option>
                                <option value="chocolates">Chocolates</option>
                                <option value="bolos">Bolos</option>
                                <option value="tortas">Tortas</option>
                                <option value="salgados">Salgados</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Preço (R$) *</label>
                            <input type="number" name="preco" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label>Quantidade em Estoque *</label>
                            <input type="number" name="estoque" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Descrição</label>
                        <textarea name="descricao"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Imagem do Produto</label>
                        <input type="file" name="imagem" accept="image/*" onchange="previewImage(event)">
                        <div class="image-upload-preview" id="imagePreview">
                            <span style="color: var(--text-light);">Nenhuma imagem selecionada</span>
                        </div>
                    </div>
                    <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem;">
                        <button type="button" class="btn-secondary" onclick="closeProductModal()">Cancelar</button>
                        <button type="submit" class="btn-primary">Salvar Produto</button>
                    </div>
                </form>
            </div>
        </div>

    </main>

    <script src="scripts/produtos.js"></script>
</body>

</html>