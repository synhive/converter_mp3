function generateInputs() {
    const container = document.getElementById('input-container');
    const count = document.getElementById('input-count').value;
    const placeholder = container.getAttribute('data-placeholder');
    container.innerHTML = '';

    for (let i = 0; i < count; i++) {
        const inputWrapper = document.createElement('div');
        inputWrapper.className = 'input-wrapper';

        const input = document.createElement('input');
        input.type = 'text';
        input.placeholder = placeholder;
        input.name = 'url[]';
        input.required = true;

        const deleteButton = document.createElement('img');
        deleteButton.src = './assets/svg/trash-2.svg';
        deleteButton.alt = 'Delete';
        deleteButton.className = 'delete-button';
        deleteButton.addEventListener('click', () => {
            inputWrapper.remove();
            updateInputCount();
        });

        inputWrapper.appendChild(input);
        inputWrapper.appendChild(deleteButton);
        container.appendChild(inputWrapper);
    }
}

function updateInputCount() {
    const container = document.getElementById('input-container');
    const inputCount = document.getElementById('input-count');
    const inputWrappers = container.getElementsByClassName('input-wrapper');
    inputCount.value = inputWrappers.length;
}

document.addEventListener('DOMContentLoaded', (event) => {
    generateInputs();
    const mainButton = document.querySelector('.traduction__button');
    const mainButtonText = mainButton.querySelector('span');
    const itemButtons = document.querySelectorAll('.traduction__item-button');
    const traductionList = document.querySelector('.traduction__list');
    const inputCount = document.getElementById('input-count');

    inputCount.addEventListener('input', () => {
        generateInputs();
    });

    itemButtons.forEach(button => {
        button.addEventListener('click', () => {
            mainButtonText.textContent = button.textContent + ' ';
            traductionList.classList.remove('active');

            const selectedLang = button.textContent.trim().toLowerCase();
            const url = new URL(window.location);
            url.searchParams.set('lang', selectedLang);
            window.location = url.toString();
        });
    });

    mainButton.addEventListener('click', (event) => {
        event.stopPropagation();
        traductionList.classList.toggle('active');
    });

    document.addEventListener('click', (event) => {
        if (!traductionList.contains(event.target) && !mainButton.contains(event.target)) {
            traductionList.classList.remove('active');
        }
    });
});