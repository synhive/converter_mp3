function getTranslation(key) {
    return translations[key] || key;
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
            .catch((err) => {
                alert("Erreur lors du traitement. Veuillez réessayer.",err);
            });
    });

    document.addEventListener('DOMContentLoaded', () => {
        checkProgress();
    });
});
