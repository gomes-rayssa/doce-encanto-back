<?php
include 'header.php';
?>

<link rel="stylesheet" href="cadastro.css" />

<main id="main-content">
<div class="formulario">
  <div class="form-container">
    <nav class="progress-bar" aria-label="Progresso do cadastro" role="navigation">
      <div class="step active" id="step1-indicator" aria-current="step">
        <span class="step-number" aria-hidden="true">1</span>
        <span class="step-label">Dados Pessoais</span>
      </div>
      <div class="step" id="step2-indicator">
        <span class="step-number" aria-hidden="true">2</span>
        <span class="step-label">Endereço</span>
      </div>
    </nav>

    <form id="registration-form" aria-labelledby="form-title">
      <div class="form-step active" id="step1">
        <h2 id="form-title">Dados Pessoais</h2>
        <div class="form-group">
          <label for="nome">Nome Completo</label>
          <input type="text" id="nome" name="nome" required aria-required="true" aria-describedby="nome-error" autocomplete="name" />
          <span class="error-message" id="nome-error" role="alert" aria-live="polite"></span>
        </div>

        <div class="form-group">
          <label for="email">E-mail</label>
          <input type="email" id="email" name="email" required aria-required="true" aria-describedby="email-error" autocomplete="email" />
          <span class="error-message" id="email-error" role="alert" aria-live="polite"></span>
        </div>

        <div class="form-group">
          <label for="celular">Celular</label>
          <input type="tel" id="celular" name="celular" placeholder="(00) 90000-0000" required aria-required="true" aria-describedby="celular-error" autocomplete="tel" />
          <span class="error-message" id="celular-error" role="alert" aria-live="polite"></span>
        </div>

        <div class="form-group">
          <label for="data-nascimento">Data de Nascimento</label>
          <input type="date" id="data-nascimento" name="data-nascimento" required aria-required="true" aria-describedby="data-nascimento-error" autocomplete="bday" />
          <span class="error-message" id="data-nascimento-error" role="alert" aria-live="polite"></span>
        </div>

        <div class="form-group">
          <label for="senha">Senha</label>
          <div class="password-container">
            <input type="password" id="senha" name="senha" required aria-required="true" aria-describedby="senha-error senha-help" autocomplete="new-password" />
            <button type="button" class="toggle-password" data-target="senha" aria-label="Mostrar senha" aria-pressed="false">
              <img src="../assets/logos/olho.png" alt="" aria-hidden="true" />
            </button>
          </div>
          <span class="help-text" id="senha-help">A senha deve ter no mínimo 6 caracteres</span>
          <span class="error-message" id="senha-error" role="alert" aria-live="polite"></span>
        </div>

        <div class="form-group">
          <label for="confirmar-senha">Confirmar Senha</label>
          <div class="password-container">
            <input type="password" id="confirmar-senha" name="confirmar-senha" required aria-required="true" aria-describedby="confirmar-senha-error" autocomplete="new-password" />
            <button type="button" class="toggle-password" data-target="confirmar-senha" aria-label="Mostrar confirmação de senha" aria-pressed="false">
              <img src="../assets/logos/olho.png" alt="" aria-hidden="true" />
            </button>
          </div>
          <span class="error-message" id="confirmar-senha-error" role="alert" aria-live="polite"></span>
        </div>

        <div class="form-buttons">
          <button type="button" class="btn btn-primary" id="next-step1">
            Próximo
          </button>
        </div>

        <p class="txt">
          Já tem uma conta?
          <a href="login.php" class="link">Faça login aqui</a>
        </p>
      </div>

      <div class="form-step" id="step2" aria-hidden="true">
        <h2>Dados de Endereço</h2>
        <div class="form-group">
          <label for="cep">CEP</label>
          <input type="text" id="cep" name="cep" placeholder="00000-000" required aria-required="true" aria-describedby="cep-error" autocomplete="postal-code" />
          <span class="error-message" id="cep-error" role="alert" aria-live="polite"></span>
        </div>

        <div class="form-group">
          <label for="rua">Rua</label>
          <input type="text" id="rua" name="rua" required aria-required="true" aria-describedby="rua-error" autocomplete="address-line1" />
          <span class="error-message" id="rua-error" role="alert" aria-live="polite"></span>
        </div>

        <div class="form-group">
          <label for="numero">Número</label>
          <input type="text" id="numero" name="numero" required aria-required="true" aria-describedby="numero-error" />
          <span class="error-message" id="numero-error" role="alert" aria-live="polite"></span>
        </div>

        <div class="form-group">
          <label for="bairro">Bairro</label>
          <input type="text" id="bairro" name="bairro" required aria-required="true" aria-describedby="bairro-error" autocomplete="address-level2" />
          <span class="error-message" id="bairro-error" role="alert" aria-live="polite"></span>
        </div>

        <div class="form-group">
          <label for="cidade">Cidade</label>
          <input type="text" id="cidade" name="cidade" required aria-required="true" aria-describedby="cidade-error" autocomplete="address-level2" />
          <span class="error-message" id="cidade-error" role="alert" aria-live="polite"></span>
        </div>

        <div class="form-group">
          <label for="estado">Estado</label>
          <select id="estado" name="estado" required aria-required="true" aria-describedby="estado-error">
            <option value="">Selecione o estado</option>
            <option value="AC">Acre</option>
            <option value="AL">Alagoas</option>
            <option value="AP">Amapá</option>
            <option value="AM">Amazonas</option>
            <option value="BA">Bahia</option>
            <option value="CE">Ceará</option>
            <option value="DF">Distrito Federal</option>
            <option value="ES">Espírito Santo</option>
            <option value="GO">Goiás</option>
            <option value="MA">Maranhão</option>
            <option value="MT">Mato Grosso</option>
            <option value="MS">Mato Grosso do Sul</option>
            <option value="MG">Minas Gerais</option>
            <option value="PA">Pará</option>
            <option value="PB">Paraíba</option>
            <option value="PR">Paraná</option>
            <option value="PE">Pernambuco</option>
            <option value="PI">Piauí</option>
            <option value="RJ">Rio de Janeiro</option>
            <option value="RN">Rio Grande do Norte</option>
            <option value="RS">Rio Grande do Sul</option>
            <option value="RO">Rondônia</option>
            <option value="RR">Roraima</option>
            <option value="SC">Santa Catarina</option>
            <option value="SP">São Paulo</option>
            <option value="SE">Sergipe</option>
            <option value="TO">Tocantins</option>
          </select>
          <span class="error-message" id="estado-error" role="alert" aria-live="polite"></span>
        </div>

        <div class="form-buttons">
          <button type="button" class="btn btn-secondary" id="prev-step2">
            Voltar
          </button>
          <button type="submit" class="btn btn-primary" id="submit-form">
            Finalizar Cadastro
          </button>
        </div>
        <p class="txt">
          Já tem uma conta?
          <a href="login.php" class="link">Faça login aqui</a>
        </p>
      </div>
    </form>
  </div>
</div>

<div id="toast-container" role="status" aria-live="polite" aria-atomic="true"></div>
</main>

<?php
include 'footer.php';
?>

<script src="cadastro.js"></script>
