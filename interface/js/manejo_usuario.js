function registrar_usuario()
{
        var nombre;
		var apellido;   
        var correo;
		var nacimiento;
		var clave;
        var str;
        
        nombre = document.getElementById("nombre_usuario").value;
		apellido = document.getElementById("apellido_usuario").value;
        clave = document.getElementById("pass1").value;        
        correo = document.getElementById("correo_usuario").value;
		//nacimiento = ;
		
        
        if (nombre == "" || apellido == "" || clave == "" || correo == "" || validarEmail(document.getElementById('correo_usuario').value))
        {
                if (validarEmail(document.getElementById('correo_usuario').value))
                {
                        alert("Correo Invalido");
                }
                else
                {
                        alert("Debe Ingresar todos los datos");
                }
        }
        else
        {        
                str = nombre +  "<tag>" + apellido + "<tag>" + correo + "<tag>" + clave;
                
                var xmlhttp;
                if (window.XMLHttpRequest)
                {// code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttp=new XMLHttpRequest();                
                }
                else
                {// code for IE6, IE5
                        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");                
                }
                xmlhttp.onreadystatechange=function()
                {                
                        if (xmlhttp.readyState==4 && xmlhttp.status==200)
                        {                                                        
                                var r = xmlhttp.responseText;                                
                                if (r == "error")
                                {
                                        alert ("Existe una cuenta con el mismo correo, error");
                                }
                                else
                                {
										//Redireccionar.
                                        //location.href="index.html";
                                }
                                
                        }
                }
                xmlhttp.open("POST","../../backend/php/crear_usuario.php",true);        
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp.setRequestHeader("Content-type", "text/html; charset=iso-8859-1");
                str = "q="+str;
                xmlhttp.setRequestHeader("Content-length", str.length);                
                xmlhttp.send(str);                
        }
}

function logear_usuario()
{        		
        var correo;		
		var clave;
        var str;
                		        
        correo = document.getElementById("correo_login").value;
		clave = document.getElementById("pass_login").value;        
		//nacimiento = ;
		
        
        if (clave == "" || correo == "")
        {
                
				alert("Debe Ingresar todos los datos");
                
        }
        else
        {        
                str = correo + "<tag>" + clave;
                
                var xmlhttp;
                if (window.XMLHttpRequest)
                {// code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttp=new XMLHttpRequest();                
                }
                else
                {// code for IE6, IE5
                        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");                
                }
                xmlhttp.onreadystatechange=function()
                {                
                        if (xmlhttp.readyState==4 && xmlhttp.status==200)
                        {                                                        
                                var r = xmlhttp.responseText;                                
                                if (r == "usuario")
                                {
										//Usuario, logeo exitoso
                                        alert ("Exito");
                                }
                                else
                                {										
										//Usuario logeo fallido
										alert ("Fracaso");
                                }
                                
                        }
                }
                xmlhttp.open("POST","../../backend/php/logear_usuario.php",true);        
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp.setRequestHeader("Content-type", "text/html; charset=iso-8859-1");
                str = "q="+str;
                xmlhttp.setRequestHeader("Content-length", str.length);                
                xmlhttp.send(str);                
        }
}

function prueba()
{
var str = 'lala';

				var xmlhttp;
                if (window.XMLHttpRequest)
                {// code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttp=new XMLHttpRequest();                
                }
                else
                {// code for IE6, IE5
                        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");                
                }
                xmlhttp.onreadystatechange=function()
                {                
                        if (xmlhttp.readyState==4 && xmlhttp.status==200)
                        {                                                        
                                var r = xmlhttp.responseText;                                
                                alert (r);
                        }
                }
                xmlhttp.open("POST","../../backend/php/lala.php",true);        
				xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp.setRequestHeader("Content-type", "text/html; charset=iso-8859-1");
                str = "q="+str;
                xmlhttp.setRequestHeader("Content-length", str.length);                
                xmlhttp.send(str);                

}