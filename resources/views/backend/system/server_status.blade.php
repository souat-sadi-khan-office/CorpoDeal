@extends('backend.layouts.app')
@section('title', 'Server Status')
@section('page_name')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-house-add-fill"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">System Status</li>
                    </ol>
                </div>

                @if (Auth::guard('admin')->user()->hasPermissionTo('stuff.create'))
                    <div class="col-sm-6 text-end">
                        <a href="{{ route('admin.stuff.create') }}" class="btn btn-soft-success">
                            <i class="bi bi-plus"></i>
                            Create New
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
@section('content')
<div class="row">
    <div class="col-lg-10 col-xxl-8 mx-auto">
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="h4 mb-0">Server information</h3>
            </div>
            <div class="card-body">
                <table class="table table-striped aiz-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th data-breakpoints="lg">Current Version</th>
                            <th data-breakpoints="lg">Required Version</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Php versions</td>
                            <td>{{ phpversion() }}</td>
                            <td>8.2</td>
                            <td>
                                @if (floatval(phpversion()) >= 8.2)
                                    <i class="bi bi-check-all text-success"></i>
                                @else
                                    <i class="bi bi-x-lg text-danger"></i>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>MySQL</td>
                            <td>
                                @php
                                    $results = DB::select('SELECT VERSION() AS version');
                                    $mysql_version = $results[0]->version;
                                @endphp
                                {{ $mysql_version }}
                            </td>
                            <td>5.6+</td>
                            <td>
                                <i class="bi bi-check-all text-success"></i>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="h4 mb-0">php.ini Config</h3>
            </div>
            <div class="card-body">
                <table class="table table-striped aiz-table">
                    <thead>
                        <tr>
                            <th>Config Name</th>
                            <th data-breakpoints="lg">Current</th>
                            <th data-breakpoints="lg">Recommended</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>file_uploads</td>
                            <td>
                                @if(ini_get('file_uploads') == 1)
                                On
                                @else
                                Off
                                @endif
                            </td>
                            <td>On</td>
                            <td>
                                @if (ini_get('file_uploads') == 1)
                                    <i class="bi bi-check-all text-success"></i>
                                @else
                                    <i class="bi bi-x-lg text-danger"></i>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>max_file_uploads</td>
                            <td>
                                {{ ini_get('max_file_uploads')}}
                            </td>
                            <td>20+</td>
                            <td>
                                @if (ini_get('max_file_uploads') >= 20)
                                    <i class="bi bi-check-all text-success"></i>
                                @else
                                    <i class="bi bi-x-lg text-danger"></i>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>upload_max_filesize</td>
                            <td>
                                {{ ini_get('upload_max_filesize') }}
                            </td>
                            <td>128M+</td>
                            <td>
                                @if (str_replace(['M','G'],"", ini_get('upload_max_filesize')) >= 128)
                                    <i class="bi bi-check-all text-success"></i>
                                @else
                                    <i class="bi bi-x-lg text-danger"></i>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>post_max_size</td>
                            <td>
                                {{ ini_get('post_max_size') }}
                            </td>
                            <td>128M+</td>
                            <td>
                                @if (str_replace(['M','G'],"", ini_get('post_max_size')) >= 128)
                                    <i class="bi bi-check-all text-success"></i>
                                @else
                                    <i class="bi bi-x-lg text-danger"></i>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>allow_url_fopen</td>
                            <td>
                                @if(ini_get('allow_url_fopen') == 1)
                                On
                                @else
                                Off
                                @endif
                            </td>
                            <td>On</td>
                            <td>
                                @if (ini_get('allow_url_fopen') == 1)
                                    <i class="bi bi-check-all text-success"></i>
                                @else
                                    <i class="bi bi-x-lg text-danger"></i>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>max_execution_time</td>
                            <td>
                                @if(ini_get('max_execution_time') == '-1')
                                Unlimited
                                @else
                                {{ ini_get('max_execution_time') }}
                                @endif
                            </td>
                            <td>600+</td>
                            <td>
                                @if (ini_get('max_execution_time') == -1 || ini_get('max_execution_time') >= 600)
                                    <i class="bi bi-check-all text-success"></i>
                                @else
                                    <i class="bi bi-x-lg text-danger"></i>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>max_input_time</td>
                            <td>
                                @if(ini_get('max_input_time') == '-1')
                                Unlimited
                                @else
                                {{ ini_get('max_input_time') }}
                                @endif
                            </td>
                            <td>120+</td>
                            <td>
                                @if (ini_get('max_input_time') == -1 || ini_get('max_input_time') >= 120)
                                    <i class="bi bi-check-all text-success"></i>
                                @else
                                    <i class="bi bi-x-lg text-danger"></i>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>max_input_vars</td>
                            <td>
                                {{ ini_get('max_input_vars') }}
                            </td>
                            <td>1000+</td>
                            <td>
                                @if (ini_get('max_input_vars') >= 1000)
                                    <i class="bi bi-check-all text-success"></i>
                                @else
                                    <i class="bi bi-x-lg text-danger"></i>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>memory_limit</td>
                            <td>
                                @if(ini_get('memory_limit') == '-1')
                                Unlimited
                                @else
                                {{ ini_get('memory_limit') }}
                                @endif
                            </td>
                            <td>256M+</td>
                            <td>
                                @php
                                    $memory_limit = ini_get('memory_limit');
                                    if (preg_match('/^(\d+)(.)$/', $memory_limit, $matches)) {
                                        if ($matches[2] == 'G') {
                                            $memory_limit = $matches[1] * 1024 * 1024 * 1024; // nnnM -> nnn GB
                                        } else if ($matches[2] == 'M') {
                                            $memory_limit = $matches[1] * 1024 * 1024; // nnnM -> nnn MB
                                        } else if ($matches[2] == 'K') {
                                            $memory_limit = $matches[1] * 1024; // nnnK -> nnn KB
                                        }
                                    }
                                @endphp
                                @if (ini_get('memory_limit') == -1 || $memory_limit >= (256 * 1024 * 1024))
                                    <i class="bi bi-check-all text-success"></i>
                                @else
                                    <i class="bi bi-x-lg text-danger"></i>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="h4 mb-0">Extensions information</h3>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Extension Name</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    @php
                    $loaded_extensions = get_loaded_extensions();
                    $required_extensions = ['bcmath', 'ctype', 'json', 'mbstring', 'zip', 'zlib', 'openssl', 'tokenizer', 'xml', 'dom',  'curl', 'fileinfo', 'gd', 'pdo_mysql']
                    @endphp
                    <tbody>
                        @foreach ($required_extensions as $extension)
                        <tr>
                            <td>{{ $extension }}</td>
                            <td>
                                @if(in_array($extension, $loaded_extensions))
                                    <i class="bi bi-check-all text-success"></i>
                                @else
                                    <i class="bi bi-x-lg text-danger"></i>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="h4 mb-0">Filesystem Permissions</h3>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>File or Folder</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    @php
                    $required_paths = ['.env', 'public', 'app/Providers', 'app/Http/Controllers', 'storage', 'resources/views']
                    @endphp
                    <tbody>
                        @foreach ($required_paths as $path)
                        <tr>
                            <td>{{ $path }}</td>
                            <td>
                                @if(is_writable(base_path($path)))
                                    <i class="bi bi-check-all text-success"></i>
                                @else
                                    <i class="bi bi-x-lg text-danger"></i>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
