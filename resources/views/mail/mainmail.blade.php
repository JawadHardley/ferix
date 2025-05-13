<x-mail::message>
    click that link to view order

    <x-mail::button :url="''">
        click me
    </x-mail::button>

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>