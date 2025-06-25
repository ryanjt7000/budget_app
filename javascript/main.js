document.addEventListener("DOMContentLoaded", () => {

    //get this users detail and display in alert for checking

    const userId = sessionStorage.getItem("user_id");
    const username = sessionStorage.getItem("username");

    const expenseTab = document.getElementById("trapezoid-expense");
    const incomeTab = document.getElementById("trapezoid-income");

    const expenseForm = document.getElementById("center-box-expense");
    const incomeForm = document.getElementById("center-box-income");

    expenseTab.addEventListener("click", function () {

        expenseForm.style.display = "flex";
        incomeForm.style.display = "none";

    });
  
      // Click handler for Income
    incomeTab.addEventListener("click", function () {

        expenseForm.style.display = "none";
        incomeForm.style.display = "flex";

    });

    if (userId ) {
        alert(`Welcome! Your user ID is ${userId} (${username})`);
    } else {
        alert("No user info found — please log in.");
    }
    const formExpense = document.getElementById("entry-form-expense");
    const formIncome = document.getElementById("entry-form-income");

    var date;
    var amountStr;
    var category;
    var note;
    var type;

  
    formExpense.addEventListener("submit", function (e) {
      e.preventDefault(); // Prevent default form submission

      date = document.getElementById("date-expense").value;
      amountStr = document.getElementById("amount-expense").value;
      category = document.getElementById("catagory-expense").value;
      note = document.getElementById("note-expense").value;
      type = "expense";
  
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
    //   console.log({
    //     date,
    //     amount,
    //     category,
    //     note,
    //   });
     
      fetch("main.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({post_type: "entry", date, type, amount, category, note, userId }),
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.status === "success") {
            alert("Submission received by server!");
            formExpense.reset();
          } else {
            alert("Server error: " + data.error);
          }
        })
        .catch((err) => {
          alert("Network error: " + err.message);
        });
      
      formExpense.reset();
    });


    formIncome.addEventListener("submit", function (e) {
        e.preventDefault(); // Prevent default form submission

        date = document.getElementById("date-income").value;
        amountStr = document.getElementById("amount-income").value;
        category = document.getElementById("catagory-income").value;
        note = document.getElementById("note-income").value;
        type = "income";
    
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
      //   console.log({
      //     date,
      //     amount,
      //     category,
      //     note,
      //   });
       
        fetch("main.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({post_type: "entry", date, type, amount, category, note, userId }),
        })
          .then((res) => res.json())
          .then((data) => {
            if (data.status === "success") {
              alert("Submission received by server!");
              formIncome.reset();
            } else {
              alert("Server error: " + data.error);
            }
          })
          .catch((err) => {
            alert("Network error: " + err.message);
          });
        
        formIncome.reset();
      });


});

  