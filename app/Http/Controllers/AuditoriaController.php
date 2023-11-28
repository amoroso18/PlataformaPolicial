<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\AuditoriaUsuario;
use App\Models\AuditoriaActividad;
use App\Enums\AuditoriaUsuariosTipoEnum;

class AuditoriaController extends Controller
{
    public static function audita_usuario($users_id, $tipo, $descripcion, $relacion = null)
    {
        $new = new AuditoriaUsuario;
        $new->users_id = $users_id;
        $new->tipo = $tipo;
        $new->descripcion = $descripcion;
        $new->relacion = $relacion;
        $new->audita_users_id = Auth::user()->id;
        $new->save();
        return  $new;
    }
    public static function audita_usuario_automatic($users_id, $tipo, $descripcion, $relacion = null, $admin_id)
    {
        $new = new AuditoriaUsuario;
        $new->users_id = $users_id;
        $new->tipo = $tipo;
        $new->descripcion = $descripcion;
        $new->relacion = $relacion;
        $new->audita_users_id = $admin_id;
        $new->save();
    }
    public static function audita_creacion_usuario($users_id, $descripcion = null)
    {
        $new = new AuditoriaUsuario;
        $new->users_id = $users_id;
        $new->tipo = AuditoriaUsuariosTipoEnum::CREACION_USUARIO;
        $new->descripcion = $descripcion;
        $new->relacion = $users_id;
        $new->audita_users_id = $users_id;
        $new->save();
    }
    public static function audita_usuario_cambio_contrasena($users_id = null)
    {
        $new = new AuditoriaUsuario;
        $new->users_id = $users_id ? $users_id : Auth::user()->id;
        $new->tipo = AuditoriaUsuariosTipoEnum::CAMBIO_PASSWORD_DESDE_PLATAFORMA;
        $new->descripcion = "*********************";
        $new->relacion = $users_id ? $users_id : Auth::user()->id;
        $new->audita_users_id = $users_id ? $users_id : Auth::user()->id;
        $new->save();
    }
    public static function audita_usuario_cambio_contrasena_email($users_id)
    {
        $new = new AuditoriaUsuario;
        $new->users_id = $users_id;
        $new->tipo = AuditoriaUsuariosTipoEnum::RECUPERACION_PASSWORD_EMAIL;
        $new->descripcion = "*********************";
        $new->relacion = $users_id;
        $new->audita_users_id = $users_id;
        $new->save();
    }
    public static function audita_usuario_desactivacion_porvoluntad()
    {
        $new = new AuditoriaUsuario;
        $new->users_id = Auth::user()->id;
        $new->tipo = AuditoriaUsuariosTipoEnum::CAMBIO_ESTADO_DESACTIVACION_VOLUNTARIO;
        $new->descripcion = "EL USUARIO SUSPENDIO SU USUARIO POR VOLUNTAD PROPIA";
        $new->relacion = 4;
        $new->audita_users_id = Auth::user()->id;
        $new->save();
    }
    public static function email_recuperar($mensaje)
    {
        return '
            <html xmlns="http://www.w3.org/1999/xhtml">
                <head>
                <meta http-equiv="content-type" content="text/html; charset=utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0;">
                <meta name="format-detection" content="telephone=no"/>
                <title>Recuperar Contraseña</title>
                <style>
                body { margin: 0; padding: 0; min-width: 100%; width: 100% !important; height: 100% !important;}
                body, table, td, div, p, a { -webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; line-height: 100%; }
                table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse !important; border-spacing: 0; }
                img { border: 0; line-height: 100%; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; }
                #outlook a { padding: 0; }
                .ReadMsgBody { width: 100%; } .ExternalClass { width: 100%; }
                .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div { line-height: 100%; }

                @media all and (min-width: 560px) {
                .container { border-radius: 8px; -webkit-border-radius: 8px; -moz-border-radius: 8px; -khtml-border-radius: 8px;}
                }

