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

            // Update the hidden input field with the selected documentId
            selectedRadioButton.value = documentId;

            trackingDoc(data, documentId);
        }
    });
}

function trackingDoc(data, documentId){
    data.forEach(review =>{
        console.log(review.documentId);
        console.log(documentId);
        console.log(review.status);

        if(documentId === review.documentId){
          if(review.status === 'Ongoing'){
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

    var revParaEl = document.createElement('p');
    var revTextEl = document.createTextNode('View the comments about your document below.');
    revParaEl.appendChild(revTextEl);
    // Create the form element with id "upbox" and set its attributes
    var formElement = document.createElement('form');
    formElement.id = 'upbox';
    formElement.action = 'requester-track.php';
    formElement.method = 'post';
    formElement.enctype = 'multipart/form-data';
    formElement.onsubmit = function() {
        return onSubmitForm();
    };
    
    // Create the div with class "file-upload"
    var fileUploadDiv = document.createElement('div');
    fileUploadDiv.className = 'file-upload';

    
    // Create the input element with class "file-input" and set its attributes
    var fileInput = document.createElement('input');
    fileInput.className = 'file-input';
    fileInput.type = 'file';
    fileInput.name = 'file';
    fileInput.accept = '.doc, .docx, .pdf';
    fileInput.style.display = 'none'; // Hide the file input


    // Create the ion-icon element
    var ionIcon = document.createElement('ion-icon');
    ionIcon.setAttribute('name', 'cloud-upload-outline');
    ionIcon.style.cursor = 'pointer';

    ionIcon.addEventListener('click', function () {
        fileInput.click(); 
    });

    // Create the paragraph element
    var paragraphElement = document.createElement('p');
    paragraphElement.textContent = 'Browse File to Upload';  

    var fileNameDisplay = document.createElement('span');
    fileNameDisplay.textContent = ''; 

    fileInput.addEventListener('change', function () {
        const fileName = fileInput.files[0] ? fileInput.files[0].name : '';
        fileNameDisplay.textContent = fileName; 
    });

    // Append the input, ion-icon, and paragraph elements to the file-upload div
    fileUploadDiv.appendChild(ionIcon);
    fileUploadDiv.appendChild(fileInput);
    fileUploadDiv.appendChild(paragraphElement);
    fileUploadDiv.appendChild(fileNameDisplay); 

    // Create the button with id "subbtn" and set its attributes
    var submitButton = document.createElement('button');
    submitButton.id = 'subbtn';
    submitButton.type = 'submit';
    submitButton.name = 'upload';
    submitButton.textContent = 'Submit';
    
    // Append the file-upload div and the submit button to the form
    formElement.appendChild(fileUploadDiv);
    formElement.appendChild(submitButton);

    uploadContainer.appendChild(statusText);
    uploadContainer.appendChild(reupParaEl);
    uploadContainer.appendChild(formElement);
    uploadContainer.appendChild(revTextEl);


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

function handleSearch(event) {
    if (event.key === 'Enter') {
        const searchInput = document.getElementById('searchInput');
        const searchTerm = searchInput.value.trim();

        // Update the URL parameter for search
        updateSearchParameter('search', searchTerm);

        // Trigger the popstate event to handle the change without a page reload
        history.pushState({ searchTerm: searchTerm }, null, window.location.href);
        window.dispatchEvent(new Event('popstate'));
    }
}	

const updateSearchParameter = (key, value) => {
    const urlParams = new URLSearchParams(window.location.search);

    if (value) {
        urlParams.set(key, value);
    } else {
        urlParams.delete(key);
    }

    const newUrl = window.location.pathname + '?' + urlParams.toString();

    // Use pushState to change the URL and add a new entry to the browser history
    history.pushState({ searchTerm: value }, null, newUrl);

    // Log the updated URL for debugging purposes
    console.log('Updated URL:', newUrl);
    location.reload();
    // updateReviews(data);
    console.log(data);
};

// Function to handle state changes
const handleStateChange = (event) => {
    const searchTerm = event.state && event.state.searchTerm;
    // updateReviews(data, searchTerm);
};

// Add an event listener for the popstate event
window.addEventListener('popstate', handleStateChange);

// Call the handleStateChange function on initial load
document.addEventListener('DOMContentLoaded', () => {
    handleStateChange({ state: history.state });
});
