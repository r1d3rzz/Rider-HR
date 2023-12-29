@props(['name','value'=>''])

<div class="mb-4">
    <x-label :name="$name" />
    <textarea name="{{$name}}" id="{{$name}}" class="form-control">{{old($name,$value)}}</textarea>
    <x-error :name="$name" />
</div>
