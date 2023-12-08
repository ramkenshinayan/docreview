const nextBtn = document.querySelector('.next');
const content = document.querySelector('.content');
const imgStatus = document.querySelector('.imgStatus');
const statusText = document.querySelector('.status');
const progressBar = document.querySelector('.progress-bar');
const circles = document.querySelectorAll('.circle');
const indicator = document.querySelector('.indicator');
const container = document.querySelector('.container');
const rightContainer = document.querySelector('.right-container');
const radioButtons = document.querySelectorAll('input[name="radioGroup"]');

// fetch data , sample lang tong data
const data = { status: 'disapproved', office: 'office', currentStep: 1 };

//steps approved
for (let i = 0; i < data.currentStep-1; i++) {
    circles[i].classList.add('circle-approved');
}

//ADD A FUNCTION IF NO SELECTED DOCUMENT:::::::::::::::::
radioButtons.forEach(radioButton => {
    radioButton.addEventListener('change', function() {
        // Check if any radio button in the group is checked
        const anyRadioButtonChecked = Array.from(radioButtons).some(rb => rb.checked);
        if (!anyRadioButtonChecked) {
            container.style.display = 'none';
        } else {
            container.style.display = 'flex';
            rightContainer.style.backgroundColor = '#38B6FF';
        }
    });
});

// depending on dataaaa
if (data.status === 'pending') {
    pending();
} else if (data.status === 'disapproved') {
    disapproved();
}

function pending() {
    const imgEl = document.createElement('img');
    imgEl.src = 'pending-img.png';
    imgEl.alt= 'pending-img';
    content.appendChild(imgEl);
    content.insertBefore(imgEl, statusText);

    statusText.textContent = `Your Document is Waiting to be Approved by the ${data.office}`;

    circles.forEach((circle, index) => {
        if (index + 1 === data.currentStep) {
            circle.classList.add('circle-pending');
        }
    });
    indicator.style.width = `${((data.currentStep - 1) / (circles.length - 1)) * 100}%`;
}

function disapproved() {
    const wrapperContainer = document.getElementById('upload-container');
    
    statusText.textContent = `Your Document has been Disapproved by the ${data.office}`;

    const reupParaEl = document.createElement('p');
    const reupTextEl = document.createTextNode('Please reupload your revised document.');
    reupParaEl.appendChild(reupTextEl);
    content.appendChild(reupParaEl);

    //upload file
    const form = document.createElement('form');
    form.action = 'includes/upload.php';
    form.method = 'post';
    form.enctype = 'multipart/form-data';

    const fileInput = document.createElement('input');
    fileInput.type = 'file';
    fileInput.name = 'file';
    fileInput.accept = '.doc, .docx, .pdf';
    fileInput.classList.add('file-input');
    fileInput.hidden = true;

    const ionIcon = document.createElement('ion-icon');
    ionIcon.name = 'cloud-upload-outline';

    const paragraph = document.createElement('p');
    paragraph.textContent = 'Browse File to Upload';

    const lineBreak = document.createElement('br');

    form.appendChild(fileInput);
    form.appendChild(ionIcon);
    form.appendChild(paragraph);
    form.appendChild(lineBreak);

    wrapperContainer.appendChild(form);

    //button
    const submitBtn = document.createElement('button');
    const submitText = document.createTextNode('Submit');
    submitBtn.appendChild(submitText);
    content.appendChild(submitBtn);

    circles.forEach((circle, index) => {
        if (index + 1 === data.currentStep) {
            circle.classList.add('circle-disapproved');
        }
    });
    indicator.style.width = `${((data.currentStep - 1) / (circles.length - 1)) * 100}%`;
}

//add document names/ids
function addRadioButtons(){
    const leftContainer = document.getElementById('left-container');
  
    for(var i = 0; i<documentData.length; i++){
      const radioInput = document.createElement('input');
      radioInput.setAttribute('type', 'radio');
      radioInput.setAttribute('id', 'radioButton' + i);
      radioInput.setAttribute('name', 'radioGroup');
      
      const labelElement = document.createElement('label');
      labelElement.setAttribute('for', 'radioButton' + i);
      
      const labelText = document.createElement('div');
      labelText.textContent = `${documentData.name}\n${documentData.id}`;
      
      labelElement.appendChild(labelText);
      
      leftContainer.appendChild(radioInput);
      leftContainer.appendChild(labelElement);
   }
    
}