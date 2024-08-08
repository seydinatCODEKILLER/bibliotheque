document.addEventListener("DOMContentLoaded", () => {
  function previewProfilePicture(event) {
    const reader = new FileReader();
    reader.onload = function () {
      const output = document.getElementById("profilePicture");
      output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
  }

  const pictureInput = document.getElementById("pictureInput");
  pictureInput.addEventListener("change", (e) => {
    previewProfilePicture(e);
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
});
