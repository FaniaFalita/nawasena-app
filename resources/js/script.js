import { I18N } from "./i18n";

/* ============ HELPERS ============ */
const $ = (sel, root = document) => root.querySelector(sel);
const $$ = (sel, root = document) => [...root.querySelectorAll(sel)];

const FIRST_KEY="nawase...sage"; // pesan pembuka dari beranda -> layar chat (tahap FE)

let currentLang = "id";

function t(key) {
  const v = (I18N[currentLang] || {})[key];
  return typeof v === "string" ? v : key;
}
function tList(key) {
  const v = (I18N[currentLang] || {})[key];
  return Array.isArray(v) ? v : [];
}

function safeGet(key, fallback) {
  try { return localStorage.getItem(key) || fallback; } catch { return fallback; }
}
function safeSet(key, value) {
  try { localStorage.setItem(key, value); } catch { /* abaikan */ }
}

function showError(el, key) {
  if (!el) return;
  el.dataset.key = key || "";
  el.textContent = key ? t(key) : "";
}

function formatTime(date) {
  return date.toLocaleTimeString(currentLang === "id" ? "id-ID" : "en-US", {
    hour: "2-digit",
    minute: "2-digit",
  });
}

/* ============ TEMA ============ */
function setTheme(theme) {
  document.documentElement.setAttribute("data-theme", theme);
  $$("[data-theme-btn]").forEach((b) =>
    b.setAttribute("aria-pressed", String(b.dataset.themeBtn === theme))
  );
  safeSet("nawasena-theme", theme);
}

/* ============ BAHASA ============ */
function setLang(lang) {
  currentLang = I18N[lang] ? lang : "id";
  document.documentElement.lang = currentLang;
  document.title = t(document.body.dataset.titleKey || "title_landing");

  $$("[data-i18n]").forEach((el) => (el.textContent = t(el.dataset.i18n)));
  $$("[data-i18n-html]").forEach((el) => (el.innerHTML = t(el.dataset.i18nHtml)));
  $$("[data-i18n-placeholder]").forEach((el) =>
    el.setAttribute("placeholder", t(el.dataset.i18nPlaceholder))
  );
  $$("[data-i18n-aria]").forEach((el) =>
    el.setAttribute("aria-label", t(el.dataset.i18nAria))
  );
  $$("[data-lang-btn]").forEach((b) =>
    b.setAttribute("aria-pressed", String(b.dataset.langBtn === currentLang))
  );

  // refresh pesan error yang lagi tampil
  $$(".error[data-key], .form-error[data-key]").forEach((el) => {
    el.textContent = t(el.dataset.key);
  });

  safeSet("nawasena-lang", currentLang);
}

/* ============ HALAMAN MASUK / DAFTAR ============ */
function initAuth() {
  const form = $("#authForm") || $("#laravelAuthForm");
  if (!form) return;

  // Deteksi mode otomatis: jika ada field nickname atau password_confirmation, berarti register. Selain itu login.
  const mode = form.dataset.mode || (form.elements["nickname"] || form.elements["password_confirmation"] ? "register" : "login");
  const err = $("#authError");

  const val = (name) =>
    form.elements[name] ? form.elements[name].value : "";

  const confirmationInput =
    form.elements["password_confirmation"] ||
    form.elements["password2"];

  if (
    confirmationInput &&
    confirmationInput.name === "password2"
  ) {
    confirmationInput.name = "password_confirmation";
  }

  const fail = (key, field) => {
    showError(err, key);
    if (form.elements[field]) form.elements[field].focus();
  };

  form.addEventListener("submit", (e) => {
    showError(err, "");

    if (
      mode === "register" &&
      !val("nickname").trim()
    ) {
      e.preventDefault();
      return fail("err_nick", "nickname");
    }

    if (!/^\S+@\S+\.\S+$/.test(val("email").trim())) {
      e.preventDefault();
      return fail("err_email", "email");
    }

    if (mode === "login") {
      if (!val("password")) {
        e.preventDefault();
        return fail("err_pass_empty", "password");
      }
    } else {
      if (val("password").length < 8) {
        e.preventDefault();
        return fail("err_pass_short", "password");
      }

      if (
        confirmationInput &&
        val("password") !==
        val("password_confirmation")
      ) {
        e.preventDefault();
        return fail(
          "err_pass_match",
          "password_confirmation"
        );
      }
    }

    // Jika valid, form diteruskan ke Laravel
  });
}

