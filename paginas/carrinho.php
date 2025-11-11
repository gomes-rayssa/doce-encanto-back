<?php
include 'header.php';

$carrinho = $_SESSION['carrinho'] ?? [];
$total = 0;
?>
<link rel="stylesheet" href="carrinho.css" />

<h1>Seu Carrinho</h1>
<div class="carrinho-container">

    <ul id="lista-carrinho" class="lista-carrinho">
        <?php if (empty($carrinho)): ?>
            <div class="carrinho-vazio">
                <i class="fas fa-shopping-cart"></i>
                <h3>Seu carrinho está vazio</h3>
                <p>Adicione alguns produtos deliciosos!</p>
                <a href="../index.php" class="btn-continuar">Continuar Comprando</a>
            </div>
        <?php else: ?>
            <?php foreach ($carrinho as $id => $item): ?>
                <?php
                $subtotal = $item['preco'] * $item['quantidade'];
                $total += $subtotal;
                ?>
                <li class="item-carrinho" data-id="<?php echo $id; ?>">
                    <div class="item-imagem">
                        <img src="<?php echo htmlspecialchars($item['imagem'] ?? '../assets/logos/logo-navbar.jpg'); ?>"
                            alt="<?php echo htmlspecialchars($item['nome']); ?>" />
                    </div>
                    <div class="item-info">
                        <h3><?php echo htmlspecialchars($item['nome']); ?></h3>
                        <p class="item-categoria"><?php echo htmlspecialchars($item['categoria'] ?? 'Produto'); ?></p>
                        <p class="item-preco">R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?></p>
                    </div>
                    <div class="item-quantidade">
                        <button onclick="alterarQuantidade('<?php echo $id; ?>', <?php echo $item['quantidade'] - 1; ?>)"
                            class="btn-quantidade">-</button>
                        <span class="quantidade"><?php echo $item['quantidade']; ?></span>
                        <button onclick="alterarQuantidade('<?php echo $id; ?>', <?php echo $item['quantidade'] + 1; ?>)"
                            class="btn-quantidade">+</button>
                    </div>
                    <div class="item-total">
                        <p>R$ <?php echo number_format($subtotal, 2, ',', '.'); ?></p>
                    </div>
                    <div class="item-remover">
                        <button onclick="removerItem('<?php echo $id; ?>')" class="btn-remover">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>

    <div class="carrinho-resumo">
        <div class="carrinho-total">Total: R$ <span id="total"><?php echo number_format($total, 2, ',', '.'); ?></span>
        </div>
        <div class="carrinho-acoes">
            <button id="limpar-carrinho" class="btn-limpar" <?php echo empty($carrinho) ? 'disabled' : ''; ?>>
                <i class="fas fa-trash"></i> Limpar Carrinho
            </button>
            <button id="finalizar-compra" class="btn-finalizar" <?php echo empty($carrinho) ? 'disabled' : ''; ?>>
                <i class="fas fa-credit-card"></i> Finalizar Compra
            </button>
        </div>
    </div>
</div>

<div id="modal-compra-sucesso" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h2>Compra Concluída</h2>
        <p>Obrigado pela sua compra!</p>
    </div>
</div>

<?php
include 'footer.php';
?>
<script src="carrinho.js"></script>