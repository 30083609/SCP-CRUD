// Function to handle tab switching
function openTab(evt, tabName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none"; // Hide all tab contents
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", ""); // Remove active class
    }
    document.getElementById(tabName).style.display = "block"; // Show selected tab
    evt.currentTarget.className += " active"; // Mark current tab active
}

// Show CRUD interface when clicking on header
function showCRUD() {
    document.getElementById('Home').style.display = 'none';
    document.getElementById('crud-container').style.display = 'block';
}

// Load the Home tab (image) by default
document.addEventListener("DOMContentLoaded", function () {
    openTab(event, 'Home');  // Automatically open Home tab on load
});


// Handle AJAX form submissions
$(document).ready(function () {
    function handleAjaxFormSubmission(formSelector, url, resultSelector) {
        $(formSelector).submit(function (event) {
            event.preventDefault();
            $(resultSelector).html("<p>Loading...</p>");
            $.ajax({
                type: "POST",
                url: url,
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function (response) {
                    $(resultSelector).html(response);
                },
                error: function (xhr, status, error) {
                    console.error("Error occurred: " + error);
                    $(resultSelector).html("<p>An error occurred. Please try again.</p>");
                }
            });
        });
    }

    // Handle form submissions for CRUD actions
    handleAjaxFormSubmission("#createForm", "scripts/create.php", "#createResult");
    handleAjaxFormSubmission("#readForm", "scripts/read.php", "#readResult");
    handleAjaxFormSubmission("#updateForm", "scripts/update.php", "#updateResult");
    handleAjaxFormSubmission("#deleteForm", "scripts/delete.php", "#deleteResult");

    // Display all SCPs on button click
    $("#displayAllButton").click(function () {
        const allResults = $("#allResults");
        const displayButton = $("#displayAllButton");
        if (allResults.css("display") === "none" || allResults.html() === "") {
            $.ajax({
                url: "scripts/list.php",
                success: function (data) {
                    allResults.html(data);
                    displayButton.text("Hide All SCPs");
                    allResults.show();
                },
                error: function () {
                    allResults.html("<p>Error loading SCPs.</p>");
                }
            });
        } else {
            allResults.hide();
            displayButton.text("Display All SCPs");
        }
    });

    // Function to clear all fields in a form and clear SCP data
    function clearForm(formId) {
        document.getElementById(formId).reset(); // Reset the form fields
        $("#allResults").html("");  // Clear the Display All SCPs section
        $("#readResult").html("");  // Clear the Read SCP result section
    }

    // Apply this clear function to each form's reset (Clear All) button
    $("#createForm .clear-button").click(function () {
        clearForm("createForm");
    });
    $("#readForm .clear-button").click(function () {
        clearForm("readForm");
    });
    $("#updateForm .clear-button").click(function () {
        clearForm("updateForm");
    });
    $("#deleteForm .clear-button").click(function () {
        clearForm("deleteForm");
    });

    // Enable or disable form fields based on checkbox selection in the update form
    $("#update_title_check").change(function () {
        $("#update_title").prop("disabled", !this.checked);
    });
    $("#update_object_class_check").change(function () {
        $("#update_object_class").prop("disabled", !this.checked);
    });
    $("#update_description_check").change(function () {
        $("#update_description").prop("disabled", !this.checked);
    });
    $("#update_procedures_check").change(function () {
        $("#update_procedures").prop("disabled", !this.checked);
    });
    $("#update_image_check").change(function () {
        $("#update_image").prop("disabled", !this.checked);
    });
});
