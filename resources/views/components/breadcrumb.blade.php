<!-- start page title -->
@props([
    'title' => null,
    'li_1' => '',
])

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
            <h4 class="mb-sm-0 font-size-18">{{ $title }}</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);" id="li-btn">{{ $li_1 }}</a></li>
                    @if(isset($title))
                        <li class="breadcrumb-item active text-capitalize">{{ $title }}</li>
                    @endif
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->
