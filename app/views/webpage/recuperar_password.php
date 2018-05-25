<?php
require_once 'php/class.phpmailer.php';

$idioma = 'EN';
$email = $_POST['email'];

$con = mysql_connect("localhost", "prod_adtk", "adtk_2014!");
if (!$con) {
    die("Error: " . mysql_error());
}

mysql_select_db("produccion_adtomatik", $con);

$result = mysql_query("SELECT id, email, idioma FROM users WHERE email='" . $email . "'");

$user = mysql_fetch_row($result);

$id = $user['0'];
$idioma=$user['2'];
$new_pass_key = md5(rand() . microtime());

$result_new_key = mysql_query("UPDATE users SET new_password_key='$new_pass_key', new_password_requested=CURRENT_TIMESTAMP
                        WHERE id=$id;");

_send_email($email, $id, $new_pass_key,$idioma);

function _send_email($email, $user_id, $new_pass, $idioma) {

    if( $idioma == 'EN'  || $idioma == 'en'){
         $contenido = file_get_contents('./template_forgot_password_en.php');
        $asunto = utf8_decode("Reset your password.");
    }else{
          $contenido = file_get_contents('./template_forgot_password.php');
        $asunto = "�Olvidaste tu contrase�a en MediaFem para Sitios?";

    }

    $contenido = str_replace("<<LINK>>", 'http://publishers.adtomatik.com/auth/reset_password/' . $user_id . '/' . $new_pass. '/'.$idioma , $contenido);

    //$contenido = $this->caracteres_html($contenido);

    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPAuth = true; // habilitamos la autenticaci�n SMTP
    $mail->Host = "ssl://smtp.gmail.com";      // establecemos GMail como nuestro servidor SMTP
    $mail->Port = 465;
    $mail->Username = "mailing@mediafem.com";  // la cuenta de correo GMail
    $mail->Password = "Sebastian02";            // password de la cuenta GMail
    $mail->From = "media@mediafem.com";  //Quien env�a el correo

	if( $idioma == 'ES' ){
            $mail->FromName = 'MediaFem para Sitios';  //Asunto del mensaje
        }else{
            $mail->FromName = 'AdTomatik For Publishers';  //Asunto del mensaje
        }

    $mail->Subject = $asunto;  //Asunto del mensaje
    $mail->IsHTML(true);
    $mail->Body = $contenido;
    $mail->AddAddress($email);

    $mail->Send();
}

?>