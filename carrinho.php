<?php
session_start();
include 'db_config.php';

// Inicializar o carrinho se não existir
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

// Calcular total
$total = 0;
if (!empty($_SESSION['carrinho'])) {
    foreach ($_SESSION['carrinho'] as $item) {
        $total += $item['preco'] * $item['quantidade'];
    }
}

include 'header.php';
?>

<link rel="stylesheet" href="carrinho.css">

<div class="carrinho-container">
    <div class="carrinho-principal">
        <h1>Meu Carrinho</h1>

        <?php if (empty($_SESSION['carrinho'])): ?>
            <div class="carrinho-vazio">
                <i class="fas fa-shopping-bag" aria-hidden="true"></i>
                <h2>Seu carrinho está vazio</h2>
                <p>Adicione produtos deliciosos ao seu carrinho!</p>
                <a href="bolos.php" class="btn-continuar-comprando">
                    <i class="fas fa-arrow-left" aria-hidden="true"></i> Ir às Compras
                </a>
            </div>
        <?php else: ?>
            <ul class="lista-carrinho" role="list">
                <?php foreach ($_SESSION['carrinho'] as $index => $item): ?>
                    <li class="item-carrinho" role="listitem">
                        <div class="item-imagem">
                            <img src="<?php echo htmlspecialchars($item['imagem']); ?>"
                                alt="<?php echo htmlspecialchars($item['nome']); ?>">
                        </div>
                        <div class="item-info">
                            <h3><?php echo htmlspecialchars($item['nome']); ?></h3>
                            <span class="item-categoria"><?php echo htmlspecialchars($item['categoria'] ?? 'Produto'); ?></span>
                        </div>
                        <div class="item-quantidade">
                            <button class="btn-quantidade" onclick="alterarQuantidade(<?php echo $index; ?>, -1)"
                                aria-label="Diminuir quantidade">
                                <i class="fas fa-minus" aria-hidden="true"></i>
                            </button>
                            <span class="quantidade-valor"><?php echo $item['quantidade']; ?></span>
                            <button class="btn-quantidade" onclick="alterarQuantidade(<?php echo $index; ?>, 1)"
                                aria-label="Aumentar quantidade">
                                <i class="fas fa-plus" aria-hidden="true"></i>
                            </button>
                        </div>
                        <div class="item-preco">
                            <strong>R$ <?php echo number_format($item['preco'] * $item['quantidade'], 2, ',', '.'); ?></strong>
                        </div>
                        <button class="btn-remover" onclick="removerItem(<?php echo $index; ?>)"
                            aria-label="Remover <?php echo htmlspecialchars($item['nome']); ?> do carrinho">
                            <i class="fas fa-trash" aria-hidden="true"></i>
                        </button>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <?php if (!empty($_SESSION['carrinho'])): ?>
        <aside class="resumo-pedido" role="complementary" aria-labelledby="resumo-title">
            <h2 id="resumo-title">Resumo do Pedido</h2>
            <div class="resumo-linha">
                <span>Subtotal:</span>
                <strong>R$ <?php echo number_format($total, 2, ',', '.'); ?></strong>
            </div>
            <div class="resumo-linha">
                <span>Frete:</span>
                <strong>R$ 10,00</strong>
            </div>
            <hr>
            <div class="resumo-total">
                <span>Total:</span>
                <strong>R$ <?php echo number_format($total + 10, 2, ',', '.'); ?></strong>
            </div>
            <button class="btn-finalizar" onclick="abrirModalPagamento()" aria-label="Finalizar compra">
                <i class="fas fa-check-circle" aria-hidden="true"></i> Finalizar Compra
            </button>
            <a href="bolos.php" class="btn-continuar-comprando">
                <i class="fas fa-arrow-left" aria-hidden="true"></i> Continuar Comprando
            </a>
        </aside>
    <?php endif; ?>
</div>

