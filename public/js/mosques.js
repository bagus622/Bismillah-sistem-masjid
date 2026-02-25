// Mosques Management JavaScript
document.addEventListener('DOMContentLoaded', function () {
    lucide.createIcons();
    console.log('Mosques page loaded');

    // Handle form submission with loading
    const deleteForm = document.getElementById('deleteForm');
    if (deleteForm) {
        deleteForm.addEventListener('submit', function (e) {
            console.log('Form submitting...');
            window.loading.show('Menghapus masjid...');
        });
    }
});

function confirmDelete(id, name) {
    console.log('=== confirmDelete called ===');
    console.log('ID:', id, 'Name:', name);

    const modal = document.getElementById('deleteModal');
    const deleteForm = document.getElementById('deleteForm');
    const mosqueName = document.getElementById('mosqueName');

    if (!modal || !deleteForm || !mosqueName) {
        window.toast.error('Error: Modal elements not found!');
        return false;
    }

    // Ensure id is a valid integer
    id = parseInt(id, 10);
    if (isNaN(id) || id <= 0) {
        window.toast.error('Error: Invalid mosque ID!');
        return false;
    }

    // Use absolute URL
    const baseUrl = window.location.origin + '/project-basmalahCopy';
    const deleteUrl = baseUrl + '/mosques/delete/' + id;
    console.log('Delete URL:', deleteUrl);

    deleteForm.action = deleteUrl;
    mosqueName.textContent = name;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
    setTimeout(() => {
        modal.classList.remove('opacity-0');
        modal.querySelector('.transform').classList.remove('scale-95');
        modal.querySelector('.transform').classList.add('scale-100');
        lucide.createIcons();
    }, 10);

    return false;
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.classList.add('opacity-0');
    modal.querySelector('.transform').classList.remove('scale-100');
    modal.querySelector('.transform').classList.add('scale-95');
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 200);
}

// Close modal on backdrop click
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('deleteModal');
    if (modal) {
        modal.addEventListener('click', function (e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
    }
});

// Close modal on Escape key
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});
