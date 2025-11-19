/**
 * Sistema de Acessibilidade - Doce Encanto
 * Gerencia todos os recursos de acessibilidade do site
 */

class AccessibilityManager {
  constructor() {
    this.settings = {
      fontSize: "medium",
      contrast: "normal",
      darkMode: false,
      readingSpacing: false,
      dyslexiaFont: false,
      grayscale: false,
    };

    this.init();
  }

  init() {
    this.createAccessibilityButton();
    this.createAccessibilityPanel();
    this.loadSettings();
    this.setupEventListeners();
    this.setupKeyboardNavigation();
    this.detectKeyboardUser();
    this.announcePageLoad();
  }

  /**
   * Cria o botão flutuante de acessibilidade
   */
  createAccessibilityButton() {
    const button = document.createElement("button");
    button.className = "accessibility-button";
    button.setAttribute("aria-label", "Abrir painel de acessibilidade");
    button.setAttribute("aria-expanded", "false");
    button.setAttribute("aria-controls", "accessibility-panel");
    button.innerHTML = '<i class="fas fa-universal-access"></i>';
    document.body.appendChild(button);

    this.accessibilityButton = button;
  }

  /**
   * Cria o painel de controles de acessibilidade
   */
  createAccessibilityPanel() {
    const panel = document.createElement("div");
    panel.id = "accessibility-panel";
    panel.className = "accessibility-panel";
    panel.setAttribute("role", "dialog");
    panel.setAttribute("aria-labelledby", "accessibility-panel-title");
    panel.setAttribute("aria-modal", "true");

    panel.innerHTML = `
      <div class="accessibility-panel-header">
        <h3 id="accessibility-panel-title">
          <i class="fas fa-universal-access"></i>
          Acessibilidade
        </h3>
        <button class="accessibility-panel-close" aria-label="Fechar painel de acessibilidade">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <div class="accessibility-panel-content">
        <!-- Tamanho da Fonte -->
        <div class="accessibility-control">
          <div class="accessibility-control-label">
            <i class="fas fa-text-height"></i>
            <span>Tamanho do Texto</span>
          </div>
          <div class="accessibility-control-buttons" role="group" aria-label="Controles de tamanho de texto">
            <button class="accessibility-btn" data-action="fontSize" data-value="medium" aria-label="Tamanho médio">
              <i class="fas fa-font"></i>
              Médio
            </button>
            <button class="accessibility-btn" data-action="fontSize" data-value="large" aria-label="Tamanho grande">
              <i class="fas fa-font"></i>
              Grande
            </button>
            <button class="accessibility-btn" data-action="fontSize" data-value="extra-large" aria-label="Tamanho extra grande">
              <i class="fas fa-font"></i>
              Muito Grande
            </button>
          </div>
        </div>

        <!-- Contraste -->
        <div class="accessibility-control">
          <div class="accessibility-control-label">
            <i class="fas fa-adjust"></i>
            <span>Contraste</span>
          </div>
          <div class="accessibility-control-buttons" role="group" aria-label="Controles de contraste">
            <button class="accessibility-btn active" data-action="contrast" data-value="normal" aria-label="Contraste normal">
              <i class="fas fa-circle"></i>
              Normal
            </button>
            <button class="accessibility-btn" data-action="contrast" data-value="high" aria-label="Alto contraste">
              <i class="fas fa-circle-half-stroke"></i>
              Alto
            </button>
          </div>
        </div>

        <!-- Modo Escuro -->
        <div class="accessibility-control">
          <div class="accessibility-control-label">
            <i class="fas fa-moon"></i>
            <span>Modo Escuro</span>
          </div>
          <div class="accessibility-control-buttons" role="group" aria-label="Controles de modo escuro">
            <button class="accessibility-btn" data-action="toggle" data-value="darkMode" aria-pressed="false">
              <i class="fas fa-moon"></i>
              Ativar/Desativar
            </button>
          </div>
        </div>

        <!-- Espaçamento de Leitura -->
        <div class="accessibility-control">
          <div class="accessibility-control-label">
            <i class="fas fa-align-left"></i>
            <span>Espaçamento de Leitura</span>
          </div>
          <div class="accessibility-control-buttons" role="group" aria-label="Controles de espaçamento">
            <button class="accessibility-btn" data-action="toggle" data-value="readingSpacing" aria-pressed="false">
              <i class="fas fa-text-width"></i>
              Ativar/Desativar
            </button>
          </div>
        </div>

        <!-- Fonte para Dislexia -->
        <div class="accessibility-control">
          <div class="accessibility-control-label">
            <i class="fas fa-font"></i>
            <span>Fonte Amigável</span>
          </div>
          <div class="accessibility-control-buttons" role="group" aria-label="Controles de fonte">
            <button class="accessibility-btn" data-action="toggle" data-value="dyslexiaFont" aria-pressed="false">
              <i class="fas fa-font"></i>
              Ativar/Desativar
            </button>
          </div>
        </div>

        <!-- Escala de Cinza -->
        <div class="accessibility-control">
          <div class="accessibility-control-label">
            <i class="fas fa-palette"></i>
            <span>Escala de Cinza</span>
          </div>
          <div class="accessibility-control-buttons" role="group" aria-label="Controles de cor">
            <button class="accessibility-btn" data-action="toggle" data-value="grayscale" aria-pressed="false">
              <i class="fas fa-eye-slash"></i>
              Ativar/Desativar
            </button>
          </div>
        </div>

        <!-- Botão de Reset -->
        <button class="accessibility-reset" aria-label="Resetar todas as configurações de acessibilidade">
          <i class="fas fa-undo"></i>
          Restaurar Padrões
        </button>
      </div>
    `;

    document.body.appendChild(panel);
    this.accessibilityPanel = panel;
  }

