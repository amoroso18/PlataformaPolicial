<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;

use App\Models\TipoGrado;
use App\Models\TipoPerfil;
use App\Models\TipoUnidad;
use App\Http\Controllers\AuditoriaController;

use App\Models\DispocicionFiscal;
use App\Models\DisposicionFiscalObjetos; // caducado
use App\Models\DisposicionFiscalTipoVideoVigilancia;
use App\Models\DisposicionFiscalReferencia;
use App\Models\DisposicionFiscalDelitos;

use App\Models\DisposicionFiscalNuevaVigilancia;
use App\Models\DisposicionFiscalNuevaVigilanciaActividad;
use App\Models\DisposicionFiscalNuevaVigilanciaEntidad;
use App\Models\DisposicionFiscalNuevaVigilanciaArchivo;
use App\Models\DisposicionFiscalDocResultado;
use App\Models\DisposicionFiscalDocResultadoAnexo;

use App\Models\DisposicionFiscalEntidadVigilancia; // reemplazo de DisposicionFiscalObjetos
use App\Models\EntidadPersona;
use App\Models\EntidadInmueble;
use App\Models\EntidadVehiculos;

use Codedge\Fpdf\Fpdf\Fpdf;

class PDFF extends FPDF
{
    private $title;
    private $date;
    private $routeImagesPathTemp;

