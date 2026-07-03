const searchInput = document.getElementById('searchInput');
    const rows = document.querySelectorAll('.data-row');

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();

        rows.forEach(row => {
            const searchData = row.dataset.search;
            if(searchData.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('keyup', filterTable);