<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use App\Models\TipoGrado;
use App\Models\TipoPerfil;
use App\Models\TipoUnidad;
use App\Http\Controllers\AuditoriaController;

use App\Models\DispocicionFiscal;
use App\Models\DisposicionFiscalObjetos;
use App\Models\DisposicionFiscalTipoVideoVigilancia;
use App\Models\DisposicionFiscalReferencia;

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


            $MYPDF->Ln(10);
            $MYPDF->SetFont('Arial', 'B', 11);
            $MYPDF->multicell(192, 5, "CREACION DE DIPOSICIONES FISCALES", 0, 'C');

            $MYPDF->Ln(8);
            $MYPDF->SetFont('Arial', 'B', 11);
            $MYPDF->multicell(192, 5, "CULMINACION DE DIPOSICIONES FISCALES", 0, 'C');

            $MYPDF->Ln(8);
            $MYPDF->SetFont('Arial', 'B', 11);
            $MYPDF->multicell(192, 5, "SUSPENCION DE DIPOSICIONES FISCALES", 0, 'C');

            $MYPDF->Ln(8);
            $MYPDF->SetFont('Arial', 'B', 11);
            $MYPDF->multicell(192, 5, "DESCARGA DE DIPOSICIONES FISCALES", 0, 'C');

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
            $expeOBJETOVV = DisposicionFiscalObjetos::where("df_id",$expe->id)->get();
            $expeTIPOVV = DisposicionFiscalTipoVideoVigilancia::where("df_id",$expe->id)->get();
            $MYPDF->Ln(4);

            $MYPDF->multicell(192, 5, "DETALLE", 0, 'L');
            $MYPDF->Ln(4);
            self::generateLineText($MYPDF,  "CASO", $expe->caso);
            self::generateLineText($MYPDF,  "NRO", $expe->nro);
            self::generateLineText($MYPDF,  "FECHA DISPOSICION", $expe->fecha_disposicion);
            self::generateLineText($MYPDF,  "FECHA DE INICIO", $expe->fecha_inicio);
            self::generateLineText($MYPDF,  "FECHA DE TERMINO", $expe->fecha_termino);

            self::generateLineText($MYPDF,  "TIPO DE PLAZO", $expe->getPlazo->descripcion);
            self::generateLineText($MYPDF,  "DIAS DE PLAZO", $expe->plazo);

            self::generateLineText($MYPDF,  "FISCAL RESPONSABLE", "DNI: ".$expe->getFiscal->dni." | ".$expe->getFiscal->nombres." ".$expe->getFiscal->paterno." ".$expe->getFiscal->materno." | ".$expe->getFiscal->procedencia." ".$expe->getFiscal->ficalia." ".$expe->getFiscal->despacho." ".$expe->getFiscal->ubigeo." | CORREO: ".$expe->getFiscal->correo." | CELULAR:".$expe->getFiscal->celular);
            self::generateLineText($MYPDF,  "FISCAL ASISTENTE", "DNI: ".$expe->getFiscalAdjunto->dni." | ".$expe->getFiscalAdjunto->nombres." ".$expe->getFiscalAdjunto->paterno." ".$expe->getFiscalAdjunto->materno." | ".$expe->getFiscalAdjunto->procedencia." ".$expe->getFiscalAdjunto->ficalia." ".$expe->getFiscalAdjunto->despacho." ".$expe->getFiscalAdjunto->ubigeo." | CORREO: ".$expe->getFiscalAdjunto->correo." | CELULAR:".$expe->getFiscalAdjunto->celular);
            self::generateLineText($MYPDF,  "RESUMEN", $expe->resumen);
            self::generateLineText($MYPDF,  "OBSERVACIONES", $expe->observaciones);
            self::generateLineText($MYPDF,  "SITUACION", $expe->getEstado->descripcion);
           
            
            $MYPDF->Ln(5);
            $MYPDF->SetFont('Arial', 'B', 11);
            $MYPDF->multicell(192, 5, "REFERENCIA", 0, 'L');
            foreach ($expeREFERENCIA  as $key => $value) {
                self::generateLineText($MYPDF,  "TIPO DOC REFERENCIA", $value->geTipoDocumentosReferencia->descripcion);
                self::generateLineText($MYPDF,  "NRO", $value->nro);
                self::generateLineText($MYPDF,  "FECHA", $value->fecha_documento);
                self::generateLineText($MYPDF,  "SIGLAS", $value->siglas);
                self::generateLineText($MYPDF,  "ARCHIVO", $value->pdf);
            }
            $MYPDF->Ln(5);
            $MYPDF->SetFont('Arial', 'B', 11);
            $MYPDF->multicell(192, 5, "TIPO DE VIDEOVIGILANCIA", 0, 'L');
            foreach ($expeTIPOVV  as $key => $value) {
                self::generateLineText($MYPDF,  $value->geTipoVideovigilancia->descripcion, "");
            }

            $MYPDF->Ln(5);
            $MYPDF->SetFont('Arial', 'B', 11);
            $MYPDF->multicell(192, 5, "OBJECTO DE VIDEOVIGILANCIA", 0, 'L');
            foreach ($expeOBJETOVV  as $key => $value) {
                self::generateLineText($MYPDF,  $value->descripcion, "");
            }
          
            $MYPDF->Output(('ReporteDeExpediente.pdf'), 'I');
            //$MYPDF->Output('D', "ReporteReferenciaSerpol.pdf", true);
            exit();
        } catch (\Exception $e) {
            // dd($e);
        }
    }
    private static function generateLineTextForDetail($pdf, $tipo, $contenido)
    {
        // if (isset($contenido) && !empty($contenido)) {
        $pdf->SetTextColor(71, 67, 141);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(40, 6, utf8_decode(trim($tipo)), 0, 0, 'L');
        $pdf->Cell(2, 6, ":", 0, 0, 'L');
        $pdf->SetTextColor(89, 90, 90);
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->Cell(40, 6, utf8_decode(trim($contenido)), 0, 0, 'L');
        // $pdf->MultiCell(90, 7, utf8_decode(trim($contenido)), 0, 'C', true);
        // }
    }
    private static function generateLineTextForDetailSpace($pdf, $tipo, $contenido)
    {
        // if (isset($contenido) && !empty($contenido)) {
        $pdf->Cell(5, 6, " ", 0, 0, 'L');
        $pdf->SetTextColor(71, 67, 141);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(40, 6, utf8_decode(trim($tipo)), 0, 0, 'L');
        $pdf->Cell(2, 6, ":", 0, 0, 'L');
        $pdf->SetTextColor(89, 90, 90);
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->Cell(40, 6, utf8_decode(trim($contenido)), 0, 0, 'L');
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
}
