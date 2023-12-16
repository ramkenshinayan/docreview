const body = document.querySelector("body"),
	sidebar = body.querySelector(".sidebar"),
	toggle = body.querySelector(".toggle"),
	modeSwitch = body.querySelector(".mode"),
	modeText = body.querySelector(".mode-text");


toggle.addEventListener("click", () => {
	sidebar.classList.toggle("close");
});

const setModePreference = (mode) => {
	localStorage.setItem("modePreference", mode);
};

const getModePreference = () => {
	return localStorage.getItem("modePreference");
};

const applyModePreference = () => {
	const mode = getModePreference();
	if (mode === "dark") {
		body.classList.add("dark");
		modeText.innerText = "Light Mode";
	} else {
		body.classList.remove("dark");
		modeText.innerText = "Dark Mode";
	}
};

applyModePreference();

modeSwitch.addEventListener("click", () => {
	body.classList.toggle("dark");

	const currentMode = body.classList.contains("dark") ? "dark" : "light";
	setModePreference(currentMode);

	if (body.classList.contains("dark")) {
		modeText.innerText = "Light Mode";
	} else {
		modeText.innerText = "Dark Mode";
	}
});
