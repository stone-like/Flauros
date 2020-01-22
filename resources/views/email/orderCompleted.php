@component('mail::message')

to {{$user->name}}

Your Order

{{$order->total}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
