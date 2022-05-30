<x-layout.home>

    <x-slot name="title">
        Sign Up
    </x-slot>

    <div class='col-lg-12 col-md-12 col-sm-12'>
        <form id="ajax-post-nav-form" action="#" method="post" role="form" novalidate>
            <div class="card" style='border-radius:30px;'>
                <div class="card-header bg-dark text-light d-flex justify-content-center"
                    style='border-radius:30px;'>
                    <h3><b>Sign Up</b></h3>
                </div>

                <div class="card-body">
                    <div class="form-group ">
                        <label>First Name<span style="color:red;">*</span></label>
                        <input type="text" class="form-control form-control-lg borderless-input signup-field"
                            data-name="first name" placeholder="first name" autocomplete required="required"
                            id="firstname" name="firstname" />
                    </div>

                    <div class="form-group ">
                        <label>Last Name<span style="color:red;">*</span></label>
                        <input type="text" class="form-control form-control-lg borderless-input signup-field"
                            data-name="last name" placeholder="last name" autocomplete required="required" id="lastname"
                            name="lastname" />
                    </div>

                    <div class="form-group ">
                        <label>Email<span style="color:red;">*</span></label>
                        <input type="email" class="form-control form-control-lg borderless-input signup-field"
                            data-name="email" placeholder="email" autocomplete required="required" id="email"
                            name="email" />
                    </div>

                    <div class="form-group ">
                        <label>Name of Cinema<span style="color:red;">*</span></label>
                        <input type="text" class="form-control form-control-lg borderless-input signup-field"
                            data-name="church name" placeholder="brand name" autocomplete required="required"
                            id="churchname" name="churchname" />
                    </div>

                    <div class="form-group">
                        <label>Address<span style="color:red;">*</span></label>
                        <input type="text" class="form-control form-control-lg borderless-input signup-field"
                            data-name="address" placeholder="address" autocomplete required="required" id="hqaddress"
                            name="hqaddress" />
                    </div>

                    <div class="form-group form-row">
                        <div class='col-4 mt-2'>
                            <div class="input-group">
                                <div class="input-group-append">
                                    <span class="input-group-text">+</span>
                                </div>
                                <input type="tel" pattern="[0-9\-]+" name="isd" id="isd"
                                    class="form-control form-control borderless-input" placeholder="ISO code" />
                            </div>
                        </div>
                        <div class='col-8'>
                            <input
                                class="form-control form-control-lg prevent-duplicate-record borderless-input"
                                data-name="phone number" type="tel" placeholder="phone number" required="required"
                                id="phonenumber" name="phonenumber" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Enter Password<span style="color:red;">*</span></label>
                        <div class="input-group input-lg mb-3">
                            <input type="password" class="form-control form-control-lg borderless-input" placeholder="password" required="required" id="password" name="password">
                            
                        </div>
                    </div>

                    <div class="form-group ">
                        <input type="submit" id="button" class="btn header-gradient text-light active btn-block btn-lg"
                            value="Proceed" style='border-radius:30px;' />
                    </div>
                </div>
            </div>
        </form>
        <hr />
        <div class='d-flex justify-content-center'>
            <a class='text-light mt-0 btn home-color' href="{{route('login')}}">Login Instead</a>
        </div>
    </div>

    @push('scripts')


    <script type='text/javascript'>
        $(document).ready(function(){
        $.get(appURl+"sanctum/csrf-cookie", function(data){
				//console.log(data)
			    });
    });
    </script>

    @endpush

</x-layout.back-btn>