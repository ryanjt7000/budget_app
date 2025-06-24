document.getElementById("signin-form").addEventListener("submit", function (e) {
    e.preventDefault();

    // Get input values
    const identifier = document.getElementById("identifier").value.trim();
    const password = document.getElementById("password").value;

    // Create payload with type: "log in"
    const payload = {
      type: "log in",
      identifier: identifier,
      password: password
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
        
        // Optionally redirect or update UI here
      sessionStorage.setItem("user_id", data.user_id);
      sessionStorage.setItem("username", data.username);
  
      // Redirect to main.html
      window.location.href = "main.html";
      } else {
        alert("Error: " + data.error);
      }
    })
    .catch(err => {
      alert("Network error: " + err.message);
    });
  });


  