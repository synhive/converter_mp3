function toggleCheckAll() {
    const mainCheckbox = document.querySelector('thead input[type="checkbox"]');
    const checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = mainCheckbox.checked;
    });
}

function toggleCheck(event) {
    const checkbox = event.target;
    const mainCheckbox = document.querySelector('thead input[type="checkbox"]');
    const checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');

    if (!mainCheckbox.checked && checkbox.checked) {
        mainCheckbox.checked = true;
    }

    const allUnchecked = Array.from(checkboxes).every(cb => !cb.checked);
    if (allUnchecked) {
        mainCheckbox.checked = false;
    }
}
