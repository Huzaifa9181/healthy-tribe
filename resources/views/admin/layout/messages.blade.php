@push('js')
    <script type="text/javascript">
        $(document).ready(function(){
            const Sweet = Swal.mixin({
                //input: 'text',
                toast: true,
                position: 'top',
                showConfirmButton: false,
                timer: 10000,
                timerProgressBar: true,
            });

            @if(session()->has('error'))
            Sweet.fire({
                icon: 'error',
                type: 'error',
                title: ' Alert :',
                text: ' {{ session('error') }} '
            });
            @endif

            @if(session()->has('success'))
            Sweet.fire({
                icon: 'success',
                type: 'success',
                title: ' Success :',
                text: ' {{ session('success') }} '
            });
            @endif
        });
    </script>
@endpush
@if(count($errors->all()) > 0)
    <div class="alert alert-warning alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h6><i class="icon fas fa-exclamation-triangle"></i> Alert!</h6>
        <ol>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ol>
    </div>
@endif
