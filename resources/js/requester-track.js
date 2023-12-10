const nextBtn = document.querySelector('.next');
const content = document.querySelector('.content');
const imgStatus = document.querySelector('.imgStatus');
const progressBar = document.querySelector('.progress-bar');
const circles = document.querySelectorAll('.circle');
const indicator = document.querySelector('.indicator');
const container = document.querySelector('.container');
const rightContainer = document.querySelector('.right-container');
const wrapperContainer = document.getElementById('upload-container');

checkRadio();

function checkRadio() {
    const radioButtons = document.querySelectorAll('input[name="radioGroup"]');
    radioButtons.forEach(radioButton => {
    const documentNameDisplay = document.querySelector('.content h3');

    radioButton.addEventListener('change', function () {
        let queryString = '';
        if (this.checked) {
            const labelContent = document.querySelector(`label[for="${this.id}"]`).innerText;
            const documentName = labelContent.split('\n')[0].trim(); // Assumes the document ID is on the second line
            const documentId = labelContent.split('\n')[1].trim();

            queryString += `document=${encodeURIComponent(documentName)}&`;
            history.pushState({}, null, `?${queryString}`);
            
            rightContainer.style.backgroundColor = '#38B6FF';
            trackingDoc(data, documentId);
        }
    });
});
}

function trackingDoc(data, documentId){
    data.forEach(review =>{
        console.log(review.reviewId);
        console.log(documentId);
        if(documentId == review.reviewId){
          if(review.status === 'Pending'){
                pending(review);
            } else if (review.status === 'Disapproved') {
                disapproved(review);
            }
        }
    })
}

function pending(data) {
    clearContainer();

    var uploadContainer = document.getElementById('upload-container');
    for (let i = 0; i < data.minOrder-1; i++) {
        circles[i].classList.add('circle-approved');
    }
    const imgEl = document.createElement('img');
    imgEl.src = 'assets/pending-img.png';
    imgEl.alt= 'pending-img';
    uploadContainer.appendChild(imgEl);
    uploadContainer.insertBefore(imgEl, statusText);

    var statusText = document.createElement('h3');

    statusText.textContent = `Your Document is Waiting to be Approved by the ` + data.officeName;
    uploadContainer.appendChild(statusText);

    circles.forEach((circle, index) => {
        circle.classList.remove('circle-pending', 'circle-disapproved');
        if (index + 1 == data.minOrder) {
            circle.classList.add('circle-pending');
        }
    });
    indicator.style.width = `${((data.minOrder - 1) / (circles.length - 1)) * 100}%`;
}

function disapproved(data) {
    clearContainer();

    for (let i = 0; i < data.minOrder-1; i++) {
        circles[i].classList.add('circle-approved');
    }

    var uploadContainer = document.getElementById('upload-container');

    var statusText = document.createElement('h3');
    statusText.textContent = `Your Document has been Disapproved by the ` + data.officeName;

    var reupParaEl = document.createElement('p');
    var reupTextEl = document.createTextNode('Please reupload your revised document.');
    reupParaEl.appendChild(reupTextEl);

    var form = document.createElement('form');
    form.action = 'includes/upload.php';
    form.method = 'post';
    form.enctype = 'multipart/form-data';

    var fileInput = document.createElement('input');
    fileInput.type = 'file';
    fileInput.name = 'file';
    fileInput.accept = '.doc, .docx, .pdf';
    fileInput.classList.add('file-input');
    fileInput.hidden = true;

    var ionIcon = document.createElement('ion-icon');
    ionIcon.name = 'cloud-upload-outline';

    var paragraph = document.createElement('p');
    paragraph.textContent = 'Browse File to Upload';

    var lineBreak = document.createElement('br');

    var submitBtn = document.createElement('button');
    var submitText = document.createTextNode('Submit');
    submitBtn.appendChild(submitText);

    uploadContainer.appendChild(statusText);
    uploadContainer.appendChild(reupParaEl);
    form.appendChild(fileInput);
    form.appendChild(ionIcon);
    form.appendChild(paragraph);
    form.appendChild(lineBreak);
    uploadContainer.appendChild(form);
    uploadContainer.appendChild(submitBtn);

    circles.forEach((circle, index) => {
        circle.classList.remove('circle-pending', 'circle-disapproved');
        if (index + 1 == data.minOrder) {
            circle.classList.add('circle-disapproved');
            console.log(circle);
        }
    });
    indicator.style.width = `${((data.minOrder - 1) / (circles.length - 1)) * 100}%`;
}

function clearContainer() {
    var uploadContainer = document.getElementById('upload-container');
    while (uploadContainer.firstChild) {
        uploadContainer.removeChild(uploadContainer.firstChild);
    }
}