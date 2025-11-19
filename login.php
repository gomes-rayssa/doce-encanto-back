<?php
include 'header.php';
?>

<link rel="stylesheet" href="login.css" />

<main id="main-content">
<div class="formulario">
  <div class="form-container">
    <form class="form-step active" id="login-form" aria-labelledby="login-title">
      <h2 id="login-title">Entrar</h2>

      <div class="form-group">
        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" required aria-required="true" aria-describedby="email-help" autocomplete="email" />
        <span class="help-text sr-only" id="email-help">Digite seu endereço de e-mail</span>
      </div>

      <div class="form-group">
        <label for="senha">Senha</label>
        <div class="password-container">
          <input type="password" id="senha" name="senha" required aria-required="true" aria-describedby="senha-error" autocomplete="current-password" />
          <button type="button" class="toggle-password" data-target="senha" aria-label="Mostrar senha" aria-pressed="false">
            <img src="../assets/logos/olho.png" alt="" aria-hidden="true" />
          </button>
        </div>
        <span class="error-message" id="senha-error" role="alert" aria-live="polite"></span>
      </div>

      <div class="form-buttons">
        <button type="submit" class="btn btn-primary" id="login-button">Entrar</button>
      </div>

      <p class="txt">
        Não tem conta?
        <a href="cadastro.php" class="link">Cadastre-se</a>
      </p>
    </form>
  </div>
</div>
</main>

<?php
include 'footer.php';
?>

<script src="login.js"></script>