                a, a:hover {
                color: #127DB3;
                }
                .footer a, .footer a:hover {
                color: #999999;
                }

                </style>
                </head>
                <body topmargin="0" rightmargin="0" bottommargin="0" leftmargin="0" marginwidth="0" marginheight="0" width="100%" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; width: 100%; height: 100%; -webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; line-height: 100%;
                background-color: #F0F0F0;
                color: #000000;"
                bgcolor="#F0F0F0"
                text="#000000">
                <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; width: 100%;" class="background"><tr><td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0;"
                bgcolor="#F0F0F0">
                <table border="0" cellpadding="0" cellspacing="0" align="center"
                width="560" style="border-collapse: collapse; border-spacing: 0; padding: 0; width: inherit;
                max-width: 560px;" class="wrapper">
                <tr>
                    <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%;
                        padding-top: 20px;
                        padding-bottom: 20px;">
                        <div style="display: none; visibility: hidden; overflow: hidden; opacity: 0; font-size: 1px; line-height: 1px; height: 0; max-height: 0; max-width: 0;
                        color: #F0F0F0;" class="preheader"></div>


                    </td>
                </tr>
                </table>
                <table border="0" cellpadding="0" cellspacing="0" align="center"
                bgcolor="#FFFFFF"
                width="560" style="border-collapse: collapse; border-spacing: 0; padding: 0; width: inherit;
                max-width: 560px;" class="container">
                <tr>
                    <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 24px; font-weight: bold; line-height: 130%;
                        padding-top: 25px;
                        color: #000000;
                        font-family: sans-serif;" class="header">
                            Recuperar Contraseña
                    </td>
                </tr>
            
                <tr>
            </tr>

                <tr>
                    <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%;
                        padding-top: 25px;
                        padding-bottom: 5px;" class="button"><a
                        href="' . $mensaje . '" target="_blank" style="text-decoration: underline;">
                            <table border="0" cellpadding="0" cellspacing="0" align="center" style="max-width: 240px; min-width: 120px; border-collapse: collapse; border-spacing: 0; padding: 0;"><tr><td align="center" valign="middle" style="padding: 12px 24px; margin: 0; text-decoration: underline; border-collapse: collapse; border-spacing: 0; border-radius: 4px; -webkit-border-radius: 4px; -moz-border-radius: 4px; -khtml-border-radius: 4px;"
                                bgcolor="#0F0CEE"><a target="_blank" style="text-decoration: underline;
                                color: #FFFFFF; font-family: sans-serif; font-size: 17px; font-weight: 400; line-height: 120%;"
                                href="' . $mensaje . '">
                                Presioná aquí para recuperar tu contraseña
                                </a>
                        </td></tr></table></a>
                    </td>
                </tr>
                <tr>
                <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-bottom: 3px; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 18px; font-weight: 300; line-height: 150%;
                    padding-top: 5px;
                    color: #000000;
                    font-family: sans-serif;" class="subheader">
                    Chirras.com 
                    "Desarrollando mentes, transformando vidas: Educación gratuita para todos"
                </td>
            </tr>
                <tr>	
                    <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%;
                        padding-top: 25px;" class="line"><hr
                        color="#E0E0E0" align="center" width="100%" size="1" noshade style="margin: 0; padding: 0;" />
                    </td>
                </tr>

