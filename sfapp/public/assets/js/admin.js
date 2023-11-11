function togglePopup() {
    disabledButton();
    document.getElementsByClassName('planExperimentation')[0].style.filter = 'blur(5px)';
    document.querySelector('header').style.filter = 'blur(5px)';
    document.querySelector('footer').style.filter = 'blur(5px)';

    var popup = document.getElementById('popup');
    popup.hidden = !popup.hidden;
}

function disabledButton()
{
    let buttons = document.getElementsByClassName("button");
    for (var i = 0; i < buttons.length; i++) {
        buttons[i].disabled = true;
        if(buttons[i].classList[0] === 'planExperimentation-div-button-add')
        {
            buttons[i].classList.add('stopHoverButtonAdd');
        }
        else
        {
            buttons[i].classList.add('stopHoverButtonModify');

        }

    }
}