  /**
   * Configura os listeners de eventos
   */
  setupEventListeners() {
    // Toggle do painel
    this.accessibilityButton.addEventListener("click", () => {
      this.togglePanel();
    });

    // Fechar painel
    const closeBtn = this.accessibilityPanel.querySelector(
      ".accessibility-panel-close"
    );
    closeBtn.addEventListener("click", () => {
      this.closePanel();
    });

    // Fechar ao clicar fora
    document.addEventListener("click", (e) => {
      if (
        !this.accessibilityPanel.contains(e.target) &&
        !this.accessibilityButton.contains(e.target)
      ) {
        this.closePanel();
      }
    });

    // Botões de controle
    const controlButtons = this.accessibilityPanel.querySelectorAll(
      ".accessibility-btn"
    );
    controlButtons.forEach((btn) => {
      btn.addEventListener("click", () => {
        const action = btn.dataset.action;
        const value = btn.dataset.value;

        if (action === "fontSize") {
          this.setFontSize(value);
        } else if (action === "contrast") {
          this.setContrast(value);
        } else if (action === "toggle") {
          this.toggleFeature(value, btn);
        }
      });
    });

    // Botão de reset
    const resetBtn = this.accessibilityPanel.querySelector(
      ".accessibility-reset"
    );
    resetBtn.addEventListener("click", () => {
      this.resetSettings();
    });

    // Tecla ESC para fechar painel
    document.addEventListener("keydown", (e) => {
      if (
        e.key === "Escape" &&
        this.accessibilityPanel.classList.contains("active")
      ) {
        this.closePanel();
      }
    });
  }

  /**
   * Abre/fecha o painel
   */
  togglePanel() {
    const isActive = this.accessibilityPanel.classList.toggle("active");
    this.accessibilityButton.setAttribute("aria-expanded", isActive);

    if (isActive) {
      // Foco no primeiro botão quando abrir
      const firstButton = this.accessibilityPanel.querySelector(
        ".accessibility-btn"
      );
      if (firstButton) {
        setTimeout(() => firstButton.focus(), 100);
      }

      this.announce("Painel de acessibilidade aberto");
    } else {
      this.announce("Painel de acessibilidade fechado");
    }
  }

  /**
   * Fecha o painel
   */
  closePanel() {
    this.accessibilityPanel.classList.remove("active");
    this.accessibilityButton.setAttribute("aria-expanded", "false");
    this.accessibilityButton.focus();
  }

