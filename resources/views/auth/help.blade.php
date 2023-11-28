@extends('layouts.appAuth')

@section('content')

<div class="nk-block-head">
    <div class="nk-block-head-content">
        <h5 class="nk-block-title">¿Necesitas ayuda?</h5>
        <div class="nk-block-des">
            <p>Contactanos.</p>
        </div>
    </div>
</div><!-- .nk-block-head -->
<div class="form-group">
    <div class="form-label-group">
        <label class="form-label" for="email">Correo electrónico</label>
    </div>
    <div class="form-control-wrap">
        <p>Texo 1</p>
    </div>
</div><!-- .form-group -->

<div class="form-group">
    <div class="form-label-group">
        <label class="form-label" for="email">Nro. Celular</label>
    </div>
    <div class="form-control-wrap">
        <p>Texo 1</p>
    </div>
</div><!-- .form-group -->

<div class="form-group">
    <div class="form-label-group">
        <label class="form-label" for="email">Dirección</label>
    </div>
    <div class="form-control-wrap">
        <p>Texo 1</p>
    </div>
</div><!-- .form-group -->




@endsection

@push('BarraLateralAnuncios')

<div class="nk-split-content nk-split-stretch bg-lighter d-flex toggle-break-lg toggle-slide toggle-slide-right" data-content="athPromo" data-toggle-screen="lg" data-toggle-overlay="true">
    <div class="slider-wrap w-100 w-max-550px p-3 p-sm-5 m-auto">
        <div class="slider-init" data-slick='{"dots":true, "arrows":false}'>
            <div class="slider-item">
                <div class="nk-feature nk-feature-center">
                    <div class="nk-feature-img">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2758.551541141327!2d-77.01756239468286!3d-12.099758121147628!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x9105c8768b68ffff%3A0xa50d78bea2203d4d!2sPER%C3%9A%20Ministerio%20del%20Interior!5e0!3m2!1ses-419!2spe!4v1701119379113!5m2!1ses-419!2spe" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                    <div class="nk-feature-content py-4 p-sm-5">
                        <h4>PERÚ Ministerio del Interior</h4>
                        <p>Av. Guardia Civil 922 Lima, San Isidro 15036</p>
                    </div>
                </div>
            </div><!-- .slider-item -->

        </div><!-- .slider-init -->
        <div class="slider-dots"></div>
        <div class="slider-arrows"></div>
    </div><!-- .slider-wrap -->
</div><!-- .nk-split-content -->

@endpush