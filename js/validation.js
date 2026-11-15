document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector("#transactionForm");

  if (!form) return;

  form.addEventListener("submit", function (event) {
    const amount = parseFloat(form.querySelector("#amount").value);
    const description = form.querySelector("#description").value.trim();
    const date = form.querySelector("#transaction_date").value;

    if (isNaN(amount) || amount <= 0) {
      alert("Please enter an amount greater than zero.");
      event.preventDefault();
      return;
    }

    if (description === "") {
      alert("Please enter a description.");
      event.preventDefault();
      return;
    }

    if (date === "") {
      alert("Please select a date.");
      event.preventDefault();
    }
  });
});
