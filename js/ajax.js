const formularios_ajax=document.querySelectorAll(".FormularioAjax");

function enviar_formulario_ajax(e){
    e.preventDefault();

    let enviar=confirm("¿Estas seguro que quieres enviar el formulario?");

    if(enviar==true){

        let data= new FormData(this);
        let method=this.getAttribute("method");
        let action=this.getAttribute("action");

        let encabezados= new Headers();

        let config={
            method: method,
            headers: encabezados,
            mode: 'cors',
            cache: 'no-cache',
            body: data
        };

        fetch(action,config)
        .then(respuesta => respuesta.text())
        .then(respuesta =>{ 
            let contenedor=document.querySelector(".form-rest");
            contenedor.innerHTML = respuesta;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

}

formularios_ajax.forEach(formularios => {
    formularios.addEventListener("submit",enviar_formulario_ajax);
});