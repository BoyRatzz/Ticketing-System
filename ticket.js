// Add Ticket Modal
const modal = document.getElementById("addTicketModal");
const openBtn = document.querySelector(".btn-ticket");
const closeBtn = document.querySelector(".close-button");

openBtn.addEventListener("click", () => {
  modal.classList.add('show');
});

closeBtn.addEventListener("click", () => {
  modal.classList.remove('show');
});

window.addEventListener("click", (e) => {
  if (e.target === modal) {
    modal.classList.remove('show');
  }
});

// Edit Ticket Modal
const editModal = document.getElementById("editTicketModal");

document.querySelectorAll(".edit-btn").forEach((button) => {
  button.addEventListener("click", function (e) {
    e.preventDefault();

    // getting data from the clicked ticket
    const id = this.dataset.id;
    const title = this.dataset.title;
    const description = this.dataset.description;
    const recipientType = this.dataset.recipientType;
    const assignedTo = this.dataset.assignedTo;
    const status = this.dataset.status;

    // fill the fields
    document.getElementById("editTicketId").value = id;
    document.getElementById("editTitle").value = title;
    document.getElementById("editDescription").value = description;
    document.getElementById("editRecipientType").value = recipientType;
    document.getElementById("editAssignedTo").value = assignedTo;
    document.getElementById("editStatus").value = status;

    // show modal
    document.getElementById("editTicketModal").style.display = "block";

    toggleAssignedField();
  });
});

const editRecipientType = document.getElementById("editRecipientType");
const assignedInput = document.getElementById("editAssignedTo");
const assignedLabel = document.getElementById("editAssignedToLabel");

function toggleAssignedField() {
  const currentRecipientType = editRecipientType.value;
  if (currentRecipientType === "general") {
    assignedInput.style.display = "none";
    assignedLabel.style.display = "none";
  } else {
    assignedInput.style.display = "block";
    assignedLabel.style.display = "block";
  }
}

toggleAssignedField();
editRecipientType.addEventListener("change", toggleAssignedField);

// close edit ticket modal
document
  .querySelector("#editTicketModal .close-button")
  .addEventListener("click", () => {
    document.getElementById("editTicketModal").style.display = "none";
  });
window.addEventListener("click", (e) => {
  if (e.target === editTicketModal) {
    editTicketModal.style.display = "none";
  }
});

const searchInput = document.getElementById("searchTicket");
const table = document.querySelector(".tickets-table tbody");

searchInput.addEventListener("input", function () {
  const filter = this.value.toLowerCase();
  const rows = table.querySelectorAll("tr");

  rows.forEach((row) => {
    const descriptionCell = row.cells[2];
    const descriptionText = descriptionCell.textContent.toLowerCase();
    if (descriptionText.includes(filter)) {
      row.style.display = ""; // show
    } else {
      row.style.display = "none"; // hide
    }
  });
});
