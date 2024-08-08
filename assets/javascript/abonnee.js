document.addEventListener("DOMContentLoaded", function () {
  const forms = document.querySelectorAll("form");

  forms.forEach((form) => {
    form.addEventListener("submit", function (event) {
      const form = event.target;
      const isSuspendForm = form.querySelector(".suspend-btn") !== null;

      // Désactiver le bouton et activer l'autre bouton
      if (isSuspendForm) {
        form.querySelector(".suspend-btn").disabled = true;
        form.nextElementSibling.disabled = false;
      } else {
        form.querySelector(".activate-btn").disabled = true;
        form.previousElementSibling.disabled = false;
      }
    });
  });
});
