function updateReviewerText(selectedOfficeName, circleIndex) {
    console.log('Selected office:', selectedOfficeName);

    var officeDropdown = document.querySelectorAll('.office-dropdown')[circleIndex - 1];

    var selectedOfficeNames = [];
    for (var i = 0; i < circleIndex; i++) {
        selectedOfficeNames.push(document.querySelectorAll('.office-dropdown')[i].textContent.trim());
    }

    if (selectedOfficeNames.includes(selectedOfficeName)) {
        alert('You cannot select the same office name twice.');
        return;
    }

    officeDropdown.textContent = selectedOfficeName;
    // Store the selected office name in the array
    selectedOfficeNames[circleIndex] = selectedOfficeName;

    
}

function selectReviewer(reviewerName, circleIndex) {
    var reviewerDropdown = document.getElementById('reviewerDropdown' + circleIndex);
    reviewerDropdown.textContent = reviewerName;
}