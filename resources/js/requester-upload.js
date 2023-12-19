const form = document.querySelector("form"),
  fileInput = document.querySelector(".file-input"),
  progressArea = document.querySelector(".progress-area"),
  uploadedArea = document.querySelector(".uploaded-area");

let fileInProgress = false;

form.addEventListener("click", () => {
  if (!fileInProgress) {
    fileInput.click();
  }
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
                              <button style="position: absolute; right: 15%; background-color: var(--primary-color); color: white; border: none; border-radius: 10px; cursor: pointer; width: 100px; height: 35px; margin-top: 20px;" onclick="removeFile(this);">Remove</button>
                              </div>
                            <ion-icon name="checkmark-outline"></ion-icon>
                          </li>`;
      uploadedArea.classList.remove("onprogress");
      uploadedArea.insertAdjacentHTML("afterbegin", uploadedHTML);
      fileInput.disabled = false; 
    }
  });

  xhr.addEventListener("loadend", () => {
    form.disabled = false;
  });

  var data = new FormData(form);
  data.append("uploadDate", new Date().toLocaleString()); 
  xhr.send(data);
  form.disabled = true;
  fileInput.disabled = true;
  fileInProgress = true; 
}

function removeFile(file) {
  file.parentNode.parentNode.remove();
  
  fileInput.value = "";
  fileInput.disabled = false;
  form.style.cursor = 'pointer';
  fileInProgress = false; // Reset fileInProgress flag
};