  /**
   * Define o tamanho da fonte
   */
  setFontSize(size) {
    // Remove classes anteriores
    document.body.classList.remove(
      "font-size-medium",
      "font-size-large",
      "font-size-extra-large"
    );

    // Adiciona nova classe
    document.body.classList.add(`font-size-${size}`);

    // Atualiza botões
    const buttons = this.accessibilityPanel.querySelectorAll(
      '[data-action="fontSize"]'
    );
    buttons.forEach((btn) => {
      btn.classList.toggle("active", btn.dataset.value === size);
    });

    this.settings.fontSize = size;
    this.saveSettings();

    const labels = {
      medium: "médio",
      large: "grande",
      "extra-large": "muito grande",
    };
    this.announce(`Tamanho do texto alterado para ${labels[size]}`);
  }

  /**
   * Define o contraste
   */
  setContrast(contrast) {
    if (contrast === "high") {
      document.body.classList.add("high-contrast");
    } else {
      document.body.classList.remove("high-contrast");
    }

    // Atualiza botões
    const buttons = this.accessibilityPanel.querySelectorAll(
      '[data-action="contrast"]'
    );
    buttons.forEach((btn) => {
      btn.classList.toggle("active", btn.dataset.value === contrast);
    });

    this.settings.contrast = contrast;
    this.saveSettings();

    this.announce(
      contrast === "high"
        ? "Alto contraste ativado"
        : "Contraste normal ativado"
    );
  }

  /**
   * Alterna uma funcionalidade
   */
  toggleFeature(feature, button) {
    this.settings[feature] = !this.settings[feature];

    const classMap = {
      darkMode: "dark-mode",
      readingSpacing: "reading-spacing",
      dyslexiaFont: "dyslexia-font",
      grayscale: "grayscale",
    };

    document.body.classList.toggle(classMap[feature], this.settings[feature]);
    button.classList.toggle("active", this.settings[feature]);
    button.setAttribute("aria-pressed", this.settings[feature]);

    this.saveSettings();

    const labels = {
      darkMode: "Modo escuro",
      readingSpacing: "Espaçamento de leitura",
      dyslexiaFont: "Fonte amigável",
      grayscale: "Escala de cinza",
    };

    this.announce(
      `${labels[feature]} ${this.settings[feature] ? "ativado" : "desativado"}`
    );
  }

  /**
   * Reseta todas as configurações
   */
  resetSettings() {
    this.settings = {
      fontSize: "medium",
      contrast: "normal",
      darkMode: false,
      readingSpacing: false,
      dyslexiaFont: false,
      grayscale: false,
    };

    // Remove todas as classes
    document.body.className = "";

    // Reseta botões
    const buttons = this.accessibilityPanel.querySelectorAll(
      ".accessibility-btn"
    );
    buttons.forEach((btn) => {
      btn.classList.remove("active");
      btn.setAttribute("aria-pressed", "false");
    });

    // Ativa botão médio e normal
    const mediumBtn = this.accessibilityPanel.querySelector(
      '[data-action="fontSize"][data-value="medium"]'
    );
    const normalBtn = this.accessibilityPanel.querySelector(
      '[data-action="contrast"][data-value="normal"]'
    );

    if (mediumBtn) mediumBtn.classList.add("active");
    if (normalBtn) normalBtn.classList.add("active");

    this.saveSettings();
    this.announce("Configurações de acessibilidade restauradas");
  }

  /**
   * Salva as configurações no localStorage
   */
  saveSettings() {
    try {
      localStorage.setItem(
        "accessibility_settings",
        JSON.stringify(this.settings)
      );
    } catch (e) {
      console.error("Erro ao salvar configurações de acessibilidade:", e);
    }
  }

  /**
   * Carrega as configurações salvas
   */
  loadSettings() {
    try {
      const saved = localStorage.getItem("accessibility_settings");
      if (saved) {
        this.settings = JSON.parse(saved);
        this.applySettings();
      }
    } catch (e) {
      console.error("Erro ao carregar configurações de acessibilidade:", e);
    }
  }

  /**
   * Aplica as configurações salvas
   */
  applySettings() {
    // Tamanho da fonte
    this.setFontSize(this.settings.fontSize);

    // Contraste
    this.setContrast(this.settings.contrast);

    // Outras funcionalidades
    const features = [
      "darkMode",
      "readingSpacing",
      "dyslexiaFont",
      "grayscale",
    ];
    features.forEach((feature) => {
      if (this.settings[feature]) {
        const button = this.accessibilityPanel.querySelector(
          `[data-value="${feature}"]`
        );
        if (button) {
          this.toggleFeature(feature, button);
        }
      }
    });
  }

