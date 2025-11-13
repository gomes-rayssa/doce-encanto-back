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

<div id="modal-pagamento" class="modal-pagamento">
    <div class="modal-pagamento-content">
        <span class="close-modal-pagamento">&times;</span>
        <h2><i class="fas fa-credit-card"></i> Finalizar Pagamento</h2>

        <div class="resumo-pedido">
            <h3>Resumo do Pedido</h3>
            <div class="resumo-item">
                <span>Subtotal:</span>
                <span>R$ <?php echo number_format($total, 2, ',', '.'); ?></span>
            </div>
            <div class="resumo-item">
                <span>Taxa de Entrega:</span>
                <span id="taxa-entrega">R$ 10,00</span>
            </div>
            <div class="resumo-item total-final">
                <span>Total:</span>
                <span id="total-final">R$ <?php echo number_format($total + 10, 2, ',', '.'); ?></span>
            </div>
        </div>

        <form id="form-pagamento">
            <div class="form-section">
                <h3>Método de Pagamento</h3>
                <div class="payment-methods">
                    <label class="payment-method">
                        <input type="radio" name="metodo" value="credito" checked>
                        <div class="method-card">
                            <i class="fas fa-credit-card"></i>
                            <span>Cartão de Crédito</span>
                        </div>
                    </label>
                    <label class="payment-method">
                        <input type="radio" name="metodo" value="debito">
                        <div class="method-card">
                            <i class="fas fa-money-check-alt"></i>
                            <span>Cartão de Débito</span>
                        </div>
                    </label>
                    <label class="payment-method">
                        <input type="radio" name="metodo" value="entrega">
                        <div class="method-card">
                            <i class="fas fa-hand-holding-usd"></i>
                            <span>Pagar na Entrega</span>
                        </div>
                    </label>
                </div>
            </div>

            <div id="dados-cartao" class="form-section">
                <h3>Dados do Cartão</h3>

                <div class="form-group">
                    <label for="numero-cartao">Número do Cartão</label>
                    <input type="text" id="numero-cartao" placeholder="0000 0000 0000 0000" maxlength="19" required>
                    <div class="card-brands">
                        <i class="fab fa-cc-visa"></i>
                        <i class="fab fa-cc-mastercard"></i>
                        <i class="fab fa-cc-amex"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="nome-cartao">Nome no Cartão</label>
                    <input type="text" id="nome-cartao" placeholder="Nome como está no cartão" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="validade">Validade</label>
                        <input type="text" id="validade" placeholder="MM/AA" maxlength="5" required>
                    </div>
                    <div class="form-group">
                        <label for="cvv">CVV</label>
                        <input type="text" id="cvv" placeholder="123" maxlength="4" required>
                        <i class="fas fa-question-circle cvv-help"
                            title="Código de 3 ou 4 dígitos no verso do cartão"></i>
                    </div>
                </div>

                <div id="parcelas-section" class="form-group">
                    <label for="parcelas">Parcelas</label>
                    <select id="parcelas">
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
            </div>

            <div class="form-section">
                <h3>Endereço de Entrega</h3>
                <?php if (isset($_SESSION['usuario_logado']) && isset($_SESSION['usuario_data']['endereco'])): ?>
                    <?php $endereco = $_SESSION['usuario_data']['endereco']; ?>
                    <div class="endereco-salvo">
                        <p><strong>Entregar em:</strong></p>
                        <p><?php echo htmlspecialchars($endereco['rua'] ?? ''); ?>,
                            <?php echo htmlspecialchars($endereco['numero'] ?? ''); ?></p>
                        <p><?php echo htmlspecialchars($endereco['bairro'] ?? ''); ?> -
                            <?php echo htmlspecialchars($endereco['cidade'] ?? ''); ?>,
                            <?php echo htmlspecialchars($endereco['estado'] ?? ''); ?></p>
                        <p>CEP: <?php echo htmlspecialchars($endereco['cep'] ?? ''); ?></p>
                    </div>
                <?php else: ?>
                    <p class="aviso-login">
                        <i class="fas fa-exclamation-triangle"></i>
                        Faça login para usar seu endereço salvo ou informe um endereço de entrega.
                    </p>
                <?php endif; ?>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-cancelar-pagamento">Cancelar</button>
                <button type="submit" class="btn-confirmar-pagamento">
                    <i class="fas fa-check"></i> Confirmar Pagamento
                </button>
            </div>
        </form>
    </div>
</div>

<div id="modal-compra-sucesso" class="modal">
    <div class="modal-content">
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <h2>Compra Realizada com Sucesso!</h2>
        <p>Seu pedido foi confirmado e está sendo preparado.</p>
        <p class="pedido-numero">Número do Pedido: <strong>#<?php echo rand(1000, 9999); ?></strong></p>
        <button class="btn-fechar-sucesso">
            <i class="fas fa-home"></i> Voltar para Home
        </button>
    </div>
</div>

<?php
include 'footer.php';
?>
<script src="carrinho.js"></script>