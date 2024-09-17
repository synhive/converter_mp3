function download() {
    const checkedInputs = document.querySelectorAll('tbody input[type="checkbox"]:checked');
    const files = [];
    checkedInputs.forEach(input => {
        files.push(input.getAttribute('data-file'));
    });

    if (files.length > 0) {
        if (files.length > 5) {
            createZip(files);
        } else {
            files.forEach(file => {
                const link = document.createElement('a');
                link.href = `downloads/${file}`;
                link.download = file.split('/').pop();
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                window.addEventListener('beforeunload', () => {
                    deleteFiles([...files, 'all_files.zip']);
                });
            });
        }
    } else {
        alert('Veuillez sélectionner au moins un fichier à télécharger.');
    }
}

function deleteFiles(files) {
    fetch('./deleteFiles.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ files: files })
    })
    .then(response => response.text())
    .then(data => {
        console.log(data);
    })
    .catch(error => {
        console.error('Erreur:', error);
    });
}

function createZip(files) {
    const formData = new FormData();
    for (let i = 0; i < files.length; i++) {
        formData.append('files[]', `downloads/${files[i]}`);
    }

    fetch('./createZip.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.blob())
    .then(blob => {
        const link = document.createElement('a');
        link.href = "downloads/all_files.zip";
        link.download = 'all_files.zip';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        window.addEventListener('beforeunload', () => {
            deleteFiles([...files, 'all_files.zip']);
        });
    })
    .catch(() => {
        alert('Erreur lors de la création du fichier ZIP.');
    });
}
