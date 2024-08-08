const dropdowns = document.querySelectorAll(".sidebar-link");

dropdowns.forEach((dropdown) => {
  dropdown.addEventListener("click", () => {
    if (dropdown.classList.contains("active")) {
      dropdown.classList.remove("active");
    } else {
      let allreadyActive = document.querySelectorAll(".active");
      allreadyActive.forEach((acitive) => {
        acitive.classList.remove("active");
      });
      dropdown.classList.add("active");
    }
  });
});

const notifications = document.querySelectorAll(".notif");
notifications.forEach((notification, index) => {
  setTimeout(() => {
    notification.remove();
  }, 8000);
  const closeIcons = document.querySelectorAll(".close");
  closeIcons.forEach((icon) => {
    icon.addEventListener("click", () => {
      notification.remove();
    });
  });
});
