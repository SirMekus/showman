<x-layout.dashboard>

        @if(!empty($branch))
            <div>
                <a class="text-decoration-none text-dark float-end" href="#">
                    <i class="fas fa-edit fa-2x"></i>
                </a>
            </div>

            <table class="table table-stripped table-bordered bg-light">
                <thead class='sticky-top'>
                    <tr class='bg-dark'>
                        <th class='text-white'>S/N</th>
                        <th class='text-white'>Cinema</th>
                        <th class='text-white'>Address</th>
                        <th class='text-white'>Date Created</th>
                    </tr>
                </thead>
                <tbody>
                    
                        @php
                            $sn = 0;
                        @endphp
                    @foreach($branch as $mybranch)
                    @php
                        $sn += 1;
                    @endphp
                    <tr>
                    <td>{{$sn}}</td>
                    <td><a href="{{route('dashboard.cinema.movies',['id'=>$mybranch->id])}}">{{request()->user()->cinema_name}}
                       @if($mybranch->movies_count > 0) <span class="badge rounded-pill bg-dark text-light">{{$mybranch->movies_count}}</span> @endif</a></td>
                    <td>{{$mybranch->address}}</td>
                    <td>{{$mybranch->created_at->toFormattedDateString()}}</td>
                    </tr>
                    @endforeach
                
                    
                </tbody>
            </table>

        
        @else
        <div v-else class="card rounded-0">
            <div class="card-body d-flex justify-content-center">
                <a class="text-decoration-none text-dark" href="#">
                    <i class="fas fa-edit fa-6x text-cente ml-3"></i>
                    <h2 class="ml-4 pl-1">Create Staff</h2>
                </a>
            </div>
        </div>
        @endif

</x-layout.dashboard>