<!-- Modal de Pagamento -->
<div id="modal-pagamento" class="modal" role="dialog" aria-labelledby="modal-pagamento-title" aria-modal="true"
    aria-hidden="true">
    <div class="modal-content">
        <button class="modal-close" aria-label="Fechar modal de pagamento">
            <i class="fas fa-times" aria-hidden="true"></i>
        </button>
        <h2 id="modal-pagamento-title">Finalizar Pagamento</h2>

        <form id="form-pagamento" action="processa_carrinho.php" method="POST">
            <div class="form-section">
                <h3>Método de Pagamento</h3>
                <div class="payment-methods" role="radiogroup" aria-label="Escolha o método de pagamento">
                    <label class="payment-option">
                        <input type="radio" name="metodo-pagamento" value="pix" required aria-required="true">
                        <div class="payment-card">
                            <i class="fas fa-qrcode" aria-hidden="true"></i>
                            <span>PIX</span>
                        </div>
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="metodo-pagamento" value="cartao" required aria-required="true">
                        <div class="payment-card">
                            <i class="fas fa-credit-card" aria-hidden="true"></i>
                            <span>Cartão de Crédito</span>
                        </div>
                    </label>
                </div>
            </div>

            <fieldset id="pix-section" class="payment-section" style="display: none;" aria-hidden="true">
                <legend>Pagamento via PIX</legend>
                <div class="pix-info">
                    <div class="qr-code-placeholder" aria-label="QR Code para pagamento">
                        <i class="fas fa-qrcode" aria-hidden="true"></i>
                        <p>QR Code será gerado após confirmação</p>
                    </div>
                    <p class="pix-instrucoes">
                        Após confirmar, você receberá um QR Code para realizar o pagamento via PIX.
                        O pedido será processado automaticamente após a confirmação do pagamento.
                    </p>
                </div>
            </fieldset>

            <fieldset id="cartao-section" class="payment-section" style="display: none;" aria-hidden="true">
                <legend>Dados do Cartão</legend>
                <div class="form-group">
                    <label for="numero-cartao">Número do Cartão</label>
                    <input type="text" id="numero-cartao" name="numero-cartao" placeholder="0000 0000 0000 0000"
                        maxlength="19" aria-required="true" autocomplete="cc-number">
                </div>

                <div class="form-group">
                    <label for="nome-cartao">Nome no Cartão</label>
                    <input type="text" id="nome-cartao" name="nome-cartao" placeholder="Nome como está no cartão"
                        required aria-required="true" autocomplete="cc-name">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="validade">Validade</label>
                        <input type="text" id="validade" name="validade" placeholder="MM/AA" maxlength="5" required
                            aria-required="true" autocomplete="cc-exp">
                    </div>
                    <div class="form-group">
                        <label for="cvv">CVV</label>
                        <input type="text" id="cvv" name="cvv" placeholder="123" maxlength="4" required
                            aria-required="true" aria-describedby="cvv-help" autocomplete="cc-csc">
                        <button type="button" class="cvv-help" aria-label="Ajuda sobre CVV">
                            <i class="fas fa-question-circle" aria-hidden="true"></i>
                        </button>
                        <span class="help-text sr-only" id="cvv-help">Código de 3 ou 4 dígitos no verso do
                            cartão</span>
                    </div>
                </div>

                <div id="parcelas-section" class="form-group">
                    <label for="parcelas">Parcelas</label>
                    <select id="parcelas" name="parcelas" aria-label="Escolha o número de parcelas">
                        <option value="1">1x de R$ <?php echo number_format($total + 10, 2, ',', '.'); ?> sem juros
                        </option>
                        <option value="2">2x de R$ <?php echo number_format(($total + 10) / 2, 2, ',', '.'); ?> sem
                            juros</option>
                        <option value="3">3x de R$ <?php echo number_format(($total + 10) / 3, 2, ',', '.'); ?> sem
                            juros</option>
                        <option value="4">4x de R$ <?php echo number_format(($total + 10) / 4, 2, ',', '.'); ?> sem
                            juros</option>
                        <option value="5">5x de R$ <?php echo number_format(($total + 10) / 5, 2, ',', '.'); ?> sem
                            juros</option>
                        <option value="6">6x de R$ <?php echo number_format(($total + 10) / 6, 2, ',', '.'); ?> sem
                            juros</option>
                    </select>
                </div>
            </fieldset>

            <div class="form-section">
                <h3>Endereço de Entrega</h3>
                <?php if (isset($_SESSION['usuario_logado']) && isset($_SESSION['usuario_data']['endereco'])): ?>
                    <?php $endereco = $_SESSION['usuario_data']['endereco']; ?>
                    <div class="endereco-salvo">
                        <p><strong>Entregar em:</strong></p>
                        <p><?php echo htmlspecialchars($endereco['rua'] ?? ''); ?>,
                            <?php echo htmlspecialchars($endereco['numero'] ?? ''); ?>
                        </p>
                        <p><?php echo htmlspecialchars($endereco['bairro'] ?? ''); ?> -
                            <?php echo htmlspecialchars($endereco['cidade'] ?? ''); ?>,
                            <?php echo htmlspecialchars($endereco['estado'] ?? ''); ?>
                        </p>
                        <p>CEP: <?php echo htmlspecialchars($endereco['cep'] ?? ''); ?></p>
                    </div>
                <?php else: ?>
                    <p class="aviso-login" role="alert">
                        <i class="fas fa-exclamation-triangle" aria-hidden="true"></i>
                        Faça login para usar seu endereço salvo ou informe um endereço de entrega.
                    </p>
                <?php endif; ?>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-cancelar-pagamento">Cancelar</button>
                <button type="submit" class="btn-confirmar-pagamento">
                    <i class="fas fa-check" aria-hidden="true"></i> Confirmar Pagamento
                </button>
            </div>
        </form>
    </div>
</div>

<div id="modal-compra-sucesso" class="modal" role="dialog" aria-labelledby="modal-sucesso-title" aria-modal="true"
    aria-hidden="true">
    <div class="modal-content">
        <div class="success-icon" aria-hidden="true">
            <i class="fas fa-check-circle"></i>
        </div>
        <h2 id="modal-sucesso-title">Compra Realizada com Sucesso!</h2>
        <p>Seu pedido foi confirmado e está sendo preparado.</p>
        <p class="pedido-numero">Número do Pedido: <strong>#<?php echo rand(1000, 9999); ?></strong></p>
        <button class="btn-fechar-sucesso" aria-label="Fechar e voltar para página inicial">
            <i class="fas fa-home" aria-hidden="true"></i> Voltar para Home
        </button>
    </div>
</div>
</main>

<?php
include 'footer.php';
?>
<script src="carrinho.js"></script>
