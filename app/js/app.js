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


function toggleTraductionList() {
    const traductionList = document.querySelector('.traduction__list');
    traductionList.classList.toggle('active');
    event.stopPropagation();
}

function changeLanguage(lang) {
    const mainButtonText = document.querySelector('.traduction__button span');
    const traductionList = document.querySelector('.traduction__list');

    mainButtonText.textContent = lang.toUpperCase() + ' ';
    traductionList.classList.remove('active');

    const url = new URL(window.location);
    url.searchParams.set('lang', lang);
    window.location = url.toString();
}

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

document.addEventListener('DOMContentLoaded', () => {
    generateInputs();
    document.addEventListener('click', (event) => {
        const traductionList = document.querySelector('.traduction__list');
        const mainButton = document.querySelector('.traduction__button');
    
        if (!traductionList.contains(event.target) && !mainButton.contains(event.target)) {
            traductionList.classList.remove('active');
        }
    });

    const inputCount = document.getElementById('input-count');
    inputCount.addEventListener('input', generateInputs);

    document.querySelector('#downloadForm').addEventListener('submit', function (event) {
        event.preventDefault();
        const formData = new FormData(this);

        document.getElementById('loader').style.display = 'block';
        document.getElementById('progress-container').style.display = 'block';
        checkProgress();

        fetch('./convert.php', { method: 'POST', body: formData })
            .then(response => response.text())
            .then(result => {
                document.getElementById('loader').style.display = 'none';
                document.querySelector('main').innerHTML = result;
            })
            .catch(() => {
                alert("Erreur lors du traitement. Veuillez réessayer.");
            });
    });

    function checkProgress() {
        const progressInterval = setInterval(() => {
            fetch('./progress.php')
                .then(response => response.json())
                .then(data => {
                    const progress = data.progress;
                    document.getElementById('progress-bar').value = progress;
                    document.getElementById('progress-text').textContent = `Progression : ${progress}%`;
    
                    if (progress >= 100) {
                        clearInterval(progressInterval);
                        console.log("Terminé !");
                        document.getElementById('loader').textContent = "🤖 Presque terminé...";
                        
                        setTimeout(() => {
                            document.getElementById('loader').textContent = "🚀 Redirection en cours... Accrochez-vous ! 💨";
                            
                            setTimeout(() => {
                                fetchContent();
                            }, 1000);
                        }, 2000);
                    }
                })
                .catch(() => clearInterval(progressInterval));
        }, 1000);
    }
    
    document.addEventListener('DOMContentLoaded', () => {
        checkProgress();
    });
    

    function fetchContent() {
        fetch('./convert.php', {
            method: 'POST',
            body: new FormData(document.querySelector('#downloadForm'))
        })
            .then(response => response.text())
            .then(result => document.querySelector('main').innerHTML = result)
            .catch(() => alert("Erreur lors du chargement du contenu."));
    }
});
