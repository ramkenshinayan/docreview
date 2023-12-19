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

	//Function for changing icon/text
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
  
	// sort
	sortItems.forEach(item => {
		item.addEventListener("click", () => {
			const sortValue = item.textContent.trim();
	
			// Toggle selection
			if (item.classList.contains("selected")) {
				item.classList.remove("selected");
				updateSortUrlParameter('sort', []); // Remove sort parameter
			} else {
				sortItems.forEach(otherItem => otherItem.classList.remove("selected"));
				item.classList.add("selected");
				localStorage.setItem('selectedSortItem', sortValue);
				updateSortUrlParameter('sort', [sortValue]);
			}
	
			updateReviews(data);
		});
	});

	//filtered
	const filterMappings = {
		"Approved": "filter1",
		"Ongoing": "filter2",
		"Standby": "filter3",
		"Disapproved": "filter4"
	};
	
	filterItems.forEach(item => {
		item.addEventListener("click", () => {
			const filterValue = item.textContent.trim();
			const selectedFilterKey = filterMappings[filterValue];
			let selectedFilters = JSON.parse(localStorage.getItem(selectedFilterKey)) || [];
	
			selectedFilters = selectedFilters.includes(filterValue)
				? selectedFilters.filter(value => value !== filterValue)
				: [...selectedFilters, filterValue];
	
			updateFilterUrlParameter(selectedFilterKey, selectedFilters);
	
			item.classList.toggle("selected", selectedFilters.includes(filterValue));
	
			store(selectedFilterKey, selectedFilters);
			highlightSelection();
	
			updateReviews(data);
		});
	});
	
	function store(key, selectedFilterValues) {
		localStorage.setItem(key, JSON.stringify(selectedFilterValues));
	}
	
	// retain filter/sort selection
	document.addEventListener('DOMContentLoaded', () => {
		const selectedSortValue = localStorage.getItem('selectedSortItem');
		if (selectedSortValue) {
			const selectedSortItem = [...sortItems].find(item => item.textContent.trim() === selectedSortValue);
			if (selectedSortItem) {
				selectedSortItem.classList.add('selected');
			}
		}

		const selectedFilterItems = JSON.parse(localStorage.getItem('selectedFilterItems')) || [];
		selectedFilterItems.forEach(selectedFilterValue => {
			const selectedFilterItem = [...filterItems].find(item => item.textContent.trim() === selectedFilterValue);
			if (selectedFilterItem) {
				selectedFilterItem.classList.add('selected');
			}
		});

		setTimeout(() => {
			highlightSelection();
		}, 0);
	});

	const highlightSelection = () => {
		const filterMappings = {
			"Approved": "filter1",
			"Ongoing": "filter2",
			"Standby": "filter3",
			"Disapproved": "filter4"
		};

		const selectedFilterItems = JSON.parse(localStorage.getItem('selectedFilterItems')) || [];

		const filterItems = document.querySelectorAll('.filter-items');
		filterItems.forEach(item => {
			const filterValue = item.textContent.trim();
			const selectedFilterKey = filterMappings[filterValue];
			const selectedFilters = JSON.parse(localStorage.getItem(selectedFilterKey)) || [];
			const isSelected = selectedFilters.includes(filterValue);
			item.classList.toggle('selected', isSelected);
		});
	};


    const getUrlParameter = (name) => {
        const urlParams = new URLSearchParams(window.location.search);
        const paramValues = urlParams.getAll(name);
        return paramValues.length === 0 ? null : paramValues;
    };
	
	const updateFilterUrlParameter = (key, values) => {
		const urlParams = new URLSearchParams(window.location.search);
	
		// Remove all existing values for the key
		urlParams.delete(key);
	
		// Add the new values as separate parameters
		values.forEach(value => {
			urlParams.append(key, value);
		});
	
		const newUrl = window.location.pathname + '?' + urlParams.toString();
		history.pushState(null, null, newUrl);
		location.reload();
	};
	

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
		location.reload();
		updateReviews(data);
		console.log(data);
	};	

    updateReviews(data);

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
	
		history.pushState({ searchTerm: value }, null, newUrl);

		location.reload();
		updateReviews(data);
	};
	
	// Function to handle state changes
	const handleStateChange = (event) => {
		const searchTerm = event.state && event.state.searchTerm;
		updateReviews(data, searchTerm);
	};

	// Add an event listener for the popstate event
	window.addEventListener('popstate', handleStateChange);

	// Call the handleStateChange function on initial load
	document.addEventListener('DOMContentLoaded', () => {
		handleStateChange({ state: history.state });
	});

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
	
				content.appendChild(nameHeader);
				content.appendChild(uploadDateParagraph);
				
	
				const statusHeader = document.createElement('h3');
				statusHeader.classList.add('status');
				statusHeader.textContent = review.Status;
	
				reviewBox.appendChild(content);
				reviewBox.appendChild(statusHeader);
	
				reviewsContainer.appendChild(reviewBox);
			});
		} else {
			reviewsContainer.innerHTML = '<p>No reviews available</p>';
		}
	}

	document.addEventListener('DOMContentLoaded', () => {
		const urlParams = new URLSearchParams(window.location.search);
		const searchTerm = urlParams.get('search');
	
		// Call updateReviews with the initial search term
		updateReviews(data);
	});	

  