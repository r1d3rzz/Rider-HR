@props(['title'=>''])

<div class="container-fluid">
    <div class="row shadow-sm p-2 bg-white">
        <div class="d-flex justify-content-between col-lg-8 mx-auto align-items-center">
            <div>
                <a id="show-sidebar" class="btn btn-sm" href="#">
                    <i class="fas fa-bars fs-5"></i>
                </a>
            </div>
            <div class="fs-5">{{$title}}</div>
            <div></div>
        </div>
    </div>
</div>
