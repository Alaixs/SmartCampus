function togglePopup(pathname) {
    disabledButton();
    document.getElementsByClassName('planExperimentation')[0].style.filter = 'blur(5px)';
    document.querySelector('header').style.filter = 'blur(5px)';
    document.querySelector('footer').style.filter = 'blur(5px)';

    createPopup(pathname)
        .then(popup => {
            document.body.appendChild(popup);
        })
        .catch(error => console.error('Erreur lors de la création de la popup :', error));
}

function createPopup(pathname) {
    return fetch(pathname)
        .then(response => response.text())
        .then(data => {
            // Créer l'élément popup avec le contenu de la réponse
            let popup = document.createElement('section');
            popup.id = 'popup'
            popup.innerHTML = data;

            return popup;
        });
}


function disabledButton() {
    let buttons = document.getElementsByClassName("button");
    for (var i = 0; i < buttons.length; i++) {
        buttons[i].disabled = true;
        if (buttons[i].classList[0] === 'planExperimentation-div-button-add') {
            buttons[i].classList.add('stopHoverButtonAdd');
        } else {
            buttons[i].classList.add('stopHoverButtonModify');
        }
    }
}