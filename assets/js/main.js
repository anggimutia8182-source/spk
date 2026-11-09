document.addEventListener('DOMContentLoaded', () => {
    // 1. Symptom Card Click Handler
    const symptomCards = document.querySelectorAll('.symptom-card');
    symptomCards.forEach(card => {
        const checkbox = card.querySelector('input[type="checkbox"]');
        if (checkbox) {
            // initial state
            if (checkbox.checked) {
                card.classList.add('selected');
            }
            card.addEventListener('click', (e) => {
                if (e.target !== checkbox) {
                    checkbox.checked = !checkbox.checked;
                }
                if (checkbox.checked) {
                    card.classList.add('selected');
                } else {
                    card.classList.remove('selected');
                }
                updateSelectedCount();
            });
        }
    });

    function updateSelectedCount() {
        const counterEl = document.getElementById('selectedGejalaCount');
        if (counterEl) {
            const count = document.querySelectorAll('.symptom-card input[type="checkbox"]:checked').length;
            counterEl.textContent = count;
        }
    }
    updateSelectedCount();

    // 2. Select All / Reset Buttons
    const btnSelectAll = document.getElementById('btnSelectAll');
    if (btnSelectAll) {
        btnSelectAll.addEventListener('click', () => {
            symptomCards.forEach(card => {
                const cb = card.querySelector('input[type="checkbox"]');
                if (cb) {
                    cb.checked = true;
                    card.classList.add('selected');
                }
            });
            updateSelectedCount();
        });
    }

    const btnResetAll = document.getElementById('btnResetAll');
    if (btnResetAll) {
        btnResetAll.addEventListener('click', () => {
            symptomCards.forEach(card => {
                const cb = card.querySelector('input[type="checkbox"]');
                if (cb) {
                    cb.checked = false;
                    card.classList.remove('selected');
                }
            });
            updateSelectedCount();
        });
    }

    // 3. Modal helper
    window.openModal = function(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    };

    window.closeModal = function(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
    };

    // Close on backdrop click
    document.querySelectorAll('.modal-backdrop').forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
        });
    });

    // 4. Table Filter
    const searchInput = document.getElementById('tableSearch');
    if (searchInput) {
        searchInput.addEventListener('input', () => {
            const query = searchInput.value.toLowerCase();
            const rows = document.querySelectorAll('.table-searchable tbody tr');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
        });
    }

    // 5. Confirm Delete
    window.confirmDelete = function(url, message = 'Apakah Anda yakin ingin menghapus data ini?') {
        if (confirm(message)) {
            window.location.href = url;
        }
    };
});
