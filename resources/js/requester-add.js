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
    selectedOfficeNames[circleIndex] = selectedOfficeName;
}

function selectReviewer(reviewerName, circleIndex) {
    var reviewerDropdown = document.getElementById('reviewerDropdown' + circleIndex);
    reviewerDropdown.textContent = reviewerName;
}

document.addEventListener('DOMContentLoaded', function () {
    var selectedOffices = [];
    
    document.querySelectorAll('.office-select').forEach(function (select) {
        select.addEventListener('change', function () {
            var selectedOffice = this.value;

            document.querySelectorAll('.office-select').forEach(function (otherSelect) {
                if (otherSelect !== select) {
                    var option = otherSelect.querySelector("option[value='" + selectedOffice + "']");
                    if (option) {
                        option.disabled = true;
                    }
                }
            });
        });
    });

    function onSubmitForm() {
        var counter = 0;
        var isValid = true;

        document.querySelectorAll('.office-select').forEach(function (select, index) {
            var selectedOffice = select.value;

            if (selectedOffice !== '') {
                counter++;
                document.getElementById('upbox').insertAdjacentHTML('beforeend', "<input type='hidden' name='office_" + counter + "' value='" + selectedOffice + "'>");
            } else {
                isValid = false;
            }
        });

        document.getElementById('upbox').insertAdjacentHTML('beforeend', "<input type='hidden' name='total_offices' value='" + counter + "'>");

        if (!isValid) {
            alert("Ensure that you select the office names before proceeding with the submission.");
        }

        return isValid;
    }

    document.getElementById('upbox').addEventListener('submit', function (event) {
        if (!onSubmitForm()) {
            event.preventDefault(); 
        }
    });
});

document.getElementById('fileInput').addEventListener('change', function () {
  var fileInput = this;
  var submitButton = document.getElementById('subbtn2');

  if (fileInput.files.length > 0) {
    submitButton.removeAttribute('disabled');
  } else {
    submitButton.setAttribute('disabled', 'disabled');
  }
});


document.getElementById('subbtn2').addEventListener('click', function (event) {
  event.preventDefault(); 
  document.getElementById('subbtn').click(); 
});