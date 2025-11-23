document.addEventListener("DOMContentLoaded", function () {
  const accessibilityButton = document.getElementById("accessibility-button");
  const accessibilityMenu = document.getElementById("accessibility-menu");
  const closeMenuButton = document.getElementById("close-accessibility-menu");
  const resetButton = document.getElementById("reset-accessibility");

  const defaultSettings = {
    fontSize: "normal",
    lineHeight: "normal",
    contrast: false,
    grayscale: false,
    inverted: false,
    largeCursor: false,
    highlightLinks: false,
    readingMask: false,
  };

  let settings = loadSettings();

  applyAllSettings();

  if (accessibilityButton) {
    accessibilityButton.addEventListener("click", toggleMenu);
    accessibilityButton.addEventListener("keydown", (e) => {
      if (e.key === "Enter" || e.key === " ") {
        e.preventDefault();
        toggleMenu();
      }
    });
  }

  if (closeMenuButton) {
    closeMenuButton.addEventListener("click", closeMenu);
  }

  document.addEventListener("click", (e) => {
    if (
      accessibilityMenu &&
      accessibilityMenu.classList.contains("active") &&
      !accessibilityMenu.contains(e.target) &&
      !accessibilityButton.contains(e.target)
    ) {
      closeMenu();
    }
  });

  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && accessibilityMenu.classList.contains("active")) {
      closeMenu();
      accessibilityButton.focus();
    }
  });

  document.getElementById("decrease-font")?.addEventListener("click", () => {
    const sizes = ["small", "normal", "large", "extra-large"];
    const currentIndex = sizes.indexOf(settings.fontSize);
    if (currentIndex > 0) {
      settings.fontSize = sizes[currentIndex - 1];
      applyFontSize();
      saveSettings();
    }
  });

  document.getElementById("increase-font")?.addEventListener("click", () => {
    const sizes = ["small", "normal", "large", "extra-large"];
    const currentIndex = sizes.indexOf(settings.fontSize);
    if (currentIndex < sizes.length - 1) {
      settings.fontSize = sizes[currentIndex + 1];
      applyFontSize();
      saveSettings();
    }
  });

  document.getElementById("decrease-spacing")?.addEventListener("click", () => {
    const spacings = ["normal", "relaxed", "loose"];
    const currentIndex = spacings.indexOf(settings.lineHeight);
    if (currentIndex > 0) {
      settings.lineHeight = spacings[currentIndex - 1];
      applyLineHeight();
      saveSettings();
    }
  });

  document.getElementById("increase-spacing")?.addEventListener("click", () => {
    const spacings = ["normal", "relaxed", "loose"];
    const currentIndex = spacings.indexOf(settings.lineHeight);
    if (currentIndex < spacings.length - 1) {
      settings.lineHeight = spacings[currentIndex + 1];
      applyLineHeight();
      saveSettings();
    }
  });

  setupToggle("toggle-contrast", "contrast", "high-contrast");
  setupToggle("toggle-grayscale", "grayscale", "grayscale");
  setupToggle("toggle-invert", "inverted", "inverted");
  setupToggle("toggle-cursor", "largeCursor", "large-cursor");
  setupToggle("toggle-links", "highlightLinks", "highlight-links");
  setupToggle("toggle-reading-mask", "readingMask", "reading-mask");

  if (resetButton) {
    resetButton.addEventListener("click", resetSettings);
  }

  if (settings.readingMask) {
    enableReadingMask();
  }

  function toggleMenu() {
    if (accessibilityMenu) {
      const isActive = accessibilityMenu.classList.toggle("active");
      accessibilityButton.setAttribute("aria-expanded", isActive);

      if (isActive) {
        setTimeout(() => closeMenuButton?.focus(), 100);
        document.body.style.overflow = "hidden";
      } else {
        document.body.style.overflow = "";
      }
    }
  }

  function closeMenu() {
    if (accessibilityMenu) {
      accessibilityMenu.classList.remove("active");
      accessibilityButton.setAttribute("aria-expanded", "false");
      document.body.style.overflow = "";
    }
  }

  function setupToggle(toggleId, settingKey, className) {
    const toggle = document.getElementById(toggleId);
    if (toggle) {
      toggle.checked = settings[settingKey];

      toggle.addEventListener("change", () => {
        settings[settingKey] = toggle.checked;

        if (toggle.checked) {
          document.body.classList.add(className);

          if (settingKey === "readingMask") {
            enableReadingMask();
          }
        } else {
          document.body.classList.remove(className);

          if (settingKey === "readingMask") {
            disableReadingMask();
          }
        }

        saveSettings();
      });
    }
  }

  function applyFontSize() {
    document.body.setAttribute("data-font-size", settings.fontSize);
    updateFontButtons();
  }

  function applyLineHeight() {
    document.body.setAttribute("data-line-height", settings.lineHeight);
    updateSpacingButtons();
  }

  function updateFontButtons() {
    const sizes = ["small", "normal", "large", "extra-large"];
    const currentIndex = sizes.indexOf(settings.fontSize);

    const decreaseBtn = document.getElementById("decrease-font");
    const increaseBtn = document.getElementById("increase-font");

    if (decreaseBtn) decreaseBtn.disabled = currentIndex === 0;
    if (increaseBtn) increaseBtn.disabled = currentIndex === sizes.length - 1;
  }

  function updateSpacingButtons() {
    const spacings = ["normal", "relaxed", "loose"];
    const currentIndex = spacings.indexOf(settings.lineHeight);

    const decreaseBtn = document.getElementById("decrease-spacing");
    const increaseBtn = document.getElementById("increase-spacing");

    if (decreaseBtn) decreaseBtn.disabled = currentIndex === 0;
    if (increaseBtn)
      increaseBtn.disabled = currentIndex === spacings.length - 1;
  }

  function applyAllSettings() {
    applyFontSize();
    applyLineHeight();

    if (settings.contrast) document.body.classList.add("high-contrast");
    if (settings.grayscale) document.body.classList.add("grayscale");
    if (settings.inverted) document.body.classList.add("inverted");
    if (settings.largeCursor) document.body.classList.add("large-cursor");
    if (settings.highlightLinks) document.body.classList.add("highlight-links");
    if (settings.readingMask) {
      document.body.classList.add("reading-mask");
      enableReadingMask();
    }

    Object.keys(settings).forEach((key) => {
      if (typeof settings[key] === "boolean") {
        const toggleMap = {
          contrast: "toggle-contrast",
          grayscale: "toggle-grayscale",
          inverted: "toggle-invert",
          largeCursor: "toggle-cursor",
          highlightLinks: "toggle-links",
          readingMask: "toggle-reading-mask",
        };

        const toggleId = toggleMap[key];
        const toggle = document.getElementById(toggleId);
        if (toggle) toggle.checked = settings[key];
      }
    });

    updateFontButtons();
    updateSpacingButtons();
  }

  function enableReadingMask() {
    let mask = document.querySelector(".reading-mask");

    if (!mask) {
      mask = document.createElement("div");
      mask.className = "reading-mask";
      document.body.appendChild(mask);
    }

    mask.classList.add("active");

    document.addEventListener("mousemove", updateMaskPosition);
  }

  function disableReadingMask() {
    const mask = document.querySelector(".reading-mask");
    if (mask) {
      mask.classList.remove("active");
    }
    document.removeEventListener("mousemove", updateMaskPosition);
  }

  function updateMaskPosition(e) {
    const mask = document.querySelector(".reading-mask");
    if (mask && mask.classList.contains("active")) {
      const y = e.clientY;
      mask.style.clipPath = `polygon(
        0 0,
        100% 0,
        100% ${y - 100}px,
        0 ${y - 100}px,
        0 ${y + 100}px,
        100% ${y + 100}px,
        100% 100%,
        0 100%
      )`;
    }
  }

  function resetSettings() {
    if (confirm("Deseja resetar todas as configurações de acessibilidade?")) {
      settings = { ...defaultSettings };

      document.body.classList.remove(
        "high-contrast",
        "grayscale",
        "inverted",
        "large-cursor",
        "highlight-links",
        "reading-mask"
      );

      disableReadingMask();

      applyAllSettings();

      saveSettings();

      showNotification("Configurações resetadas com sucesso!");
    }
  }

  function saveSettings() {
    try {
      localStorage.setItem("accessibility-settings", JSON.stringify(settings));
    } catch (e) {
      console.warn(
        "Não foi possível salvar as configurações de acessibilidade:",
        e
      );
    }
  }

  function loadSettings() {
    try {
      const saved = localStorage.getItem("accessibility-settings");
      return saved ? JSON.parse(saved) : { ...defaultSettings };
    } catch (e) {
      console.warn(
        "Não foi possível carregar as configurações de acessibilidade:",
        e
      );
      return { ...defaultSettings };
    }
  }

  function showNotification(message) {
    const notification = document.createElement("div");
    notification.className = "notification success show";
    notification.textContent = message;
    notification.style.position = "fixed";
    notification.style.top = "2rem";
    notification.style.right = "2rem";
    notification.style.zIndex = "10000";
    notification.style.background = "linear-gradient(135deg, #10b981, #059669)";
    notification.style.color = "white";
    notification.style.padding = "1rem 1.5rem";
    notification.style.borderRadius = "12px";
    notification.style.boxShadow = "0 4px 20px rgba(0, 0, 0, 0.2)";
    notification.style.fontWeight = "600";

    document.body.appendChild(notification);

    setTimeout(() => {
      notification.style.opacity = "0";
      notification.style.transform = "translateX(100%)";
      setTimeout(() => notification.remove(), 300);
    }, 3000);
  }

  // Atalhos de Teclado
  document.addEventListener("keydown", (e) => {
    // Alt + A: Abrir menu de acessibilidade
    if (e.altKey && e.key === "a") {
      e.preventDefault();
      toggleMenu();
    }

    // Alt + +: Aumentar fonte
    if (e.altKey && (e.key === "+" || e.key === "=")) {
      e.preventDefault();
      document.getElementById("increase-font")?.click();
    }

    // Alt + -: Diminuir fonte
    if (e.altKey && e.key === "-") {
      e.preventDefault();
      document.getElementById("decrease-font")?.click();
    }

    // Alt + C: Toggle contraste
    if (e.altKey && e.key === "c") {
      e.preventDefault();
      const toggle = document.getElementById("toggle-contrast");
      if (toggle) {
        toggle.checked = !toggle.checked;
        toggle.dispatchEvent(new Event("change"));
      }
    }
  });

  accessibilityMenu?.addEventListener("keydown", (e) => {
    if (e.key === "Tab") {
      const focusableElements = accessibilityMenu.querySelectorAll(
        'button, [tabindex]:not([tabindex="-1"]), input'
      );

      const firstElement = focusableElements[0];
      const lastElement = focusableElements[focusableElements.length - 1];

      if (e.shiftKey && document.activeElement === firstElement) {
        e.preventDefault();
        lastElement.focus();
      } else if (!e.shiftKey && document.activeElement === lastElement) {
        e.preventDefault();
        firstElement.focus();
      }
    }
  });
});
