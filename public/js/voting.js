document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('.vote-form');

    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(form);
            const postId = form.getAttribute('data-post-id');
            const voteType = form.getAttribute('data-vote-type');

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Get the post container
                        const postContainer = document.querySelector(`[data-post-id="${postId}"]`);
                        const upvoteBtn = postContainer.querySelector('.upvote-btn');
                        const downvoteBtn = postContainer.querySelector('.downvote-btn');
                        const voteCount = postContainer.querySelector('.vote-count');

                        // Reset both arrows to default color
                        upvoteBtn.classList.remove('text-orange-500');
                        downvoteBtn.classList.remove('text-purple-500');
                        upvoteBtn.classList.add('text-gray-400');
                        downvoteBtn.classList.add('text-gray-400');

                        // Update the arrow color based on vote type
                        if (voteType === 'upvote') {
                            upvoteBtn.classList.remove('text-gray-400');
                            upvoteBtn.classList.add('text-orange-500');
                        } else {
                            downvoteBtn.classList.remove('text-gray-400');
                            downvoteBtn.classList.add('text-purple-500');
                        }

                        // Update vote count if provided
                        if (data.newCount !== undefined) {
                            voteCount.textContent = data.newCount;
                        }
                    } else {
                        alert(data.message || 'Error processing vote');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error processing vote');
                });
        });
    });
});

