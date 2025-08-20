@extends('backend.layouts.app')
@section('title', 'Update Product Information')
@push('style')
    <link rel="stylesheet" href="{{ asset('backend/assets/css/dropify.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/tempus-dominus.min.css') }}">
    <style>
        #imagePreview img {
            max-width: 150px;
            margin: 10px;
            border: 2px solid #ddd;
        }
    </style>
@endpush

@section('page_name')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="h3 mb-0">Update product information</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-house-add-fill"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.product.index') }}">
                                Product Management
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Update product information</li>
                    </ol>
                </div>
                <div class="col-sm-6 text-end">
                    @if (Auth::guard('admin')->user()->hasPermissionTo('product.view'))
                        <a href="{{ route('admin.product.index') }}" class="btn btn-soft-danger">
                            <i class="bi bi-backspace"></i>
                            Back
                        </a>
                    @endif 
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')

    <form action="{{ route('admin.product.update', $model->id) }}" method="POST" class="content_form"
        enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="row">

            <div style="display: {{ $model->details->pc_builder_item == 1 ? 'block' : 'none' }};" class="pc-builder-area col-md-12 mb-3">
                <div class="accordion accordion-flush" id="accordionFlushExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                PC Builder
                            </button>
                        </h2>
                        <div id="flush-collapseOne" class="accordion-collapse collapse show" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">
                                <div class="row">
                                    <!-- component_type -->
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="component_type">Component Type</label>
                                        <select name="component_type" id="component_type" class="form-control select" data-placeholder="Select one">
                                            <option value="">Select one</option>
                                            <option {{ $model->details->component_type == 'core' ? 'selected' : '' }} value="core">Core Components</option>
                                            <option {{ $model->details->component_type == 'peri' ? 'selected' : '' }} value="peri">Peripherals & Others</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row core-component-area" style="display: {{ $model->details->component_type == 'core' ? 'block' : 'none' }};">
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="core_component_type">Componnt Type</label>
                                        <select name="core_component_type" id="core_component_type" class="form-control select" data-placeholder="Select one">
                                            <option value="">Select one</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->comp_type == 'processor' ? 'selected' : '' }} value="processor">Processor</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->comp_type == 'cpu_cooler' ? 'selected' : '' }} value="cpu_cooler">CPU Cooler</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->comp_type == 'motherboard' ? 'selected' : '' }} value="motherboard">MotherBoard</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->comp_type == 'ram' ? 'selected' : '' }} value="ram">RAM</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->comp_type == 'storage' ? 'selected' : '' }} value="storage">Storage</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->comp_type == 'psu' ? 'selected' : '' }} value="psu">POWER SUPPLY UNIT</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->comp_type == 'graphics-card' ? 'selected' : '' }} value="graphics-card">Graphics Card</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->comp_type == 'casing' ? 'selected' : '' }} value="casing">Casing</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row peri-component-area" style="display: {{ $model->details->component_type == 'peri' ? 'block' : 'none' }};">
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="peri_component_type">Componnt Type</label>
                                        <select name="peri_component_type" id="peri_component_type" class="form-control select" data-placeholder="Select one">
                                            <option value="">Select one</option>
                                            <option {{ $model->details->peri_component_type == 'monitor' ? 'selected' : '' }} value="monitor">Monitor</option>
                                            <option {{ $model->details->peri_component_type == 'casing_fan' ? 'selected' : '' }} value="casing_fan">Casing Fan</option>
                                            <option {{ $model->details->peri_component_type == 'keyboard' ? 'selected' : '' }} value="keyboard">Keyboard</option>
                                            <option {{ $model->details->peri_component_type == 'mouse' ? 'selected' : '' }} value="mouse">Mouse</option>
                                            <option {{ $model->details->peri_component_type == 'anti-virus' ? 'selected' : '' }} value="anti-virus">Anti Virus</option>
                                            <option {{ $model->details->peri_component_type == 'headphone' ? 'selected' : '' }} value="headphone">Headphone</option>
                                            <option {{ $model->details->peri_component_type == 'ups' ? 'selected' : '' }} value="ups">UPS</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- cpu_area -->
                                <div class="row core-component-item-area" id="cpu_area" style="display: {{ $model->pc_builder && $model->pc_builder->comp_type == 'processor' ? 'block' : 'none' }};">
                                    <!-- cpu_brand -->
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="cpu_brand">CPU Brand</label>
                                        <select name="cpu_brand" id="cpu_brand" class="form-control select" data-placeholder="Select one">
                                            <option value="">Select one</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->cpu_brand == 'intel' ? 'selected' : '' }} value="intel">Intel</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->cpu_brand == 'amd' ? 'selected' : '' }} value="amd">AMD</option>
                                        </select>
                                    </div>

                                    <!-- cpu_generation -->
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="cpu_generation">CPU Generation</label>
                                        <select name="cpu_generation" id="cpu_generation" class="form-control select" data-placeholder="Select one">
                                            <option value="">Select one</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->cpu_generation == '7' ? 'selected' : '' }}  value="7">7th Gen</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->cpu_generation == '8' ? 'selected' : '' }}  value="8">8th Gen</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->cpu_generation == '9' ? 'selected' : '' }}  value="9">9th Gen</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->cpu_generation == '10' ? 'selected' : '' }}  value="10">10th Gen</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->cpu_generation == '11' ? 'selected' : '' }}  value="11">11th Gen</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->cpu_generation == '12' ? 'selected' : '' }}  value="12">12th Gen</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->cpu_generation == '13' ? 'selected' : '' }}  value="13">13th Gen</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->cpu_generation == '14' ? 'selected' : '' }}  value="14">14th Gen</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->cpu_generation == '15' ? 'selected' : '' }}  value="15">15th Gen</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->cpu_generation == '5000' ? 'selected' : '' }}  value="5000">5000 Serices Processor</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->cpu_generation == '7000' ? 'selected' : '' }}  value="7000">7000 Serices Processor</option>
                                        </select>
                                    </div>

                                    <!-- socket_type -->
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="socket_type">Socket Type</label>
                                        <input type="text" name="socket_type" id="socket_type" class="form-control" value="{{ $model->pc_builder ? $model->pc_builder->socket_type : '' }}">
                                    </div>

                                    <!-- default_tdp -->
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="default_tdp">Default TDP</label>
                                        <input type="text" name="default_tdp" id="default_tdp" class="form-control number" value="{{ $model->pc_builder ? $model->pc_builder->default_tdp : '' }}" >
                                    </div>
                                </div>

                                <!-- motherboard_area -->
                                <div class="row core-component-item-area" id="motherboard_area" style="display: {{ $model->pc_builder && $model->pc_builder->comp_type == 'motherboard' ? 'flex' : 'none' }};">

                                    <!-- mb_supported_cpu_brand -->
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="mb_supported_cpu_brand">Supported CPU Brand</label>
                                        <select name="mb_supported_cpu_brand" id="mb_supported_cpu_brand" class="form-control select">
                                            <option value="">Select one</option>
                                            <option {{ $model->pc_builder &&  $model->pc_builder->mb_supported_cpu_brand == 'intel' ? 'selected' : '' }} value="intel">Intel</option>
                                            <option {{ $model->pc_builder &&  $model->pc_builder->mb_supported_cpu_brand == 'amd' ? 'selected' : '' }} value="amd">AMD</option>
                                        </select>
                                    </div>

                                    <!-- mb_cpu_generation -->
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="mb_cpu_generation">Supported CPU Generation</label>
                                        <select name="mb_cpu_generation" id="mb_cpu_generation" class="form-control select" data-placeholder="Select one">
                                            <option value="">Select one</option>
                                            <option {{ $model->pc_builder &&  $model->pc_builder->mb_cpu_generation == '7' ? 'selected' : '' }} value="7">7th Gen</option>
                                            <option {{ $model->pc_builder &&  $model->pc_builder->mb_cpu_generation == '8' ? 'selected' : '' }} value="8">8th Gen</option>
                                            <option {{ $model->pc_builder &&  $model->pc_builder->mb_cpu_generation == '9' ? 'selected' : '' }} value="9">9th Gen</option>
                                            <option {{ $model->pc_builder &&  $model->pc_builder->mb_cpu_generation == '10' ? 'selected' : '' }} value="10">10th Gen</option>
                                            <option {{ $model->pc_builder &&  $model->pc_builder->mb_cpu_generation == '11' ? 'selected' : '' }} value="11">11th Gen</option>
                                            <option {{ $model->pc_builder &&  $model->pc_builder->mb_cpu_generation == '12' ? 'selected' : '' }} value="12">12th Gen</option>
                                            <option {{ $model->pc_builder &&  $model->pc_builder->mb_cpu_generation == '13' ? 'selected' : '' }} value="13">13th Gen</option>
                                            <option {{ $model->pc_builder &&  $model->pc_builder->mb_cpu_generation == '14' ? 'selected' : '' }} value="14">14th Gen</option>
                                            <option {{ $model->pc_builder &&  $model->pc_builder->mb_cpu_generation == '15' ? 'selected' : '' }} value="15">15th Gen</option>
                                            <option {{ $model->pc_builder &&  $model->pc_builder->mb_cpu_generation == '5000' ? 'selected' : '' }} value="5000">5000 Serices Processor</option>
                                            <option {{ $model->pc_builder &&  $model->pc_builder->mb_cpu_generation == '7000' ? 'selected' : '' }} value="7000">7000 Serices Processor</option>
                                        </select>
                                    </div>

                                    <!-- mb_supported_socket -->
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="mb_supported_socket">Supported CPU Socket</label>
                                        <input placeholder="LGA1700" type="text" name="mb_supported_socket" id="mb_supported_socket" class="form-control" value="{{ $model->pc_builder ? $model->pc_builder->mb_supported_socket : '' }}">
                                    </div>

                                    <!-- mb_form_factor -->
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="mb_form_factor">Form Factor</label>
                                        <select name="mb_form_factor" id="mb_form_factor" class="form-control select">
                                            <option value="">Select one</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->mb_form_factor == 'atx' ? 'selected' : '' }} value="atx">ATX</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->mb_form_factor == 'matx' ? 'selected' : '' }} value="matx">MATX</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->mb_form_factor == 'mitx' ? 'selected' : '' }} value="mitx">Mini iTX</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->mb_form_factor == 'other' ? 'selected' : '' }} value="other">Other</option>
                                        </select>
                                    </div>

                                    <!-- mb_supported_memory_type -->
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="mb_supported_memory_type">Supported memory type</label>
                                        <select name="mb_supported_memory_type" id="mb_supported_memory_type" class="form-control select">
                                            <option value="">Select one</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->mb_supported_memory_type == 'DDR 3' ? 'selected' : '' }} value="DDR 3">DDR 3</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->mb_supported_memory_type == 'DDR 4' ? 'selected' : '' }} value="DDR 4">DDR 4</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->mb_supported_memory_type == 'DDR 5' ? 'selected' : '' }} value="DDR 5">DDR 5</option>
                                        </select>
                                    </div>

                                    <!-- mb_default_tdp -->
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="mb_default_tdp">Default TDP</label>
                                        <input type="text" name="mb_default_tdp" id="mb_default_tdp" class="form-control number" value="{{ $model->pc_builder ? $model->pc_builder->mb_default_tdp : '' }}">
                                    </div>

                                    <!-- mb_number_of_ram -->
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="mb_number_of_ram">Number of RAM Channel</label>
                                        <select name="mb_number_of_ram" id="mb_number_of_ram" class="form-control select">
                                            <option value="">Select one</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->mb_number_of_ram == '1' ? 'selected' : '' }} value="1">Single</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->mb_number_of_ram == '2' ? 'selected' : '' }} value="2">Dual</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->mb_number_of_ram == '4' ? 'selected' : '' }} value="4">Quad</option>
                                        </select>
                                    </div>

                                    <!-- mb_xmp_support -->
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="mb_xmp_support">XMP Support?</label>
                                        <select name="mb_xmp_support" id="mb_xmp_support" class="form-control select">
                                            <option value="">Select one</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->mb_xmp_support == '1' ? 'selected' : '' }} value="1">Yes</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->mb_xmp_support == '0' ? 'selected' : '' }} value="0">No</option>
                                        </select>
                                    </div>

                                    <!-- mb_m2_storage_support -->
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="mb_m2_storage_support">M.2 Storage Support ?</label>
                                        <select name="mb_m2_storage_support" id="mb_m2_storage_support" class="form-control select">
                                            <option value="">Select one</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->mb_m2_storage_support == '1' ? 'selected' : '' }} value="1">Yes</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->mb_m2_storage_support == '0' ? 'selected' : '' }} value="0">No</option>
                                        </select>
                                    </div>

                                    <!-- mb_number_of_m2_support -->
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="mb_number_of_m2_support">Number of M.2 Storage Support</label>
                                        <input type="text" name="mb_number_of_m2_support" id="mb_number_of_m2_support" class="form-control number" value="{{ $model->pc_builder ? $model->pc_builder->mb_number_of_m2_support : '0' }}">
                                    </div>

                                    <!-- mb_sata_storage_support -->
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="mb_sata_storage_support">SATA Storage Support ?</label>
                                        <select name="mb_sata_storage_support" id="mb_sata_storage_support" class="form-control select">
                                            <option value="">Select one</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->mb_sata_storage_support == 1 ? 'selected' : '' }} value="1">Yes</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->mb_sata_storage_support == 0 ? 'selected' : '' }} value="0">No</option>
                                        </select>
                                    </div>

                                    <!-- mb_number_of_sata_support -->
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="mb_number_of_sta_support">Number of SATA Storage Support</label>
                                        <input type="text" name="mb_number_of_sta_support" id="mb_number_of_sta_support" class="form-control number" value="{{ $model->pc_builder ? $model->pc_builder->mb_number_of_sta_support : 0 }}">
                                    </div>

                                    <!-- mb_lic_support -->
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="mb_lic_support">Liquid Cooler Support?</label>
                                        <select name="mb_lic_support" id="mb_lic_support" class="form-control select">
                                            <option value="">Select one</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->mb_lic_support == 1 ? 'selected' : '' }} value="1">Yes</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->mb_lic_support == 0 ? 'selected' : '' }} value="0">No</option>
                                        </select>
                                    </div>

                                    <!-- mb_pcie_slot -->
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="mb_pcie_slot">Graphics Card Supportd PCIe Slot</label>
                                        <select name="mb_pcie_slot" id="mb_pcie_slot" class="form-control select" data-placeholder="Select one">
                                            <option value="">Select one</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->mb_pcie_slot == 'pcie-3' ? 'selected' : '' }} value="pcie-3">PCIe 3</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->mb_pcie_slot == 'pcie-4' ? 'selected' : '' }} value="pcie-4">PCIe 4</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- ram_area -->
                                <div class="row core-component-item-area" id="ram_area" style="display: {{ $model->pc_builder && $model->pc_builder->comp_type == 'ram' ? 'block' : 'none' }};">
                                    <!-- ram_memory_type -->
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="ram_memory_type">Memory Type</label>
                                        <select name="ram_memory_type" id="ram_memory_type" class="form-control select" data-placeholder="Select one">
                                            <option value="">Select one</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->ram_memory_type == 'DDR 3' ? 'selected' : '' }} value="DDR 3">DDR 3</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->ram_memory_type == 'DDR 4' ? 'selected' : '' }} value="DDR 4">DDR 4</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->ram_memory_type == 'DDR 5' ? 'selected' : '' }} value="DDR 5">DDR 5</option>
                                        </select>
                                    </div>

                                    <!-- ram_speed -->
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="ram_speed">RAM Speed</label>
                                        <input placeholder="3200 MHz" type="text" name="ram_speed" id="ram_speed" class="form-control" value="{{ $model->pc_builder ? $model->pc_builder->ram_speed : '' }}">
                                    </div>

                                    <!-- ram_xmp_support -->
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="ram_xmp_support">XMP Supported?</label>
                                        <select name="ram_xmp_support" id="ram_xmp_support" class="form-control select" data-placeholder="Select one">
                                            <option value="">Select one</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->ram_xmp_support == 1 ? 'selected' : '' }} value="1">Yes</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->ram_xmp_support == 0 ? 'selected' : '' }} value="0">No</option>
                                        </select>
                                    </div>

                                    <!-- ram_default_tdp -->
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="ram_default_tdp">Default TDP</label>
                                        <input placeholder="LGA1700" type="text" name="ram_default_tdp" id="ram_default_tdp" class="form-control number" value="{{ $model->pc_builder ? $model->pc_builder->ram_default_tdp : 0 }}">
                                    </div>
                                </div>

                                <!-- storage -->
                                <div class="row core-component-item-area" id="storage_area" style="display: {{ $model->pc_builder && $model->pc_builder->comp_type == 'storage' ? 'block' : 'none' }};">
                                    <!-- storage_type -->
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="storage_type">Storage Type</label>
                                        <select name="storage_type" id="storage_type" class="form-control select" data-placeholder="Select one">
                                            <option value="">Select one</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->storage_type == 'hdd' ? 'selected' : '' }} value="hdd">HardDisk</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->storage_type == 'ssd' ? 'selected' : '' }} value="ssd">SSD</option>
                                        </select>
                                    </div>

                                    <!-- storage_m2_support -->
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="storage_m2_support">M.2 Supported?</label>
                                        <select name="storage_m2_support" id="storage_m2_support" class="form-control select" data-placeholder="Select one">
                                            <option value="">Select one</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->storage_m2_support == 1 ? 'selected' : '' }} value="1">Yes</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->storage_m2_support == 0 ? 'selected' : '' }} value="0">No</option>
                                        </select>
                                    </div>

                                    <!-- storage_sata_support -->
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="storage_sata_support">SATA Supported?</label>
                                        <select name="storage_sata_support" id="storage_sata_support" class="form-control select" data-placeholder="Select one">
                                            <option value="">Select one</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->storage_sata_support == 1 ? 'selected' : '' }} value="1">Yes</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->storage_sata_support == 0 ? 'selected' : '' }} value="0">No</option>
                                        </select>
                                    </div>

                                    <!-- storage_default_tdp -->
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="storage_default_tdp">Default TDP</label>
                                        <input placeholder="LGA1700" type="text" name="storage_default_tdp" id="storage_default_tdp" class="form-control number" value="{{ $model->pc_builder ? $model->pc_builder->storage_default_tdp : 0 }}">
                                    </div>
                                </div>

                                <!-- psu -->
                                <div class="row core-component-item-area" id="psu_area" style="display: {{ $model->pc_builder && $model->pc_builder->comp_type == 'psu' ? 'block' : 'none' }};"></div>

                                <!-- graphics_card -->
                                <div class="row core-component-item-area" id="gCard_area" style="display: {{ $model->pc_builder && $model->pc_builder->comp_type == 'graphics-card' ? 'block' : 'none' }};">
                                    <!-- gc_supported_pcie_slot -->
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="gc_supported_pcie_slot">Supportd PCIe Slot</label>
                                        <select name="gc_supported_pcie_slot" id="gc_supported_pcie_slot" class="form-control select" data-placeholder="Select one">
                                            <option value="">Select one</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->gc_supported_pcie_slot == 'pcie-2' ? 'selected' : '' }} value="pcie-2">PCIe 2</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->gc_supported_pcie_slot == 'pcie-3' ? 'selected' : '' }} value="pcie-3">PCIe 3</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->gc_supported_pcie_slot == 'pcie-4' ? 'selected' : '' }} value="pcie-4">PCIe 4</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->gc_supported_pcie_slot == 'other' ? 'selected' : '' }} value="other">Other</option>
                                        </select>
                                    </div>
                                    <!-- gc_supported_form_factor -->
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="gc_supported_form_factor">Supportd Form Factor</label>
                                        <select name="gc_supported_form_factor" id="gc_supported_form_factor" class="form-control select" data-placeholder="Select one">
                                            <option value="">Select one</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->gc_supported_form_factor == 'atx' ? 'selected' : '' }} value="atx">ATX</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->gc_supported_form_factor == 'matx' ? 'selected' : '' }} value="matx">MATX</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->gc_supported_form_factor == 'mitx' ? 'selected' : '' }} value="mitx">Mini iTX</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->gc_supported_form_factor == 'other' ? 'selected' : '' }} value="other">Other</option>
                                        </select>
                                    </div>
                                    <!-- gc_default_tdp -->
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="gc_default_tdp">Default TDP</label>
                                        <input type="text" name="gc_default_tdp" id="gc_default_tdp" class="form-control number" value="{{ $model->pc_builder ? $model->pc_builder->gc_default_tdp : 0 }}">
                                    </div>
                                </div>

                                <!-- cpu_cooler -->
                                <div class="row core-component-item-area" id="cpu_cooler_area" style="display: {{ $model->pc_builder && $model->pc_builder->comp_type == 'cpu_cooler' ? 'block' : 'none' }};">
                                    <!-- cc_default_tdp -->
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="cc_default_tdp">Default TDP</label>
                                        <input type="text" name="cc_default_tdp" id="cc_default_tdp" class="form-control number" value="{{ $model->pc_builder ? $model->pc_builder->cc_default_tdp : 0 }}">
                                    </div>
                                </div>

                                <!-- casing_area -->
                                <div class="row core-component-item-area" id="casing_area" style="display: {{ $model->pc_builder && $model->pc_builder->comp_type == 'casing' ? 'block' : 'none' }};">
                                    <!-- casing_form_factor -->
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="casing_form_factor">Form Factor</label>
                                        <select name="casing_form_factor" id="casing_form_factor" class="form-control select" data-placeholder="Select One">
                                            <option value="">Select one</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->casing_form_factor == 'atx' ? 'selected' : '' }} value="atx">ATX</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->casing_form_factor == 'matx' ? 'selected' : '' }} value="matx">MATX</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->casing_form_factor == 'mitx' ? 'selected' : '' }} value="mitx">Mini-ITX</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->casing_form_factor == 'other' ? 'selected' : '' }} value="other">Other</option>
                                        </select>
                                    </div>

                                    <!-- casing_psu_installed -->
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="casing_psu_installed">PSU Installed</label>
                                        <select name="casing_psu_installed" id="casing_psu_installed" class="form-control select" data-placeholder="Select One">
                                            <option value="">Select one</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->casing_psu_installed == 1 ? 'selected' : '' }} value="1">Yes</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->casing_psu_installed == 0 ? 'selected' : '' }} value="0">No</option>
                                        </select>
                                    </div>

                                    <!-- casing_fan_installed -->
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="casing_fan_installed">Fan Installed</label>
                                        <select name="casing_fan_installed" id="casing_fan_installed" class="form-control select" data-placeholder="Select One">
                                            <option value="">Select one</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->casing_fan_installed == 1 ? 'selected' : '' }} value="1">Yes</option>
                                            <option {{ $model->pc_builder && $model->pc_builder->casing_fan_installed == 0 ? 'selected' : '' }} value="0">No</option>
                                        </select>
                                    </div>

                                    <!-- casing_number_of_fan_front -->
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="casing_number_of_fan_front">Number of FAN front </label>
                                        <input type="text" name="casing_number_of_fan_front" id="casing_number_of_fan_front" class="form-control number" value="{{ $model->pc_builder ? $model->pc_builder->casing_number_of_fan_front : 0 }}">
                                    </div>

                                    <!-- casing_number_of_fan_top -->
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="casing_number_of_fan_top">Number of FAN Top </label>
                                        <input type="text" name="casing_number_of_fan_top" id="casing_number_of_fan_top" class="form-control number" value="{{ $model->pc_builder ? $model->pc_builder->casing_number_of_fan_top : 0 }}">
                                    </div>

                                    <!-- casing_number_of_fan_back -->
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="casing_number_of_fan_back">Number of FAN Back </label>
                                        <input type="text" name="casing_number_of_fan_back" id="casing_number_of_fan_back" class="form-control number" value="{{ $model->pc_builder ? $model->pc_builder->casing_number_of_fan_back : 0 }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7 col-md-7">
                <div class="row">
                    <!-- Product Information -->
                    <div class="col-md-12 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="h5 mb-0">Product Information</h2>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="add_to_pc_build">Add this product as PC Builder item?</label>
                                        <select name="add_to_pc_build" id="add_to_pc_build" class="form-control">
                                            <option {{ $model->details->pc_builder_item == 1 ? 'selected' : '' }} value="1">Yes</option>
                                            <option {{ $model->details->pc_builder_item == 0 ? 'selected' : '' }} value="0">No</option>
                                        </select>
                                    </div>

                                    <div class="col-md-12 form-group mb-3">
                                        <label for="points">Points <span class="text-danger">*</span></label>
                                        <input type="text" name="points" id="points" class="form-control number" value="{{ $model->points ? $model->points : 0 }}" required>
                                    </div>

                                    <div class="col-md-12 form-group mb-3">
                                        <label for="product_type">Product Type <span class="text-danger">*</span></label>
                                        <select name="product_type" id="product_type" class="form-control">
                                            <option {{ $model->product_type == 'digital' ? 'selected' : '' }} value="digital">Digital</option>
                                            <option {{ $model->product_type == 'physical' ? 'selected' : '' }} value="physical">Physical</option>
                                        </select>
                                    </div>

                                    <div class="col-md-12 form-group mb-3">
                                        <label for="name">Product name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" id="name" class="form-control" required
                                            value="{{ $model->name }}">
                                    </div>

                                    <div class="col-md-12 form-group mb-3">
                                        <label for="slug">Product slug <span class="text-danger">*</span></label>
                                        <input type="text" name="slug" id="slug" class="form-control" required
                                            value="{{ $model->slug }}">
                                    </div>

                                    <div class="col-md-12 form-group mb-3">
                                        <label for="category_id">Category <span class="text-danger">*</span></label>
                                        <input type="hidden" id="defaultCategoryImage"
                                            value="{{ asset($model->category->photo) }}">
                                        <select name="category_id[]" id="category_id"
                                            class="form-control category_id007 select">
                                            @if ($model->category_id)
                                                <option selected value="{{ $model->category_id }}">
                                                    {{ $model->category->name }}</option>
                                            @endif
                                        </select>
                                    </div>

                                    <div class="Sub_Categories row"></div>

                                    <div class="col-md-12 form-group mb-3">
                                        <label for="brand_id">Brand</label>
                                        <select name="brand_id" id="brand_id" class="form-control">
                                            @if ($model->brand_id)
                                                <option selected value="{{ $model->brand_id }}">{{ $model->brand->name }}
                                                </option>
                                            @endif
                                        </select>
                                    </div>

                                    @if ($model->brand_type_id != null)
                                        <div class="col-md-12 form-group mb-3" id="brand_type_area">
                                            <label for="brand_type_id">Brand type</label>
                                            <select name="brand_type_id" id="brand_type_id" class="form-control select">
                                                @if ($brandTypes)
                                                    @foreach ($brandTypes as $brandType)
                                                        <option {{ $brandType->id == $model->brand_type_id ? 'selected' : '' }} value="{{ $brandType->id }}">{{ $brandType->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    @else
                                        <div class="col-md-12 form-group mb-3" style="display:none;" id="brand_type_area">
                                            <label for="brand_type_id">Brand type</label>
                                            <select name="brand_type_id" id="brand_type_id" class="form-control"></select>
                                        </div>
                                    @endif
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="unit">Unit</label>
                                        <select name="unit" id="unit" class="form-control select" >
                                            <option {{ $model->details->unit == 'piece' ? 'selected' : '' }} value="piece">Piece</option>
                                            <option {{ $model->details->unit == 'kg' ? 'selected' : '' }} value="kg">KG</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="minus_stock">Minus Stock <span class="text-danger">*</span></label>
                                        <input type="number" name="minus_stock" min="0" max="9999999999" id="minus_stock" class="form-control" value="{{$model->minus_stock}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Images -->
                    <div class="col-md-12 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="h5 mb-0">Product Images</h2>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="images">Gallery Images (540 X 600)</label>
                                        <input type="file" name="images[]" id="images" class="form-control" multiple
                                            data-max-file-size="2M">
                                        <small class="text-muted">These images are visible in product details page gallery.
                                            Use 600x600 sizes images.</small>
                                    </div>

                                    <div id="imagePreview" class="col-md-12.mb-3">
                                        @if ($model->image)
                                            <div class="row">
                                                @foreach ($model->image as $key => $image)
                                                    <div class="col-md-2">
                                                        <img src="{{ asset($image->image) }}"
                                                            alt="{{ $model->title . ' - ' . $key }}">
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                    <div class="col-md-12 form-group">
                                        <label for="thumb_image">Thumbnail Image (540 X 600)</label>
                                        <input type="file" name="thumb_image" id="thumb_image"
                                            class="form-control dropify" data-max-file-size="2M"
                                            data-default-file="{{ $model->thumb_image ? asset($model->thumb_image) : '' }}">
                                        <small class="text-muted">This image is visible in all product box. Use 300x300
                                            sizes image. Keep some blank space around main object of your image as we had to
                                            crop some edge in different devices to make it responsive.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Videos -->
                    <div class="col-md-12 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="h5 mb-0">Product Videos</h2>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="video_provider">Video Provider</label>
                                        <input type="text" name="video_provider" id="video_provider"
                                            class="form-control" placeholder="YouTube" value="{{ $model->video_provider }}">
                                    </div>

                                    <div class="col-md-12 form-group mb-3">
                                        <label for="video_link">Video Link</label>
                                        <textarea name="video_link" id="video_link" cols="30" rows="4" class="form-control">{{ $model->video_link }}</textarea>
                                            <small class="text-muted">Use the proper embed code from youtube.</small>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Description -->
                    <div class="col-md-12 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="h5 mb-0">Product Description</h2>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @include('backend.components.descriptionInput', [
                                        'description' => $model->details->description,
                                    ])
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SEO Meta Tags -->
                    <div class="col-md-12 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="h5 mb-0">SEO Meta Tags</h2>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="site_title">Site title</label>
                                        <input type="text" name="site_title" id="site_title" class="form-control"
                                            placeholder="Site Title" value="{{ $model->details->site_title }}">
                                    </div>
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="meta_title">Meta title</label>
                                        <input type="text" name="meta_title" id="meta_title" class="form-control"
                                            placeholder="Meta Title" value="{{ $model->details->meta_title }}">
                                    </div>
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="meta_keyword">Meta keyword</label>
                                        <textarea name="meta_keyword" id="meta_keyword" cols="30" rows="4" class="form-control">{{ $model->details->meta_keyword }}</textarea>
                                    </div>
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="meta_description">Meta description</label>
                                        <textarea name="meta_description" id="meta_description" cols="30" rows="4" class="form-control">{{ $model->details->meta_description }}</textarea>
                                    </div>
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="meta_article_tags">Meta article tag</label>
                                        <textarea name="meta_article_tags" id="meta_article_tags" cols="30" rows="4" class="form-control">{{ $model->details->meta_article_tags }}</textarea>
                                    </div>
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="meta_script_tags">Meta script tag</label>
                                        <textarea name="meta_script_tags" id="meta_script_tags" cols="30" rows="4" class="form-control">{{ $model->details->meta_script_tags }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Laptop Finder Module -->
                    <div style="display: {{ $model->category->hasParentCategory(get_settings('default_laptop_category')) ? 'block' : 'none'}}" class="col-md-12 mb-4 laptop-finder-module">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="h5 mb-0">Laptop Finder</h2>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 form-group mb-4">
                                        <label for="laptop_budget">Laptop Budget</label>
                                        <select name="laptop_budget" data-placeholder="Select Laptop Budget" id="laptop_budget" class="form-control select">
                                            <option value="">Select Laptop Budget</option>
                                            @foreach ($laptopBudgets as $laptopBudget)
                                                <option {{ $model->budgets && $model->budgets->budget_id == $laptopBudget['id'] ? 'selected' : '' }} value="{{ $laptopBudget['id'] }}">Up To {{ $laptopBudget['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-12 form-group mb-4">
                                        <label for="laptop_purpose">Laptop purpose </label>
                                        <select name="laptop_purpose[]" data-placeholder="Select Laptop purpose " id="laptop_purpose" class="form-control select" multiple>
                                            <option value="">Select Laptop purpose </option>
                                            @php
                                                $purposeIds = $model->purposes->pluck('purpose_id')->toArray();
                                            @endphp
                                            @foreach ($laptopPurposes as $laptopPurpose)
                                                <option {{ in_array($laptopPurpose['id'], $purposeIds) ? 'selected' : '' }} value="{{ $laptopPurpose['id'] }}">{{ $laptopPurpose['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-12 form-group mb-4">
                                        <label for="laptop_screen_size">Laptop screen size </label>
                                        <select name="laptop_screen_size" data-placeholder="Select Laptop screen size " id="laptop_screen_size" class="form-control select">
                                            <option value="">Select Laptop screen size </option>
                                            @foreach ($laptopScreenSizes as $laptopScreenSize)
                                                <option {{ $model->screenSizes && $model->screenSizes->size_id == $laptopScreenSize['id'] ? 'selected' : '' }} value="{{ $laptopScreenSize['id'] }}">{{ $laptopScreenSize['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-12 form-group mb-4">
                                        <label for="laptop_portability">Laptop Portability </label>
                                        <select name="laptop_portability" data-placeholder="Select Laptop Portability " id="laptop_portability" class="form-control select">
                                            <option value="">Select Laptop Portability </option>
                                            @foreach ($laptopPortables as $laptopPortable)
                                                <option {{ $model->portabilites && $model->portabilites->portable_id == $laptopPortable['id'] ? 'selected' : '' }} value="{{ $laptopPortable['id'] }}">{{ $laptopPortable['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-12 form-group mb-4">
                                        <label for="laptop_features">Laptop Features </label>
                                        <select name="laptop_features[]" data-placeholder="Select Laptop Features " id="laptop_features" class="form-control select" multiple>
                                            <option value="">Select Laptop Features </option>
                                            @php
                                                $featurIds = $model->features->pluck('feature_id')->toArray();
                                            @endphp
                                            @foreach ($laptopFeatures as $laptopFeature)
                                                <option {{ in_array($laptopFeature['id'], $featurIds) ? 'selected' : '' }} value="{{ $laptopFeature['id'] }}">{{ $laptopFeature['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="row">

                    <!-- Discount -->
                    <div class="col-md-12 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="h5 mb-0">Discount</h2>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="is_discounted">Discount Available</label>
                                        <select name="is_discounted" id="is_discounted" class="form-control">
                                            <option {{ $model->is_discounted == 0 ? 'selected' : '' }} value="0">No
                                            </option>
                                            <option {{ $model->is_discounted == 1 ? 'selected' : '' }} value="1">Yes
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="discount_type">Discount Type </label>
                                        <select name="discount_type" id="discount_type" class="form-control"
                                            {{ $model->is_discounted == 0 ? 'disabled' : '' }}>
                                            <option {{ $model->discount_type == 'amount' ? 'selected' : '' }}
                                                value="amount">Amount</option>
                                            <option {{ $model->discount_type == 'amount' ? 'percentage' : '' }}
                                                value="percentage">Percent</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="discount">Amount</label>
                                        <input type="text" name="discount" id="discount" class="form-control"
                                            value="{{ $model->discount_type == 'amount' ? number_format(covert_to_defalut_currency($model->discount), 2) : $model->discount }}"
                                            {{ $model->is_discounted == 0 ? 'disabled' : '' }}>
                                    </div>
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="discount_start_date">Discount start date</label>
                                        <input type="text" name="discount_start_date" id="discount_start_date"
                                            class="form-control date" {{ $model->is_discounted == 0 ? 'disabled' : '' }}
                                            value="{{ $model->discount_start_date ? get_system_date($model->discount_start_date) : '' }}">
                                    </div>
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="discount_end_date">Discount end date</label>
                                        <input type="text" name="discount_end_date" id="discount_end_date"
                                            class="form-control date" {{ $model->is_discounted == 0 ? 'disabled' : '' }}
                                            value="{{ $model->discount_end_date ? get_system_date($model->discount_end_date) : '' }}">
                                    </div>
                                    <div id="date_error" style="color: red; display: none;"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="col-md-12 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="h5 mb-0">Status</h2>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 form-group mb-3">
                                        <select name="status" id="status" class="form-control">
                                            <option {{ $model->status == '1' ? 'selected' : '' }} value="1">Active</option>
                                            <option {{ $model->status == '0' ? 'selected' : '' }} value="0">Inactive
                                        </select>
                                    </div>
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="stage">Stage </label>
                                        <select name="stage" id="stage" class="form-control select">
                                            <option {{ $model->stage == 'normal' ? 'selected' : '' }} value="normal">Normal</option>
                                            <option {{ $model->stage == 'pre-order' ? 'selected' : '' }} value="pre-order">Pre Order</option>
                                            <option {{ $model->stage == 'upcoming' ? 'selected' : '' }} value="upcoming">Upcoming</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Feature Product -->
                    <div class="col-md-12 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="h5 mb-0">Feature Product</h2>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="is_featured">Feature Product</label>
                                        <select name="is_featured" id="is_featured" class="form-control">
                                            <option {{ $model->is_featured == 0 ? 'selected' : '' }} value="0">No
                                            </option>
                                            <option {{ $model->is_featured == 1 ? 'selected' : '' }} value="1">Yes
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Return Policy -->
                    <div class="col-md-12 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="h5 mb-0">Return Policy</h2>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="is_returnable">Is Returnable</label>
                                        <select name="is_returnable" id="is_returnable" class="form-control">
                                            <option {{ $model->is_returnable == 0 ? 'selected' : '' }} value="0">No
                                            </option>
                                            <option {{ $model->is_returnable == 1 ? 'selected' : '' }} value="1">Yes
                                            </option>
                                        </select>
                                    </div>

                                    <label for="return_deadline">Return Deadline</label>
                                    <div class="col-md-12 input-group mb-3">
                                        <input type="text" min="0" class="form-control number"
                                            name="return_deadline" id="return_deadline"
                                            {{ $model->is_returnable == 0 ? 'disabled' : '' }}
                                            value="{{ $model->return_deadline }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="basic-addon2">Days</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Low Stock Quantity Warning -->
                    <div class="col-md-12 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="h5 mb-0">Low Stock Quantity Warning</h2>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="low_stock_quantity">Quantity</label>
                                        <input type="number" min="0" name="low_stock_quantity"
                                            id="low_stock_quantity" class="form-control"
                                            value="{{ $model->details ? $model->details->low_stock_quantity : 0 }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cash on Delivery -->
                    <div class="col-md-12 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="h5 mb-0">Cash on Delivery</h2>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- shipping_cost -->
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="shipping_cost">Shipping Cost <span class="text-danger">*</span></label>
                                        <input type="text" name="shipping_cost" id="shipping_cost" class="form-control" value="{{ covert_to_defalut_currency($model->details->shipping_cost) }}" required >
                                    </div>

                                    <div class="col-md-12 form-group mb-3">
                                        <label for="cash_on_delivery">Cash on Delivery</label>
                                        <select name="cash_on_delivery" id="case_on_deliver" class="form-control">
                                            <option
                                                {{ $model->details ? ($model->details->cash_on_delivery == 1 ? 'selected' : '') : 0 }}
                                                value="1">Available</option>
                                            <option
                                                {{ $model->details ? ($model->details->cash_on_delivery == 0 ? 'selected' : '') : 0 }}
                                                value="0">Not available</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Estimate Shipping Time -->
                    <div class="col-md-12 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="h5 mb-0">Estimate Shipping Time</h2>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <label for="est_shipping_time">Estimate Shipping Time</label>
                                    <div class="col-md-12 input-group mb-3">
                                        <input type="text" min="0" class="form-control number"
                                            name="est_shipping_time" id="est_shipping_time"
                                            value="{{ $model->details ? $model->details->est_shipping_days : '' }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="basic-addon2">Days</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Vat & TAX -->
                    <div class="col-md-12 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="h5 mb-0">Vat & TAX</h2>
                            </div>
                            <div class="card-body">
                                @foreach ($model->taxes as $tax)
                                    <div class="row">
                                        <div class="col-md-6 form-group mb-3">
                                            <input type="hidden" name="tax_id[]" value="{{ $tax->id }}">
                                            <label for="taxes_{{ $tax->id }}">{{ $tax->tax_model->name }}</label>
                                            <input type="text" min="0" name="taxes[]"
                                                id="taxes_{{ $tax->id }}" class="form-control"
                                                value="{{ $tax->tax }}">
                                        </div>
                                        <div class="col-md-6 form-group mb-3">
                                            <label for="tax_type_{{ $tax->id }}">Type</label>
                                            <select name="tax_types[]" id="tax_type_{{ $tax->id }}"
                                                class="form-control">
                                                <option {{ $tax->tax_type == 'amount' ? 'select' : '' }} value="flat">
                                                    Flat</option>
                                                <option {{ $tax->tax_type == 'pencent' ? 'select' : '' }} value="percent">
                                                    Percent</option>
                                            </select>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 form-group mb-3 text-end">
                <button class="btn btn-soft-success" type="submit" id="submit">
                    <i class="bi bi-send"></i>
                    Update
                </button>
                <button class="btn btn-soft-warning" style="display: none;" id="submitting" type="button" disabled>
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Loading...
                </button>
            </div>
        </div>
    </form>

    <input type="hidden" id="default_laptop_category" value="{{ get_settings('default_laptop_category') }}">

@endsection

@push('script')
    <script src="{{ asset('backend/assets/js/dropify.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/tempus-dominus.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/pc-builder.js') }}"></script>
    <script>
        $(document).ready(function() {
            _componentSelect();
            _formValidation();
            _initCkEditor("editor");

            $('.dropify').dropify({
                imgFileExtensions: ['png', 'jpg', 'jpeg', 'gif', 'bmp', 'webp']
            });

            // For image preview
            document.getElementById('images').addEventListener('change', function(event) {
                var imagePreview = document.getElementById('imagePreview');
                imagePreview.innerHTML = '';

                var files = event.target.files;

                if (files) {
                    for (var i = 0; i < files.length; i++) {
                        var file = files[i];

                        if (file.type.startsWith('image/')) {
                            var reader = new FileReader();

                            reader.onload = function(e) {
                                var imgElement = document.createElement('img');
                                imgElement.src = e.target.result;
                                imgElement.style.maxWidth = '150px';
                                imgElement.style.margin = '10px';
                                imgElement.classList.add('img-thumbnail');

                                imagePreview.appendChild(imgElement);
                            };

                            reader.readAsDataURL(file);
                        }
                    }
                }
            });

            const element = document.getElementById('discount_start_date');
            const input = document.getElementById('discount_start_date');
            const picker = new tempusDominus.TempusDominus(element, {
                defaultDate: new Date(),
                display: {
                    components: {
                        calendar: true,
                        date: true,
                        month: true,
                        year: true,
                        decades: true,
                        clock: false
                    }
                }
            });

            const element1 = document.getElementById('discount_end_date');
            const input1 = document.getElementById('discount_end_date');
            const picker1 = new tempusDominus.TempusDominus(element1, {
                defaultDate: new Date(),
                display: {
                    components: {
                        calendar: true,
                        date: true,
                        month: true,
                        year: true,
                        decades: true,
                        clock: false
                    }
                }
            });

            $('#discount_start_date, #discount_end_date').on('change', function() {
                const startDateValue = $('#discount_start_date').val();
                const endDateValue = $('#discount_end_date').val();
                const $errorDiv = $('#date_error');

                $errorDiv.empty();

                const today = new Date();
                const startDate = new Date(startDateValue);

                if (!endDateValue) {
                    $errorDiv.text('End date cannot be null.').show();
                    return;
                }

                const endDate = new Date(endDateValue);
                if (endDate < startDate) {
                    $errorDiv.text('End date must be greater than or equal to start date.').show();
                } else {
                    $errorDiv.hide();
                }
            });

            function generateSlug(name) {
                return name
                    .toString()
                    .toLowerCase()
                    .trim()
                    .replace(/&/g, '-and-')
                    .replace(/[^a-z0-9 -]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');
            }

            $('#name').on('input', function() {
                const name = $(this).val();
                const slug = generateSlug(name);
                $('#slug').val(slug);

                // Check if the slug exists
                $.ajax({
                    url: '{{ route('admin.slug.check') }}',
                    type: 'GET',
                    data: {
                        slug: slug,
                        id: '{{ $model->id }}',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.exists) {
                            const timestamp = Date.now();
                            $('#slug').val(slug + '-' + timestamp);
                        }
                    }
                });
            });
        });
    </script>
    <script src="{{ asset('backend/assets/js/addproduct.js') }}"></script>
@endpush
