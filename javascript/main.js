document.addEventListener("DOMContentLoaded", () => {

    //get this users detail and display in alert for checking

    const userId = sessionStorage.getItem("user_id");
    const username = sessionStorage.getItem("username");

    if (userId ) {
        alert(`Welcome! Your user ID is ${userId} (${username})`);
    } else {
        alert("No user info found — please log in.");
    }
    const form = document.getElementById("entry-form");

  
    form.addEventListener("submit", function (e) {
      e.preventDefault(); // Prevent default form submission

      const date = document.getElementById("date").value;
      const amountStr = document.getElementById("amount").value;
      const category = document.getElementById("catagory").value;
      const note = document.getElementById("note").value;
  
      // Check if required fields are filled
      if (!date || !amountStr || !category) {
        alert("Please fill in all required fields.");
        return;
      }
  
      // Try to parse the amount
      const amount = parseFloat(amountStr);
      if (isNaN(amount) || amount < 0) {
        alert("Amount must be a non-negative number.");
        return;
      }
  
      // All checks passed — you can now process or log the data
      console.log({
        date,
        amount,
        category,
        note,
      });
  
      fetch("main.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({type: "entry", date, amount, category, note, userId }),
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.status === "success") {
            alert("Submission received by server!");
            form.reset();
          } else {
            alert("Server error: " + data.error);
          }
        })
        .catch((err) => {
          alert("Network error: " + err.message);
        });
      
      form.reset();
    });
  });
  