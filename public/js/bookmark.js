document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".bookmark-btn").forEach(function (button) {
        button.addEventListener("click", function () {
            const postId = this.dataset.postId;
            const userId = "{{ Auth::id() }}";
            const token = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");

            fetch(`/posts/${postId}/save`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": token,
                },
                body: JSON.stringify({ user_id: userId }),
            })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error("Network response was not ok");
                    }
                    return response.json();
                })
                .then((data) => {
                    if (data.saved) {
                        this.classList.remove("far");
                        this.classList.add("fas");
                    } else {
                        this.classList.remove("fas");
                        this.classList.add("far");
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                    alert("An error occurred while saving the post.");
                });
        });
    });
});