                <tr>
                    <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%;" class="list-item"><table align="center" border="0" cellspacing="0" cellpadding="0" style="width: inherit; margin: 0; padding: 0; border-collapse: collapse; border-spacing: 0;">
                        <tr>
                            <td align="left" valign="top" style="border-collapse: collapse; border-spacing: 0;
                                padding-top: 30px;
                                padding-right: 20px;"></td>
                            <td align="left" valign="top" style="font-size: 17px; font-weight: 400; line-height: 160%; border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0;
                                padding-top: 25px;
                                color: #000000;
                                font-family: sans-serif;" class="paragraph">
                                    <b style="color: #333333;">Duración</b><br/>
                                    La duración de este enlace de recuperación solo se puede utilizar en el mismo día que se solicito.
                            </td>
                        </tr>
                        <tr>
                            <td align="left" valign="top" style="border-collapse: collapse; border-spacing: 0;
                                padding-top: 30px;
                                padding-right: 20px;"></td>
                            <td align="left" valign="top" style="font-size: 17px; font-weight: 400; line-height: 160%; border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0;
                                padding-top: 25px;
                                color: #000000;
                                font-family: sans-serif;" class="paragraph">
                                    <b style="color: #333333;">Caducidad</b><br/>
                                Cada enlace de recuperación de contraseña es único y se bloquearán conforme se solicite más de 1 en el mismo día.
                            </td>
                        </tr>

                    </table></td>
                </tr>
                <tr>	
                <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%;
                    padding-top: 25px;" class="line"><hr
                    color="#E0E0E0" align="center" width="100%" size="1" noshade style="margin: 0; padding: 0;" />
                </td>
            </tr>
            <tr>
            <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 17px; font-weight: 400; line-height: 160%;
                padding-top: 25px; 
                color: #000000;
                font-family: sans-serif;" class="paragraph">
                    * Puedes ignorar este correo en caso no lo solicitaste.<br>
                    * Si no deseas recibir este tipo de mensajes en tu bandeja puedes informarnos para tomar medidas de protección de datos y vulnerabilidad de información de usuario.
                
            </td>
        </tr>
    

                <tr>
                    <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%;
                        padding-top: 25px;" class="line"><hr
                        color="#E0E0E0" align="center" width="100%" size="1" noshade style="margin: 0; padding: 0;" />
                    </td>
                </tr>

                <!-- PARAGRAPH -->
                <!-- Set text color and font family ("sans-serif" or "Georgia, serif"). Duplicate all text styles in links, including line-height -->
                <tr>
                    <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 17px; font-weight: 400; line-height: 160%;
                        padding-top: 20px;
                        padding-bottom: 25px;
                        color: #000000;
                        font-family: sans-serif;" class="paragraph">
                        ¿Preguntas y Soporte? <a href="mailto:chirrasperu@gmail.com" target="_blank" style="color: #127DB3; font-family: sans-serif; font-size: 17px; font-weight: 400; line-height: 160%;">chirrasperu@gmail.com</a>
                    </td>
                </tr>

                <!-- End of WRAPPER -->
                </table>

                <!-- WRAPPER -->
                <!-- Set wrapper width (twice) -->
                <table border="0" cellpadding="0" cellspacing="0" align="center"
                width="560" style="border-collapse: collapse; border-spacing: 0; padding: 0; width: inherit;
                max-width: 560px;" class="wrapper">

        
                <tr>
                    <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%;
                        padding-top: 25px;" class="social-icons"><table
                        width="256" border="0" cellpadding="0" cellspacing="0" align="center" style="border-collapse: collapse; border-spacing: 0; padding: 0;">
                        <tr>

                            <!-- ICON 1 -->
                            <td align="center" valign="middle" style="margin: 0; padding: 0; padding-left: 10px; padding-right: 10px; border-collapse: collapse; border-spacing: 0;"><a target="_blank"
                                href="https://www.facebook.com/ChirrasOficial/"
                            style="text-decoration: none;"><img border="0" vspace="0" hspace="0" style="padding: 0; margin: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; border: none; display: inline-block;
                                color: #000000;"
                                alt="F" title="Facebook"
                                width="44" height="44"
                                src="https://raw.githubusercontent.com/konsav/email-templates/master/images/social-icons/facebook.png"></a></td>


