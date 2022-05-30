<x-layout.home>

    <x-slot name="title">
        Login
    </x-slot>

    <div class='col-lg-12 col-md-12 col-sm-12'>
        <div class="card" style='border-radius:30px;'>
            <div class="card-header bg-dark d-flex justify-content-center" style='border-radius:30px;'>
                <h3 class='text-light fw-bold'>Welcome</h3>
            </div>

            <div class="card-body">
                <form action="{{route('login')}}" role="form" id="form" method="post">
                    <div class="form-group">
                        <label>Email<span style="color:red;">*</span></label>
                        <input type="text" class="form-control form-control-lg borderless-input" placeholder="email"
                            autocomplete required="required" name='email' />
                    </div>

                    <div class="form-group mt-3">
                        <label>Password<span style="color:red;">*</span></label>
                        <input type="password" class="form-control form-control-lg borderless-input"
                            placeholder="password" autocomplete name="password" />
                    </div>

                    <div class="form-group mt-3">
                        <input class="btn btn-block btn-dark btn-lg w-100" type="submit" value="Log In" />
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script type='text/javascript'>
        window.addEventListener("DOMContentLoaded", function() {
            axios.get('sanctum/csrf-cookie').then(response => {
            //do nothing
        });
        
    }, false);
    </script>
    @endpush

</x-layout.home>