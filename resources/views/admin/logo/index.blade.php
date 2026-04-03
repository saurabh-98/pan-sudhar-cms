@extends('layout.admin')

@section('content')

<div class="container mt-4">

    <h3 class="mb-4">Logo Management</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">

        <!-- FORM -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Logo Settings</div>

                <div class="card-body">

                    <form action="{{ route('admin.logo.save') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- TYPE -->
                        <label>Logo Type</label>
                        <select name="type" class="form-control mb-3" id="logoType">
                            <option value="image">Image Logo</option>
                            <option value="text">Text Logo</option>
                        </select>

                        <!-- IMAGE -->
                        <div id="imageField">
                            <input type="file" name="logo" class="form-control mb-3">
                        </div>

                        <!-- TEXT -->
                        <div id="textField" style="display:none;">
                            <input type="text" name="logo_text" class="form-control mb-3"
                                placeholder="Enter logo text">

                            <input type="color" name="color" class="form-control mb-3" value="#ff5722">

                            <select name="font_size" class="form-control mb-3">
                                <option value="20">Small</option>
                                <option value="28" selected>Medium</option>
                                <option value="36">Large</option>
                            </select>
                        </div>

                        <button class="btn btn-primary w-100">Save Logo</button>

                    </form>

                </div>
            </div>
        </div>

        <!-- PREVIEW -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Logo Preview</div>

                <div class="card-body text-center">

                    @if($logo && $type === 'image')
                        <img src="{{ asset($logo) }}" height="80">
                    @elseif($type === 'text')
                        <h2 style="color:{{ $color }}; font-size:{{ $font_size }}px;">
                            {{ $logo_text }}
                        </h2>
                    @else
                        <p>No logo configured</p>
                    @endif

                </div>
            </div>
        </div>

    </div>

</div>

@endsection


@section('scripts')

<script>
document.getElementById('logoType').addEventListener('change', function(){

    if(this.value === 'text'){
        document.getElementById('textField').style.display = 'block';
        document.getElementById('imageField').style.display = 'none';
    }else{
        document.getElementById('textField').style.display = 'none';
        document.getElementById('imageField').style.display = 'block';
    }

});
</script>

@endsection