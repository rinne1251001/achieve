@extends('layouts.app')
@section('title', 'お探しのページは見つかりませんでした')
@section('content')

<div class="wrapper3">

    <aside class="sidebar">
    </aside>

    <main style="padding: 0 clamp(10px, 5vw, 30px) 30%; height: calc(100vh -  50px); display: grid; place-content: center; place-items: center; gap: 20px;">

        <div style="position: relative;">
            <div class="nf_404">404</div>
            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, calc(-50% + clamp(0.5em, 7vw, 2.2em)));">
                <div class="robot_head" style="color: transparent; font-size: clamp(30px, 15vw, 100px);">
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </div>
            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, calc(-50% - clamp(1.2em, 12vw, 4.2em)));">
                <div class="robot_antenna" style="font-size: clamp(50px, 25vw, 150px);">
                    <div></div>
                    <div></div>
                </div>
            </div>
        </div>
        <div>お探しのページは見つかりませんでした</div>

    </main>

    <aside class="sidebar">
    </aside>

</div>

@endsection

@push('scripts')
<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <symbol id="flower" viewBox="0 0 33 33">
        <path d="M16.5 12a4.5 4.5 0 110 9 4.5 4.5 0 010-9zm0-2.02a6.52 6.52 0 100 13.03 6.52 6.52 0 000-13.03zM16.58 0a6.7 6.7 0 015.18 2.44l.37.5.55-.09.69-.03a6.72 6.72 0 016.58 8.07v.03l.09.06a6.71 6.71 0 010 11.14h-.02l.09.56a6.72 6.72 0 01-7.37 7.37l-.62-.1-.05.09a6.71 6.71 0 01-10.76.52l-.34-.47-.65.1a6.72 6.72 0 01-7.29-7.95v-.03l-.07-.04a6.71 6.71 0 010-11.14L3 11l-.01-.05A6.75 6.75 0 019.57 2.9c.47 0 .92.05 1.36.14h.03l.04-.07A6.71 6.71 0 0116.58 0z" fill="currentColor" fill-rule="evenodd"/>
    </symbol>
</svg>
<svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" style="display: none;">
    <symbol id="circle" viewBox="0 0 33 33">
        <path d="m28.68 27.56-.5.6c-1.5 1.5-3.28 2.7-5.26 3.54l-1.08.34zm3.17-5.05-.13.41-.82 1.51-12.85 8.41-1.56.16-.58-.06zm.89-3.24-.06.55-.3.99-18.26 11.95-.96-.1-.6-.18zm.26-2.6-.14 1.4L11.4 32.12l-1.35-.42zm-.22-2.55.12 1.22L8.88 31.07l-1.09-.6zm-.53-2.35.33 1.08L6.75 29.77l-.88-.73zm-.68-1.97.15.27.24.78L5.1 28.43l-.31-.26-.45-.55zm-1.1-2 .53.95L3.6 26.7l-.7-.84zm-1.22-1.67.7.85L2.4 25.02l-.52-.96zm-1.63-1.76.56.46.25.3-27.17 17.8-.34-1.08zm-1.74-1.43.89.73L.6 20.8l-.3-.98-.02-.11zM23.86 1.8l1.1.6L.16 18.61.05 17.4zm-12.92-.78L4.42 5.28l.38-.45c1.5-1.5 3.28-2.7 5.25-3.54zM21.34.8l1.35.42L0 16.07l.14-1.4zm-3.1-.63 1.58.16.1.03L.3 13.23v-.06l.5-1.57zM16.41 0 1.38 9.85l1.1-2.02L14.07.23z" fill="currentColor" fill-rule="evenodd"/>
    </symbol>
</svg>
@endpush