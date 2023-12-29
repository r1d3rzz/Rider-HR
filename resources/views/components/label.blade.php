@props(['name'=>'','optional'=>false])

<label class="fs-6" for="{{$name}}">{{ucfirst($name)}} <small class="text-muted">{{$optional ? "(Optional)" :
        ""}}</small></label>
