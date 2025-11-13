<?php
include 'header.php';
?>

<link rel="stylesheet" href="login.css" />

<div class="formulario">
  <div class="form-container">
    <form class="form-step active" id="login-form">
      <h2>Entrar</h2>

      <div class="form-group">
        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" required />
      </div>

      <div class="form-group">
        <label for="senha">Senha</label>
        <div class="password-container">
          <input type="password" id="senha" name="senha" required />
          <img src="../assets/logos/olho.png" alt="Mostrar Senha" class="toggle-password" data-target="senha" />
        </div>
        <span class="error-message" id="senha-error"></span>
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

<?php
include 'footer.php';
?>

<script src="login.js"></script>