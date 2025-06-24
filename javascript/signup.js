document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("signup-form");
  
    form.addEventListener("submit", function (e) {
      e.preventDefault();
  
      const email1 = document.getElementById("email").value.trim();
      const email2 = document.getElementById("confirm-email").value.trim();
      const username = document.getElementById("username").value.trim();
      const pass1 = document.getElementById("password").value;
      const pass2 = document.getElementById("confirm-password").value;
  
      // Basic email validation
      if (!email1 || !email2 || email1 !== email2) {
        alert("Emails must be filled and match.");
        return;
      }
  
      // Password match
      if (pass1 !== pass2) {
        alert("Passwords do not match.");
        return;
      }
  
      // Password complexity check
      const complexityRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).{8,}$/;
      if (!complexityRegex.test(pass1)) {
        alert("Password must be at least 8 characters long and include a lowercase letter, uppercase letter, number, and symbol.");
        return;
      }

      if (!username) {
        username = email1;
      }
  
      // Everything is valid — prepare data
      const payload = {
        type: "signup",
        email: email1,
        username: username,
        password: pass1
      };
  
      // Send POST request to main.php
      fetch("main.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify(payload)
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === "success") {
          alert("Sign up successful!");
          form.reset();
        } else {
          alert("Error: " + data.error);
        }
      })
      .catch(err => {
        alert("Network error: " + err.message);
      });
    });
  });


  