function buscarUsuarioPorEmail() {
    const email= document.getElementById("email").value;

    if (!email) {
        document.getElementById("resultado").textContent = "Por favor ingresa un email";
    return;
   }

       fetch('/apis/api.php?email='+ encodeURIComponent(email))
            .then(response => response.json())
            .then(data => {
              if (data.available) {
              document.getElementById("resultado").textContent= "email disponible";
              }else{
                  document.getElementById("resultado").textContent= "email NO disponible";
           }
   });
}


