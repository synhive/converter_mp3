function checkProgress() {
    const progressInterval = setInterval(() => {
        fetch('./progress.php')
            .then(response => response.json())
            .then(data => {
                const progress = data.progress;
                document.getElementById('progress-bar').value = progress;

                document.getElementById('progress-text').textContent = `${getTranslation('progress')} ${progress}%`;

                if (progress >= 100) {
                    clearInterval(progressInterval);
                    document.getElementById('loader').textContent = getTranslation('loading');
                    
                    setTimeout(() => {
                        document.getElementById('loader').textContent = getTranslation('redirecting');
                    }, 2000);
                }
            })
            .catch(() => clearInterval(progressInterval));
    }, 1000);
}

