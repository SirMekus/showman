<meta name="csrf-token" content="{{csrf_token()}}">
        
        <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0 shrink-to-fit=no"/>

         <meta name="theme-color" content="#800000f0"/>
        
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="apple-mobile-web-app-capable" content="yes">
        
        <meta name="base_url" content="{{ URL::to('/') }}">

        <title>{{config('app.name')}}</title>
        <link href="{{route('home')}}{{ mix('css/app.css') }}" rel="stylesheet">
        <link href="{{route('home')}}/css/f_awesome/css/all.min.css" rel="stylesheet">