    function __construct($title)
    {
        parent::__construct();
        $this->title = $title;
        $this->date = new \DateTime();
    }
    public function Header()
    {
        $this->SetFont('Courier', 'B', 7);
        $this->SetTextColor(89, 90, 90);
        $this->multicell(192, -2, $this->title, 0, 'C');
        $this->Ln(8);
    }
    public function Footer()
    {
        $this->SetY(-15);
        $this->SetTextColor(89, 90, 90);
        $this->SetFont('Courier', 'B', 7);
        $this->multicell(192, 4, $this->title, 0, 'C');
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 5, 'Pag. ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

class ReportesController extends Controller
{
    public static function reporte($IDUSUARIO)
    {
        // Texto o datos que deseas codificar en el QR Code
        try {
            $USUARIO = User::find($IDUSUARIO);
            $INFO = AuditoriaController::audita_usuario(Auth::user()->id, "DESCARGA REPORTE", "AUDITORIA DE USUARIO", $USUARIO->id);

            $ContenidoTitulo = utf8_decode('CÓDIGO DE SEGURIDAD NRO.' . $INFO->id . ' | CÓDIGO USUARIO NRO.' . Auth::user()->id . ' | FECHA DESCARGA ' . $INFO->created_at);
            $MYPDF = new PDFF($ContenidoTitulo);
            $MYPDF->AliasNbPages();
            $MYPDF->AddPage();
            $MYPDF->image('images/sivipol/BannerSivipol.png', 75, 10, 60);
            //$MYPDF->image('images/qrvalidar.png', 172.5, 31.5, 31);
            $MYPDF->Ln(10);
            // $MYPDF->SetFont('Arial', 'B', 18);
            // $MYPDF->multicell(192, -2, "REPORTE DE USUARIO", 0, 'C');
            $MYPDF->Ln(10);
            $MYPDF->SetFont('Arial', 'B', 11);
            $MYPDF->SetTextColor(89, 90, 90);
            $MYPDF->multicell(192, -2, "REPORTE DE USUARIO SIVIPOL", 0, 'C');
            $MYPDF->Ln(4);

            self::generateLineTextForDetail($MYPDF, 'USUARIO', "0000" . $USUARIO->id);
            self::generateLineTextForDetail($MYPDF, 'FECHA CREACIÓN', $USUARIO->created_at);
            $MYPDF->Ln(4);
            self::generateLineTextForDetail($MYPDF, 'NOMBRES', strtoupper($USUARIO->nombres));
            self::generateLineTextForDetail($MYPDF, 'CARNET', $USUARIO->carnet);
            $MYPDF->Ln(4);
            self::generateLineTextForDetail($MYPDF, 'APELLIDOS', strtoupper($USUARIO->apellidos));
            self::generateLineTextForDetail($MYPDF, 'DNI', $USUARIO->dni);
            $MYPDF->Ln(4);
            self::generateLineTextForDetail($MYPDF, 'CELULAR', $USUARIO->phone);
            self::generateLineTextForDetail($MYPDF, 'CORREO', $USUARIO->email);
            $MYPDF->Ln(4);
            self::generateLineTextForDetail($MYPDF, 'GRADO', $USUARIO->getGrado->descripcion);
            self::generateLineTextForDetail($MYPDF, 'UNIDAD', $USUARIO->getUnidad->descripcion);
            $MYPDF->Ln(4);
            self::generateLineTextForDetail($MYPDF, 'SITUACION', $USUARIO->getEstado->descripcion);
            self::generateLineTextForDetail($MYPDF, 'PERFIL', $USUARIO->getPerfil->descripcion);


            // $MYPDF->Ln(10);
            // $MYPDF->SetFont('Arial', 'B', 11);
            // $MYPDF->multicell(192, 5, "CREACION DE DIPOSICIONES FISCALES", 0, 'C');

            // $MYPDF->Ln(8);
            // $MYPDF->SetFont('Arial', 'B', 11);
            // $MYPDF->multicell(192, 5, "CULMINACION DE DIPOSICIONES FISCALES", 0, 'C');

            // $MYPDF->Ln(8);
            // $MYPDF->SetFont('Arial', 'B', 11);
            // $MYPDF->multicell(192, 5, "SUSPENCION DE DIPOSICIONES FISCALES", 0, 'C');

            // $MYPDF->Ln(8);
            // $MYPDF->SetFont('Arial', 'B', 11);
            // $MYPDF->multicell(192, 5, "DESCARGA DE DIPOSICIONES FISCALES", 0, 'C');

            $MYPDF->Ln(8);
            $MYPDF->SetFont('Arial', 'B', 11);
            $MYPDF->multicell(192, 5, "HISTORIAL DE CONEXION DE USUARIO", 0, 'C');
            $MYPDF->Ln(5);
            foreach ($USUARIO->getHistorialConexion as $key => $value) {
                self::generateLineText($MYPDF,  $value->created_at, $value->dipositivo . " | " . $value->lugar . " | " . $value->autenticacion . " | " . $value->navegador);
            }
            $MYPDF->Ln(5);
            $MYPDF->SetFont('Arial', 'B', 11);
            $MYPDF->multicell(192, 5, "HISTORIAL DE ACTIVIDAD DE USUARIO", 0, 'C');
            $MYPDF->Ln(5);
            foreach ($USUARIO->getHistorialAuditoria as $key => $value) {
                self::generateLineText($MYPDF,  $value->created_at, "CODIGO: " . $value->id . " | " .  $value->tipo . " | " . $value->descripcion);
            }
            $MYPDF->Ln(5);

            $MYPDF->Output(('ReporteUsuario.pdf'), 'I');
            //$MYPDF->Output('D', "ReporteReferenciaSerpol.pdf", true);
            exit();
        } catch (\Exception $e) {
            // dd($e);
        }
    }
    public static function reporteUsuarios()
    {
        // Texto o datos que deseas codificar en el QR Code
        try {
            $INFO = AuditoriaController::audita_usuario(Auth::user()->id, "DESCARGA REPORTE", "AUDITORIA DE USUARIOS", Auth::user()->id);
            $ContenidoTitulo = utf8_decode('CÓDIGO DE SEGURIDAD NRO.' . $INFO->id . ' | CÓDIGO USUARIO NRO.' . Auth::user()->id . ' | FECHA DESCARGA ' . $INFO->created_at);
            $MYPDF = new PDFF($ContenidoTitulo);
            $MYPDF->AliasNbPages();
            $MYPDF->AddPage();
            $MYPDF->image('images/sivipol/BannerSivipol.png', 75, 10, 60);
            //$MYPDF->image('images/qrvalidar.png', 172.5, 31.5, 31);
            $MYPDF->Ln(10);
            // $MYPDF->SetFont('Arial', 'B', 18);
            // $MYPDF->multicell(192, -2, "REPORTE DE USUARIO", 0, 'C');
            $MYPDF->Ln(10);
            $MYPDF->SetFont('Arial', 'B', 11);
            $MYPDF->SetTextColor(89, 90, 90);
            $MYPDF->multicell(192, -2, "REPORTE DE USUARIOS POR PERFIL SIVIPOL", 0, 'C');
            $MYPDF->Ln(4);


            $TipoPerfil = TipoPerfil::get();
            foreach ($TipoPerfil as $key => $valuePerfil) {
                self::generateLineText($MYPDF,  "" . $valuePerfil->descripcion, "");
                $USUARIO = User::where("perfil_id", $valuePerfil->id)->get();
                if($USUARIO->count() == 0){
                    self::generateLineTextForDetailSpace($MYPDF, 'NO HAY USUARIOS CON ESTE PERFIL', "");
                    $MYPDF->Ln(6);  
                }
                foreach ($USUARIO as $key => $value) {
                    self::generateLineTextForDetailSpace($MYPDF, 'USUARIO', "0000" . $value->id);
                    self::generateLineTextForDetailSpace($MYPDF, 'FECHA CREACIÓN', $value->created_at);
                    $MYPDF->Ln(4);
                    self::generateLineTextForDetailSpace($MYPDF, 'NOMBRES', strtoupper($value->nombres));
                    self::generateLineTextForDetailSpace($MYPDF, 'CARNET', $value->carnet);
                    $MYPDF->Ln(4);
                    self::generateLineTextForDetailSpace($MYPDF, 'APELLIDOS', strtoupper($value->apellidos));
                    self::generateLineTextForDetailSpace($MYPDF, 'DNI', $value->dni);
                    $MYPDF->Ln(4);
                    self::generateLineTextForDetailSpace($MYPDF, 'CELULAR', $value->phone);
                    self::generateLineTextForDetailSpace($MYPDF, 'CORREO', $value->email);
                    $MYPDF->Ln(4);
                    self::generateLineTextForDetailSpace($MYPDF, 'GRADO', $value->getGrado->descripcion);
                    self::generateLineTextForDetailSpace($MYPDF, 'UNIDAD', $value->getUnidad->descripcion);
                    $MYPDF->Ln(4);
                    self::generateLineTextForDetailSpace($MYPDF, 'SITUACION', $value->getEstado->descripcion);
                    self::generateLineTextForDetailSpace($MYPDF, 'PERFIL', $value->getPerfil->descripcion);
                    $MYPDF->Ln(6);
                }
                $MYPDF->Ln(6);
            }


            // self::generateLineTextForDetail($MYPDF, 'USUARIO', "0000" . $USUARIO->id);
            // self::generateLineTextForDetail($MYPDF, 'FECHA CREACIÓN', $USUARIO->created_at);



            $MYPDF->Output(('ReporteUsuarios.pdf'), 'I');
            //$MYPDF->Output('D', "ReporteReferenciaSerpol.pdf", true);
            exit();
        } catch (\Exception $e) {
            // dd($e);
        }
    }
    public static function expediente_reporte($idexpe){
          // Texto o datos que deseas codificar en el QR Code
          try {
            $imageType = 'jpeg';
            if (App::environment('local')) {
                $routeImagesPathTemp = public_path('temp/');
            } else {
                $routeImagesPathTemp = '/var/www/html/sivipol/temp/';
            }
            $INFO = AuditoriaController::audita_usuario(Auth::user()->id, "DESCARGA REPORTE", "EXPEDIENTE", $idexpe);
            $ContenidoTitulo = utf8_decode('CÓDIGO DE SEGURIDAD NRO.' . $INFO->id . ' | CÓDIGO USUARIO NRO.' . Auth::user()->id . ' | FECHA DESCARGA ' . $INFO->created_at);
            $MYPDF = new PDFF($ContenidoTitulo);
            $MYPDF->AliasNbPages();
            $MYPDF->AddPage();
            $MYPDF->image('images/sivipol/BannerSivipol.png', 75, 10, 60);
            //$MYPDF->image('images/qrvalidar.png', 172.5, 31.5, 31);
            $MYPDF->Ln(10);
            // $MYPDF->SetFont('Arial', 'B', 18);
            // $MYPDF->multicell(192, -2, "REPORTE DE USUARIO", 0, 'C');
            $MYPDF->Ln(10);
            $MYPDF->SetFont('Arial', 'B', 11);
            $MYPDF->SetTextColor(89, 90, 90);
            $MYPDF->multicell(192, -2, "REPORTE DE EXPEDIENTE", 0, 'C');
            $MYPDF->Ln(4);

            $expe = DispocicionFiscal::find($idexpe);
            $expeREFERENCIA = DisposicionFiscalReferencia::where("df_id",$expe->id)->get();
            $expeOBJETOVV = DisposicionFiscalEntidadVigilancia::where("df_id",$expe->id)->orderBy('entidads_id','asc')->get();
            $expeTIPOVV = DisposicionFiscalTipoVideoVigilancia::where("df_id",$expe->id)->get();
            $expeDelitos =  DisposicionFiscalDelitos::where("df_id",$expe->id)->get();
            $MYPDF->Ln(4);

            $MYPDF->multicell(192, 5, "DETALLE", 0, 'L');
            $MYPDF->Ln(2);
            self::generateLineTextSpace($MYPDF,  "CASO", strtoupper($expe->caso));$MYPDF->Ln(1);
            self::generateLineTextSpace($MYPDF,  "NRO", strtoupper($expe->nro));$MYPDF->Ln(1);
            self::generateLineTextSpace($MYPDF,  "FECHA DISPOSICION", $expe->fecha_disposicion);$MYPDF->Ln(1);
            self::generateLineTextSpace($MYPDF,  "FECHA DE INICIO", $expe->fecha_inicio);$MYPDF->Ln(1);
            self::generateLineTextSpace($MYPDF,  "FECHA DE TERMINO", $expe->fecha_termino);$MYPDF->Ln(1);

            self::generateLineTextSpace($MYPDF,  "TIPO DE PLAZO", strtoupper($expe->getPlazo->descripcion));$MYPDF->Ln(1);
            self::generateLineTextSpace($MYPDF,  "DIAS DE PLAZO", $expe->plazo);$MYPDF->Ln(1);
            if($expe->getFiscal->dni){
                self::generateLineTextSpace($MYPDF,  "FISCAL RESPONSABLE", strtoupper("DNI: ".$expe->getFiscal->dni." | ".$expe->getFiscal->nombres." ".$expe->getFiscal->paterno." ".$expe->getFiscal->materno." | ".$expe->getFiscal->procedencia." ".$expe->getFiscal->ficalia." ".$expe->getFiscal->despacho." ".$expe->getFiscal->ubigeo." | CORREO: ".$expe->getFiscal->correo." | CELULAR:".$expe->getFiscal->celular));$MYPDF->Ln(1);
            }
            if($expe->getFiscalAdjunto->dni){
                self::generateLineTextSpace($MYPDF,  "FISCAL ASISTENTE", strtoupper("DNI: ".$expe->getFiscalAdjunto->dni." | ".$expe->getFiscalAdjunto->nombres." ".$expe->getFiscalAdjunto->paterno." ".$expe->getFiscalAdjunto->materno." | ".$expe->getFiscalAdjunto->procedencia." ".$expe->getFiscalAdjunto->ficalia." ".$expe->getFiscalAdjunto->despacho." ".$expe->getFiscalAdjunto->ubigeo." | CORREO: ".$expe->getFiscalAdjunto->correo." | CELULAR:".$expe->getFiscalAdjunto->celular));$MYPDF->Ln(1);
            }
            if($expe->getOficial){
                self::generateLineTextSpace($MYPDF,  "OFICIAL A CARGO", strtoupper($expe->getOficial->carnet." | ".$expe->getOficial->nombres." ".$expe->getOficial->paterno." ".$expe->getOficial->materno." | ".$expe->getOficial->getGrado->descripcion));$MYPDF->Ln(1);
            }
            self::generateLineTextSpace($MYPDF,  "RESUMEN", strtoupper($expe->resumen));$MYPDF->Ln(1);
            self::generateLineTextSpace($MYPDF,  "OBSERVACIONES", strtoupper($expe->observaciones));$MYPDF->Ln(1);
            self::generateLineTextSpace($MYPDF,  "SITUACION", strtoupper($expe->getEstado->descripcion));$MYPDF->Ln(1);
           
            
            $MYPDF->Ln(5);
            $MYPDF->SetFont('Arial', 'B', 11);
            $MYPDF->multicell(192, 5, "DELITOS", 0, 'L');
            $MYPDF->Ln(2);
            foreach ($expeDelitos  as $key => $value) {
                $MYPDF->Cell(5, 6, " ", 0, 0, 'L');
                $MYPDF->SetTextColor(89, 90, 90);
                $MYPDF->SetFont('Helvetica', '', 10);
                $MYPDF->multicell(180, 3, strtoupper(utf8_decode(trim($value->geTipoDelitos->tipo." - ".$value->geTipoDelitos->subtipo." - ".$value->geTipoDelitos->modalidad))), 0, 'j');
                $MYPDF->Ln(3);
            }

            $MYPDF->Ln(5);
            $MYPDF->SetFont('Arial', 'B', 11);
            $MYPDF->multicell(192, 5, "REFERENCIA", 0, 'L');
            $MYPDF->Ln(2);
            foreach ($expeREFERENCIA  as $key => $value) {
                self::generateLineTextSpace($MYPDF,  "TIPO DOC REFERENCIA", $value->geTipoDocumentosReferencia->descripcion);$MYPDF->Ln(1);
                self::generateLineTextSpace($MYPDF,  "NRO", $value->nro);$MYPDF->Ln(1);
                self::generateLineTextSpace($MYPDF,  "FECHA", $value->fecha_documento);$MYPDF->Ln(1);
                self::generateLineTextSpace($MYPDF,  "SIGLAS", $value->siglas);$MYPDF->Ln(1);
                self::generateLineTextSpace($MYPDF,  "ARCHIVO", $value->pdf);$MYPDF->Ln(1);
            }
            $MYPDF->Ln(5);
            $MYPDF->SetFont('Arial', 'B', 11);
            $MYPDF->multicell(192, 5, "TIPO DE VIDEOVIGILANCIA", 0, 'L');
            $MYPDF->Ln(2);
            foreach ($expeTIPOVV  as $key => $value) {
                self::generateLineTextSpace($MYPDF,  $value->geTipoVideovigilancia->descripcion, "");$MYPDF->Ln(1);
            }

            $MYPDF->Ln(5);
            $MYPDF->SetFont('Arial', 'B', 11);
            $MYPDF->multicell(192, 5, "OBJECTO DE VIDEOVIGILANCIA", 0, 'L');
            $MYPDF->Ln(2);
            foreach ($expeOBJETOVV  as $key => $value) {
                if($value->entidads_id == 1 || $value->entidads_id == 2){
                    $EntidadPersona = EntidadPersona::with(['getTipoNacionalidad', 'getTipoDocumentoIdentidad'])->where('id',$value->codigo_relacion)->first();

                    $img = explode(',',$EntidadPersona->foto,2);
                    $pic = 'data://text/plain;base64,'. $img;

                    if ($EntidadPersona->foto) {
                        $base64Image = $EntidadPersona->foto; // Tus datos BLOB
                        // Decodifica el BLOB y guarda la imagen en un archivo temporal
                        $imageData = base64_decode($base64Image);
                        $tempImageFile = $EntidadPersona->documento . '_temp_image.jpg'; // Nombre del archivo temporal
                        file_put_contents($routeImagesPathTemp . $tempImageFile, $imageData);
                        chmod($routeImagesPathTemp . $tempImageFile, 0755);
                        // Inserta la imagen en el PDF
                        $MYPDF->image($routeImagesPathTemp . $tempImageFile, 9, 72, 45);
                        // Elimina el archivo temporal
                        unlink($routeImagesPathTemp . $tempImageFile);
                    }
                    // $MYPDF->image('data:image/' . $imageType . ';base64,' . $EntidadPersona->foto, 10, 10, 150, 150);
                    $MYPDF->Image($pic,10,30,0,0,'png');
                    self::generateLineTextForDetailSpace($MYPDF, 'NACIONALIDAD', $EntidadPersona->getTipoNacionalidad->descripcion);
                    self::generateLineTextForDetailSpace($MYPDF, 'TIPO DOCUMENTO', $EntidadPersona->getTipoDocumentoIdentidad->descripcion);
                    $MYPDF->Ln(4);
                    self::generateLineTextForDetailSpace($MYPDF, 'DOCUMENTO', strtoupper($EntidadPersona->documento));
                    self::generateLineTextForDetailSpace($MYPDF, 'NOMBRES', $EntidadPersona->nombres);
                    $MYPDF->Ln(4);
                    self::generateLineTextForDetailSpace($MYPDF, 'APELLIDO PATERNO', strtoupper($EntidadPersona->paterno));
                    self::generateLineTextForDetailSpace($MYPDF, 'APELLIDO MATERNO', $EntidadPersona->materno);
                    $MYPDF->Ln(4);
                    self::generateLineTextForDetailSpace($MYPDF, 'ESTADO CIVIL', $EntidadPersona->estado_civil);
                    self::generateLineTextForDetailSpace($MYPDF, 'SEXO', $EntidadPersona->sexo);
                    $MYPDF->Ln(4);
                    self::generateLineTextForDetailSpace($MYPDF, 'FECHA NAC.', $EntidadPersona->fecha_nacimiento);
                    if($value->entidads_id == 1){
                        self::generateLineTextForDetailSpace($MYPDF, strtoupper('ubigeo nacimiento'), $EntidadPersona->ubigeo_nacimiento);
                        $MYPDF->Ln(4);
                        self::generateLineTextForDetailSpace($MYPDF, strtoupper('depart. nacimiento'), $EntidadPersona->departamento_nacimiento);
                        self::generateLineTextForDetailSpace($MYPDF, strtoupper('provincia nacimiento'), $EntidadPersona->provincia_nacimiento);
                        $MYPDF->Ln(4);
                        self::generateLineTextForDetailSpace($MYPDF, strtoupper('distrito nacimiento'), $EntidadPersona->distrito_nacimiento);
                        $MYPDF->Ln(5);
                    }else{
                        $MYPDF->Ln(5);
                    }
                    self::generateLineTextSpace($MYPDF, strtoupper('lugar nacimiento'), $EntidadPersona->lugar_nacimiento);
                    self::generateLineTextForDetailSpace($MYPDF, strtoupper('ubigeo domicilio'), $EntidadPersona->ubigeo_domicilio);
                    $MYPDF->Ln(4);
                    self::generateLineTextForDetailSpace($MYPDF, strtoupper('depart. domicilio'), $EntidadPersona->departamento_domicilio);
                    self::generateLineTextForDetailSpace($MYPDF, strtoupper('provincia domicilio'), $EntidadPersona->provincia_domicilio);
                    $MYPDF->Ln(4);
                    self::generateLineTextForDetailSpace($MYPDF, strtoupper('distrito domicilio'), $EntidadPersona->distrito_domicilio);
                    $MYPDF->Ln(5);
                    self::generateLineTextSpace($MYPDF, strtoupper('lugar domicilio'), $EntidadPersona->lugar_domicilio);
                    // self::generateLineTextForDetailSpace($MYPDF, 'foto', $EntidadPersona->foto);
                    // self::generateLineTextForDetailSpace($MYPDF, 'firma', $EntidadPersona->firma);
                    $MYPDF->Ln(5);
                }elseif($value->entidads_id == 3){
                    $EntidadVehiculos = EntidadVehiculos::where('id',$value->codigo_relacion)->first();
                    self::generateLineTextForDetailSpace($MYPDF, strtoupper('placa'), $EntidadVehiculos->placa);
                    self::generateLineTextForDetailSpace($MYPDF, strtoupper('serie'), $EntidadVehiculos->serie);
                    $MYPDF->Ln(4);
                    self::generateLineTextForDetailSpace($MYPDF, strtoupper('Nro. de motor'), strtoupper($EntidadVehiculos->numero_motor));
                    self::generateLineTextForDetailSpace($MYPDF, strtoupper('color'), $EntidadVehiculos->color);
                    $MYPDF->Ln(4);
                    self::generateLineTextForDetailSpace($MYPDF, strtoupper('marca'), strtoupper($EntidadVehiculos->marca));
                    self::generateLineTextForDetailSpace($MYPDF, strtoupper('modelo'), $EntidadVehiculos->modelo);
                    $MYPDF->Ln(4);
                    self::generateLineTextForDetailSpace($MYPDF, strtoupper('AÑO FABRICACIÓN'), $EntidadVehiculos->ano);
                    self::generateLineTextForDetailSpace($MYPDF, strtoupper('tipo de carrocerÍa'), $EntidadVehiculos->tipo_carroceria);
                    $MYPDF->Ln(4);
                    self::generateLineTextForDetailSpace($MYPDF, strtoupper('vin'), $EntidadVehiculos->vin);
                    self::generateLineTextForDetailSpace($MYPDF, strtoupper('tipo motor'), $EntidadVehiculos->tipo_motor);
                    $MYPDF->Ln(4);
                    self::generateLineTextForDetailSpace($MYPDF, strtoupper('cilindrada motor'), $EntidadVehiculos->cilindrada_motor);
                    self::generateLineTextForDetailSpace($MYPDF, strtoupper('tipo combustible'), $EntidadVehiculos->tipo_combustible);
                    $MYPDF->Ln(4);
                    self::generateLineTextForDetailSpace($MYPDF, strtoupper('tipo transmisiÓn'), $EntidadVehiculos->tipo_transmision);
                    self::generateLineTextForDetailSpace($MYPDF, strtoupper('tipo tracciÓn'), $EntidadVehiculos->tipo_traccion);
                    $MYPDF->Ln(4);
                    self::generateLineTextForDetailSpace($MYPDF, strtoupper('kilometraje'), $EntidadVehiculos->kilometraje);
                    self::generateLineTextForDetailSpace($MYPDF, strtoupper('placa anterior'), $EntidadVehiculos->placaanterior);
                    $MYPDF->Ln(5);
                    self::generateLineTextSpace($MYPDF, strtoupper('estado vehiculo'), $EntidadVehiculos->estado_vehiculo);
                    $MYPDF->Ln(5);
                }elseif($value->entidads_id == 4){
                    $EntidadInmueble = EntidadInmueble::with(['getTipoInmueble'])->where('id',$value->codigo_relacion)->first();
                  
                    self::generateLineTextSpace($MYPDF, ('tipo'), $EntidadInmueble->getTipoInmueble->descripcion); $MYPDF->Ln(1);
                    self::generateLineTextSpace($MYPDF, ('direccion'), $EntidadInmueble->direccion); $MYPDF->Ln(1);
                    self::generateLineTextSpace($MYPDF, ('departamento'), $EntidadInmueble->departamento); $MYPDF->Ln(1);
                    self::generateLineTextSpace($MYPDF, ('provincia'), $EntidadInmueble->provincia); $MYPDF->Ln(1);
                    self::generateLineTextSpace($MYPDF, ('distrito'), $EntidadInmueble->distrito); $MYPDF->Ln(1);
                    self::generateLineTextSpace($MYPDF, ('referencia'), $EntidadInmueble->referencia); $MYPDF->Ln(1);
                    self::generateLineTextSpace($MYPDF, ('color_exterior'), $EntidadInmueble->color_exterior); $MYPDF->Ln(1);
                    self::generateLineTextSpace($MYPDF, ('caract. especiales'), $EntidadInmueble->caracteristicas_especiales); $MYPDF->Ln(1);
                    self::generateLineTextSpace($MYPDF, ('estado conservacion'), $EntidadInmueble->estado_conservacion ?? "SIN ESTADO"); $MYPDF->Ln(1);
                    self::generateLineTextSpace($MYPDF, ('ubigeo'), $EntidadInmueble->ubigeo); $MYPDF->Ln(1);
                    if($EntidadInmueble->latitud){
                        self::generateLineTextSpace($MYPDF, ('latitud'), $EntidadInmueble->latitud); $MYPDF->Ln(1);
                        self::generateLineTextSpace($MYPDF, ('longitud'), $EntidadInmueble->longitud); $MYPDF->Ln(1);
                        self::generateLineTextSpace($MYPDF, ('mapa'), "https://maps.google.com/?q=".$EntidadInmueble->latitud.",".$EntidadInmueble->longitud.""); $MYPDF->Ln(1);
                    }
                    self::generateLineTextSpace($MYPDF, ('observaciones'), $EntidadInmueble->observaciones ?? "SIN OBSERVACIONES");
                    $MYPDF->Ln(5);
                    
                }
                // self::generateLineText($MYPDF,  $value->entidads_id, $value->codigo_relacion);
            }
          
            $MYPDF->Ln(5);
            $MYPDF->SetFont('Arial', 'B', 11);
            $MYPDF->multicell(192, 5, "ACTIVIDADES", 0, 'L');
            $MYPDF->Ln(2);
            foreach ($expe->getNuevaVigilancia as $key => $value) {

                self::generateLineTextForDetailSpace($MYPDF, 'Documento', $value->geTipoDocumentosReferencia->descripcion);
                self::generateLineTextForDetailSpace($MYPDF, 'nro Documento', $value->numeroDocumento);
                $MYPDF->Ln(4);
                self::generateLineTextForDetailSpace($MYPDF, 'fecha Documento', $value->fechaDocumento);
                self::generateLineTextForDetailSpace($MYPDF, 'NOMBRES', $EntidadPersona->nombres);
                $MYPDF->Ln(5);
                self::generateLineTextSpace($MYPDF,  "siglas Documento", $value->siglasDocumento);$MYPDF->Ln(1);
                self::generateLineTextSpace($MYPDF,  "asunto", $value->asunto);$MYPDF->Ln(1);
                self::generateLineTextSpace($MYPDF,  "responde a", $value->respondea);$MYPDF->Ln(1);
                self::generateLineTextSpace($MYPDF,  "evaluacion", $value->evaluacion);$MYPDF->Ln(1);
                self::generateLineTextSpace($MYPDF,  "conclusiones", $value->conclusiones);$MYPDF->Ln(1);
                self::generateLineTextSpace($MYPDF,  "archivo", $value->archivo);$MYPDF->Ln(1);
                $MYPDF->Ln(5);
            }


            $MYPDF->Output(('ReporteDeExpediente.pdf'), 'I');
            //$MYPDF->Output('D', "ReporteReferenciaSerpol.pdf", true);
            exit();
        } catch (\Exception $e) {
            dd($e);
        }
    }
    public static function reporte_infozona($idexpe){
        $INFO = AuditoriaController::audita_usuario(Auth::user()->id, "DESCARGA REPORTE", "INFOZONA", $idexpe);
        $ContenidoTitulo = utf8_decode('CÓDIGO DE SEGURIDAD NRO.' . $INFO->id . ' | CÓDIGO USUARIO NRO.' . Auth::user()->id . ' | FECHA DESCARGA ' . $INFO->created_at);
        $MYPDF = new PDFF($ContenidoTitulo);
        $MYPDF->AliasNbPages();
        $MYPDF->AddPage();
        $MYPDF->image('images/sivipol/BannerSivipol.png', 75, 10, 60);
        //$MYPDF->image('images/qrvalidar.png', 172.5, 31.5, 31);
        $MYPDF->Ln(10);
        // $MYPDF->SetFont('Arial', 'B', 18);
        // $MYPDF->multicell(192, -2, "REPORTE DE USUARIO", 0, 'C');
        $MYPDF->Ln(10);
        $MYPDF->SetFont('Arial', 'B', 11);
        $MYPDF->SetTextColor(89, 90, 90);
        $MYPDF->multicell(192, -2, "REPORTE DE INFOZONA", 0, 'C');
        $MYPDF->Ln(6);
        $MYPDF->SetFont('Arial', 'B', 11);
        $MYPDF->multicell(192, 5, "DETALLE", 0, 'L');
        $MYPDF->Ln(2);
        $EntidadInmueble = EntidadInmueble::with(['getTipoInmueble'])->where('id',$idexpe)->first();
        self::generateLineTextSpace($MYPDF, strtoupper('tipo'), strtoupper($EntidadInmueble->getTipoInmueble->descripcion)); $MYPDF->Ln(1);
        self::generateLineTextSpace($MYPDF, strtoupper('direccion'), $EntidadInmueble->direccion); $MYPDF->Ln(1);
        self::generateLineTextSpace($MYPDF, strtoupper('departamento'), $EntidadInmueble->departamento); $MYPDF->Ln(1);
        self::generateLineTextSpace($MYPDF, strtoupper('provincia'), $EntidadInmueble->provincia); $MYPDF->Ln(1);
        self::generateLineTextSpace($MYPDF, strtoupper('distrito'), $EntidadInmueble->distrito); $MYPDF->Ln(1);
        self::generateLineTextSpace($MYPDF, strtoupper('referencia'), $EntidadInmueble->referencia); $MYPDF->Ln(1);
        self::generateLineTextSpace($MYPDF, strtoupper('color_exterior'), $EntidadInmueble->color_exterior); $MYPDF->Ln(1);
        self::generateLineTextSpace($MYPDF, strtoupper('caract. especiales'), $EntidadInmueble->caracteristicas_especiales); $MYPDF->Ln(1);
        self::generateLineTextSpace($MYPDF, strtoupper('estado conservacion'), $EntidadInmueble->estado_conservacion); $MYPDF->Ln(1);
        self::generateLineTextSpace($MYPDF, strtoupper('ubigeo'), $EntidadInmueble->ubigeo); $MYPDF->Ln(1);
        self::generateLineTextSpace($MYPDF, strtoupper('latitud'), $EntidadInmueble->latitud); $MYPDF->Ln(1);
        self::generateLineTextSpace($MYPDF, strtoupper('longitud'), $EntidadInmueble->longitud); $MYPDF->Ln(1);
        self::generateLineTextSpace($MYPDF, strtoupper('mapa'), "https://maps.google.com/?q=".$EntidadInmueble->latitud.",".$EntidadInmueble->longitud.""); $MYPDF->Ln(1);
        self::generateLineTextSpace($MYPDF, strtoupper('observaciones'), $EntidadInmueble->observaciones);
        $MYPDF->Ln(5);

        $MYPDF->SetFont('Arial', 'B', 11);
        $MYPDF->multicell(192, 5, "PROPIETARIOS", 0, 'L');
        $MYPDF->Ln(2);

        $MYPDF->SetFont('Arial', 'B', 11);
        $MYPDF->multicell(192, 5, "EXPEDIENTES RELACIONADOS", 0, 'L');
        $MYPDF->Ln(2);

        $MYPDF->Output(('ReporteInfozona.pdf'), 'I');

    }
    public static function reporte_infosombra($idexpe){
        $INFO = AuditoriaController::audita_usuario(Auth::user()->id, "DESCARGA REPORTE", "INFOSOMBRA", $idexpe);
        $ContenidoTitulo = utf8_decode('CÓDIGO DE SEGURIDAD NRO.' . $INFO->id . ' | CÓDIGO USUARIO NRO.' . Auth::user()->id . ' | FECHA DESCARGA ' . $INFO->created_at);
        $MYPDF = new PDFF($ContenidoTitulo);
        $MYPDF->AliasNbPages();
        $MYPDF->AddPage();
        $MYPDF->image('images/sivipol/BannerSivipol.png', 75, 10, 60);
        //$MYPDF->image('images/qrvalidar.png', 172.5, 31.5, 31);
        $MYPDF->Ln(10);
        // $MYPDF->SetFont('Arial', 'B', 18);
        // $MYPDF->multicell(192, -2, "REPORTE DE USUARIO", 0, 'C');
        $MYPDF->Ln(10);
        $MYPDF->SetFont('Arial', 'B', 11);
        $MYPDF->SetTextColor(89, 90, 90);
        $MYPDF->multicell(192, -2, "REPORTE DE INFOSOMBRA", 0, 'C');
        $MYPDF->Ln(6);


        $MYPDF->SetFont('Arial', 'B', 11);
        $MYPDF->multicell(192, 5, "DETALLE", 0, 'L');
        $MYPDF->Ln(2);
        $EntidadPersona = EntidadPersona::with(['getTipoNacionalidad', 'getTipoDocumentoIdentidad'])->where('id',$idexpe)->first();
        self::generateLineTextForDetailSpace($MYPDF, 'NACIONALIDAD', $EntidadPersona->getTipoNacionalidad->descripcion);
        self::generateLineTextForDetailSpace($MYPDF, 'TIPO DOCUMENTO', $EntidadPersona->getTipoDocumentoIdentidad->descripcion);
        $MYPDF->Ln(4);
        self::generateLineTextForDetailSpace($MYPDF, 'DOCUMENTO', strtoupper($EntidadPersona->documento));
        self::generateLineTextForDetailSpace($MYPDF, 'NOMBRES', $EntidadPersona->nombres);
        $MYPDF->Ln(4);
        self::generateLineTextForDetailSpace($MYPDF, 'APELLIDO PATERNO', strtoupper($EntidadPersona->paterno));
        self::generateLineTextForDetailSpace($MYPDF, 'APELLIDO MATERNO', $EntidadPersona->materno);
        $MYPDF->Ln(4);
        self::generateLineTextForDetailSpace($MYPDF, 'ESTADO CIVIL', $EntidadPersona->estado_civil);
        self::generateLineTextForDetailSpace($MYPDF, 'SEXO', $EntidadPersona->sexo);
        $MYPDF->Ln(4);
        self::generateLineTextForDetailSpace($MYPDF, 'FECHA NAC.', $EntidadPersona->fecha_nacimiento);
        if($EntidadPersona->nacionalidad_id == 1){
            self::generateLineTextForDetailSpace($MYPDF, strtoupper('ubigeo nacimiento'), $EntidadPersona->ubigeo_nacimiento);
            $MYPDF->Ln(4);
            self::generateLineTextForDetailSpace($MYPDF, strtoupper('depart. nacimiento'), $EntidadPersona->departamento_nacimiento);
            self::generateLineTextForDetailSpace($MYPDF, strtoupper('provincia nacimiento'), $EntidadPersona->provincia_nacimiento);
            $MYPDF->Ln(4);
            self::generateLineTextForDetailSpace($MYPDF, strtoupper('distrito nacimiento'), $EntidadPersona->distrito_nacimiento);
            $MYPDF->Ln(4);
        }else{
            $MYPDF->Ln(5);
            self::generateLineTextSpace($MYPDF, strtoupper('lugar nacimiento'), $EntidadPersona->lugar_nacimiento);
        }
        self::generateLineTextForDetailSpace($MYPDF, strtoupper('ubigeo domicilio'), $EntidadPersona->ubigeo_domicilio);
        $MYPDF->Ln(4);
        self::generateLineTextForDetailSpace($MYPDF, strtoupper('depart. domicilio'), $EntidadPersona->departamento_domicilio);
        self::generateLineTextForDetailSpace($MYPDF, strtoupper('provincia domicilio'), $EntidadPersona->provincia_domicilio);
        $MYPDF->Ln(4);
        self::generateLineTextForDetailSpace($MYPDF, strtoupper('distrito domicilio'), $EntidadPersona->distrito_domicilio);
        $MYPDF->Ln(5);
        self::generateLineTextSpace($MYPDF, strtoupper('lugar domicilio'), $EntidadPersona->lugar_domicilio);
        // self::generateLineTextForDetailSpace($MYPDF, 'foto', $EntidadPersona->foto);
        // self::generateLineTextForDetailSpace($MYPDF, 'firma', $EntidadPersona->firma);
        $MYPDF->Ln(5);

        $MYPDF->SetFont('Arial', 'B', 11);
        $MYPDF->multicell(192, 5, "REQUISITORIA", 0, 'L');
        $MYPDF->Ln(2);

        $MYPDF->SetFont('Arial', 'B', 11);
        $MYPDF->multicell(192, 5, "ANTECEDENTES", 0, 'L');
        $MYPDF->Ln(2);

        $MYPDF->SetFont('Arial', 'B', 11);
        $MYPDF->multicell(192, 5, "DENUNCIAS", 0, 'L');
        $MYPDF->Ln(2);

        $MYPDF->SetFont('Arial', 'B', 11);
        $MYPDF->multicell(192, 5, "EXPEDIENTES RELACIONADOS", 0, 'L');
        $MYPDF->Ln(2);


        $MYPDF->Output(('ReporteInfoSombra.pdf'), 'I');
    }
    private static function generateLineTextForDetail($pdf, $tipo, $contenido)
    {
        // if (isset($contenido) && !empty($contenido)) {
        $pdf->SetTextColor(71, 67, 141);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(40, 6, strtoupper(utf8_decode(trim($tipo))), 0, 0, 'L');
        $pdf->Cell(2, 6, ":", 0, 0, 'L');
        $pdf->SetTextColor(89, 90, 90);
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->Cell(40, 6, strtoupper(utf8_decode(trim($contenido))), 0, 0, 'L');
        // $pdf->MultiCell(90, 7, utf8_decode(trim($contenido)), 0, 'C', true);
        // }
    }
    private static function generateLineTextForDetailSpace($pdf, $tipo, $contenido)
    {
        // if (isset($contenido) && !empty($contenido)) {
        $pdf->Cell(5, 6, " ", 0, 0, 'L');
        $pdf->SetTextColor(71, 67, 141);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(40, 6, strtoupper(utf8_decode(trim($tipo))), 0, 0, 'L');
        $pdf->Cell(2, 6, ":", 0, 0, 'L');
        $pdf->SetTextColor(89, 90, 90);
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->Cell(40, 6, strtoupper(utf8_decode(trim($contenido))), 0, 0, 'L');
        // $pdf->MultiCell(90, 7, utf8_decode(trim($contenido)), 0, 'C', true);
        // }
    }
    private static function generateLineText($pdf, $tipo, $contenido)
    {
        // if (isset($contenido) && !empty($contenido)) {
        $pdf->SetTextColor(71, 67, 141);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(40, 4, utf8_decode(trim($tipo)), 0, 0, 'L');
        $pdf->Cell(2, 4, ":", 0, 0, 'L');
        $pdf->SetTextColor(89, 90, 90);
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->multicell(140, 4, utf8_decode(trim($contenido)), 0, 'j');
        // }
    }
    private static function generateLineTextSpace($pdf, $tipo, $contenido)
    {
        // if (isset($contenido) && !empty($contenido)) {
        $pdf->Cell(5, 6, " ", 0, 0, 'L');
        $pdf->SetTextColor(71, 67, 141);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(40, 4, strtoupper(utf8_decode(trim($tipo))), 0, 0, 'L');
        $pdf->Cell(2, 4, ":", 0, 0, 'L');
        $pdf->SetTextColor(89, 90, 90);
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->multicell(140, 3, strtoupper(utf8_decode(trim($contenido))), 0, 'j');
        // }
    }
    public static function importfotos()
    {

        $filePath = public_path('assets/personas.csv'); // Ajusta el nombre del archivo según tu estructura

        if (file_exists($filePath)) {
            $csvFile = fopen($filePath, 'r');

            // Lee la primera fila como encabezado
            $header = fgetcsv($csvFile);

            while (($row = fgetcsv($csvFile)) !== false) {
                // Combina el encabezado con los datos de la fila
                $data = array_combine($header, $row);

                // Inserta los datos en la base de datos
                EntidadPersona::where('documento', $data['dni'])->update([
                    'foto' => $data['foto'],
                    'firma' => $data['firma'],
                    // Agrega otros campos según sea necesario
                ]);
            }
            fclose($csvFile);
        } else {
        }
    }
}
