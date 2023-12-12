const sort = document.querySelector(".sort-box"),
	sortBtn = sort.querySelector(".sort-btn"),
	sortAll = sort.querySelectorAll(".sort"),
	sortCol = document.getElementsByClassName("sort-btn"),

	filter = document.querySelector(".filter-box"),
	filterBtn = filter.querySelector(".filter-btn"),
	filterAll = filter.querySelectorAll(".filter"),
	filterCol = document.getElementsByClassName("filter-btn"),
	sortItems = document.querySelectorAll(".sort-items"),
	filterItems = document.querySelectorAll(".filter-items"),

	documentName = document.querySelector(".name");


	//EXPAND SORT
	for (let i = 0; i < sortCol.length; i++) {
		sortCol[i].addEventListener("click", toggleActive);
	}
	
	for (let j = 0; j < filterCol.length; j++) {
		filterCol[j].addEventListener("click", toggleActive);
	}
	
	function toggleActive() {
		this.classList.toggle("active");
		const content = this.nextElementSibling;
		if (content.style.display === "block") {
			content.style.display = "none";
		} else {
			content.style.display = "block";
		}
	}

	//SORT CHANGE
	sortBtn.addEventListener("click", () => {
		sortBtn.classList.toggle("open");
		
	});
	filterBtn.addEventListener("click", () => {
		filterBtn.classList.toggle("open");
		
	});

	//SORT SELECTION
	sortAll.forEach(option => {
		option.addEventListener("click", () => {changeIcon(option, sortBtn)})
	});

	//FILTER SELECTION
	filterAll.forEach(option => {
		option.addEventListener("click", () => {changeIcon(option, filterBtn)})
	});

	//Function for 
	function changeIcon(option, button){
		let selected = option.innerText;
		button.innerText = selected;

		const existingIcon = button.querySelector('.icon');
		if (existingIcon) {
		  existingIcon.remove();
		}

		// add icon
		const iconSpan = document.createElement('span');
		iconSpan.className = 'icon';

		const expandImg = document.createElement('ion-icon');
		expandImg.name = "chevron-up-outline";

		iconSpan.appendChild(expandImg);
		sortBtn.appendChild(iconSpan);
	}

	//update content
	function updateReviews(reviews) {
		const reviewsContainer = document.querySelector('.history');
		reviewsContainer.innerHTML = ''; 
	
		if (reviews && reviews.length > 0) {
			reviews.forEach(review => {
				const reviewBox = document.createElement('div');
				reviewBox.classList.add('box');
				reviewBox.id = `box-${review.id}`;
	
				const content = document.createElement('div');
				content.classList.add('content');
	
				const nameHeader = document.createElement('h1');
				nameHeader.classList.add('name');
				nameHeader.textContent = review.DocumentName;
	
				const uploadDateParagraph = document.createElement('p');
				uploadDateParagraph.textContent = 'Upload Date: ';
	
				const uploadDateSpan = document.createElement('span');
				uploadDateSpan.textContent = new Date(review.UploadDate).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
	
				uploadDateParagraph.appendChild(uploadDateSpan);
	
				const reviewDateParagraph = document.createElement('p');
				reviewDateParagraph.textContent = 'Review Date: ';
	
				const reviewDateSpan = document.createElement('span');
				reviewDateSpan.textContent = new Date(review.ReviewDate).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
	
				reviewDateParagraph.appendChild(reviewDateSpan);
	
				content.appendChild(nameHeader);
				content.appendChild(uploadDateParagraph);
				content.appendChild(reviewDateParagraph);
	
				const statusHeader = document.createElement('h3');
				statusHeader.classList.add('status');
				statusHeader.textContent = review.ApprovalStatus;
	
				reviewBox.appendChild(content);
				reviewBox.appendChild(statusHeader);
	
				reviewsContainer.appendChild(reviewBox);
			});
		} else {
			reviewsContainer.innerHTML = '<p>No reviews available</p>';
		}
	}

