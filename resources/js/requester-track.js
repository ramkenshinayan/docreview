const nextBtn = document.querySelector('.next');
const content = document.querySelector('.content');
const imgStatus = document.querySelector('.imgStatus');
const progressBar = document.querySelector('.progress-bar');
const circles = document.querySelectorAll('.circle');
const indicator = document.querySelector('.indicator');
const container = document.querySelector('.container');
const rightContainer = document.querySelector('.right-container');
const wrapperContainer = document.getElementById('upload-container');
const approval = document.getElementById('approvals');

checkRadio();

function checkRadio() {
	approval.addEventListener('change', function (event) {
		const selectedRadioButton = event.target;
        let queryString = '';
		if (selectedRadioButton.type === 'radio' && selectedRadioButton.checked) {
            const labelContent = document.querySelector(`label[for="${selectedRadioButton.id}"]`).innerText;
            const documentName = labelContent.split('\n')[0].trim(); 
            const documentId = labelContent.split('\n')[1].trim();

            queryString += `document=${encodeURIComponent(documentName)}&`;
            history.pushState({}, null, `?${queryString}`);
            
            rightContainer.style.backgroundColor = '#38B6FF';
            
            trackingDoc(data, documentId);
		}
	});
}

function trackingDoc(data, documentId){
    data.forEach(review =>{
        console.log(review.documentId);
        console.log(documentId);
        if(documentId == review.documentId){
          if(review.status === 'Pending'){
                pending(review);
                console.log(data);
                console.log(documentId);
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

    var viewText = document.createElement('p');
    viewText.textContent = 'View the needed revisions below.';

    submitBtn.appendChild(submitText);

    uploadContainer.appendChild(statusText);
    uploadContainer.appendChild(reupParaEl);
    form.appendChild(fileInput);
    form.appendChild(ionIcon);
    form.appendChild(paragraph);
    form.appendChild(lineBreak);
    uploadContainer.appendChild(form);
    uploadContainer.appendChild(submitBtn);
    uploadContainer.appendChild(viewText);

    const iframe = document.createElement('iframe');
    iframe.src = `data:application/pdf;base64,${data.pdfContent}`;

    uploadContainer.appendChild(iframe);
 
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