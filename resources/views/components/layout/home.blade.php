<!DOCTYPE html>
<html>
    <head>
        <title>{{ $title ?? config('app.name') }}</title>
        <x-head/>
        
        @stack('styles')
    </head>
    <body>
        <x-top-menu/>
        <div class='{{$parent_class ?? 'container'}}'>
            <div class='row justify-content-center'>
            {{ $slot }}
            </div>
        </div>

        <x-js/>
        
        @stack('scripts')
        
    </body>
</html>