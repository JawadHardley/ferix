document.addEventListener("DOMContentLoaded", function () {
    const html = document.documentElement;
    const toggleBtn = document.getElementById("themeToggle");
    const themeIcon = document.getElementById("themeIcon");

    // Set initial theme from localStorage
    const storedTheme = localStorage.getItem("preferred-theme") || "light";
    html.setAttribute("data-bs-theme", storedTheme);
    updateIcon(storedTheme);

    toggleBtn.addEventListener("click", function () {
        const currentTheme = html.getAttribute("data-bs-theme");
        const newTheme = currentTheme === "dark" ? "light" : "dark";
        html.setAttribute("data-bs-theme", newTheme);
        localStorage.setItem("preferred-theme", newTheme);
        updateIcon(newTheme);
    });

    function updateIcon(theme) {
        if (theme === "dark") {
            themeIcon.classList.remove("fa-sun");
            themeIcon.classList.add("fa-moon");
        } else {
            themeIcon.classList.remove("fa-moon");
            themeIcon.classList.add("fa-sun");
        }
    }
});


  const toggle = document.getElementById('togglePassword');
  const password = document.getElementById('password');

  toggle.addEventListener('click', function () {
    // Toggle the type attribute
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);

    // Toggle icon (optional)
    this.innerHTML = type === 'password' ? '<i class="fa fa-eye"></i>' : '<i class="fa fa-eye-slash"></i>';
  });


  
  const toggle2 = document.getElementById('togglePassword2');
  const password2 = document.getElementById('password2');

  toggle2.addEventListener('click', function () {
    // Toggle the type attribute
    const type = password2.getAttribute('type') === 'password' ? 'text' : 'password';
    password2.setAttribute('type', type);

    // Toggle icon (optional)
    this.innerHTML = type === 'password' ? '<i class="fa fa-eye"></i>' : '<i class="fa fa-eye-slash"></i>';
  });

