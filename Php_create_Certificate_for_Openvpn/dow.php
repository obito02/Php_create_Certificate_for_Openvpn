<?php
/*ESTA FUENTE ES DE CODIGO ABIERTO CUALQUIER MODIFICACION DEBE SER ATRIBUIDA A SUS CREADORES, ASI MISMO A LA
BIBLIOTECA DE PHPSECLIB PARA LA CONEXION SSH */
set_include_path(get_include_path() . PATH_SEPARATOR . 'phpseclib');
include 'Net/SSH2.php';
function create_user($username){
$ssh = new Net_SSH2("127.0.0.1");
$password = file_get_contents("root.txt");
$name_cert = "$username";
if (!$ssh->login("root", trim($password))) {
    print ('Login Failed');
    print_R($ssh->errors);
}else{
   if (file_exists("keys/".$username.".zip")){
       return "keys/$username.zip";
   }
   mkdir("keys/".$username);
   // es
 $plantilla = file_get_contents("plantilla.ovpn");
 $plantilla = str_replace("\n", "\r\n", $plantilla);
 //MODIFY ADJUST
 file_put_contents("keys/$username/cliente.ovpn",  str_replace("{DEFAULT}", "$username", $plantilla));
  $output = $ssh->exec("cd /etc/openvpn/easy-rsa/ && . ./vars && ./pkitool --batch $name_cert  && cd keys && cp $name_cert.crt $name_cert.key /var/www/html/keys/$name_cert && cp ca.crt /var/www/html/keys/$name_cert && zip -r /var/www/html/keys/$username.zip /var/www/html/keys/$username && rm -R /var/www/html/keys/$username ");
 if (file_exists("keys/$username.zip")){
  return "keys/$username.zip";
 }
}
}
if (isset($_POST['u'])){
    $username = $_POST['u'];
    $key = $_POST['key'];
    if (empty($username) || empty($key)){
        echo "FALTA ALGO";
        exit;
    }
	//change de password use md5 function 
    if (md5($key) != ""){
        die("POR EJEMPL OAHROA TE ESTOY HACKEADNO");
    }
    $uri = create_user($username);
    echo "CLIK DESCARGAR AHORA <a href=\"$uri\">$uri</a>";
    exit;
}
?>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<center>
    <div id="conte">
    <label>NOMBRE DE USUARIO NO CARACTERIS ESPECIALES</label>
    <input id="name" type="text" style="width: 150px;"></input>
    <label>CLAVE</label>
    <input id="pa" type="password" style="width: 150px;"></input>
    <input onclick="enviar();" type="button" id="cr" value="CREAR USUARIO" />
    </div>
    <div id="banner"></div>
</center>
<script>
    function enviar(){
        $("#conte").hide("slow");
        var username = $("#name").val();
        var pa = $("#pa").val();
       
        $.post("dow.php",{u:username,key:pa},function(data){
            $("#banner").replaceWith("<div id=\"banner\">"+data+"</div>");
            $("#conte").show("slow");
        });
    }

</script>
