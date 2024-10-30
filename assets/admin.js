document.addEventListener("DOMContentLoaded", function(event) {
	// Activate first tab
	var habfurFirstTab = document.getElementById("habfur-settings-tab");
	if (habfurFirstTab) {
		habfurFirstTab.classList.add("nav-tab-active");
	}

	// On click of tab activate it
	var habfurTabs = document.querySelectorAll(".nav-tab-wrapper a");
	if (habfurTabs.length) {
		habfurTabs.forEach(function(tab) {
			tab.addEventListener("click", function(e) {
				e.preventDefault();

				// Remove nav-tab-active class
				document.querySelectorAll(".nav-tab-wrapper a").forEach(function(element) {
					element.classList.remove("nav-tab-active");
				});

				// Add class to clicked element
				e.currentTarget.classList.add("nav-tab-active");

				// Hide all tabs content
				document.querySelectorAll(".habfur-tabs").forEach(function(element) {
					element.style.display = "none";
				});

				// Hide save button in case of help tab
				if (e.target.id == "habfur-help-tab") {
					document.querySelectorAll('.habfur-save-settings-container').forEach(function(element) {
						element.style.display = 'none';
					});
				} else {
					document.querySelectorAll('.habfur-save-settings-container').forEach(function(element) {
						element.style.display = 'block';
					});
				}

				// Show content of clicked tab
				var clickedTabHref = e.currentTarget.getAttribute("data-tab");
				if (clickedTabHref) {
					document.getElementById(clickedTabHref).style.display = "block";
				}
			})
		});
	}

	function habfurCheckUnCheckRoles(check) {
		var habfurRoles = document.getElementsByClassName('habfur_hide_individual_role');
		for(var i = 0; i < habfurRoles.length; i++) {
			habfurRoles[i].checked = check;
		}
	}

	// Check all roles if all role checked
	var habfurHideAllRole = document.getElementById('habfur_hide_all_role');
	if (habfurHideAllRole && habfurHideAllRole.checked) {
		habfurCheckUnCheckRoles(true);
	}

	var habfurCheckbox = document.getElementById('habfur_hide_all_role');
	if (habfurCheckbox) {
		habfurCheckbox.addEventListener('change', function(event) {
			if (event.currentTarget.checked) {
				habfurCheckUnCheckRoles(true);
			} else {
				habfurCheckUnCheckRoles(false);
			}
		});
	}

	var habfurCheckboxes = document.getElementsByClassName('habfur_hide_individual_role');
	for (var role of habfurCheckboxes) {
		role.addEventListener('change', function(event) {
			var checkboxes = document.querySelectorAll('#habfur-hide-individual-role input[type=checkbox]:checked');
			if (checkboxes.length == habfurCheckboxes.length) {
				habfurCheckbox.checked = true;
			} else {
				habfurCheckbox.checked = false;
			}
		});
  }

	// Send settings to backend on click of submit button
	var habfurSaveSettings = document.getElementById("habfur-save-settings");
	if (habfurSaveSettings) {
		habfurSaveSettings.addEventListener("click", function(e) {
			e.preventDefault();
	
			var habfurErrorMessage = document.getElementById("habfur-error-message");
			var habfurSaveSettingButton = document.getElementById("habfur-save-settings");
	
			habfurErrorMessage.innerHTML = "";
			habfurSaveSettingButton.value = "Saving Settings ...";
			habfurSaveSettingButton.classList.add("is-busy");
	
			var habfurForm = document.getElementById("habfur-settings-form");
			var habfurData = new URLSearchParams(new FormData(habfurForm));
			
			var habfurXhr = new XMLHttpRequest();
			habfurXhr.open("POST", ajaxurl, true);
			habfurXhr.onreadystatechange = function() {
				if (this.readyState === XMLHttpRequest.DONE) {
					if (this.status === 200) {
						var response = JSON.parse(habfurXhr.response);
						if (response.status == "success") {
							habfurErrorMessage.innerHTML = '<div class="updated"><p>' + response.message + '</p></div>';
						} else if (response.status == "error") {
							habfurErrorMessage.innerHTML = '<div class="error"><p>' + response.message + '</p></div>';
						} else {
							habfurErrorMessage.innerHTML = '<div class="error"><p>No settings were saved.</p></div>';
						}
					} else {
						habfurErrorMessage.innerHTML = '<div class="error"><p>Error occurred while saving settings.</p></div>';
					}
					habfurSaveSettingButton.value = "Save Settings";
					habfurSaveSettingButton.classList.remove("is-busy");
				}
			}
			habfurXhr.send(habfurData);
		});
	}
});
