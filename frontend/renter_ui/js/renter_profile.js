document.addEventListener('DOMContentLoaded', () => {
    const editBtn = document.getElementById('editBtn');
    const saveBtn = document.getElementById('saveBtn');
    const saveMessage = document.getElementById('saveMessage');
    const inputs = document.querySelectorAll('.profile-input');
    const displayName = document.getElementById('display-name');

    // Handle Edit/Cancel Toggle
    editBtn.addEventListener('click', () => {
        const isEditing = editBtn.innerText === "Edit Profile";
        
        if (isEditing) {
            enterEditMode();
        } else {
            exitEditMode();
        }
    });

    // Handle Save Feature
    saveBtn.addEventListener('click', () => {
        // Update the header name to match the first input (Full Name)
        if (inputs[0].value.trim() !== "") {
            displayName.innerText = inputs[0].value;
        }

        // Trigger Success Animation
        showToast();
        
        // Lock inputs back up
        exitEditMode();
    });

    function enterEditMode() {
        inputs.forEach(input => {
            input.readOnly = false;
            input.classList.add('editing');
        });
        editBtn.innerText = "Cancel";
        editBtn.className = "btn btn-secondary";
        saveBtn.style.display = "inline-block";
    }

    function exitEditMode() {
        inputs.forEach(input => {
            input.readOnly = true;
            input.classList.remove('editing');
        });
        editBtn.innerText = "Edit Profile";
        editBtn.className = "btn btn-primary";
        saveBtn.style.display = "none";
    }

    function showToast() {
        saveMessage.classList.add('show');
        setTimeout(() => {
            saveMessage.classList.remove('show');
        }, 3000);
    }
});