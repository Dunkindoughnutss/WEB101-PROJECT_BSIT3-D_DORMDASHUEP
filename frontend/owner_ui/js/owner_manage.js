document.addEventListener('DOMContentLoaded', function() {
    const requestItems = document.querySelectorAll('.request-item');
    const historyModal = document.getElementById('historyModal');
    const openHistoryBtn = document.getElementById('openHistoryBtn');
    const closeHistoryBtn = document.getElementById('closeHistoryBtn');

    // UI Elements for details
    const detName = document.getElementById('detName');
    const detContact = document.getElementById('detContact');
    const detEmail = document.getElementById('detEmail');
    const detGender = document.getElementById('detGender');
    const detHouse = document.getElementById('detHouse');
    const detDate = document.getElementById('detDate');
    const approveLink = document.getElementById('approveLink');
    const rejectLink = document.getElementById('rejectLink');

    // Handle switching between requests
    requestItems.forEach(item => {
        item.addEventListener('click', function() {
            // Remove active class from all
            requestItems.forEach(i => i.classList.remove('active'));
            // Add to clicked
            this.classList.add('active');

            // Parse data from data-attribute
            const data = JSON.parse(this.getAttribute('data-request'));

            // Update Details View
            detName.textContent = data.renterName;
            detContact.textContent = data.contact;
            detEmail.textContent = data.email;
            detGender.textContent = data.gender;
            detHouse.textContent = data.house_name;
            detDate.textContent = new Date(data.created_at).toLocaleDateString('en-US', {
                month: 'short', day: '2-digit', year: 'numeric'
            });

            // Update Action Links
            approveLink.href = `owner_manage.php?action=approve&res_id=${data.reservation_id}`;
            rejectLink.href = `owner_manage.php?action=reject&res_id=${data.reservation_id}`;
        });
    });

    // Initialize first request links if exists
    if (requestItems.length > 0) {
        const firstData = JSON.parse(requestItems[0].getAttribute('data-request'));
        approveLink.href = `owner_manage.php?action=approve&res_id=${firstData.reservation_id}`;
        rejectLink.href = `owner_manage.php?action=reject&res_id=${firstData.reservation_id}`;
    }

    // Modal Controls
    openHistoryBtn.addEventListener('click', () => historyModal.style.display = 'flex');
    closeHistoryBtn.addEventListener('click', () => historyModal.style.display = 'none');

    // Close modal on outside click
    window.addEventListener('click', (e) => {
        if (e.target === historyModal) historyModal.style.display = 'none';
    });
});