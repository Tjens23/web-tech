const searchForm = document.getElementById('search-form');
const searchInput = document.getElementById('search-input');
const searchResults = document.getElementById('search-results');

searchForm.addEventListener('submit', function (e) {
    e.preventDefault();
    const query = searchInput.value;

    fetch(`/search?query=${query}`, {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
        .then(response => response.json())
        .then(data => {
            searchResults.innerHTML = '';
            if (data.users.length > 0) {
                data.users.forEach(user => {
                    const userCard = document.createElement('div');
                    userCard.classList.add('bg-white', 'rounded-md', 'shadow-md', 'p-4', 'mb-4');
                    userCard.innerHTML = `
                                    <h2 class="text-lg font-semibold">${user.username}</h2>
                                `;
                    searchResults.appendChild(userCard);
                });
            } else {
                searchResults.innerHTML = '<p>No users found.</p>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            searchResults.innerHTML = '<p>An error occurred while searching.</p>';
        });
});
