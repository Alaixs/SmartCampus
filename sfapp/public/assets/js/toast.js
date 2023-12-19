function showToastSA() {

Toastify({
    text: "SA ajouté avec succès",
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

  document.getElementById("add_sa_form_number").value = "";
}

function showToastRoom() {
  
  Toastify({
      text: "Salle ajoutée avec succès",
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

    document.getElementById("add_room_form_name").value = "";
    document.getElementById("add_room_form_capacity").value = "";
    document.getElementById("add_room_form_hasComputers").checked = false;;
    document.getElementById("add_room_form_area").value = "";
    document.getElementById("add_room_form_nbWindows").value = "";
      
}

function showToastSAEdit() {
  
    Toastify({
        text: "SA modifié avec succès",
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

  function showToastRoomEdit() {
    
      Toastify({
          text: "Salle modifiée avec succès",
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