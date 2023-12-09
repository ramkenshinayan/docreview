const form = document.querySelector("form"),
  fileInput = document.querySelector(".file-input"),
  progressArea = document.querySelector(".progress-area"),
  uploadedArea = document.querySelector(".uploaded-area");

form.addEventListener("click", () => {
  fileInput.click();
});

fileInput.onchange = ({ target }) => {
  let file = target.files[0];
  if (file) {
    let fileName = file.name;
    if (fileName.length >= 12) {
      let splitName = fileName.split('.');
      fileName = splitName[0].substring(0, 13) + "... ." + splitName[1];
    }
    uploadFile(fileName);
  }
}

function uploadFile(name) {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "includes/upload.php");
  xhr.upload.addEventListener("progress", ({ loaded, total }) => {
    let fileLoaded = Math.floor((loaded / total) * 100);
    let fileTotal = Math.floor(total / 1000);
    let fileSize;
    (fileTotal < 1024) ? fileSize = fileTotal + " KB" : fileSize = (loaded / (1024 * 1024)).toFixed(2) + " MB";
    let progressHTML = `<li class="row">
                          <ion-icon name="document-outline"></ion-icon>
                          <div class="content">
                            <div class="details">
                              <span class="name">${name} • Uploading</span>
                              <span class="percent">${fileLoaded}%</span>
                            </div>
                            <div class="progress-bar">
                              <div class="progress" style="width: ${fileLoaded}%"></div>
                            </div>
                          </div>
                        </li>`;
    uploadedArea.classList.add("onprogress");
    progressArea.innerHTML = progressHTML;
    if (loaded == total) {
      progressArea.innerHTML = "";
      let uploadedHTML = `<li class="row">
                            <div class="content upload">
                              <ion-icon name="document-outline"></ion-icon>
                              <div class="details">
                                <span class="name">${name} • Uploaded</span>
                                <span class="size">${fileSize}</span>
                              </div>
                            </div>
                            <ion-icon name="checkmark-outline"></ion-icon>
                            <div class="removefile">
                            <button onclick="removeFile(this);">Remove</button>
                            </div>
                          </li>`;
      uploadedArea.classList.remove("onprogress");
      uploadedArea.insertAdjacentHTML("afterbegin", uploadedHTML);
    }
  });
  var data = new FormData(form);
  data.append("uploadDate", new Date().toLocaleString()); 
  xhr.send(data);
  form.disabled = "true";
  // fileInput.disabled = true;
  form.style.cursor = 'default';
}

function removeFile(file) {
  file.parentNode.parentNode.remove();
  
  fileInput.value = "";
  fileInput.disabled = false;
  form.style.cursor = 'pointer';
};

document.getElementById('closeModalButton').addEventListener('click', function () {
  // Replace 'your-homepage.html' with the actual URL of your homepage
  window.location.href = 'request-home.html';
});

document.addEventListener('DOMContentLoaded', function() {
  const allDropdowns = document.querySelectorAll('.seq');
  const chosenOptions = new Set();
  let chosenDropdowns = 0;

  allDropdowns.forEach(function(seq, index) {
    const dropdownItems = seq.querySelectorAll('.dropdown-item');
    const reviewerTitleBtn = seq.querySelector('.custom-dropdown-btn');
    const nameOfReviewerBtn = seq.querySelector('#name-of-reviewer-btn');
    const nameOfReviewerDropdown = seq.querySelector('#name-of-reviewer-dropdown');
    const subsequentDropdowns = document.querySelectorAll(`.seq:nth-child(n + ${index + 2}) .dropdown-item`);

    dropdownItems.forEach(function(item) {
      item.addEventListener('click', function(event) {
        const selectedReviewer = event.target.textContent;

        // Check if the user has chosen in the previous dropdown
        if (chosenDropdowns < index) {
          alert("Please choose in the previous reviewer first.");
          return;
        }

        if (!chosenOptions.has(selectedReviewer) && chosenDropdowns <= index) {
          chosenOptions.add(selectedReviewer);
          chosenDropdowns = index + 1;

          reviewerTitleBtn.textContent = selectedReviewer;

          // Fetch reviewer names from the database
          const reviewerNames = getReviewerNamesFromDatabase(selectedReviewer);

          // Populate the name of reviewer dropdown
          nameOfReviewerDropdown.innerHTML = '';
          reviewerNames.forEach(function(name) {
            const li = document.createElement('li');
            li.innerHTML = `<a class="dropdown-item">${name}</a>`;
            li.addEventListener('click', function() {
              nameOfReviewerBtn.textContent = name; // Update the title with the selected name
            });
            nameOfReviewerDropdown.appendChild(li);
          });

          dropdownItems.forEach(function(dropdownItem) {
            if (dropdownItem.textContent === selectedReviewer) {
              dropdownItem.style.pointerEvents = 'none';
              dropdownItem.style.color = 'grey';
            }
          });

          subsequentDropdowns.forEach(function(subsequentItem) {
            if (subsequentItem.textContent === selectedReviewer) {
              subsequentItem.style.pointerEvents = 'none';
              subsequentItem.style.color = 'grey';
            }
          });
        }
      });
    });
  });
});



function getReviewerNamesFromDatabase(reviewer) {
  // For this example, returning a static list of names
  if (reviewer === 'Unit') {
    return ['John Doe', 'Jane Smith'];
  } else if (reviewer === 'Office of the Vice President for Academic Affairs') {
    return ['Alice Johnson', 'Bob Williams'];
  } else if (reviewer === 'Office of the Vice President for Finance') {
    return ['Eva Brown', 'Michael Davis'];
  } else if (reviewer === 'Office for Legal Affairs') {
    return ['Olivia Wilson', 'William Garcia'];
  } else if (reviewer === 'Office of the Vice President for Administration') {
    return ['Sophia Martinez', 'David Rodriguez'];
  } else {
    return [];
  }
}
