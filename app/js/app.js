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
    generateInputs();

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
    
                        // Remplacer le loader par un message humoristique
                        document.getElementById('loader').textContent = "🤖 Presque terminé...";
                        
                        // Attendre 2 secondes avant de charger le contenu final
                        setTimeout(() => {
                            document.getElementById('loader').textContent = "🚀 Redirection en cours... Accrochez-vous ! 💨";
                            
                            // Attendre encore une seconde pour l'effet dramatique
                            setTimeout(() => {
                                fetchContent();  // Afficher le contenu après 1 seconde
                            }, 100);
                        }, 2000);
                    }
                })
                .catch(() => clearInterval(progressInterval));
        }, 1000);
    }
    

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
