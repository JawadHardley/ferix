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

  if (toggle !== null) {
    toggle.addEventListener('click', function () {
      // Toggle the type attribute
      const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
      password.setAttribute('type', type);
  
      // Toggle icon (optional)
      this.innerHTML = type === 'password' ? '<i class="fa fa-eye"></i>' : '<i class="fa fa-eye-slash"></i>';
    });
  }


  const toggle2 = document.getElementById('togglePassword2');
  const password2 = document.getElementById('password2');
  
  if (toggle2 !== null) {

  toggle2.addEventListener('click', function () {
    // Toggle the type attribute
    const type = password2.getAttribute('type') === 'password' ? 'text' : 'password';
    password2.setAttribute('type', type);

    // Toggle icon (optional)
    this.innerHTML = type === 'password' ? '<i class="fa fa-eye"></i>' : '<i class="fa fa-eye-slash"></i>';
  });
  }




  
  document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('multiStepForm');
    const steps = document.querySelectorAll('.step');
    const formSteps = document.querySelectorAll('.form-step');
    const progress = document.getElementById('progress');
    const prevBtns = document.querySelectorAll('.btn-prev');
    const nextBtns = document.querySelectorAll('.btn-next');
    const successMessage = document.querySelector('.success-message');

    let currentStep = 1;
    updateProgress();

    // Validation rules
    const validationRules = {
      x1: {
            validate: (value) => value.trim().length >= 3,
            message: 'Please enter your full name (minimum 3 characters)'
        },
        email: {
            validate: (value) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value),
            message: 'Please enter a valid email address'
        },
        phone: {
            validate: (value) => /^\+?[\d\s-]{10,}$/.test(value),
            message: 'Please enter a valid phone number'
        },
        country: {
            validate: (value) => value !== '',
            message: 'Please select your country'
        },
        message: {
            validate: (value) => !value || value.length <= 500,
            message: 'Please keep your message under 500 characters'
        },
        terms: {
            validate: (value) => value === true,
            message: 'You must agree to the terms and conditions'
        }
    };

    // Next button click handler
    nextBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            if (validateStep(currentStep)) {
                if (btn.type === 'submit') {
                    handleSubmit();
                    return;
                }
                currentStep++;
                updateStep();
            }
        });
    });

    // Previous button click handler
    prevBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            currentStep--;
            updateStep();
        });
    });

    // Form submit handler
    // form.addEventListener('submit', (e) => {
    //     e.preventDefault();
    //     handleSubmit();
    // });

    function updateStep() {
        // Update steps
        steps.forEach(step => {
            const stepNum = parseInt(step.dataset.step);
            step.classList.remove('active', 'completed');
            if (stepNum === currentStep) {
                step.classList.add('active');
            } else if (stepNum < currentStep) {
                step.classList.add('completed');
            }
        });

        // Update form steps visibility
        formSteps.forEach(step => {
            step.classList.remove('active');
            if (parseInt(step.dataset.step) === currentStep) {
                step.classList.add('active');
            }
        });

        // Update buttons
        updateButtons();
        // Update progress bar
        updateProgress();
    }

    function updateButtons() {
        // Update Previous button state
        prevBtns.forEach(btn => {
            btn.disabled = currentStep === 1;
        });
    }

    function updateProgress() {
        const percent = ((currentStep - 1) / (steps.length - 1)) * 100;
        progress.style.width = `${percent}%`;
    }

    function validateStep(step) {
        const currentFormStep = document.querySelector(`.form-step[data-step="${step}"]`);
        const inputs = currentFormStep.querySelectorAll('input[required], select[required], input[type="text"]');
        let isValid = true;

        inputs.forEach(input => {
            const rule = validationRules[input.name];
            if (rule) {
                const value = input.type === 'checkbox' ? input.checked : input.value;
                console.log(value, 'value', input)
                const isInputValid = rule.validate(value);
                const errorMessage = input.parentElement.querySelector('.error-message');

                if (!isInputValid) {
                    isValid = false;
                    input.classList.add('error');
                    if (errorMessage) {
                        errorMessage.classList.add('visible');
                    }
                } else {
                    input.classList.remove('error');
                    if (errorMessage) {
                        errorMessage.classList.remove('visible');
                    }
                }
            }
        });

        return isValid;
    }

    // function handleSubmit() {
    //     if (validateStep(currentStep)) {
    //         form.style.display = 'none';
    //         successMessage.classList.add('active');
    //         // Here you would typically send the form data to a server
    //         // console.log('Form submitted!', Object.fromEntries(new FormData(form)));
    //     }
    // }

    // Add input event listeners for real-time validation
    form.querySelectorAll('input, select').forEach(input => {
        input.addEventListener('input', () => {
            if (input.required || input.type === 'text') {
                const rule = validationRules[input.name];
                if (rule) {
                    const value = input.type === 'checkbox' ? input.checked : input.value;
                    const isValid = rule.validate(value);
                    const errorMessage = input.parentElement.querySelector('.error-message');

                    if (!isValid) {
                        input.classList.add('error');
                        if (errorMessage) {
                            errorMessage.classList.add('visible');
                        }
                    } else {
                        input.classList.remove('error');
                        if (errorMessage) {
                            errorMessage.classList.remove('visible');
                        }
                    }
                }
            }
        });
    });
});