document.addEventListener('DOMContentLoaded', async ()=>{
    //recibe las licencias
    let licencias = await licenciasVencidas();
    let polizas;
    //verifica el RESOLVE de licencias
    if(licencias){
        //recibe las polizas
        polizas = await polizasVencidas();
    }else{
        //console.assert(polizas,'Error en consulta de licencias');
    }
    //convertimos a JSON
    let respuestaPolizas = JSON.parse(polizas);
    let respuestaLicencias = JSON.parse(licencias);

    //generamos las notificaciones
    generarNotificacion(respuestaPolizas, respuestaLicencias);
});

/*========CONSULTA LAS LICENCIAS POR VENCER==========*/
const licenciasVencidas = () =>{
    return new Promise((resolve,reject) => {
        let fechaVencimiento = Date.today().add({days: 15 });
        let fechaParse = fechaVencimiento.toString('yyyy-MM-dd');
        let objeto = {
            tabla: 'conductores',
            campo: 'fechaVigencia_conductores',
            valor: fechaParse
        }
        let datos = JSON.stringify(objeto);
        let http = new XMLHttpRequest();
        http.open('POST','../listarNotificacion.php',true);
        http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        http.send('datos='+datos);

        http.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200){
                resolve(this.responseText);
            }
        };
    });
};
/*=======CONSULTA LAS POLIZAS A VENCER=========*/
const polizasVencidas = () =>{
    return new Promise((resolve, reject) =>{
        let fechaVencimiento = Date.today().add({days:15});
        let fechaParse = fechaVencimiento.toString('yyyy-MM-dd');
        let objeto = {
            tabla: 'unidades',
            campo: 'vigencia',
            valor: fechaParse
        }
        let datos = JSON.stringify(objeto);
        let http = new XMLHttpRequest();
        http.open('POST','../listarNotificacion.php',true);
        http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        http.send('datos='+datos);

        http.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200){
                resolve(this.responseText);
            }
        };
    });
};
/*=======GENERA LA NOTIFICACION===========*/
const generarNotificacion = (respuestaPolizas, respuestaLicencias) => {
    let badge = document.getElementById('badge');
    let content = document.getElementById('contentNotifications');
    let totalNotificaciones = respuestaPolizas.num_rows+respuestaLicencias.num_rows;
    badge.textContent = `${totalNotificaciones}`;
    if(totalNotificaciones > 0){
        badge.textContent = `${totalNotificaciones}`;
        let notifications = '';
        if(respuestaLicencias.num_rows > 0){
            respuestaLicencias.mensaje.forEach((element,index) => {
                notifications += `
                    <div class="dropdown-item" style="width:100%; color: #999; padding: 2px 5px; cursor: pointer;">
                        <div class="header-notification" style="font-size:.9em;">
                            <p style="margin-bottom:2px; font-weight:bold;">Licencia a vencer:</p>
                        </div>
                        <div class="body-notification" style="font-size:.8em; text-transform:uppercase;">
                            · ${element.nombre_conductores}
                        </div>
                        <div class="footer" style="font-size:.6em; padding-left: 15px;">
                            <p style="margin:0px"><i class="far fa-address-card"></i> ${element.noLicencia_conductores}</p>
                            <p style="margin:0px"><i class="far fa-calendar-alt"></i> ${element.fechaVigencia_conductores}</p>
                        </div>
                    </div>
                    <hr>
                `;
            });
        }
        if(respuestaPolizas.num_rows > 0){
            respuestaPolizas.mensaje.forEach((element,index) => {
                notifications += `
                    <div class="dropdown-item" style="width:100%; color: #999; padding: 2px 5px; cursor: pointer;">
                        <div class="header-notification" style="font-size:.9em;">
                            <p style="margin-bottom:2px; font-weight:bold;">Poliza a vencer:</p>
                        </div>
                        <div class="body-notification" style="font-size:.8em; text-transform:uppercase;">
                            · ${element.num_eco}
                        </div>
                        <div class="footer" style="font-size:.6em; padding-left: 15px;">
                        
                            <p style="margin:0px"><i class="far fa-calendar-alt"></i> ${element.vigencia}</p>
                        </div>
                    </div>
                    <hr>
                `;
            });
        }
        content.innerHTML = notifications;
        
    }else{
        badge.textContent = '';
        content.innerHTML = '<h6 class="text-center">No hay notificaciones</h6>';
    }
};