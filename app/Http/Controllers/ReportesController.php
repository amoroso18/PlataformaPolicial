<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;

use App\Models\TipoDelitos;
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

use App\Models\EntidadRequisitoriaPeruano;
use App\Models\EntidadRequisitoriaVehiculo;
use App\Models\EntidadDenuncias;
use App\Models\EntidadAntecedente;

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
                if ($USUARIO->count() == 0) {
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
    public static function expediente_reporte($idexpe)
    {
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
            $MYPDF->SetAutoPageBreak(true, 30);
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
            $expeREFERENCIA = DisposicionFiscalReferencia::where("df_id", $expe->id)->get();
            $expeOBJETOVV = DisposicionFiscalEntidadVigilancia::where("df_id", $expe->id)->orderBy('entidads_id', 'asc')->get();
            $expeTIPOVV = DisposicionFiscalTipoVideoVigilancia::where("df_id", $expe->id)->get();
            $expeDelitos =  DisposicionFiscalDelitos::where("df_id", $expe->id)->get();
            $MYPDF->Ln(4);

            $MYPDF->multicell(192, 5, "1) DETALLE", 0, 'L');
            $MYPDF->Ln(2);
            self::generateLineTextSpace($MYPDF,  "CASO", strtoupper($expe->caso));
            $MYPDF->Ln(1);
            self::generateLineTextSpace($MYPDF,  "NRO", strtoupper($expe->nro));
            $MYPDF->Ln(1);
            self::generateLineTextSpace($MYPDF,  "FECHA DISPOSICION", $expe->fecha_disposicion);
            $MYPDF->Ln(1);
            self::generateLineTextSpace($MYPDF,  "FECHA DE INICIO", $expe->fecha_inicio);
            $MYPDF->Ln(1);
            self::generateLineTextSpace($MYPDF,  "FECHA DE TERMINO", $expe->fecha_termino);
            $MYPDF->Ln(1);

            self::generateLineTextSpace($MYPDF,  "TIPO DE PLAZO", strtoupper($expe->getPlazo->descripcion));
            $MYPDF->Ln(1);
            self::generateLineTextSpace($MYPDF,  "DIAS DE PLAZO", $expe->plazo);
            $MYPDF->Ln(1);
            if ($expe->getFiscal->dni) {
                self::generateLineTextSpace($MYPDF,  "FISCAL RESPONSABLE", strtoupper("DNI: " . $expe->getFiscal->dni . " | " . $expe->getFiscal->nombres . " " . $expe->getFiscal->paterno . " " . $expe->getFiscal->materno . " | " . $expe->getFiscal->procedencia . " " . $expe->getFiscal->ficalia . " " . $expe->getFiscal->despacho . " " . $expe->getFiscal->ubigeo . " | CORREO: " . $expe->getFiscal->correo . " | CELULAR:" . $expe->getFiscal->celular));
                $MYPDF->Ln(1);
            }
            if ($expe->getFiscalAdjunto->dni) {
                self::generateLineTextSpace($MYPDF,  "FISCAL ASISTENTE", strtoupper("DNI: " . $expe->getFiscalAdjunto->dni . " | " . $expe->getFiscalAdjunto->nombres . " " . $expe->getFiscalAdjunto->paterno . " " . $expe->getFiscalAdjunto->materno . " | " . $expe->getFiscalAdjunto->procedencia . " " . $expe->getFiscalAdjunto->ficalia . " " . $expe->getFiscalAdjunto->despacho . " " . $expe->getFiscalAdjunto->ubigeo . " | CORREO: " . $expe->getFiscalAdjunto->correo . " | CELULAR:" . $expe->getFiscalAdjunto->celular));
                $MYPDF->Ln(1);
            }
            if ($expe->getOficial) {
                self::generateLineTextSpace($MYPDF,  "OFICIAL A CARGO", strtoupper($expe->getOficial->carnet . " | " . $expe->getOficial->getGrado->descripcion . " | " . $expe->getOficial->nombres . " " . $expe->getOficial->paterno . " " . $expe->getOficial->materno));
                $MYPDF->Ln(1);
            }
            self::generateLineTextSpace($MYPDF,  "RESUMEN", strtoupper($expe->resumen));
            $MYPDF->Ln(1);
            self::generateLineTextSpace($MYPDF,  "OBSERVACIONES", strtoupper($expe->observaciones));
            $MYPDF->Ln(1);
            self::generateLineTextSpace($MYPDF,  "SITUACION", strtoupper($expe->getEstado->descripcion));
            $MYPDF->Ln(1);


            $MYPDF->Ln(5);
            $MYPDF->SetFont('Arial', 'B', 11);
            $MYPDF->multicell(192, 5, "2) DELITOS", 0, 'L');
            $MYPDF->Ln(2);
            foreach ($expeDelitos  as $key => $value) {
                $SR = $key + 1;
                $MYPDF->Cell(5, 6, " ", 0, 0, 'L');
                $MYPDF->SetTextColor(89, 90, 90);
                $MYPDF->SetFont('Helvetica', '', 10);
                $MYPDF->multicell(183, 4, strtoupper(utf8_decode(trim("2." . $SR . ".- " . $value->geTipoDelitos->tipo . " " . $value->geTipoDelitos->subtipo . " - " . $value->geTipoDelitos->modalidad))), 0, 'J');
            }
            $MYPDF->Ln(5);
            $MYPDF->SetFont('Arial', 'B', 11);
            $MYPDF->multicell(192, 5, "3) DOCUMENTOS DIGITALES", 0, 'L');
            $MYPDF->Ln(2);
            foreach ($expeREFERENCIA  as $key => $value) {
                self::generateLineTextSpace($MYPDF,  "TIPO DOC REFERENCIA", $value->geTipoDocumentosReferencia->descripcion);
                $MYPDF->Ln(1);
                self::generateLineTextSpace($MYPDF,  "NRO", $value->nro);
                $MYPDF->Ln(1);
                self::generateLineTextSpace($MYPDF,  "FECHA", $value->fecha_documento);
                $MYPDF->Ln(1);
                self::generateLineTextSpace($MYPDF,  "SIGLAS", $value->siglas);
                $MYPDF->Ln(1);
                self::generateLineTextSpace($MYPDF,  "ARCHIVO", $value->pdf);
                $MYPDF->Ln(1);
            }
            $MYPDF->Ln(5);
            $MYPDF->SetFont('Arial', 'B', 11);
            $MYPDF->multicell(192, 5, "4) TIPO DE VIDEOVIGILANCIA", 0, 'L');
            $MYPDF->Ln(2);
            foreach ($expeTIPOVV  as $key => $value) {
                $SR = $key + 1;
                self::generateLineTextSpace($MYPDF,  "4." . $SR . ".- " . $value->geTipoVideovigilancia->descripcion, "");
                $MYPDF->Ln(1);
            }

            $MYPDF->Ln(5);
            $MYPDF->SetFont('Arial', 'B', 11);
            $MYPDF->multicell(192, 5, "5) OBJECTO DE VIDEOVIGILANCIA", 0, 'L');
            $MYPDF->Ln(2);
            foreach ($expeOBJETOVV  as $key => $value) {
                if ($value->entidads_id == 1 || $value->entidads_id == 2) {
                    $EntidadPersona = EntidadPersona::with(['getTipoNacionalidad', 'getTipoDocumentoIdentidad'])->where('id', $value->codigo_relacion)->first();

                    if ($EntidadPersona->foto) {
                        $MYPDF->Ln(1);
                        $x = $MYPDF->GetX() + 6;
                        $y = $MYPDF->GetY();
                        $base64Image = $EntidadPersona->foto; // Tus datos BLOB
                        // Decodifica el BLOB y guarda la imagen en un archivo temporal
                        $imageData = base64_decode($base64Image);
                        $tempImageFile = $EntidadPersona->documento . '_temp_image.jpg'; // Nombre del archivo temporal
                        file_put_contents($routeImagesPathTemp . $tempImageFile, $imageData);
                        chmod($routeImagesPathTemp . $tempImageFile, 0755);
                        // Inserta la imagen en el PDF
                        $MYPDF->image($routeImagesPathTemp . $tempImageFile, $x, $y, 25, 30);
                        $MYPDF->SetXY($x + 15, $y + 15);
                        $MYPDF->Ln(15);
                        // Elimina el archivo temporal
                        unlink($routeImagesPathTemp . $tempImageFile);
                    }
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
                    if ($value->entidads_id == 1) {
                        self::generateLineTextForDetailSpace($MYPDF, strtoupper('ubigeo nacimiento'), $EntidadPersona->ubigeo_nacimiento);
                        $MYPDF->Ln(4);
                        self::generateLineTextForDetailSpace($MYPDF, strtoupper('depart. nacimiento'), $EntidadPersona->departamento_nacimiento);
                        self::generateLineTextForDetailSpace($MYPDF, strtoupper('provincia nacimiento'), $EntidadPersona->provincia_nacimiento);
                        $MYPDF->Ln(4);
                        self::generateLineTextForDetailSpace($MYPDF, strtoupper('distrito nacimiento'), $EntidadPersona->distrito_nacimiento);
                        $MYPDF->Ln(5);
                    } else {
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
                    $MYPDF->Ln(3);

                    if($value->entidads_id == 1 && !empty($EntidadPersona->documento)){
                        $data_rq = EntidadRequisitoriaPeruano::where([['nrodocumento',$EntidadPersona->documento]])->get();
                        if(isset($data_rq) && count($data_rq) > 0){
                            $MYPDF->Ln(2);
                            self::generateLineTextSpace($MYPDF, "REQUISITORIA", "", 5);
                            $MYPDF->Ln(2);
                            // $data_rq = EntidadRequisitoriaPeruano::inRandomOrder()->limit(3)->get();
                            foreach ($data_rq as $key => $value) {
                                $NUM = $key + 1;
                                self::generateLineTextSpace($MYPDF,   $NUM . ") CODIGO " . "NRO. 000000" . $value->id, "", 5);
                                self::generateLineTextSpace($MYPDF,  "fecha", $value->fecha, 9);
                                self::generateLineTextSpace($MYPDF,  "tipo", $value->tipo, 9);
                                self::generateLineTextSpace($MYPDF,  "delito", $value->delitos, 9);
                                self::generateLineTextSpace($MYPDF,  "situacion", $value->situacion, 9);
                                self::generateLineTextSpace($MYPDF,  "autoridad judicial", $value->autoridadjudicial, 9);
                                self::generateLineTextSpace($MYPDF,  "documento", $value->documento, 9);
                                self::generateLineTextSpace($MYPDF,  "fecha documento", $value->fechadocumento, 9);
                                $MYPDF->Ln(2);
                            }
                        }
                 
                        $data_ant = EntidadAntecedente::where([['nrodocumento',$EntidadPersona->documento]])->get();
                        if(isset($data_ant) &&  count($data_ant) > 0){
                            $MYPDF->Ln(2);
                            self::generateLineTextSpace($MYPDF, "ANTECEDENTES", "", 5);
                            $MYPDF->Ln(2);
                            // $data_ant = EntidadAntecedente::inRandomOrder()->limit(3)->get();
                            foreach ($data_ant as $key => $value) {
                                $NUM = $key + 1;
                                self::generateLineTextSpace($MYPDF,   $NUM . ") CODIGO " . "NRO. 000000" . $value->id, "", 5);
                                self::generateLineTextSpace($MYPDF,  "fecha", $value->fecha, 9);
                                self::generateLineTextSpace($MYPDF,  "nro documento", $value->nrodocumento, 9);
                                self::generateLineTextSpace($MYPDF,  "delito", $value->delitos, 9);
                                self::generateLineTextSpace($MYPDF,  "situacion", $value->situacion, 9);
                                self::generateLineTextSpace($MYPDF,  "autoridad judicial", $value->autoridadjudicial, 9);
                                self::generateLineTextSpace($MYPDF,  "documento", $value->documento, 9);
                                self::generateLineTextSpace($MYPDF,  "fecha documento", $value->fechadocumento, 9);
                                $MYPDF->Ln(2);
                            }
                        }

                        $data_den = EntidadDenuncias::where([['nrodocumento',$EntidadPersona->documento]])->get();
                        if(isset($data_den) && count($data_den) > 0){
                            $MYPDF->Ln(2);
                            self::generateLineTextSpace($MYPDF, "DENUNCIAS", "", 5);
                            $MYPDF->Ln(2);
                             // $data_den = EntidadDenuncias::inRandomOrder()->limit(3)->get();
                             foreach ($data_den as $key => $value) {
                                $NUM = $key + 1;
                                self::generateLineTextSpace($MYPDF,   $NUM . ") CODIGO " . "NRO. 000000" . $value->id, "", 5);
                                self::generateLineTextSpace($MYPDF,  "fecha", $value->fecha_denuncia, 9);
                                self::generateLineTextSpace($MYPDF,  "comisaria", $value->comisaria, 9);
                                self::generateLineTextSpace($MYPDF,  "nro documento", $value->nrodocumento, 9);
                                self::generateLineTextSpace($MYPDF,  "condicion", $value->condicion, 9);
                                self::generateLineTextSpace($MYPDF,  "motivo", $value->motivo, 9);
                                self::generateLineTextSpace($MYPDF,  "contenido judicial", $value->contenido, 9);
                                $MYPDF->Ln(2);
                            }
                        }
                    }
                } elseif ($value->entidads_id == 3) {
                    $EntidadVehiculos = EntidadVehiculos::where('id', $value->codigo_relacion)->first();
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
                    $MYPDF->Ln(3);
                } elseif ($value->entidads_id == 4) {
                    $EntidadInmueble = EntidadInmueble::with(['getTipoInmueble'])->where('id', $value->codigo_relacion)->first();

                    self::generateLineTextSpace($MYPDF, ('tipo'), $EntidadInmueble->getTipoInmueble->descripcion);
                    $MYPDF->Ln(1);
                    self::generateLineTextSpace($MYPDF, ('direccion'), $EntidadInmueble->direccion);
                    $MYPDF->Ln(1);
                    self::generateLineTextSpace($MYPDF, ('departamento'), $EntidadInmueble->departamento);
                    $MYPDF->Ln(1);
                    self::generateLineTextSpace($MYPDF, ('provincia'), $EntidadInmueble->provincia);
                    $MYPDF->Ln(1);
                    self::generateLineTextSpace($MYPDF, ('distrito'), $EntidadInmueble->distrito);
                    $MYPDF->Ln(1);
                    self::generateLineTextSpace($MYPDF, ('referencia'), $EntidadInmueble->referencia);
                    $MYPDF->Ln(1);
                    self::generateLineTextSpace($MYPDF, ('color_exterior'), $EntidadInmueble->color_exterior);
                    $MYPDF->Ln(1);
                    self::generateLineTextSpace($MYPDF, ('caract. especiales'), $EntidadInmueble->caracteristicas_especiales);
                    $MYPDF->Ln(1);
                    self::generateLineTextSpace($MYPDF, ('estado conservacion'), $EntidadInmueble->estado_conservacion ?? "SIN ESTADO");
                    $MYPDF->Ln(1);
                    self::generateLineTextSpace($MYPDF, ('ubigeo'), $EntidadInmueble->ubigeo);
                    $MYPDF->Ln(1);
                    if ($EntidadInmueble->latitud) {
                        self::generateLineTextSpace($MYPDF, ('latitud'), $EntidadInmueble->latitud);
                        $MYPDF->Ln(1);
                        self::generateLineTextSpace($MYPDF, ('longitud'), $EntidadInmueble->longitud);
                        $MYPDF->Ln(1);
                        self::generateLineTextSpace($MYPDF, ('mapa'), "https://maps.google.com/?q=" . $EntidadInmueble->latitud . "," . $EntidadInmueble->longitud . "");
                        $MYPDF->Ln(1);
                    }
                    self::generateLineTextSpace($MYPDF, ('observaciones'), $EntidadInmueble->observaciones ?? "SIN OBSERVACIONES");
                    $MYPDF->Ln(3);

                    $data_VV = DisposicionFiscalNuevaVigilanciaEntidad::where([['codigo_relacion',$EntidadInmueble->id],['entidads_id',4]])->get();
                    foreach ($data_VV  as $key => $value) {
                        $data_VVA = DisposicionFiscalNuevaVigilanciaArchivo::where([['dfnve_id',$value->id],['ta_id',1]])->limit(3)->get();
                        $MYPDF->Ln(1);
                        $x = $MYPDF->GetX() + 6;
                        foreach ($data_VVA as $key2 => $value2) {
                                if ($value2->archivo) {
                                    $y = $MYPDF->GetY();    
                                    $MYPDF->image(public_path('files/').$value2->archivo, $x, $y, 55, 45);
                                    $x = $x+62;
                                    // $MYPDF->SetXY($x + 15, $y + 15);
                                }
                        }
                        if(count($data_VVA)> 0){
                            $MYPDF->SetXY($x + 20, $y + 20);
                            $MYPDF->Ln(30);
                        }
                    }


                }

               
                $data_VV = DisposicionFiscalNuevaVigilanciaEntidad::where([['codigo_relacion',$value->codigo_relacion],['entidads_id',$value->entidads_id]])->get();
                  // $data_VV = DisposicionFiscalNuevaVigilanciaEntidad::inRandomOrder()->limit(3)->get();
                // dd($data_VV );
                if($data_VV  && count($data_VV ) > 0){
                    $MYPDF->Ln(2);
                    self::generateLineTextSpace($MYPDF, "eXPEDIENTES RELACIONADOS", "", 5);
                    $MYPDF->Ln(2);
                    foreach ($data_VV as $key => $value) {
                        $data_vva = DisposicionFiscalNuevaVigilanciaActividad::where([['id', $value->dfnva_id]])->get();
                        // $data_vva = DisposicionFiscalNuevaVigilanciaActividad::inRandomOrder()->limit(1)->get();
                        foreach ($data_vva as $key2 => $value2) {
                            $data_vvna = DisposicionFiscalNuevaVigilancia::where([['id', $value2->dfnv_id]])->get();
                            // $data_vvna = DisposicionFiscalNuevaVigilancia::inRandomOrder()->limit(1)->get();
                            foreach ($data_vvna as $key3 => $value3) {
                                $data_df = DispocicionFiscal::where([['id', $value3->df_id]])->get();
                                // $data_df = DispocicionFiscal::inRandomOrder()->limit(1)->get();
                                foreach ($data_df as $key4 => $value4) {
                                    $NUM = $key + 1;
                                    self::generateLineTextSpace($MYPDF,   $NUM . ") CODIGO " . "NRO. 000000" . $value4->id, "", 5);
                                    self::generateLineTextSpace($MYPDF,  "caso", $value4->caso, 9);
                                    self::generateLineTextSpace($MYPDF,  "nro", $value4->nro, 9);
                                    self::generateLineTextSpace($MYPDF,  "fecha disposicion", $value4->fecha_disposicion, 9);
                                    self::generateLineTextSpace($MYPDF,  "resumen", $value4->resumen, 9);
                                    self::generateLineTextSpace($MYPDF,  "plazo", $value4->plazo, 9);
                                    self::generateLineTextSpace($MYPDF,  "fecha inicio", $value4->fecha_inicio, 9);
                                    self::generateLineTextSpace($MYPDF,  "fecha termino", $value4->fecha_termino, 9);
                                    self::generateLineTextSpace($MYPDF,  "Estado", $value4->getEstado->descripcion, 9);
    
                                    if($value4->fecha_inicio && $value4->fecha_termino && $value4->estado_id != 2){
                                        $fecha_hoy = new \DateTime();
                                        $fecha_inicio = \DateTime::createFromFormat('Y-m-d', $value4->fecha_inicio);
                                        $fecha_fin = \DateTime::createFromFormat('Y-m-d', $value4->fecha_termino);
                                        $interval = $fecha_hoy->diff($fecha_fin);
                                        $dias_faltantes = $interval->days;
                                        $caduco = $fecha_hoy > $fecha_fin;
                                        if ($fecha_hoy->format('Y-m-d') === $fecha_fin->format('Y-m-d')) {
                                            self::generateLineTextSpace($MYPDF,  "Situación", "Hoy vence el plazo", 9);
                                        } else if ($fecha_hoy > $fecha_fin) {
                                            self::generateLineTextSpace($MYPDF,  "Situación", "La fecha ya caducó", 9);
                                        } else {
                                            self::generateLineTextSpace($MYPDF,  "Situación", "Días faltantes: " . $dias_faltantes, 9);
                                        }
                                        $MYPDF->Ln(2);
                                    }else if($value4->estado_id == 2){
                                        self::generateLineTextSpace($MYPDF,  "Situación", "CULMINADO", 9);
                                        $MYPDF->Ln(2);
                                    }else{
                                        self::generateLineTextSpace($MYPDF,  "Situación", "Error de información", 9);
                                        $MYPDF->Ln(2);
                                    }
                                }
                            }
                        }
                    }
                }

                $MYPDF->Cell(5, 6, " ", 0, 0, 'L');
                $MYPDF->multicell(189, 4,  str_repeat("-", 153), 0, 'j');
                $MYPDF->Ln(1);
            }

            $MYPDF->Ln(5);
            $MYPDF->SetFont('Arial', 'B', 11);
            $MYPDF->multicell(192, 5, "6) HISTORIAL DE ACTIVIDADES", 0, 'L');
            $MYPDF->Ln(2);
            foreach ($expe->getNuevaVigilancia as $key => $value) {

                $SR = $key + 1;
                self::generateLineTextSpace($MYPDF,  "6." . $SR . ".- " . $value->fechaDocumento, "", 3);
                self::generateLineTextForDetailSpace($MYPDF, 'Documento', $value->geTipoDocumentosReferencia->descripcion, 10);
                self::generateLineTextForDetailSpace($MYPDF, 'nro Documento', $value->numeroDocumento);
                $MYPDF->Ln(5);
                self::generateLineTextSpace($MYPDF,  "siglas Documento", $value->siglasDocumento, 10);
                self::generateLineTextSpace($MYPDF,  "asunto", $value->asunto, 10);
                self::generateLineTextSpace($MYPDF,  "responde a", $value->respondea, 10);
                self::generateLineTextSpace($MYPDF,  "evaluacion", $value->evaluacion, 10);
                self::generateLineTextSpace($MYPDF,  "conclusiones", $value->conclusiones, 10);
                self::generateLineTextSpace($MYPDF,  "archivo", $value->archivo, 10);
                foreach ($value->getNuevaVigilanciaActividad as $key2 => $value2) {
                    $SR2 = $key2 + 1;
                    $MYPDF->Ln(1);
                    // self::generateLineTextSpace($MYPDF,  "6.".$SR.".".$SR2.".- "."Fecha/hora", $value2->fechahora,15);
                    self::generateLineTextSpace($MYPDF,  "6." . $SR . "." . $SR2 . " " . $value2->fechahora, "", 10);
                    $MYPDF->Ln(1);
                    foreach ($value2->getNuevaVigilanciaEntidad as $key3 => $value3) {
                        $SR3 = $key3 + 1;
                        $MYPDF->Ln(1);
                        self::generateLineTextSpace($MYPDF,  "6." . $SR . "." . $SR2 . "." . $SR3 . ".- " . $value3->getTipoEntidad->descripcion, "", 20);
                        $MYPDF->Ln(1);
                        if ($value3->entidads_id == 1 || $value3->entidads_id == 2) {
                            $EntidadPersona = EntidadPersona::with(['getTipoNacionalidad', 'getTipoDocumentoIdentidad'])->where('id', $value3->codigo_relacion)->first();

                            if ($EntidadPersona->foto) {
                                $MYPDF->Ln(1);
                                $x = $MYPDF->GetX() + 34;
                                $y = $MYPDF->GetY();
                                $base64Image = $EntidadPersona->foto; // Tus datos BLOB
                                // Decodifica el BLOB y guarda la imagen en un archivo temporal
                                $imageData = base64_decode($base64Image);
                                $tempImageFile = $EntidadPersona->documento . '_temp_image.jpg'; // Nombre del archivo temporal
                                file_put_contents($routeImagesPathTemp . $tempImageFile, $imageData);
                                chmod($routeImagesPathTemp . $tempImageFile, 0755);
                                // Inserta la imagen en el PDF
                                $MYPDF->image($routeImagesPathTemp . $tempImageFile, $x, $y, 25, 30);
                                $MYPDF->SetXY($x + 15, $y + 15);
                                $MYPDF->Ln(15);
                                // Elimina el archivo temporal
                                unlink($routeImagesPathTemp . $tempImageFile);
                            }
                            self::generateLineTextForDetailSpace($MYPDF, 'NACIONALIDAD', $EntidadPersona->getTipoNacionalidad->descripcion, 33);
                            self::generateLineTextForDetailSpace($MYPDF, 'TIPO DOCUMENTO', $EntidadPersona->getTipoDocumentoIdentidad->descripcion);
                            $MYPDF->Ln(4);
                            self::generateLineTextForDetailSpace($MYPDF, 'DOCUMENTO', strtoupper($EntidadPersona->documento), 33);
                            self::generateLineTextForDetailSpace($MYPDF, 'NOMBRES', $EntidadPersona->nombres);
                            $MYPDF->Ln(4);
                            self::generateLineTextForDetailSpace($MYPDF, 'APELLIDO PATERNO', strtoupper($EntidadPersona->paterno), 33);
                            self::generateLineTextForDetailSpace($MYPDF, 'APELLIDO MATERNO', $EntidadPersona->materno);
                            $MYPDF->Ln(4);
                            self::generateLineTextForDetailSpace($MYPDF, 'ESTADO CIVIL', $EntidadPersona->estado_civil, 33);
                            self::generateLineTextForDetailSpace($MYPDF, 'SEXO', $EntidadPersona->sexo);
                            $MYPDF->Ln(4);
                            self::generateLineTextForDetailSpace($MYPDF, 'FECHA NAC.', $EntidadPersona->fecha_nacimiento, 33);
                            if ($value3->entidads_id == 1) {
                                self::generateLineTextForDetailSpace($MYPDF, strtoupper('ubigeo nacimiento'), $EntidadPersona->ubigeo_nacimiento);
                                $MYPDF->Ln(4);
                                self::generateLineTextForDetailSpace($MYPDF, strtoupper('depart. nacimiento'), $EntidadPersona->departamento_nacimiento, 33);
                                self::generateLineTextForDetailSpace($MYPDF, strtoupper('provincia nacimiento'), $EntidadPersona->provincia_nacimiento);
                                $MYPDF->Ln(4);
                                self::generateLineTextForDetailSpace($MYPDF, strtoupper('distrito nacimiento'), $EntidadPersona->distrito_nacimiento, 33);
                                $MYPDF->Ln(5);
                            } else {
                                $MYPDF->Ln(5);
                            }
                            self::generateLineTextSpaceAcitividadSub4($MYPDF, strtoupper('lugar nacimiento'), $EntidadPersona->lugar_nacimiento, 33);
                            self::generateLineTextForDetailSpace($MYPDF, strtoupper('ubigeo domicilio'), $EntidadPersona->ubigeo_domicilio, 33);
                            $MYPDF->Ln(4);
                            self::generateLineTextForDetailSpace($MYPDF, strtoupper('depart. domicilio'), $EntidadPersona->departamento_domicilio, 33);
                            self::generateLineTextForDetailSpace($MYPDF, strtoupper('provincia domicilio'), $EntidadPersona->provincia_domicilio);
                            $MYPDF->Ln(4);
                            self::generateLineTextForDetailSpace($MYPDF, strtoupper('distrito domicilio'), $EntidadPersona->distrito_domicilio, 33);
                            $MYPDF->Ln(5);
                            self::generateLineTextSpaceAcitividadSub4($MYPDF, strtoupper('lugar domicilio'), $EntidadPersona->lugar_domicilio, 33);
                            // self::generateLineTextForDetailSpace($MYPDF, 'foto', $EntidadPersona->foto);
                            // self::generateLineTextForDetailSpace($MYPDF, 'firma', $EntidadPersona->firma);
                            $MYPDF->Ln(3);

                            
                            if($value3->entidads_id == 1 && !empty($EntidadPersona->documento)){
                                $data_rq = EntidadRequisitoriaPeruano::where([['nrodocumento',$EntidadPersona->documento]])->get();
                                if(isset($data_rq) && count($data_rq) > 0){
                                    $MYPDF->Ln(2);
                                    self::generateLineTextSpace($MYPDF, "REQUISITORIA", "", 33);
                                    $MYPDF->Ln(2);
                                    // $data_rq = EntidadRequisitoriaPeruano::inRandomOrder()->limit(3)->get();
                                    foreach ($data_rq as $key_Data => $value_data) {
                                        $NUM = $key_Data + 1;
                                        self::generateLineTextSpace($MYPDF,   $NUM . ") CODIGO " . "NRO. 000000" . $value_data->id, "", 33);
                                        self::generateLineTextSpace($MYPDF,  "delito", $value_data->delitos, 37);
                                        $MYPDF->Ln(2);
                                    }
                                }
                        
                                $data_ant = EntidadAntecedente::where([['nrodocumento',$EntidadPersona->documento]])->get();
                                if(isset($data_ant) &&  count($data_ant) > 0){
                                    $MYPDF->Ln(2);
                                    self::generateLineTextSpace($MYPDF, "ANTECEDENTES", "", 33);
                                    $MYPDF->Ln(2);
                                    // $data_ant = EntidadAntecedente::inRandomOrder()->limit(3)->get();
                                    foreach ($data_ant as  $key_Data => $value_data) {
                                        $NUM = $key_Data + 1;
                                        self::generateLineTextSpace($MYPDF,   $NUM . ") CODIGO " . "NRO. 000000" . $value_data->id, "", 33);
                                        self::generateLineTextSpace($MYPDF,  "fecha", $value_data->fecha, 37);
                                        self::generateLineTextSpace($MYPDF,  "delito", $value_data->delitos, 37);
                                        self::generateLineTextSpace($MYPDF,  "situacion", $value_data->situacion, 37);
                                        $MYPDF->Ln(2);
                                    }
                                }

                                $data_den = EntidadDenuncias::where([['nrodocumento',$EntidadPersona->documento]])->get();
                                if(isset($data_den) && count($data_den) > 0){
                                    $MYPDF->Ln(2);
                                    self::generateLineTextSpace($MYPDF, "DENUNCIAS", "", 33);
                                    $MYPDF->Ln(2);
                                    // $data_den = EntidadDenuncias::inRandomOrder()->limit(3)->get();
                                    foreach ($data_den as $key_Data => $value_data) {
                                        $NUM = $key_Data + 1;
                                        self::generateLineTextSpace($MYPDF,   $NUM . ") CODIGO " . "NRO. 000000" . $value_data->id, "", 37);
                                        self::generateLineTextSpace($MYPDF,  "fecha", $value_data->fecha_denuncia, 37);
                                        self::generateLineTextSpace($MYPDF,  "comisaria", $value_data->comisaria, 37);
                                        self::generateLineTextSpace($MYPDF,  "nro documento", $value_data->nrodocumento, 37);
                                        self::generateLineTextSpace($MYPDF,  "condicion", $value_data->condicion, 37);
                                        $MYPDF->Ln(2);
                                    }
                                }
                            }



                        } elseif ($value3->entidads_id == 3) {
                            $EntidadVehiculos = EntidadVehiculos::where('id', $value3->codigo_relacion)->first();
                            self::generateLineTextForDetailSpace($MYPDF, strtoupper('placa'), $EntidadVehiculos->placa, 33);
                            self::generateLineTextForDetailSpace($MYPDF, strtoupper('serie'), $EntidadVehiculos->serie);
                            $MYPDF->Ln(4);
                            self::generateLineTextForDetailSpace($MYPDF, strtoupper('Nro. de motor'), strtoupper($EntidadVehiculos->numero_motor), 33);
                            self::generateLineTextForDetailSpace($MYPDF, strtoupper('color'), $EntidadVehiculos->color);
                            $MYPDF->Ln(4);
                            self::generateLineTextForDetailSpace($MYPDF, strtoupper('marca'), strtoupper($EntidadVehiculos->marca), 33);
                            self::generateLineTextForDetailSpace($MYPDF, strtoupper('modelo'), $EntidadVehiculos->modelo);
                            $MYPDF->Ln(4);
                            self::generateLineTextForDetailSpace($MYPDF, strtoupper('AÑO FABRICACIÓN'), $EntidadVehiculos->ano, 33);
                            self::generateLineTextForDetailSpace($MYPDF, strtoupper('tipo de carrocerÍa'), $EntidadVehiculos->tipo_carroceria);
                            $MYPDF->Ln(4);
                            self::generateLineTextForDetailSpace($MYPDF, strtoupper('vin'), $EntidadVehiculos->vin, 33);
                            self::generateLineTextForDetailSpace($MYPDF, strtoupper('tipo motor'), $EntidadVehiculos->tipo_motor);
                            $MYPDF->Ln(4);
                            self::generateLineTextForDetailSpace($MYPDF, strtoupper('cilindrada motor'), $EntidadVehiculos->cilindrada_motor, 33);
                            self::generateLineTextForDetailSpace($MYPDF, strtoupper('tipo combustible'), $EntidadVehiculos->tipo_combustible);
                            $MYPDF->Ln(4);
                            self::generateLineTextForDetailSpace($MYPDF, strtoupper('tipo transmisiÓn'), $EntidadVehiculos->tipo_transmision, 33);
                            self::generateLineTextForDetailSpace($MYPDF, strtoupper('tipo tracciÓn'), $EntidadVehiculos->tipo_traccion);
                            $MYPDF->Ln(4);
                            self::generateLineTextForDetailSpace($MYPDF, strtoupper('kilometraje'), $EntidadVehiculos->kilometraje, 33);
                            self::generateLineTextForDetailSpace($MYPDF, strtoupper('placa anterior'), $EntidadVehiculos->placaanterior);
                            $MYPDF->Ln(5);
                            self::generateLineTextSpaceAcitividadSub4($MYPDF, strtoupper('estado vehiculo'), $EntidadVehiculos->estado_vehiculo, 33);
                            $MYPDF->Ln(3);
                        } elseif ($value3->entidads_id == 4) {
                            $EntidadInmueble = EntidadInmueble::with(['getTipoInmueble'])->where('id', $value3->codigo_relacion)->first();

                            self::generateLineTextSpaceAcitividadSub4($MYPDF, ('tipo'), $EntidadInmueble->getTipoInmueble->descripcion, 33);
                            $MYPDF->Ln(1);
                            self::generateLineTextSpaceAcitividadSub4($MYPDF, ('direccion'), $EntidadInmueble->direccion, 33);
                            $MYPDF->Ln(1);
                            self::generateLineTextSpaceAcitividadSub4($MYPDF, ('departamento'), $EntidadInmueble->departamento, 33);
                            $MYPDF->Ln(1);
                            self::generateLineTextSpaceAcitividadSub4($MYPDF, ('provincia'), $EntidadInmueble->provincia, 33);
                            $MYPDF->Ln(1);
                            self::generateLineTextSpaceAcitividadSub4($MYPDF, ('distrito'), $EntidadInmueble->distrito, 33);
                            $MYPDF->Ln(1);
                            self::generateLineTextSpaceAcitividadSub4($MYPDF, ('referencia'), $EntidadInmueble->referencia, 33);
                            $MYPDF->Ln(1);
                            self::generateLineTextSpaceAcitividadSub4($MYPDF, ('color'), $EntidadInmueble->color_exterior, 33);
                            $MYPDF->Ln(1);
                            self::generateLineTextSpaceAcitividadSub4($MYPDF, ('caract. especiales'), $EntidadInmueble->caracteristicas_especiales, 33);
                            $MYPDF->Ln(1);
                            self::generateLineTextSpaceAcitividadSub4($MYPDF, ('estado conservacion'), $EntidadInmueble->estado_conservacion ?? "SIN ESTADO", 33);
                            $MYPDF->Ln(1);
                            self::generateLineTextSpaceAcitividadSub4($MYPDF, ('ubigeo'), $EntidadInmueble->ubigeo, 33);
                            $MYPDF->Ln(1);
                            if ($EntidadInmueble->latitud) {
                                self::generateLineTextSpaceAcitividadSub4($MYPDF, ('latitud'), $EntidadInmueble->latitud, 33);
                                $MYPDF->Ln(1);
                                self::generateLineTextSpaceAcitividadSub4($MYPDF, ('longitud'), $EntidadInmueble->longitud, 33);
                                $MYPDF->Ln(1);
                                self::generateLineTextSpaceAcitividadSub4($MYPDF, ('mapa'), "https://maps.google.com/?q=" . $EntidadInmueble->latitud . "," . $EntidadInmueble->longitud . "", 33);
                                $MYPDF->Ln(1);
                            }
                            self::generateLineTextSpaceAcitividadSub4($MYPDF, ('observaciones'), $EntidadInmueble->observaciones ?? "SIN OBSERVACIONES", 33);
                            $MYPDF->Ln(3);
                        }
                     
                        foreach ($value3->getNuevaVigilanciaArchivo as $key4 => $value4) {
                            if($value4->ta_id == 1){
                                $x = $MYPDF->GetX() + 33;
                                if ($value4->archivo) {
                                    $y = $MYPDF->GetY();    
                                    $MYPDF->image(public_path('files/').$value4->archivo, $x, $y, 40, 40);
                                    $x = $x+62;
                                    $MYPDF->SetXY($x + 20, $y + 20);
                                    $MYPDF->Ln(21);
                                }
                            }else{
                                $SR4 = $key4 + 1;
                                self::generateLineTextSpaceAcitividadSub4($MYPDF, 'ARCHIVO ANEXO ' . $SR4, asset('files') . "/" . $value4->archivo, 33);
                            }
                        }
                        $MYPDF->Ln(4);

                        $data_VV = DisposicionFiscalNuevaVigilanciaEntidad::where([['codigo_relacion',$value3->codigo_relacion],['entidads_id',$value3->entidads_id]])->get();
                        if($data_VV  && count($data_VV ) > 0){
                            $MYPDF->Ln(2);
                            self::generateLineTextSpace($MYPDF, "eXPEDIENTES RELACIONADOS", "", 33);
                            $MYPDF->Ln(2);
                            $NUM_xd = 1;
                            foreach ($data_VV as $key11 => $valuexd) {
                                $data_vva = DisposicionFiscalNuevaVigilanciaActividad::where([['id', $valuexd->dfnva_id]])->get();
                                // $data_vva = DisposicionFiscalNuevaVigilanciaActividad::inRandomOrder()->limit(1)->get();
                                foreach ($data_vva as $key22 => $value2_xd) {
                                    $data_vvna = DisposicionFiscalNuevaVigilancia::where([['id', $value2_xd->dfnv_id]])->get();
                                    // $data_vvna = DisposicionFiscalNuevaVigilancia::inRandomOrder()->limit(1)->get();
                                    foreach ($data_vvna as $key33 => $value3_xd) {
                                        $data_df = DispocicionFiscal::where([['id', $value3_xd->df_id]])->get();
                                        // $data_df = DispocicionFiscal::inRandomOrder()->limit(1)->get();
                                        
                                        foreach ($data_df as $key44 => $value4) {
                                            self::generateLineTextSpace($MYPDF,   $NUM_xd . ") CODIGO " . "NRO. 000000" . $value4->id, "", 33);
                                            self::generateLineTextSpace($MYPDF,  "caso", $value4->caso, 37);
                                            self::generateLineTextSpace($MYPDF,  "nro", $value4->nro, 37);
                                            self::generateLineTextSpace($MYPDF,  "fecha disposicion", $value4->fecha_disposicion, 37);
                                            self::generateLineTextSpace($MYPDF,  "plazo", $value4->plazo, 37);
                                            self::generateLineTextSpace($MYPDF,  "fecha inicio", $value4->fecha_inicio, 37);
                                            self::generateLineTextSpace($MYPDF,  "fecha termino", $value4->fecha_termino, 37);
                                            self::generateLineTextSpace($MYPDF,  "Estado", $value4->getEstado->descripcion, 37);
            
                                            if($value4->fecha_inicio && $value4->fecha_termino && $value4->estado_id != 2){
                                                $fecha_hoy = new \DateTime();
                                                $fecha_inicio = \DateTime::createFromFormat('Y-m-d', $value4->fecha_inicio);
                                                $fecha_fin = \DateTime::createFromFormat('Y-m-d', $value4->fecha_termino);
                                                $interval = $fecha_hoy->diff($fecha_fin);
                                                $dias_faltantes = $interval->days;
                                                $caduco = $fecha_hoy > $fecha_fin;
                                                if ($fecha_hoy->format('Y-m-d') === $fecha_fin->format('Y-m-d')) {
                                                    self::generateLineTextSpace($MYPDF,  "Situación", "Hoy vence el plazo", 37);
                                                } else if ($fecha_hoy > $fecha_fin) {
                                                    self::generateLineTextSpace($MYPDF,  "Situación", "La fecha ya caducó", 37);
                                                } else {
                                                    self::generateLineTextSpace($MYPDF,  "Situación", "Días faltantes: " . $dias_faltantes, 37);
                                                }
                                                $MYPDF->Ln(2);
                                            }else if($value4->estado_id == 2){
                                                self::generateLineTextSpace($MYPDF,  "Situación", "CULMINADO", 37);
                                                $MYPDF->Ln(2);
                                            }else{
                                                self::generateLineTextSpace($MYPDF,  "Situación", "Error de información", 37);
                                                $MYPDF->Ln(2);
                                            }
                                            $NUM_xd++;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                $MYPDF->Ln(1);
                $MYPDF->Cell(5, 6, " ", 0, 0, 'L');
                $MYPDF->multicell(189, 4,  str_repeat("-", 153), 0, 'j');
                $MYPDF->Ln(1);
            }

            $MYPDF->Ln(2);
            $MYPDF->SetFont('Arial', 'B', 11);
            $MYPDF->multicell(192, 5, "7) NOTIFICACIONES", 0, 'L');
            $MYPDF->Ln(2);
            foreach ($expe->getNotificaciones as $key => $value) {
                $NUM = $key + 1;
                // $isExpe = asset('expediente_reporte');
                self::generateLineTextSpace($MYPDF,   "7." . $NUM . ".- " . $value->created_at, $value->titulo . " | " . $value->contenido . " | " . strtoupper($value->getOficial->carnet . " | " . $value->getOficial->getGrado->descripcion . " | " . $value->getOficial->nombres . " " . $value->getOficial->paterno . " " . $value->getOficial->materno . " | " . $value->getOficial->correo), 5);
                $MYPDF->Ln(1);
            }

            if (!empty($expe->getResultado)) {
                $MYPDF->Ln(2);
                $MYPDF->SetFont('Arial', 'B', 11);
                $MYPDF->multicell(192, 5, "8) REPORTE FINAL", 0, 'L');
                $MYPDF->Ln(2);
                foreach ($expe->getResultado as $key => $value) {
                    $SR = $key + 1;
                    self::generateLineTextSpace($MYPDF,  "8." . $SR . ".- " . $value->fecha_documento, "", 3);
                    self::generateLineTextSpace($MYPDF, 'Documento', $value->geTipoDocumentosReferencia->descripcion, 10);
                    self::generateLineTextSpace($MYPDF, 'asunto', $value->asunto, 10);
                    self::generateLineTextSpace($MYPDF, 'resultado Final', $value->resultadoFinal, 10);
                    self::generateLineTextSpace($MYPDF, 'destino', $value->destino, 10);
                    self::generateLineTextSpace($MYPDF, 'archivo', asset('') . "files/" . $value->archivo, 10);
                    $MYPDF->Ln(1);
                    foreach ($value->getResultadoAnexo as $key2 => $value2) {
                        $SR2 = $key2 + 1;
                        self::generateLineTextSpace($MYPDF, '* ARCHIVO ANEXO ' . $SR2, asset('') . "files/" . $value2->archivo, 15);
                    }

                    $MYPDF->Ln(3);
                    // id, df_id, , , , , , , , estado, created_at, updated_at

                }
            }

            $MYPDF->Output(('ReporteDeExpediente.pdf'), 'I');
            exit();
        } catch (\Exception $e) {
            dd($e);
        }
    }
    public static function reporte_infozona($idexpe)
    {
        $imageType = 'jpeg';
        if (App::environment('local')) {
            $routeImagesPathTemp = public_path('temp/');
        } else {
            $routeImagesPathTemp = '/var/www/html/sivipol/temp/';
        }
        $INFO = AuditoriaController::audita_usuario(Auth::user()->id, "DESCARGA REPORTE", "INFOZONA", $idexpe);
        $ContenidoTitulo = utf8_decode('CÓDIGO DE SEGURIDAD NRO.' . $INFO->id . ' | CÓDIGO USUARIO NRO.' . Auth::user()->id . ' | FECHA DESCARGA ' . $INFO->created_at);
        $MYPDF = new PDFF($ContenidoTitulo);
        $MYPDF->AliasNbPages();
        $MYPDF->AddPage();
        $MYPDF->SetAutoPageBreak(true, 35);
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
        $EntidadInmueble = EntidadInmueble::with(['getTipoInmueble'])->where('id', $idexpe)->first();
        self::generateLineTextSpace($MYPDF, strtoupper('tipo'), strtoupper($EntidadInmueble->getTipoInmueble->descripcion));
        $MYPDF->Ln(1);
        self::generateLineTextSpace($MYPDF, strtoupper('direccion'), $EntidadInmueble->direccion);
        $MYPDF->Ln(1);
        self::generateLineTextSpace($MYPDF, strtoupper('departamento'), $EntidadInmueble->departamento);
        $MYPDF->Ln(1);
        self::generateLineTextSpace($MYPDF, strtoupper('provincia'), $EntidadInmueble->provincia);
        $MYPDF->Ln(1);
        self::generateLineTextSpace($MYPDF, strtoupper('distrito'), $EntidadInmueble->distrito);
        $MYPDF->Ln(1);
        self::generateLineTextSpace($MYPDF, strtoupper('referencia'), $EntidadInmueble->referencia);
        $MYPDF->Ln(1);
        self::generateLineTextSpace($MYPDF, strtoupper('color_exterior'), $EntidadInmueble->color_exterior);
        $MYPDF->Ln(1);
        self::generateLineTextSpace($MYPDF, strtoupper('caract. especiales'), $EntidadInmueble->caracteristicas_especiales);
        $MYPDF->Ln(1);
        self::generateLineTextSpace($MYPDF, strtoupper('estado conservacion'), $EntidadInmueble->estado_conservacion);
        $MYPDF->Ln(1);
        if ($EntidadInmueble->ubigeo) {
            self::generateLineTextSpace($MYPDF, strtoupper('ubigeo'), $EntidadInmueble->ubigeo);
            $MYPDF->Ln(1);
        }
        self::generateLineTextSpace($MYPDF, strtoupper('latitud'), $EntidadInmueble->latitud);
        $MYPDF->Ln(1);
        self::generateLineTextSpace($MYPDF, strtoupper('longitud'), $EntidadInmueble->longitud);
        $MYPDF->Ln(1);


        $MYPDF->Cell(5, 6, " ", 0, 0, 'L');
        $MYPDF->SetTextColor(71, 67, 141);
        $MYPDF->SetFont('Helvetica', '', 9);
        $MYPDF->Cell(40, 4, strtoupper(utf8_decode(trim("mapa"))), 0, 0, 'L');
        $MYPDF->Cell(2, 4, ":", 0, 0, 'L');
        $MYPDF->SetTextColor(89, 90, 90);
        $MYPDF->SetFont('Helvetica', '', 10);
        $MYPDF->multicell(140, 4, "https://www.google.com/maps/search/?api=1&query=" . $EntidadInmueble->latitud . "," . $EntidadInmueble->longitud . "&zoom=20", 0, 'J');
        $MYPDF->Ln(1);
        if ($EntidadInmueble->observaciones) {
            self::generateLineTextSpace($MYPDF, strtoupper('observaciones'), $EntidadInmueble->observaciones);
        }

        $data_VV = DisposicionFiscalNuevaVigilanciaEntidad::where([['codigo_relacion',$EntidadInmueble->id],['entidads_id',4]])->get();
        foreach ($data_VV  as $key => $value) {
            $data_VVA = DisposicionFiscalNuevaVigilanciaArchivo::where([['dfnve_id',$value->id],['ta_id',1]])->limit(3)->get();
            $MYPDF->Ln(1);
            $x = $MYPDF->GetX() + 6;
           foreach ($data_VVA as $key2 => $value2) {
                if ($value2->archivo) {
                    $y = $MYPDF->GetY();    
                    $MYPDF->image(public_path('files/').$value2->archivo, $x, $y, 55, 45);
                    $x = $x+62;
                    // $MYPDF->SetXY($x + 15, $y + 15);
                }
           }
           $MYPDF->SetXY($x + 20, $y + 20);
           $MYPDF->Ln(30);
        }

        $MYPDF->Ln(1);
        $MYPDF->SetFont('Arial', 'B', 11);
        $MYPDF->multicell(192, 5, "PROPIETARIOS", 0, 'L');
        $MYPDF->Ln(2);

        $expeOBJETOVV = EntidadPersona::with(['getTipoNacionalidad', 'getTipoDocumentoIdentidad'])->inRandomOrder()->limit(3)->get();
        foreach ($expeOBJETOVV  as $key => $EntidadPersona) {
            if ($EntidadPersona->foto) {
                $MYPDF->Ln(1);
                $x = $MYPDF->GetX() + 6;
                $y = $MYPDF->GetY();
                $base64Image = $EntidadPersona->foto; // Tus datos BLOB
                // Decodifica el BLOB y guarda la imagen en un archivo temporal
                $imageData = base64_decode($base64Image);
                $tempImageFile = $EntidadPersona->documento . '_temp_image.jpg'; // Nombre del archivo temporal
                file_put_contents($routeImagesPathTemp . $tempImageFile, $imageData);
                chmod($routeImagesPathTemp . $tempImageFile, 0755);
                // Inserta la imagen en el PDF
                $MYPDF->image($routeImagesPathTemp . $tempImageFile, $x, $y, 25, 30);
                $MYPDF->SetXY($x + 15, $y + 15);
                $MYPDF->Ln(15);
                // Elimina el archivo temporal
                unlink($routeImagesPathTemp . $tempImageFile);
            }
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
            if ($EntidadPersona->entidads_id == 1) {
                self::generateLineTextForDetailSpace($MYPDF, strtoupper('ubigeo nacimiento'), $EntidadPersona->ubigeo_nacimiento);
                $MYPDF->Ln(4);
                self::generateLineTextForDetailSpace($MYPDF, strtoupper('depart. nacimiento'), $EntidadPersona->departamento_nacimiento);
                self::generateLineTextForDetailSpace($MYPDF, strtoupper('provincia nacimiento'), $EntidadPersona->provincia_nacimiento);
                $MYPDF->Ln(4);
                self::generateLineTextForDetailSpace($MYPDF, strtoupper('distrito nacimiento'), $EntidadPersona->distrito_nacimiento);
                $MYPDF->Ln(5);
            } else {
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
            $MYPDF->Ln(3);
        }


        $MYPDF->SetFont('Arial', 'B', 11);
        $MYPDF->multicell(192, 5, "EXPEDIENTES RELACIONADOS", 0, 'L');
        $MYPDF->Ln(2);


         // $data_VV = DisposicionFiscalNuevaVigilanciaEntidad::where([['codigo_relacion',$EntidadPersona->id]])->get();
        $data_VV = DisposicionFiscalNuevaVigilanciaEntidad::inRandomOrder()->limit(3)->get();
        // dd($data_VV );
        foreach ($data_VV as $key => $value) {
            // $data_vva = DisposicionFiscalNuevaVigilanciaActividad::where([['id', $value->dfnva_id]])->get();
            $data_vva = DisposicionFiscalNuevaVigilanciaActividad::inRandomOrder()->limit(1)->get();
            foreach ($data_vva as $key2 => $value2) {
                // $data_vvna = DisposicionFiscalNuevaVigilancia::where([['id', $value2->dfnv_id]])->get();
                $data_vvna = DisposicionFiscalNuevaVigilancia::inRandomOrder()->limit(1)->get();
                foreach ($data_vvna as $key3 => $value3) {
                    // $data_df = DispocicionFiscal::where([['id', $value3->df_id]])->get();
                    $data_df = DispocicionFiscal::inRandomOrder()->limit(1)->get();
                    foreach ($data_df as $key4 => $value4) {
                        $NUM = $key + 1;
                        self::generateLineTextSpace($MYPDF,   $NUM . ") CODIGO " . "NRO. 000000" . $value4->id, "", 1);
                        self::generateLineTextSpace($MYPDF,  "caso", $value4->caso, 5);
                        self::generateLineTextSpace($MYPDF,  "nro", $value4->nro, 5);
                        self::generateLineTextSpace($MYPDF,  "fecha disposicion", $value4->fecha_disposicion, 5);
                        self::generateLineTextSpace($MYPDF,  "resumen", $value4->resumen, 5);
                        self::generateLineTextSpace($MYPDF,  "plazo", $value4->plazo, 5);
                        self::generateLineTextSpace($MYPDF,  "fecha inicio", $value4->fecha_inicio, 5);
                        self::generateLineTextSpace($MYPDF,  "fecha termino", $value4->fecha_termino, 5);
                        self::generateLineTextSpace($MYPDF,  "Estado", $value4->getEstado->descripcion, 5);

                        $fecha_hoy = new \DateTime();
                        $fecha_inicio = \DateTime::createFromFormat('Y-m-d', $value4->fecha_inicio);
                        $fecha_fin = \DateTime::createFromFormat('Y-m-d', $value4->fecha_termino);
                        // Calcular la diferencia en días entre dos fechas
                        $interval = $fecha_hoy->diff($fecha_fin);
                        $dias_faltantes = $interval->days;

                        // Verificar si una fecha ya caducó
                        $caduco = $fecha_hoy > $fecha_fin;

                        // Verificar si hoy es la fecha de caducidad
                        if ($fecha_hoy->format('Y-m-d') === $fecha_fin->format('Y-m-d')) {
                            self::generateLineTextSpace($MYPDF,  "Situación", "Hoy vence el plazo", 5);
                        } else if ($fecha_hoy > $fecha_fin) {
                            self::generateLineTextSpace($MYPDF,  "Situación", "La fecha ya caducó", 5);
                        } else {
                            self::generateLineTextSpace($MYPDF,  "Situación", "Días faltantes: " . $dias_faltantes, 5);
                        }



                        $MYPDF->Ln(2);
                    }
                }
            }
        }

        $MYPDF->Output(('ReporteInfozona.pdf'), 'I');
    }
    public static function reporte_infosombra($idexpe)
    {
        $imageType = 'jpeg';
        if (App::environment('local')) {
            $routeImagesPathTemp = public_path('temp/');
        } else {
            $routeImagesPathTemp = '/var/www/html/sivipol/temp/';
        }
        $INFO = AuditoriaController::audita_usuario(Auth::user()->id, "DESCARGA REPORTE", "INFOSOMBRA", $idexpe);
        $ContenidoTitulo = utf8_decode('CÓDIGO DE SEGURIDAD NRO.' . $INFO->id . ' | CÓDIGO USUARIO NRO.' . Auth::user()->id . ' | FECHA DESCARGA ' . $INFO->created_at);
        $MYPDF = new PDFF($ContenidoTitulo);
        $MYPDF->AliasNbPages();
        $MYPDF->AddPage();
        $MYPDF->SetAutoPageBreak(true, 35);
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
        $EntidadPersona = EntidadPersona::with(['getTipoNacionalidad', 'getTipoDocumentoIdentidad'])->where('id', $idexpe)->first();
        if ($EntidadPersona->foto) {
            $MYPDF->Ln(1);
            $x = $MYPDF->GetX() + 6;
            $y = $MYPDF->GetY();
            $base64Image = $EntidadPersona->foto; // Tus datos BLOB
            // Decodifica el BLOB y guarda la imagen en un archivo temporal
            $imageData = base64_decode($base64Image);
            $tempImageFile = $EntidadPersona->documento . '_temp_image.jpg'; // Nombre del archivo temporal
            file_put_contents($routeImagesPathTemp . $tempImageFile, $imageData);
            chmod($routeImagesPathTemp . $tempImageFile, 0755);
            // Inserta la imagen en el PDF
            $MYPDF->image($routeImagesPathTemp . $tempImageFile, $x, $y, 45, 45);
            $MYPDF->SetXY($x + 32, $y + 32);
            $MYPDF->Ln(15);
            // Elimina el archivo temporal
            unlink($routeImagesPathTemp . $tempImageFile);
        }
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
        if ($EntidadPersona->nacionalidad_id == 1) {
            self::generateLineTextForDetailSpace($MYPDF, strtoupper('ubigeo nacimiento'), $EntidadPersona->ubigeo_nacimiento);
            $MYPDF->Ln(4);
            self::generateLineTextForDetailSpace($MYPDF, strtoupper('depart. nacimiento'), $EntidadPersona->departamento_nacimiento);
            self::generateLineTextForDetailSpace($MYPDF, strtoupper('provincia nacimiento'), $EntidadPersona->provincia_nacimiento);
            $MYPDF->Ln(4);
            self::generateLineTextForDetailSpace($MYPDF, strtoupper('distrito nacimiento'), $EntidadPersona->distrito_nacimiento);
            $MYPDF->Ln(4);
        } else {
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

        $data_VV = DisposicionFiscalNuevaVigilanciaEntidad::where('codigo_relacion', $EntidadPersona->id)
        ->whereIn('entidads_id', [1, 2])
        ->get();
        foreach ($data_VV  as $key => $value) {
            $data_VVA = DisposicionFiscalNuevaVigilanciaArchivo::where([['dfnve_id',$value->id],['ta_id',1]])->limit(3)->get();
            $MYPDF->Ln(1);
            $x = $MYPDF->GetX() + 6;
           foreach ($data_VVA as $key2 => $value2) {
                if ($value2->archivo) {
                    $y = $MYPDF->GetY();    
                    $MYPDF->image(public_path('files/').$value2->archivo, $x, $y, 55, 45);
                    $x = $x+62;
                    // $MYPDF->SetXY($x + 15, $y + 15);
                }
           }
           $MYPDF->SetXY($x + 20, $y + 20);
           $MYPDF->Ln(30);
        }

        $MYPDF->Ln(2);
        $MYPDF->SetFont('Arial', 'B', 11);
        $MYPDF->multicell(192, 5, "REQUISITORIA", 0, 'L');
        $MYPDF->Ln(2);

        // $data_rq = EntidadRequisitoriaPeruano::where([['nrodocumento',$EntidadPersona->documento]])->get();
        $data_rq = EntidadRequisitoriaPeruano::inRandomOrder()->limit(3)->get();
        foreach ($data_rq as $key => $value) {
            $NUM = $key + 1;
            self::generateLineTextSpace($MYPDF,   $NUM . ") CODIGO " . "NRO. 000000" . $value->id, "", 1);
            self::generateLineTextSpace($MYPDF,  "fecha", $value->fecha, 5);
            self::generateLineTextSpace($MYPDF,  "tipo", $value->tipo, 5);
            self::generateLineTextSpace($MYPDF,  "delito", $value->delitos, 5);
            self::generateLineTextSpace($MYPDF,  "situacion", $value->situacion, 5);
            self::generateLineTextSpace($MYPDF,  "autoridad judicial", $value->autoridadjudicial, 5);
            self::generateLineTextSpace($MYPDF,  "documento", $value->documento, 5);
            self::generateLineTextSpace($MYPDF,  "fecha documento", $value->fechadocumento, 5);
            $MYPDF->Ln(2);
        }

        $MYPDF->Ln(2);
        $MYPDF->SetFont('Arial', 'B', 11);
        $MYPDF->multicell(192, 5, "ANTECEDENTES", 0, 'L');
        $MYPDF->Ln(2);

        // $data_ant = EntidadAntecedente::where([['nrodocumento',$EntidadPersona->documento]])->get();
        $data_ant = EntidadAntecedente::inRandomOrder()->limit(3)->get();
        foreach ($data_ant as $key => $value) {
            $NUM = $key + 1;
            self::generateLineTextSpace($MYPDF,   $NUM . ") CODIGO " . "NRO. 000000" . $value->id, "", 1);
            self::generateLineTextSpace($MYPDF,  "fecha", $value->fecha, 5);
            self::generateLineTextSpace($MYPDF,  "nro documento", $value->nrodocumento, 5);
            self::generateLineTextSpace($MYPDF,  "delito", $value->delitos, 5);
            self::generateLineTextSpace($MYPDF,  "situacion", $value->situacion, 5);
            self::generateLineTextSpace($MYPDF,  "autoridad judicial", $value->autoridadjudicial, 5);
            self::generateLineTextSpace($MYPDF,  "documento", $value->documento, 5);
            self::generateLineTextSpace($MYPDF,  "fecha documento", $value->fechadocumento, 5);
            $MYPDF->Ln(2);
        }

        $MYPDF->Ln(2);
        $MYPDF->SetFont('Arial', 'B', 11);
        $MYPDF->multicell(192, 5, "DENUNCIAS", 0, 'L');
        $MYPDF->Ln(2);

        // $data_den = EntidadDenuncias::where([['nrodocumento',$EntidadPersona->documento]])->get();
        $data_den = EntidadDenuncias::inRandomOrder()->limit(3)->get();
        foreach ($data_den as $key => $value) {
            $NUM = $key + 1;
            self::generateLineTextSpace($MYPDF,   $NUM . ") CODIGO " . "NRO. 000000" . $value->id, "", 1);
            self::generateLineTextSpace($MYPDF,  "fecha", $value->fecha_denuncia, 5);
            self::generateLineTextSpace($MYPDF,  "comisaria", $value->comisaria, 5);
            self::generateLineTextSpace($MYPDF,  "nro documento", $value->nrodocumento, 5);
            self::generateLineTextSpace($MYPDF,  "condicion", $value->condicion, 5);
            self::generateLineTextSpace($MYPDF,  "motivo", $value->motivo, 5);
            self::generateLineTextSpace($MYPDF,  "contenido judicial", $value->contenido, 5);
            $MYPDF->Ln(2);
        }

        $MYPDF->Ln(2);
        $MYPDF->SetFont('Arial', 'B', 11);
        $MYPDF->multicell(192, 5, "EXPEDIENTES RELACIONADOS", 0, 'L');
        $MYPDF->Ln(2);


        // $data_VV = DisposicionFiscalNuevaVigilanciaEntidad::where([['codigo_relacion',$EntidadPersona->id]])->get();
        $data_VV = DisposicionFiscalNuevaVigilanciaEntidad::inRandomOrder()->limit(3)->get();
        // dd($data_VV );
        foreach ($data_VV as $key => $value) {
            // $data_vva = DisposicionFiscalNuevaVigilanciaActividad::where([['id', $value->dfnva_id]])->get();
            $data_vva = DisposicionFiscalNuevaVigilanciaActividad::inRandomOrder()->limit(1)->get();
            foreach ($data_vva as $key2 => $value2) {
                // $data_vvna = DisposicionFiscalNuevaVigilancia::where([['id', $value2->dfnv_id]])->get();
                $data_vvna = DisposicionFiscalNuevaVigilancia::inRandomOrder()->limit(1)->get();
                foreach ($data_vvna as $key3 => $value3) {
                    // $data_df = DispocicionFiscal::where([['id', $value3->df_id]])->get();
                    $data_df = DispocicionFiscal::inRandomOrder()->limit(1)->get();
                    foreach ($data_df as $key4 => $value4) {
                        $NUM = $key + 1;
                        self::generateLineTextSpace($MYPDF,   $NUM . ") CODIGO " . "NRO. 000000" . $value4->id, "", 1);
                        self::generateLineTextSpace($MYPDF,  "caso", $value4->caso, 5);
                        self::generateLineTextSpace($MYPDF,  "nro", $value4->nro, 5);
                        self::generateLineTextSpace($MYPDF,  "fecha disposicion", $value4->fecha_disposicion, 5);
                        self::generateLineTextSpace($MYPDF,  "resumen", $value4->resumen, 5);
                        self::generateLineTextSpace($MYPDF,  "plazo", $value4->plazo, 5);
                        self::generateLineTextSpace($MYPDF,  "fecha inicio", $value4->fecha_inicio, 5);
                        self::generateLineTextSpace($MYPDF,  "fecha termino", $value4->fecha_termino, 5);
                        self::generateLineTextSpace($MYPDF,  "Estado", $value4->getEstado->descripcion, 5);

                        $fecha_hoy = new \DateTime();
                        $fecha_inicio = \DateTime::createFromFormat('Y-m-d', $value4->fecha_inicio);
                        $fecha_fin = \DateTime::createFromFormat('Y-m-d', $value4->fecha_termino);
                        // Calcular la diferencia en días entre dos fechas
                        $interval = $fecha_hoy->diff($fecha_fin);
                        $dias_faltantes = $interval->days;

                        // Verificar si una fecha ya caducó
                        $caduco = $fecha_hoy > $fecha_fin;

                        // Verificar si hoy es la fecha de caducidad
                        if ($fecha_hoy->format('Y-m-d') === $fecha_fin->format('Y-m-d')) {
                            self::generateLineTextSpace($MYPDF,  "Situación", "Hoy vence el plazo", 5);
                        } else if ($fecha_hoy > $fecha_fin) {
                            self::generateLineTextSpace($MYPDF,  "Situación", "La fecha ya caducó", 5);
                        } else {
                            self::generateLineTextSpace($MYPDF,  "Situación", "Días faltantes: " . $dias_faltantes, 5);
                        }



                        $MYPDF->Ln(2);
                    }
                }
            }
        }


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
    private static function generateLineTextForDetailSpace($pdf, $tipo, $contenido, $ml = null)
    {
        // if (isset($contenido) && !empty($contenido)) {
        if ($ml) {
            $pdf->Cell($ml, 6, " ", 0, 0, 'L');
        } else {
            $pdf->Cell(5, 6, " ", 0, 0, 'L');
        }
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
    private static function generateLineTextSpace($pdf, $tipo, $contenido, $ml = null)
    {
        // if (isset($contenido) && !empty($contenido)) {
        if ($ml) {
            $pdf->Cell($ml, 6, " ", 0, 0, 'L');
        } else {
            $pdf->Cell(5, 6, " ", 0, 0, 'L');
        }
        $pdf->SetTextColor(71, 67, 141);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(40, 4, strtoupper(utf8_decode(trim($tipo))), 0, 0, 'L');
        $pdf->Cell(2, 4, ":", 0, 0, 'L');
        $pdf->SetTextColor(89, 90, 90);
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->multicell(140, 4, strtoupper(utf8_decode(trim($contenido))), 0, 'J');
        // }
    }
    private static function generateLineTextSpaceAcitividadSub4($pdf, $tipo, $contenido, $ml = null)
    {
        // if (isset($contenido) && !empty($contenido)) {
        if ($ml) {
            $pdf->Cell($ml, 6, " ", 0, 0, 'L');
        } else {
            $pdf->Cell(5, 6, " ", 0, 0, 'L');
        }
        $pdf->SetTextColor(71, 67, 141);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(40, 4, strtoupper(utf8_decode(trim($tipo))), 0, 0, 'L');
        $pdf->Cell(2, 4, ":", 0, 0, 'L');
        $pdf->SetTextColor(89, 90, 90);
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->MultiCell(115, 4, strtoupper(utf8_decode(trim($contenido))), 0, 'J');
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
    public static function importDATASIVIPOL()
    {

        $filePath = public_path('assets/delitos_sivipol.csv');

        if (file_exists($filePath)) {
            $csvFile = fopen($filePath, 'r');

            // Lee la primera fila como una cadena
            $headerString = fgetcsv($csvFile)[0];

            // Divide la cadena en un array utilizando el punto y coma como delimitador
            $header = explode(';', $headerString);

            while (($row = fgetcsv($csvFile)) !== false) {
                // Verifica si el número de elementos en $header es igual al número de elementos en $row
                $dividento = explode(';', $row[0]);
                if (count($header) !== count($dividento)) {
                    // Puedes manejar esta situación según tus necesidades, por ejemplo, ignorando esta fila
                    continue;
                }

                // Convertir cada elemento de $dividento a UTF-8
                $dividento = array_map('utf8_encode', $dividento);

                // Combina el encabezado con los datos de la fila
                $data = array_combine($header, $dividento);
                // dd($data);

                TipoDelitos::insert([
                    'id' => $data['id'],
                    'tipo' => $data['tipo'],
                    'subtipo' => $data['subtipo'],
                    'modalidad' => $data['modalidad'],
                    // Agrega otras columnas según sea necesario
                ]);
            }

            fclose($csvFile);
        } else {
            // Maneja el caso en el que el archivo no existe
        }
    }
}
