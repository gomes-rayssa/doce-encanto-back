<?php
include 'header.php';

$carrinho = $_SESSION['carrinho'] ?? [];
$total = 0;
?>
<link rel="stylesheet" href="carrinho.css" />

<main id="main-content">
<h1>Seu Carrinho</h1>
<div class="carrinho-container">

    <ul id="lista-carrinho" class="lista-carrinho" role="list" aria-label="Itens do carrinho">
        <?php if (empty($carrinho)): ?>
            <div class="carrinho-vazio" role="status">
                <i class="fas fa-shopping-cart" aria-hidden="true"></i>
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
                <li class="item-carrinho" data-id="<?php echo $id; ?>" role="listitem">
                    <div class="item-imagem">
                        <img src="<?php echo htmlspecialchars($item['imagem'] ?? '../assets/logos/logo-navbar.jpg'); ?>"
                            alt="<?php echo htmlspecialchars($item['nome']); ?>" />
                    </div>
                    <div class="item-info">
                        <h3><?php echo htmlspecialchars($item['nome']); ?></h3>
                        <p class="item-categoria"><?php echo htmlspecialchars($item['categoria'] ?? 'Produto'); ?></p>
                        <p class="item-preco">R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?></p>
                    </div>
                    <div class="item-quantidade" role="group" aria-label="Quantidade de <?php echo htmlspecialchars($item['nome']); ?>">
                        <button onclick="alterarQuantidade('<?php echo $id; ?>', <?php echo $item['quantidade'] - 1; ?>)"
                            class="btn-quantidade" aria-label="Diminuir quantidade de <?php echo htmlspecialchars($item['nome']); ?>">-</button>
                        <span class="quantidade" aria-label="Quantidade atual: <?php echo $item['quantidade']; ?>"><?php echo $item['quantidade']; ?></span>
                        <button onclick="alterarQuantidade('<?php echo $id; ?>', <?php echo $item['quantidade'] + 1; ?>)"
                            class="btn-quantidade" aria-label="Aumentar quantidade de <?php echo htmlspecialchars($item['nome']); ?>">+</button>
                    </div>
                    <div class="item-total">
                        <p aria-label="Subtotal: R$ <?php echo number_format($subtotal, 2, ',', '.'); ?>">R$ <?php echo number_format($subtotal, 2, ',', '.'); ?></p>
                    </div>
                    <div class="item-remover">
                        <button onclick="removerItem('<?php echo $id; ?>')" class="btn-remover" aria-label="Remover <?php echo htmlspecialchars($item['nome']); ?> do carrinho">
                            <i class="fas fa-trash" aria-hidden="true"></i>
                        </button>
                    </div>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>

    <aside class="carrinho-resumo" role="complementary" aria-labelledby="resumo-title">
        <div class="carrinho-total" id="resumo-title">Total: R$ <span id="total"><?php echo number_format($total, 2, ',', '.'); ?></span>
        </div>
        <div class="carrinho-acoes">
            <button id="limpar-carrinho" class="btn-limpar" <?php echo empty($carrinho) ? 'disabled' : ''; ?> aria-label="Limpar todos os itens do carrinho">
                <i class="fas fa-trash" aria-hidden="true"></i> Limpar Carrinho
            </button>
            <button id="finalizar-compra" class="btn-finalizar" <?php echo empty($carrinho) ? 'disabled' : ''; ?> aria-label="Finalizar compra">
                <i class="fas fa-credit-card" aria-hidden="true"></i> Finalizar Compra
            </button>
        </div>
    </aside>
</div>

<div id="modal-pagamento" class="modal-pagamento" role="dialog" aria-labelledby="modal-pagamento-title" aria-modal="true" aria-hidden="true">
    <div class="modal-pagamento-content">
        <button class="close-modal-pagamento" aria-label="Fechar modal de pagamento">&times;</button>
        <h2 id="modal-pagamento-title"><i class="fas fa-credit-card" aria-hidden="true"></i> Finalizar Pagamento</h2>

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

        <form id="form-pagamento" aria-labelledby="modal-pagamento-title">
            <fieldset class="form-section">
                <legend><h3>Método de Pagamento</h3></legend>
                <div class="payment-methods" role="radiogroup" aria-label="Escolha o método de pagamento">
                    <label class="payment-method">
                        <input type="radio" name="metodo" value="credito" checked aria-label="Cartão de Crédito">
                        <div class="method-card">
                            <i class="fas fa-credit-card" aria-hidden="true"></i>
                            <span>Cartão de Crédito</span>
                        </div>
                    </label>
                    <label class="payment-method">
                        <input type="radio" name="metodo" value="debito" aria-label="Cartão de Débito">
                        <div class="method-card">
                            <i class="fas fa-money-check-alt" aria-hidden="true"></i>
                            <span>Cartão de Débito</span>
                        </div>
                    </label>
                    <label class="payment-method">
                        <input type="radio" name="metodo" value="entrega" aria-label="Pagar na Entrega">
                        <div class="method-card">
                            <i class="fas fa-hand-holding-usd" aria-hidden="true"></i>
                            <span>Pagar na Entrega</span>
                        </div>
                    </label>
                </div>
            </fieldset>

            <fieldset id="dados-cartao" class="form-section">
                <legend><h3>Dados do Cartão</h3></legend>

                <div class="form-group">
                    <label for="numero-cartao">Número do Cartão</label>
                    <input type="text" id="numero-cartao" name="numero-cartao" placeholder="0000 0000 0000 0000" maxlength="19" required aria-required="true" autocomplete="cc-number">
                    <div class="card-brands" aria-hidden="true">
                        <i class="fab fa-cc-visa"></i>
                        <i class="fab fa-cc-mastercard"></i>
                        <i class="fab fa-cc-amex"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="nome-cartao">Nome no Cartão</label>
                    <input type="text" id="nome-cartao" name="nome-cartao" placeholder="Nome como está no cartão" required aria-required="true" autocomplete="cc-name">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="validade">Validade</label>
                        <input type="text" id="validade" name="validade" placeholder="MM/AA" maxlength="5" required aria-required="true" autocomplete="cc-exp">
                    </div>
                    <div class="form-group">
                        <label for="cvv">CVV</label>
                        <input type="text" id="cvv" name="cvv" placeholder="123" maxlength="4" required aria-required="true" aria-describedby="cvv-help" autocomplete="cc-csc">
                        <button type="button" class="cvv-help" aria-label="Ajuda sobre CVV">
                            <i class="fas fa-question-circle" aria-hidden="true"></i>
                        </button>
                        <span class="help-text sr-only" id="cvv-help">Código de 3 ou 4 dígitos no verso do cartão</span>
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
                            <?php echo htmlspecialchars($endereco['numero'] ?? ''); ?></p>
                        <p><?php echo htmlspecialchars($endereco['bairro'] ?? ''); ?> -
                            <?php echo htmlspecialchars($endereco['cidade'] ?? ''); ?>,
                            <?php echo htmlspecialchars($endereco['estado'] ?? ''); ?></p>
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

<div id="modal-compra-sucesso" class="modal" role="dialog" aria-labelledby="modal-sucesso-title" aria-modal="true" aria-hidden="true">
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
