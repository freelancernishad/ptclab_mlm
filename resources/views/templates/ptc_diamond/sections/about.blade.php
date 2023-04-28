@php
    $content = getContent('about.content', true);
@endphp
<div class="section about-section">
    <div class="container">
        <div class="row g-4 gy-lg-0 justify-content-between align-items-lg-center">
            <div class="col-lg-6">

                @php
                $path = getImage('assets/images/frontend/about/'.$content->data_values->image,'1024x970');
                $ext = pathinfo($path, PATHINFO_EXTENSION);

                @endphp

                @if($ext=='mp4')
                <video id="myVideo" src="{{ getImage('assets/images/frontend/about/'.$content->data_values->image,'1024x970') }}" class="img-fluid" playsinline autoplay loop muted></video>
                @else
                <img src="{{ getImage('assets/images/frontend/about/'.$content->data_values->image,'1024x970') }}" alt="image" class="img-fluid">
                @endif

            </div>
            <div class="col-lg-6 col-xl-5">
                <span class="section__subtitle">
                    {{ __($content->data_values->subheading ) }}
                </span>
                <h2 class="section__title mt-0" data-img-src="{{ asset($activeTemplateTrue . 'images/title-bg.svg') }}" s-break="-2">
                    {{ __($content->data_values->heading ) }}
                </h2>

                <div>
                    @php echo $content->data_values->description @endphp
                </div>

            </div>
        </div>
    </div>
</div>
<script>document.getElementById('myVideo').play()</script>
