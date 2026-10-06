// Theme toggle: cycles to the opposite of what is currently shown and remembers it.
document.querySelector(".theme-toggle")?.addEventListener("click", () => {
  const root = document.documentElement;
  const current =
    root.dataset.theme ||
    (matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light");
  const next = current === "dark" ? "light" : "dark";
  root.dataset.theme = next;
  try {
    localStorage.setItem("theme", next);
  } catch {}
});

// Mobile navigation
document.querySelector(".menu-toggle")?.addEventListener("click", (event) => {
  const header = document.querySelector(".site-header");
  const open = header.classList.toggle("is-open");
  event.currentTarget.setAttribute("aria-expanded", String(open));
});

// Toasts: dismiss on click, success messages fade out on their own
function dismiss(toast) {
  toast.classList.add("is-leaving");
  setTimeout(() => toast.remove(), 200);
}

document.querySelectorAll(".toast").forEach((toast) => {
  toast.querySelector("button")?.addEventListener("click", () => dismiss(toast));
  if (!toast.classList.contains("toast--error")) {
    setTimeout(() => dismiss(toast), 5000);
  }
});

// Confirm destructive actions
document.querySelectorAll("form[data-confirm]").forEach((form) => {
  form.addEventListener("submit", (event) => {
    if (!confirm(form.dataset.confirm)) event.preventDefault();
  });
});

// Tabs
document.querySelectorAll("[role=tablist]").forEach((list) => {
  const tabs = list.querySelectorAll("[role=tab]");
  tabs.forEach((tab) => {
    tab.addEventListener("click", () => {
      tabs.forEach((other) => {
        const selected = other === tab;
        other.setAttribute("aria-selected", String(selected));
        document.getElementById(other.getAttribute("aria-controls")).hidden = !selected;
      });
    });
  });
});
