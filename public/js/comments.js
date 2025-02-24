document.addEventListener("DOMContentLoaded", function () {
    const commentButtons = document.querySelectorAll(".comment-btn");
    const modal = document.getElementById("comment-modal");
    const closeModal = document.getElementById("close-modal");
    const postIdInput = document.getElementById("post-id");
    const commentForm = document.getElementById("comment-form");

    commentButtons.forEach((button) => {
        button.addEventListener("click", function () {
            postIdInput.value = this.dataset.postId;
            modal.classList.remove("hidden");
        });
    });

    closeModal.addEventListener("click", function () {
        modal.classList.add("hidden");
    });

    commentForm.addEventListener("submit", function (e) {
        e.preventDefault();
        const postId = document.getElementById("post-id").value;
        const comment = document.getElementById("comment").value;

        fetch(`/posts/${postId}/comments`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
            body: JSON.stringify({ comment }),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    location.reload();
                } else {
                    location.reload();
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                location.reload();
            });
    });
});