const getUrlParameter = (name) => {
    const urlParams = new URLSearchParams(window.location.search);
    const paramValues = urlParams.getAll(name);
    return paramValues.length === 0 ? null : paramValues;
};


updateReviews(data);


const highlightSelection = () => {
    const selectedFilters = getUrlParameter('filter');
    const selectedSort = getUrlParameter('sort');

    const filterItems = document.querySelectorAll('.filter-items');
    filterItems.forEach(item => {
        if (selectedFilters && selectedFilters.includes(item.textContent)) {
            item.classList.add('selected');
        } else {
            item.classList.remove('selected');
        }
    });

    // Highlight selected sort
    const sortItems = document.querySelectorAll('.sort-items');
    sortItems.forEach(item => {
        if (item.textContent === selectedSort) {
            item.classList.add('selected');
        } else {
            item.classList.remove('selected');
        }
    });
    updateReviews(data);
};


highlightSelection();

const filterMappings = {
    "Approved": "filter1",
    "Pending": "filter2",
    "Disapproved": "filter3"
};

document.querySelectorAll('.filter-items').forEach(item => {
    item.addEventListener('click', () => {
        const filterValue = item.textContent;
        const selectedFilterKey = filterMappings[filterValue];
        const selectedFilters = getUrlParameter(selectedFilterKey) || [];

        const updatedFilters = selectedFilters.includes(filterValue)
            ? selectedFilters.filter(value => value !== filterValue)
            : [...selectedFilters, filterValue];

        updateFilterUrlParameter(selectedFilterKey, updatedFilters);
        
        highlightSelection();
        updateReviews(data);
    });
});

const updateFilterUrlParameter = (key, values) => {
    const urlParams = new URLSearchParams(window.location.search);

    urlParams.delete(key);

    if (values) {
        values.forEach((value, index) => {
            if (value.trim() !== "") {
                urlParams.append(key, value);
            }
        });
    }

    const newUrl = window.location.pathname + '?' + urlParams.toString();
    history.pushState(null, null, newUrl);
};



document.querySelectorAll('.sort-items').forEach(item => {
    item.addEventListener("click", () => {
        document.querySelectorAll('.sort-items').forEach(otherItem => {
            if (otherItem !== item) {
                otherItem.classList.remove("selected");
            }
        });

        item.classList.toggle("selected");
    });
});

// sorted
document.querySelectorAll('.sort-items').forEach(item => {
    item.addEventListener('click', () => {
        const sortValue = item.textContent;
        updateSortUrlParameter('sort', [sortValue]); 
        highlightSelection();
    });
});



document.querySelectorAll('.sort-items').forEach(item => {
    item.addEventListener("click", () => {
        document.querySelectorAll('.sort-items').forEach(otherItem => {
            if (otherItem !== item) {
                otherItem.classList.remove("selected");
            }
        });

        item.classList.toggle("selected");
    });
});

// function to change icon
function changeIcon(option, button) {
    let selected = option.innerText;
    button.innerText = selected;

    const existingIcon = button.querySelector('.icon');
    if (existingIcon) {
        existingIcon.remove();
    }

    // add icon
    const iconSpan = document.createElement('span');
    iconSpan.className = 'icon';

    const expandImg = document.createElement('ion-icon');
    expandImg.name = "chevron-up-outline";

    iconSpan.appendChild(expandImg);
    button.appendChild(iconSpan);
}


const updateSortUrlParameter = (key, values) => {
    const urlParams = new URLSearchParams(window.location.search);

    if (values) {
        urlParams.delete(key);

        values.forEach(value => {
            if (!urlParams.getAll(key).includes(value)) {
                urlParams.append(key, value);
            }
        });
    }

    const newUrl = window.location.pathname + '?' + urlParams.toString();
    history.pushState(null, null, newUrl);
    // location.reload();
    updateReviews(data);
    console.log(data);
};