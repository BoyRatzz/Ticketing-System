const addTicketBtn = document.querySelector('.btn-ticket');
const modal = document.getElementById('addTicketModal');
const closeBtn = document.querySelector('.close-button');
const recipientSelect = document.getElementById('recipient_type');
const assignedInput = document.getElementById('assigned_to');
const assignedLabel = document.getElementById('assignedLabel');

// open modal when addTicketBtn is clicked
addTicketBtn.addEventListener('click', () => {
    modal.style.display = 'block';
});

// close modal
closeBtn.addEventListener('click', () => {
    modal.style.display = 'none';
});
window.addEventListener('click', (e) => {
    if (e.target == modal) {
        modal.style.display = 'none';
    }
});

// Show assigned input only if recipient_type is 'specific'
recipientSelect.addEventListener('change', () => {
    if (recipientSelect.value === 'specific') {
        assignedInput.style.display = 'block';
        assignedLabel.style.display = 'block';
    } else {
        assignedInput.style.display = 'none';
        assignedLabel.style.display = 'none';
    }
});