/* ============ BERANDA (pesan pembuka) ============ */
function initHome() {
  const form = $("#curhatForm");
  if (!form) return;

  const message = $("#message");
  const count = $("#count");
  const msgError = $("#msgError");
  const submitBtn = $("#submitBtn");

  message.addEventListener("input", () => {
    count.textContent = message.value.length;
    if (message.value.trim().length >= 10) showError(msgError, "");
  });

  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const text = message.value.trim();

    if (!text) {
      showError(msgError, "err_empty");
      message.focus();
      return;
    }

    if (text.length < 10) {
      showError(msgError, "err_short");
      message.focus();
      return;
    }

    showError(msgError, "");

    const csrf = $('meta[name="csrf-token"]')?.content;
    if (submitBtn) submitBtn.disabled = true;

    try {
      const response = await fetch("/conversations", {
        method: "POST",
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
          ...(csrf ? { "X-CSRF-TOKEN": csrf } : {}),
        },
        body: JSON.stringify({
          message: text,
          mood:
            Number(
              (form.querySelector('input[name="mood"]:checked') || {}).value
            ) || null,
          topic:
            (form.querySelector('input[name="topic"]:checked') || {}).value ||
            null,
          lang: currentLang,
        }),
      });

      const data = await response.json();

      if (!response.ok || !data.url) {
        throw new Error("Conversation could not be created");
      }

      window.location.href = data.url;
    } catch {
      msgError.textContent =
        currentLang === "en"
          ? "Couldn't start the conversation. Please try again."
          : "Obrolan belum bisa dibuat. Coba lagi ya.";
    } finally {
      if (submitBtn) submitBtn.disabled = false;
    }
  });
}

/* ============ LAYAR CHAT ============ */
function initChat() {
  const body = $("#chatBody");
  if (!body) return;

  const form = $("#chatForm");
  const input = $("#chatInput");
  const sendBtn = $("#chatSend");
  let busy = false;

  const scrollDown = () => { body.scrollTop = body.scrollHeight; };

  function addMessage(role, text) {
    const wrap = document.createElement("div");
    wrap.className = `msg msg-${role}`;
    const bubble = document.createElement("div");
    bubble.className = "bubble";
    bubble.textContent = text;
    const time = document.createElement("span");
    time.className = "msg-time";
    time.textContent = formatTime(new Date());
    wrap.append(bubble, time);
    body.append(wrap);
    scrollDown();
    return wrap;
  }

  function showTyping() {
    const wrap = document.createElement("div");
    wrap.className = "msg msg-bot typing";
    wrap.setAttribute("role", "status");
    wrap.setAttribute("aria-label", t("chat_typing"));
    const bubble = document.createElement("div");
    bubble.className = "bubble";
    for (let i = 0; i < 3; i++) {
      const dot = document.createElement("span");
      dot.className = "dot";
      bubble.append(dot);
    }
    wrap.append(bubble);
    body.append(wrap);
    scrollDown();
    return wrap;
  }

  function currentHistory() {
    return $$(".msg", body)
      .filter((el) => !el.classList.contains("typing"))
      .slice(-12)
      .map((el) => ({
        role: el.classList.contains("msg-user") ? "user" : "assistant",
        content: $(".bubble", el)?.textContent?.trim() || "",
      }))
      .filter((turn) => turn.content.length > 0);
  }

  async function botReply(message, history = []) {
    const typing = showTyping();
    const csrf = $('meta[name="csrf-token"]')?.content;

    try {
      const response = await fetch("/api/chat/reply", {
        method: "POST",
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
          ...(csrf ? { "X-CSRF-TOKEN": csrf } : {}),
        },
        body: JSON.stringify({
          message,
          lang: currentLang,
          history: history.slice(-12),
        }),
      });

      if (!response.ok) {
        throw new Error("Chat request failed");
      }

      const data = await response.json();

      if (typeof data.reply !== "string" || !data.reply.trim()) {
        throw new Error("Chat response was empty");
      }

      addMessage("bot", data.reply.trim());
    } catch {
      addMessage("bot", t("chat_error"));
    } finally {
      typing.remove();
    }
  }

  if (body.dataset.mode === "new") {
    const ticket = "NWS-" + Math.random().toString(36).slice(2, 8).toUpperCase();
    const ticketEl = $("#chatTicket");
    if (ticketEl) ticketEl.textContent = ticket;

    let first = null;
    try {
      first = JSON.parse(sessionStorage.getItem(FIRST_KEY) || "null");
      sessionStorage.removeItem(FIRST_KEY);
    } catch { /* abaikan */ }

    if (first && first.message) {
      const history = currentHistory();

      addMessage("user", first.message);
      busy = true;
      sendBtn.disabled = true;

      botReply(first.message, history).finally(() => {
        busy = false;
        sendBtn.disabled = false;
      });
    } else {
      addMessage("bot", t("bot_greeting"));
    }
  } else {
    scrollDown();
  }

  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    const text = input.value.trim();
    if (!text || busy) return;

    const history = currentHistory();

    input.value = "";
    input.style.height = "";
    addMessage("user", text);

    busy = true;
    sendBtn.disabled = true;
    await botReply(text, history);
    busy = false;
    sendBtn.disabled = false;
    input.focus();
  });

  input.addEventListener("keydown", (e) => {
    if (e.key === "Enter" && !e.shiftKey && !e.isComposing) {
      e.preventDefault();
      form.requestSubmit();
    }
  });

  input.addEventListener("input", () => {
    input.style.height = "auto";
    input.style.height = Math.min(input.scrollHeight, 140) + "px";
  });
}

/* ============ INIT ============ */
$$("[data-theme-btn]").forEach((b) =>
  b.addEventListener("click", () => setTheme(b.dataset.themeBtn))
);
$$("[data-lang-btn]").forEach((b) =>
  b.addEventListener("click", () => setLang(b.dataset.langBtn))
);

setTheme(safeGet("nawasena-theme", "light"));
setLang(safeGet("nawasena-lang", "id"));

initAuth();
initHome();
initChat();