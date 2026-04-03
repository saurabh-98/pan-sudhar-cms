// ==============================
// AUTH MODULE (PRODUCTION READY)
// ==============================

$(document).ready(function () {

    console.log("AUTH JS LOADED ✅");

    let isSubmitting = false;

    const forms = document.querySelectorAll("form");

    forms.forEach(form => {

        const submitBtn = form.querySelector("button[type='submit'], .btn-login-full, .btn-auth");

        form.addEventListener("submit", function (e) {
            e.preventDefault();

            if (isSubmitting) return;
            isSubmitting = true;

            clearAllErrors(form);

            let valid = true;

            const inputs = form.querySelectorAll("input[required]");

            inputs.forEach(input => {

                if (input.value.trim() === "") {
                    showError(input, `${input.name} is required`);
                    valid = false;
                    return;
                }

                if (input.type === "email" && !validateEmail(input.value)) {
                    showError(input, "Invalid email format");
                    valid = false;
                }

                if (input.name === "password" && input.value.length < 6) {
                    showError(input, "Minimum 6 characters required");
                    valid = false;
                }

                if (input.name === "password_confirmation") {
                    const pass = form.querySelector("input[name='password']");
                    if (pass && input.value !== pass.value) {
                        showError(input, "Passwords do not match");
                        valid = false;
                    }
                }

            });

            if (!valid) {
                isSubmitting = false;
                return;
            }

            Swal.fire({
                title: "Confirm?",
                text: getActionText(form),
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Yes"
            }).then(result => {

                if (!result.isConfirmed) {
                    isSubmitting = false;
                    return;
                }

                // 🔥 Button loading state
                if (submitBtn) {
                    submitBtn.dataset.original = submitBtn.innerHTML;
                    submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm"></span> Processing...`;
                    submitBtn.disabled = true;
                }

                let formData = new FormData(form);

                $.ajax({
                    url: form.action,
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        "X-CSRF-TOKEN": $('input[name="_token"]').val(),
                        "Accept": "application/json"
                    },

                    success: function (data) {

                        console.log("Response:", data);

                        resetButton();

                        if (data.status === "success") {

                            Swal.fire({
                                icon: "success",
                                title: data.message,
                                timer: 1200,
                                showConfirmButton: true
                            });

                            // ✅ SAFE REDIRECT
                            if (data.redirect) {
                                setTimeout(() => {
                                    window.location.href = data.redirect;
                                }, 1200);
                            }

                        } else {
                            Swal.fire("Error", data.message || "Something went wrong", "error");
                        }

                        isSubmitting = false;
                    },

                    error: function (xhr) {

                        resetButton();

                        // 🔥 Laravel validation errors
                        if (xhr.status === 422 && xhr.responseJSON?.errors) {
                            let errors = xhr.responseJSON.errors;

                            Object.values(errors).forEach(msg => {
                                Swal.fire("Validation Error", msg[0], "error");
                            });

                            isSubmitting = false;
                            return;
                        }

                        Swal.fire("Error", "Server error", "error");
                        isSubmitting = false;
                    }
                });

                function resetButton() {
                    if (submitBtn) {
                        submitBtn.innerHTML = submitBtn.dataset.original || "Submit";
                        submitBtn.disabled = false;
                    }
                }

            });

        });

    });


    // =========================
    // HELPERS
    // =========================

    function showError(input, message) {
        input.style.border = "2px solid red";

        let error = input.parentNode.querySelector(".error-text");

        if (!error) {
            error = document.createElement("small");
            error.classList.add("error-text");
            error.style.color = "red";
            input.parentNode.appendChild(error);
        }

        error.innerText = message;
    }

    function clearAllErrors(form) {
        form.querySelectorAll(".error-text").forEach(e => e.remove());
        form.querySelectorAll("input").forEach(i => i.style.border = "none");
    }

    function validateEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    function getActionText(form) {
        let url = form.action;

        if (url.includes("login")) return "Login to your account?";
        if (url.includes("register")) return "Create new account?";
        if (url.includes("forgot-password")) return "Send reset link?";
        if (url.includes("reset-password")) return "Reset your password?";
        return "Proceed?";
    }

});