document.addEventListener('DOMContentLoaded', () => {
    const editBtn = document.getElementById('editBtn');
    const saveBtn = document.getElementById('saveBtn');
    const saveMessage = document.getElementById('saveMessage');
    const inputs = document.querySelectorAll('.profile-input:not([disabled])');
    const displayName = document.getElementById('display-name');

    /**
     * Toggles the form into Edit Mode
     */
    const enterEditMode = () => {
        inputs.forEach(input => {
            input.readOnly = false;
            input.classList.add('editing'); // Highlight fields being edited
        });
        
        editBtn.style.display = 'none';
        saveBtn.style.display = 'block';
        
        // Focus on the first input automatically
        if (inputs.length > 0) inputs[0].focus();
    };

    /**
     * Handles the "Edit Profile" button click
     */
    if (editBtn) {
        editBtn.addEventListener('click', (e) => {
            e.preventDefault();
            enterEditMode();
        });
    }

    /**
     * Toast Notification Logic
     * If the PHP script reloads the page with a success message, 
     * we show the toast and then hide it after 3 seconds.
     */
    if (saveMessage && saveMessage.classList.contains('show')) {
        setTimeout(() => {
            saveMessage.classList.remove('show');
            
            // Clean the URL (remove any success query params if you added them)
            const url = new URL(window.location);
            url.searchParams.delete('success');
            window.history.replaceState({}, '', url);
        }, 3000);
    }
});