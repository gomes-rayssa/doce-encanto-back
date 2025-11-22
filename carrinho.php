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