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
    const mainButton = document.querySelector('.traduction__button');
    const mainButtonText = mainButton.querySelector('span');
    const itemButtons = document.querySelectorAll('.traduction__item-button');
    const traductionList = document.querySelector('.traduction__list');
    const inputCount = document.getElementById('input-count');

    if(!document.getElementById('convert')){
        generateInputs();
        inputCount.addEventListener('input', () => {
            generateInputs();
        });

        const form = document.querySelector('#downloadForm');
        form.addEventListener('submit', function(event) {
            event.preventDefault(); 

            const formData = new FormData(form);

            document.getElementById('loader').style.display = 'block';
            document.getElementById('progress-container').style.display = 'block';
            
            checkProgress();

            fetch('./convert.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(result => {

                document.getElementById('loader').style.display = 'none';

                document.querySelector('main').innerHTML = result;
            })
            .catch(error => {
                console.error('Erreur:', error);
                document.getElementById('loader').style.display = 'none';
                alert("Erreur lors du traitement. Veuillez réessayer.");
            });
        });

    function checkProgress() {
        const progressInterval = setInterval(() => {
            fetch('./progress.php')
                .then(response => response.json())
                .then(data => {
                    const progress = data.progress;

                    document.getElementById('progress-text').textContent = `Progression : ${progress}%`;
                    document.getElementById('progress-bar').value = progress;

                    if (progress >= 100) {
                        clearInterval(progressInterval);
                    }
                })
                .catch(error => {
                    clearInterval(progressInterval);
                    console.error('Erreur lors de la récupération de la progression:', error);
                });
        }, 1000);
    }
    }

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
