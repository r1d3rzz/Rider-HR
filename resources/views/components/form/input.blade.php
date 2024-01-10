@props(['name','type'=>'text','optional'=>false,'value'=>''])

<div class="mb-4">
    <x-label :name="$name" :optional="$optional" />
    <input type="{{$type}}" name="{{$name}}" id="{{$name}}" value="{{old($name,$value)}}"
        {{$attributes->merge(['class'=>'form-control'])}}>
    <x-error :name="$name" />
</div>