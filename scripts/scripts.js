// Function to handle tab switching
function openTab(tabName) {
    var tabcontent = document.getElementsByClassName("tabcontent");
    var tablinks = document.getElementsByClassName("tablinks");

    // Hide all tab contents
    for (var i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Remove 'active' class from all tablinks
    for (var j = 0; j < tablinks.length; j++) {
        tablinks[j].classList.remove("active");
    }

    // Show the selected tab, if it exists
    var currentTab = document.getElementById(tabName);
    if (currentTab) {
        currentTab.style.display = "block";
    }
}

$(document).ready(function () {
    // Open the Home tab by default
    openTab('Home');

    // Handle tab click events
    $('.tablinks').on('click', function () {
        var tabName = $(this).data('tab');
        openTab(tabName);
        $(this).addClass('active');
    });

    // Handle form submissions for CRUD actions
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
                    $(formSelector)[0].reset(); // Clear form after submission
                },
                error: function (xhr, status, error) {
                    console.error("Error occurred: " + error);
                    $(resultSelector).html("<p>An error occurred. Please try again.</p>");
                }
            });
        });
    }

    // Handle form submissions for each CRUD action
    handleAjaxFormSubmission("#createForm", "scripts/create.php", "#createResult");
    handleAjaxFormSubmission("#readForm", "scripts/read.php", "#readResult");
    handleAjaxFormSubmission("#updateForm", "scripts/update.php", "#updateResult");
    handleAjaxFormSubmission("#deleteForm", "scripts/delete.php", "#deleteResult");

    // Clear form functionality
    $(".clear-button").click(function () {
        var formId = $(this).closest("form").attr("id");
        document.getElementById(formId).reset();

        // Clear all result displays
        $("#allResults").html(""); 
        $("#readResult").html("");
        $("#createResult").html("");
        $("#updateResult").html("");
        $("#deleteResult").html("");
    });

    // Display all SCPs on button click (without images)
    $("#displayAllButton").click(function () {
        const allResults = $("#allResults");
        const displayButton = $("#displayAllButton");

        if (allResults.css("display") === "none" || allResults.html() === "") {
            $.ajax({
                url: "scripts/list.php", // This file excludes images now
                dataType: 'json',
                success: function (data) {
                    // Clear previous results
                    allResults.html('');

                    // Check if data is an array and loop through each SCP
                    if (Array.isArray(data)) {
                        data.forEach(function (scp) {
                            const scpDiv = `
                                <div class="scp-entry">
                                    <h3>${scp.title} (${scp.scp_id})</h3>
                                    <p><strong>Object Class:</strong> ${scp.object_class}</p>
                                    <p><strong>Description:</strong> ${scp.description}</p>
                                    <p><strong>Special Containment Procedures:</strong> ${scp.procedures}</p>
                                </div>`;
                            allResults.append(scpDiv);
                        });
                    } else {
                        allResults.html("<p>No SCP entries found.</p>");
                    }

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
});
