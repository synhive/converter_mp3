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

document.addEventListener('click', (event) => {
    const traductionList = document.querySelector('.traduction__list');
    const mainButton = document.querySelector('.traduction__button');

    if (!traductionList.contains(event.target) && !mainButton.contains(event.target)) {
        traductionList.classList.remove('active');
    }
});