  /**
   * Configura navegação por teclado aprimorada
   */
  setupKeyboardNavigation() {
    // Adiciona atalhos de teclado
    document.addEventListener("keydown", (e) => {
      // Alt + A para abrir painel de acessibilidade
      if (e.altKey && e.key === "a") {
        e.preventDefault();
        this.togglePanel();
      }

      // Alt + número para tamanho de fonte
      if (e.altKey && ["1", "2", "3"].includes(e.key)) {
        e.preventDefault();
        const sizes = ["medium", "large", "extra-large"];
        this.setFontSize(sizes[parseInt(e.key) - 1]);
      }

      // Alt + C para alternar contraste
      if (e.altKey && e.key === "c") {
        e.preventDefault();
        const newContrast =
          this.settings.contrast === "normal" ? "high" : "normal";
        this.setContrast(newContrast);
      }

      // Alt + D para modo escuro
      if (e.altKey && e.key === "d") {
        e.preventDefault();
        const button = this.accessibilityPanel.querySelector(
          '[data-value="darkMode"]'
        );
        if (button) {
          this.toggleFeature("darkMode", button);
        }
      }
    });

    // Navegação por Tab melhorada
    const focusableElements = [
      "a[href]",
      "button:not([disabled])",
      "input:not([disabled])",
      "select:not([disabled])",
      "textarea:not([disabled])",
      '[tabindex]:not([tabindex="-1"])',
    ].join(", ");

    document.addEventListener("keydown", (e) => {
      if (e.key === "Tab") {
        const elements = Array.from(
          document.querySelectorAll(focusableElements)
        );
        const firstElement = elements[0];
        const lastElement = elements[elements.length - 1];

        if (e.shiftKey && document.activeElement === firstElement) {
          e.preventDefault();
          lastElement.focus();
        } else if (!e.shiftKey && document.activeElement === lastElement) {
          e.preventDefault();
          firstElement.focus();
        }
      }
    });
  }

  /**
   * Detecta quando o usuário está usando teclado
   */
  detectKeyboardUser() {
    let isKeyboardUser = false;

    document.addEventListener("keydown", (e) => {
      if (e.key === "Tab") {
        isKeyboardUser = true;
        document.body.classList.add("keyboard-user");
      }
    });

    document.addEventListener("mousedown", () => {
      if (isKeyboardUser) {
        isKeyboardUser = false;
        document.body.classList.remove("keyboard-user");
      }
    });
  }

  /**
   * Anuncia mensagens para leitores de tela
   */
  announce(message) {
    const announcer =
      document.getElementById("accessibility-announcer") ||
      this.createAnnouncer();
    announcer.textContent = message;

    // Limpa após 1 segundo
    setTimeout(() => {
      announcer.textContent = "";
    }, 1000);
  }

  /**
   * Cria o elemento de anúncio para leitores de tela
   */
  createAnnouncer() {
    const announcer = document.createElement("div");
    announcer.id = "accessibility-announcer";
    announcer.setAttribute("role", "status");
    announcer.setAttribute("aria-live", "polite");
    announcer.setAttribute("aria-atomic", "true");
    announcer.style.position = "absolute";
    announcer.style.left = "-10000px";
    announcer.style.width = "1px";
    announcer.style.height = "1px";
    announcer.style.overflow = "hidden";
    document.body.appendChild(announcer);
    return announcer;
  }

  /**
   * Anuncia o carregamento da página
   */
  announcePageLoad() {
    const pageTitle = document.title;
    setTimeout(() => {
      this.announce(`Página carregada: ${pageTitle}`);
    }, 500);
  }
}

// Inicializa o sistema de acessibilidade quando o DOM estiver pronto
if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", () => {
    window.accessibilityManager = new AccessibilityManager();
  });
} else {
  window.accessibilityManager = new AccessibilityManager();
}

// Exporta para uso em outros scripts
if (typeof module !== "undefined" && module.exports) {
  module.exports = AccessibilityManager;
}