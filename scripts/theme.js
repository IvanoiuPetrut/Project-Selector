// Applies the remembered theme before the page renders (loaded without defer in <head>).
try {
  const theme = localStorage.getItem("theme");
  if (theme) document.documentElement.dataset.theme = theme;
} catch {}
