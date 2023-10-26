// JavaScript for handling question posting and display

document.addEventListener("DOMContentLoaded", function () {
    const questionForm = document.getElementById("question-form");
    const topicSelect = document.getElementById("topic");
    const questionTextarea = document.getElementById("question");
    const questionList = document.getElementById("question-list");

    questionForm.addEventListener("submit", function (event) {
        event.preventDefault();

        const topic = topicSelect.value;
        const question = questionTextarea.value;

        // You can use AJAX to send the question data to the server (PHP) for processing and database storage.
        // Example using fetch API:
        fetch("post_question.php", {
            method: "POST",
            body: JSON.stringify({ topic, question }),
            headers: { "Content-Type": "application/json" },
        })
            .then((response) => response.json())
            .then((data) => {
                // Handle the response (e.g., display a success message or error)
                if (data.success) {
                    // Clear the input
                    questionTextarea.value = "";
                    // Reload or update the question list
                    updateQuestionList();
                } else {
                    alert("Error posting the question.");
                }
            })
            .catch((error) => {
                console.error(error);
                alert("An error occurred.");
            });
    });

    function updateQuestionList() {
        // You can use AJAX to retrieve and display questions from the server.
        // Example using fetch API:
        fetch("get_questions.php")
            .then((response) => response.json())
            .then((data) => {
                // Update the question list with the received data
                questionList.innerHTML = "";
                data.forEach((question) => {
                    const questionItem = document.createElement("div");
                    questionItem.classList.add("question-item");
                    questionItem.innerHTML = `<strong>Topic:</strong> ${question.topic}<br>${question.question}`;
                    questionList.appendChild(questionItem);
                });
            })
            .catch((error) => {
                console.error(error);
                alert("An error occurred while fetching questions.");
            });
    }

    // Initial question list update
    updateQuestionList();
});
