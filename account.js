const accountContainerSelector = document.querySelector(
  ".sidebar-content-createaccounts"
);
const ticketContainerSelector = document.querySelector(
  ".sidebar-content-tickets"
);

const accountContainer = document.querySelector(".account-container");
const ticketContainer = document.querySelector(".ticket-container");

ticketContainerSelector.addEventListener("click", () => {
  ticketContainer.style.display = "block";
  ticketContainerSelector.style.backgroundColor = "#dddddd";
  accountContainer.style.display = "none";
  accountContainerSelector.style.backgroundColor = "#ffffffee";
});

accountContainerSelector.addEventListener("click", () => {
  ticketContainer.style.display = "none";
  accountContainer.style.display = "block";
  accountContainerSelector.style.backgroundColor = "#dddddd";
  ticketContainerSelector.style.backgroundColor = "#ffffffee";
});

const addAccountForm = document.querySelector("add-account-form");

addAccountForm?.addEventListener("submit", (e) => {
  e.preventDefault();
  addAccountForm.reset();
});

// Edit modal
document.querySelectorAll(".edit-account").forEach((button) => {
  button.addEventListener("click", function (e) {
    e.preventDefault();

    const id = this.getAttribute("data-id");
    const username = this.getAttribute("data-title");
    const passwordHash = this.getAttribute("data-description");
    const modal = document.getElementById("editAccountModal");

    // Fill modal fields
    document.getElementById("edit-id").value = id;
    document.getElementById("edit-username").value = username;
    document.getElementById("edit-password").value = ""; // always blank
    document.getElementById("edit-role").value = this.closest("tr")
      .querySelector("td:nth-child(4)")
      .innerText.trim();

    // Show modal
    modal.style.display = "block";
  });
});

// Close modal
document.querySelector(".close-edit-modal").addEventListener("click", () => {
  document.getElementById("editAccountModal").style.display = "none";
});

// Close modal when clicking outside content
window.addEventListener("click", function (e) {
  const modal = document.getElementById("editAccountModal");
  if (e.target === modal) modal.style.display = "none";
});

const searchAccount = document.getElementById("searchAccount");
const table = document.querySelector(".accounts-table tbody");

searchAccount.addEventListener("input", function () {
  const filter = this.value.toLowerCase();
  const rows = table.querySelectorAll("tr");

  rows.forEach((row) => {
    const descriptionCell = row.cells[1];
    const descriptionText = descriptionCell.textContent.toLowerCase();
    if (descriptionText.includes(filter)) {
      row.style.display = ""; // show
    } else {
      row.style.display = "none"; // hide
    }
  });
});
