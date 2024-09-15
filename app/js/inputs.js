function generateInputs() {
    const container = document.getElementById('input-container');
    const count = document.getElementById('input-count').value;
    const placeholder = container.getAttribute('data-placeholder');
    container.innerHTML = '';

    for (let i = 0; i < count; i++) {
        const inputWrapper = document.createElement('div');
        inputWrapper.className = 'input-wrapper';
        inputWrapper.innerHTML = `<input type="text" name="url[]" placeholder="${placeholder}" required>
                                  <img src="./assets/svg/trash-2.svg" alt="Delete" class="delete-button">`;
        inputWrapper.querySelector('.delete-button').addEventListener('click', () => {
            inputWrapper.remove();
            updateInputCount();
        });
        container.appendChild(inputWrapper);
    }
}

function updateInputCount() {
    const inputCount = document.getElementById('input-count');
    inputCount.value = document.querySelectorAll('.input-wrapper').length;
}

document.addEventListener('DOMContentLoaded', () => {
    const inputCount = document.getElementById('input-count');
    inputCount.addEventListener('input', generateInputs);
    generateInputs();
});
