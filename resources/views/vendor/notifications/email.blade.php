@component('mail::message')
{{-- Greeting --}}
@if (! empty($greeting))
    <table
        width="640"
        cellpadding="0"
        cellspacing="0"
        border="0"
        class="wrapper"
        bgcolor="#F5F5F5"
    >
        <tr>
            <td align="center" valign="top">
                <table
                    width="600"
                    cellpadding="0"
                    cellspacing="0"
                    border="0"
                    class="container"
                >
                    <tr>
                        <td
                            align="center"
                            valign="center"
                            style="
                  padding: 2.5rem 0;
                  display: flex;
                  align-items: center;
                  justify-content: center;
                  "
                        >
                            <h1
                                class="title"
                                style="
                     font-size: 1.5rem;
                     font-family: 'Dosis',
                     sans-serif;
                     color: #666666;
                     margin-left: 1.75rem;
                     text-transform: uppercase;
                     letter-spacing: 2px;
                     "
                            >
                                {{ $greeting }}
                            </h1>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@else
@if ($level === 'error')
# @lang('Whoops!')
@else
# @lang('Hello!')
@endif
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach

{{-- Action Button --}}
@isset($actionText)
<?php
    switch ($level) {
        case 'success':
        case 'error':
            $color = $level;
            break;
        default:
            $color = 'primary';
    }
?>
@component('mail::button', ['url' => $actionUrl, 'color' => $color])
{{ $actionText }}
@endcomponent
@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
@lang('Atte.'),<br>
@lang('Equipo Contract')
@endif

{{-- Subcopy --}}
@isset($actionText)
@slot('subcopy')
@lang(
    "Si tiene problemas al hacer clic en el botón \":actionText\",  copie y pegue la siguiente URL\n".
    'en su navegador web:',
    [
        'actionText' => $actionText,
    ]
) <span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
@endslot
@endisset
@endcomponent
