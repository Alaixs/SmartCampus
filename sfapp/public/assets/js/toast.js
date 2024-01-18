function showToast(message)
{
    Toastify({
        text: message,
        duration: 4000,
        newWindow: true,
        close: true,
        gravity: "top",
        position: "center",
        stopOnFocus: true,
        style: {
            background: "#18d097",
        },
        onClick: function(){}
    }).showToast();
}