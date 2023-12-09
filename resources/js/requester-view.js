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

	documentName = document.querySelector(".name"),
	searchInput = document.querySelector(".search-box");


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

	//filtered
	filterItems.forEach(item => {
        item.addEventListener("click", () => {
			item.classList.toggle("selected");
			document.querySelectorAll(" .filter-items.selected");
			updateURL();
        });
    });


	//sorted
	sortItems.forEach(item => {
		item.addEventListener("click", () => {
			sortItems.forEach(otherItem => {
				if (otherItem !== item) {
					otherItem.classList.remove("selected");
				}
			});
	
			item.classList.toggle("selected");
	
			const selectedItem = document.querySelector(".sort-select .sort-items.selected");
	
			if (selectedItem) {
				changeIcon(selectedItem, sortBtn);
			}
	
			document.querySelectorAll(".sort-select .sort-items.selected");
			updateURL();

		});
	});

	function updateURL() {
    const selectedSort = document.querySelector(".sort-select .sort-items.selected");
    const selectedFilter = document.querySelector(".filter-select .filter-items.selected");
    const searchTerm = searchInput.value.trim();

    let queryString = "";
    if (selectedSort) {
        queryString += `sort=${encodeURIComponent(selectedSort.innerText)}&`;
	
    }
    if (selectedFilter) {
        queryString += `filter=${encodeURIComponent(selectedFilter.innerText)}&`;
		
    }
    if (searchTerm) {
        queryString += `search=${encodeURIComponent(searchTerm)}&`;
    }

    // Remove the trailing '&' if present
    queryString = queryString.replace(/&$/, '');

    const newURL = window.location.pathname + (queryString ? `?${queryString}` : "");
    history.pushState(null, null, newURL);
	location.reload();
}