                            <!-- ICON 4 -->
                            <td align="center" valign="middle" style="margin: 0; padding: 0; padding-left: 10px; padding-right: 10px; border-collapse: collapse; border-spacing: 0;"><a target="_blank"
                                href="https://www.instagram.com/chirrasoficial/?igshid=ZGUzMzM3NWJiOQ%3D%3D"
                            style="text-decoration: none;"><img border="0" vspace="0" hspace="0" style="padding: 0; margin: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; border: none; display: inline-block;
                                color: #000000;"
                                alt="I" title="Instagram"
                                width="44" height="44"
                                src="https://raw.githubusercontent.com/konsav/email-templates/master/images/social-icons/instagram.png"></a></td>

                        </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 13px; font-weight: 400; line-height: 150%;
                        padding-top: 20px;
                        padding-bottom: 20px;
                        color: #999999;
                        font-family: sans-serif;" class="footer">
                        Le enviamos esta correo electrónico porque queremos hacer del mundo un lugar mejor, donde puedas estudiar e informarte de manera gratuita. Puede cambiar la configuración de su suscripción en cualquier momento, no dudes en contactarnos.
                    </td>
                </tr>
                </table>
                </td></tr></table>
                </body>
                </html>
                ';
    }
    public static function detect_errors_return_json($e)
    {
        return [
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ];
    }

    public static function token_reset_pass($idUser){
        $date = new \DateTime();
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 5; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $codigo = $randomString.rand(10000, 99999);
         return $date->format('YmdHis')."_".$codigo."_".$idUser;
    }
    public static function token_sesion($idUser,$autenticacion)
    {
        $date = new \DateTime();
        $generate_token = base64_encode(self::generar_codigo());
        $new = new AuditoriaActividad;
        $new->users_id = $idUser;
        $new->lugar = self::obtener_dispositivo_version();
        $new->dipositivo = self::obtener_dispositivo();
        $new->navegador = self::obtener_navegador();
        $new->autenticacion = $autenticacion;
        $new->save();
        return $generate_token;
    }
    private static function generar_codigo(){
        $date = new \DateTime();
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 5; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $codigo = $randomString.rand(10000, 99999);
         return $date->format('YmdHis').$codigo;
      
    }
    public static function obtener_navegador(){
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if(strpos($user_agent, 'MSIE') !== FALSE)
           $nombre_navegador= 'Internet explorer';
         elseif(strpos($user_agent, 'Edge') !== FALSE) //Microsoft Edge
            $nombre_navegador= 'Microsoft Edge';
         elseif(strpos($user_agent, 'Trident') !== FALSE) //IE 11
            $nombre_navegador= 'Internet explorer';
         elseif(strpos($user_agent, 'Opera Mini') !== FALSE)
             $nombre_navegador= "Opera Mini";
         elseif(strpos($user_agent, 'Opera') || strpos($user_agent, 'OPR') !== FALSE)
            $nombre_navegador= "Opera";
         elseif(strpos($user_agent, 'Firefox') !== FALSE)
            $nombre_navegador= 'Mozilla Firefox';
         elseif(strpos($user_agent, 'Chrome') !== FALSE)
            $nombre_navegador= 'Google Chrome';
         elseif(strpos($user_agent, 'Safari') !== FALSE)
            $nombre_navegador= "Safari";
         else
            $nombre_navegador= 'SIN DETECTAR NAVEGADOR';  

         return strtoupper($nombre_navegador);
    }
    public static function obtener_dispositivo(){
        $tablet_browser = 0;
        $mobile_browser = 0;
        $body_class = 'desktop';
         
        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
            $tablet_browser++;
            $body_class = "tablet";
        }
         
        if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
            $mobile_browser++;
            $body_class = "mobile";
        }
         
        if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
            $mobile_browser++;
            $body_class = "mobile";
        }
         
        $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
        $mobile_agents = array(
            'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
            'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
            'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
            'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
            'newt','noki','palm','pana','pant','phil','play','port','prox',
            'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
            'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
            'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
            'wapr','webc','winw','winw','xda ','xda-');
         
        if (in_array($mobile_ua,$mobile_agents)) {
            $mobile_browser++;
        }
         
        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'opera mini') > 0) {
            $mobile_browser++;
            //Check for tablets on opera mini alternative headers
            $stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA'])?$_SERVER['HTTP_X_OPERAMINI_PHONE_UA']:(isset($_SERVER['HTTP_DEVICE_STOCK_UA'])?$_SERVER['HTTP_DEVICE_STOCK_UA']:''));
            if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
              $tablet_browser++;
            }
        }
        if ($tablet_browser > 0) {
        // Si es tablet has lo que necesites
           return 'Tablet';
        }
        else if ($mobile_browser > 0) {
        // Si es dispositivo mobil has lo que necesites
            return 'Celular';
        }
        else {
        // Si es ordenador de escritorio has lo que necesites
            return 'ordenador';
        }  
    }
    public static function obtener_dispositivo_version(){
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $plataformas = array(
            'Windows 10' => 'Windows NT 10.0+',
            'Windows 8.1' => 'Windows NT 6.3+',
            'Windows 8' => 'Windows NT 6.2+',
            'Windows 7' => 'Windows NT 6.1+',
            'Windows Vista' => 'Windows NT 6.0+',
            'Windows XP' => 'Windows NT 5.1+',
            'Windows 2003' => 'Windows NT 5.2+',
            'Windows' => 'Windows otros',
            'iPhone' => 'iPhone',
            'iPad' => 'iPad',
            'Mac OS X' => '(Mac OS X+)|(CFNetwork+)',
            'Mac otros' => 'Macintosh',
            'Android' => 'Android',
            'BlackBerry' => 'BlackBerry',
            'Linux' => 'Linux',
         );
         foreach($plataformas as $plataforma=>$pattern){
            if (preg_match('/(?i)'.$pattern.'/', $user_agent))
               return $plataforma;
         }
         return 'No detectado';
    }
    public static function generarNombreAleatorio() {
        $nombres = [
            'Pedro', 'Juan', 'Ana', 'María', 'Luis', 'Sofía', 'Carlos', 'Laura', 'David', 'Elena',
            'Diego', 'Carmen', 'Javier', 'Isabel', 'Pablo', 'Valeria', 'Roberto', 'Marta', 'Adrián', 'Leticia',
            'Andrés', 'Beatriz', 'Raúl', 'Eva', 'Fernando', 'Natalia', 'Hugo', 'Olga', 'Miguel', 'Silvia'
        ];
        return $nombres[array_rand($nombres)];
    }
    public static function generarApellidoAleatorio() {
        $apellidos = [
            'Gómez', 'López', 'Rodríguez', 'Martínez', 'Fernández', 'González', 'Pérez', 'Díaz', 'Hernández', 'Moreno',
            'Torres', 'Vargas', 'Suárez', 'Romero', 'Flores', 'Ortega', 'Molina', 'Serrano', 'Castro', 'Ruiz',
            'Jiménez', 'Giménez', 'Mendoza', 'Rojas', 'Sánchez', 'Navarro', 'Aguilar', 'Lara', 'Pacheco', 'Reyes'
        ];
        return $apellidos[array_rand($apellidos)];
    }
    public static function generarNacionalidadAleatoria() {
        $nacionalidades = [
            'Peruano', 'Venezolano', 'Argentino', 'Mexicano', 'Colombiano', 'Chileno', 'Ecuatoriano', 'Boliviano', 'Paraguayo', 'Uruguayo',
            'Brasileño', 'Costarricense', 'Guatemalteco', 'Salvadoreño', 'Panameño', 'Cubano', 'Hondureño', 'Nicaragüense', 'Dominicano', 'Puertorriqueño',
            'Estadounidense', 'Canadiense', 'Alemán', 'Francés', 'Italiano', 'Español', 'Inglés', 'Ruso', 'Chino', 'Japonés'
        ];
        return $nacionalidades[array_rand($nacionalidades)];
    }
  
}
