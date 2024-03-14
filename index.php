<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <title>Registration | Login Page</title>
</head>

<body>

    <div class="container" id="container">
        <div class="form-container sign-up">
            <form id="signupForm">
                <h1>Create Account</h1>
                <div class="social-icons">
                    <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
                <span>or use your email for registration</span>
                <input type="text" name="name" id="name" placeholder="Name">
                <input type="email" name="email" id="email" placeholder="Email">
                <input type="password" name="pass" id="pass" placeholder="Password">
                <input type="password" name="conpass" id="conpass" placeholder="Confirm Password">
                <button>Sign Up</button>
            </form>
        </div>
        <!-- ... (other parts of your HTML) ... -->
        <div class="form-container sign-in">
            <form id="loginForm">
                <h1>Sign In</h1>
                <div class="social-icons">
                    <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
                <span>or use your email password</span>
                <input type="email" name="email" id="loginEmail" placeholder="Email">
                <input type="password" name="pass" id="loginPassword" placeholder="Password">
                <a href="#">Forget Your Password?</a>
                <button>Sign In</button>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Welcome Back!</h1>
                    <p>Provide your personal information to access the complete set of site features, ensuring seamless utilization of our diverse functionality, including the <em><b>REMAINDER APP SCHEDULER.</b></em></p>
                    <button class="hidden" id="login">Sign In</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Hello, Friend!</h1>
                    <p>Register with your personal information to unlock the full array of site features, including the comprehensive scheduling capabilities of our <em><b>REMAINDER APP</b></em></p>
                    <button class="hidden" id="register">Sign Up</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script src="script.js"></script>

    <!-- Add this script block after the existing script block in your index.php file -->
    <script>
        function submitForm(event) {
            event.preventDefault(); // Prevent default form submission

            const passwordInput = document.getElementById("pass");
            const confirmPasswordInput = document.getElementById("conpass");

            // Check if password and confirm password match on the client side
            if (passwordInput.value !== confirmPasswordInput.value) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Password and Confirm Password do not match.',
                });
                return;
            }

            // Additional client-side validation if needed...

            // Send data to the server after client-side validation
            const formData = new FormData(document.getElementById("signupForm"));

            fetch("check_email.php", {
                method: "POST",
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    // Email does not exist, proceed with registration
                    registerUser();
                } else {
                    // Email already exists, show error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Email already exists.',
                    });
                }
            })
            .catch(error => {
                console.error("Error:", error);
            });
        }

        function registerUser() {
            // Continue with the registration process
            const formData = new FormData(document.getElementById("signupForm"));

            fetch("register.php", {
                method: "POST",
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                // Display SweetAlert based on the response
                Swal.fire({
                    icon: data.status === "success" ? 'success' : 'error',
                    title: data.status === "success" ? 'Success' : 'Error',
                    text: data.message,
                    timer: 9000, // Set the duration in milliseconds (3000 = 3 seconds)
                    showConfirmButton: false, // Hide the "OK" button
                });

                // Redirect the user on successful registration
                if (data.status === "success") {
                    setTimeout(function() {
                        window.location.href = "index.php"; // Replace with your success page URL
                    }, 3000); // Set the duration in milliseconds (3000 = 3 seconds)
                }
            })
            .catch(error => {
                console.error("Error:", error);
            });
        }

        // Attach the submitForm function to the form submit event
        document.getElementById("signupForm").addEventListener("submit", submitForm);
    </script>



<!-- Add this script block after the existing script block in your index.php file -->
<script>
    function submitLoginForm(event) {
        event.preventDefault(); // Prevent default form submission

        const emailInput = document.getElementById("loginEmail");
        const passwordInput = document.getElementById("loginPassword");

        // Additional client-side validation if needed...

        // Send login data to the server after client-side validation
        const formData = new FormData();
        formData.append("email", emailInput.value);
        formData.append("password", passwordInput.value);

        fetch("login.php", {
            method: "POST",
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            // Display SweetAlert based on the response
            Swal.fire({
                icon: data.status === "success" ? 'success' : 'error',
                title: data.status === "success" ? 'Success' : 'Error',
                text: data.message,
                timer: 9000, // Set the duration in milliseconds (3000 = 3 seconds)
                showConfirmButton: false, // Hide the "OK" button
            });

            // Redirect the user on successful login
            if (data.status === "success") {
                setTimeout(function() {
                    window.location.href = "dash.php"; // Replace with your dashboard page URL
                }, 3000); // Set the duration in milliseconds (3000 = 3 seconds)
            }
        })
        .catch(error => {
            console.error("Error:", error);
        });
    }

    // Attach the submitLoginForm function to the login form submit event
    document.getElementById("loginForm").addEventListener("submit", submitLoginForm);
</script>



</body>

</html>
