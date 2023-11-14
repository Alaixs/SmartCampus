function quitPopup()
{
    if(document.getElementById('popup').hidden)
    {
        return;
    }

    let popup = document.getElementById('popup');
    popup.hidden = !popup.hidden;
    let buttons = document.getElementsByClassName("button");
    for (let i = 0; i < buttons.length; i++) {
        buttons[i].disabled = false;
        if(buttons[i].classList[0] === 'planExperimentation-div-button-add')
        {
            buttons[i].classList.remove('stopHoverButtonAdd');
        }
        else
        {
            buttons[i].classList.remove('stopHoverButtonModify');

        }

    }
    document.getElementsByClassName('planExperimentation')[0].style.removeProperty('filter');
    document.querySelector('header').style.removeProperty('filter');
    document.querySelector('footer').style.removeProperty('filter');

}

