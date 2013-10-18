function mostrar_actividades_registro()
{               
    var xmlhttp;
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();                
		var r = xmlhttp.responseText;		
    }
    else
    {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");                
		var r = xmlhttp.responseText;                                                        		
    }
	xmlhttp.onreadystatechange=function()
    {                
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {                                                                    
			document.getElementById('mostrar_actividades').innerHTML = xmlhttp.responseText;			
        }
    }
	xmlhttp.open("GET","../../backend/php/mostrar_actividades.php",true);                    
    xmlhttp.send();                        	
}

mostrar_actividades_registro();