<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>DD Group API </title>

    <link rel="preload" href="{{ public_path('fonts/Geogrotesque-Bold.woff2') }}" as="font" type="font/woff2"
        crossorigin>


    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- ddgro font -->
    <link rel="stylesheet" href="{{ asset('fonts/Geogrotesque-Bold.woff2') }}">


    <!-- Styles -->
    <style>
    @font-face {
        font-family: DejaVu Sans;
        src: url("{{ public_path('fonts/Geogrotesque-Bold.woff2') }}") format('woff2');
    }

    body {
        font-family: DejaVu Sans;
        font-weight: normal;
        font-style: normal;


    }

    .page-break {
        page-break-after: always;
    }

    tr th {
        /* padding-top: 5px;
        padding-bottom: 10px;
        line-height: 12px; */
    }

    .products tr:nth-child(even) {
        background-color: #f2f2f2;
    }
    </style>
    </style>
</head>

<body>

    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAVcAAABICAMAAAB1GEbQAAAAY1BMVEUAAAEAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAD1kR71kR71kR71kR71kR71kR71kR71kR71kR71kR71kR71kR71kR71kR71kR4AAAD1kR4Xco8GAAAAH3RSTlMAECAwQFBgcICQoLDA0ODw8ODQwLCgkIBwYFBAMCAQSgP5XQAABzlJREFUeNrtnIuWqiwUgLEas7JESbG8xPs/5S8JbByBysuc+WfxrXXWiXTj7hMBb4Peo62+gzxO4oeTGD2p2BhCG+RZ3msHqZFnBa+MFcizhleWtcizglcvdiWvLEOeqbTVCJr6PnYdSizE+snsstRCLEGeRalZT43eYbP7xna4fPt9+QaWfVQv6tiZCKzB46VQl+Vb8waWgX7Qw25Nw2IImYTjxckpRC/4OiWjsO7r/cPIJXQFX46aRqhL59UWLocNWoKUcVL0BruHiWQvl0fmRL+cdV5MMa5pznnjDI63b3mFdG0/aLtzskVuCvakRR2TvHY/JQCvJo7IyuEBuL0ClxfB4UyvIuGX81c3lTYjmOj1cQ7Aq3E5snB8TPD6iCDYSDjX62M/3ytawCsX524AttY6yetjA8FGwrlek83v8No1IXeiX+b6Jnr9gmCLlpleH4d/4fUQ9RzjBJoQJJpEglOs94ljLpBkHA0Ar8cdsD/DXtSCzyI4PsPx8YHXcwTEstZkttfmA6+jGoNI/yWiEGnLT3BojvhSKW7slz12SCcRW7AE75TZzftev4w9/hYdYicH5ObmPjFwe4U5bTLwCoT2oesEg8S7Xs9qC0djMHz9ntfxFoKH/HYm195rX/jcq+qltuDVaA+NSKA6p9dxC/+yBgcX8f1Uryhexqs4kb32pQle5S8Mwet7DWADO8Th9RwDF2j7gTEYjo9kqtel2mvWe731pSleY2HT4hVdLInuTL/7nflAEkCw9Vx7otdAdiQzrxPkvVbc9sUpXqMXXmO31/hjr5EhGPjY6znWSNRoP4dWaGVUfDHF64+310e4aHu1bGIGt1RoTWVzneA1uLj7182ow7L3ZG6vwBaC5/evlsnpm/OsmoxgEphkTfA6ez5w/txrt4ULBOsEotLTLK9JsMh9Qxi0PvcawgkVeDVoP9kvuhxdXvc7IDyos0wIDgZa48/nr2PO20Xuxzq1ur1uTqoJQaIH0wlQaKgOfofd687Ul8da8EVbI0we2nBu7Cr2L70mUbDEfW58R27GImLJRTtyINFELU9c1wcgcxiTzy6vsIUYluvbE2W5a2XhFAGnh3s+EB/CYJHnB64Negl4tbJ3XM8CPa/v67zp1RIMexn6bzPnwfx18ecy8goNmOr1hNxeD8jIfoZXFL7aiyeHleNrr2HkJLTMB66UjqVO9Oq8XwAj05T7BWav7qviIQypLvPRMmes89m9aI2RtZOwEibTvVqCExVycjTX3+813iGn13iLHGyO072agpNDML76O24Iv8rrJhlLO2yd3uNo87LWfZx8jzJ7hZmeJTg57YPh+sfzuNPqUv5dXj0ez/+A5k5z8h2xLHKzzENJf5JbxkzAmbeRhe47/FmqlDHvdXEKxrzX5cmZ97oCOfNeV4AySUps84HYzRZ5vlMzAfHvtSwJUTdUPAty/ymthNTyQ6uXri2/vIsETZ7i610UrqR8ri9zywlpBheEb7Iga+4iGyIOvBt0ZADpyBtTAbVFhonSUPCFZZepqKvovlJLW0oYoa2shVOIxK44o9qTaxStDWOV/EAGpYLfjhju5UIOqCniC2t4KLQU820tawjP+JNiJWOtNm6MsujAzaig3kbLB4cxFpumPGciLbWZ/mqw/hJbi0UdrRix0OqASW5HL925GJlWWt5Jt1BablChkivhbdyG0i59WoFXsQJvKFfpIqWUjrLIr90/VShUIWOY3q+qhfEGWWCWmbzyoFvK0qpfRtNu3ZtMJU95jdUP9QJDr6zWS7jzpx6n5a0nlWowK7tCoZ6yI/1yqA+8igZ9b1WbpiyrqtqQRadhXBAZXeVe5BL5l7XBK35+uLcwRFFIpXhuk754UWi5edbAazYoYcagWcB/vCMg3BVYw6wwelXm82cbh36AGNtrAQWtM1H/gauMVWOvsGnwqj9hmd55gLsbWO68QDPJD2goFbykmlh/nBHV3VKGYZ5dEZa6vJYszVmm1sdyOAFExzguVH3jug29YovXu81rWxLG9yyBTvfnvFaU6aW8KylDJR9BVJ76SJLC42A2rzVv+yXSG/+4vdKyhYLaVPvcUMO7IHCVd4rkPmpRJtfN+M6oyG3stblmddVl9o+8IqKX2s4YjMJpJp6olbOV+/D8pTB7BfeN8sqpzFnAHsaNGoxSAvuNDLZW93mV6jDCGWSmec3lvs//jdcG66VaiWmfPyettfdCsLrmhsXUy+hVycnQJ15hjOxiOzC4ElpBGGi6YTXLA68wA8vFxvu8VvZaVa32oRmUavixdUmhgFoY0evuE4+roD5YSVu/QYKm4rTGLKDQQHBTUhjjawg25XWjtwaWVfC5orR+/0WhHbDme/d/B9Fr5cizLGKU9X+5bWGo/zNYq9BiL3YVKOtJ/WXtZcmYgJTVCD8fmEyN/X3DVaix97oKNfZeV6El3us63FLvdR2qIvNe16L2f09/Ef4DOEvwYJL5CqMAAAAASUVORK5CYII="
        alt="Logo" width="200">

    <table width="100%" style="padding-top: 14px">
        <tbody>
            <tr>
                <td colspan="2" style="font-size: 14px;"><strong>Oferta indywidualna</strong></td>
                <td style="font-size: 10px; text-align: right;"><span>&nbsp;Wygenerowano:

                        {{ date('Y-m-d', strtotime($application['data']['created_at'])) }} | ważna do:
                        {{ date('Y-m-d', strtotime($application['data']['created_at'] . ' +3 months')) }}
                    </span>

                </td>
            </tr>
        </tbody>
    </table>

    <table width="100%"
        style="border-bottom: 1px solid orange; padding-bottom: 14px; border-top: 1px solid orange; padding-top: 14px; margin-top: 20px;">
        <tbody>

            <tr style="font-size: 10px;">
                <td>&nbsp;</td>
                <td>Dział sprzedaży: Adam Runo: +48 508 000 813 adam.runo@ddgro.eu</td>
                <td>&nbsp;</td>
            </tr>
            <tr style="font-size: 10px;">
                <td>&nbsp;</td>
                <td>Dział obsługi klienta: Greta Sosnowska +48 517 062 150 greta.sosnowska@ddgro.eu</td>
                <td>&nbsp;</td>
            </tr>
            <tr style="font-size: 10px;">
                <td>&nbsp;</td>
                <td>Producent: DECK-DRY POLSKA Sp. z o.o., Wenus 73A, 80-299 Gdańsk, Polska</td>
                <td>&nbsp;</td>
            </tr>
        </tbody>
    </table>


    <table width="100%" style="margin-top: 14px;">
        <tr>
            <td><strong>Przygotowano
                    dla:</strong>{{ is_array($application) ? $application['data']['name_surname'] : '' }}</td>

        </tr>
        <tr>

            <td><strong>Email:</strong>{{ is_array($application) ? $application['data']['email'] : '' }}</td>
        </tr>


    </table>

    <br />


    <!-- main system  -->
    <table class="products" width="100%" style="font-size: 10px;">
        <thead style="background-color: lightgray; text-align: left;">
            <tr>
                <th>&nbsp;&nbsp;Dystans*-Kod </br> &nbsp;</th>
                <th>&nbsp;&nbsp;Nazwa </br> &nbsp;</th>
                <th>&nbsp;&nbsp;Nazwa skrócona </br> &nbsp;</th>
                <th>&nbsp;&nbsp;Wysokość </br> (mm)</th>
                <th>&nbsp;&nbsp;Cena katalogowa </br> ( PLN netto )</th>
                <th>&nbsp;&nbsp;Ilość </br> &nbsp;</th>
                <th>&nbsp;&nbsp;Wartość </br> &nbsp;</th>
            </tr>
        </thead>
        <tbody>

            <!-- under main system  -->
            @if ($application['under_main_system_name'] !== 'initial')

            <tr style="font-size: 12px; text-align: left; background-color: lightgray;">
                <td colspan="7" style="padding-bottom: 10px; padding-top: 6px;">
                    &nbsp;&nbsp;Seria
                    <strong>{{$application['under_main_system_name'] }}</strong>
                    pod {{$application['data']['type'] === 'slab' ? 'płyty' : 'deski' }}
                </td>
            </tr>

            @foreach($application['order_for_under_main_system'] as $product)
            <tr>
                @if(isset($product->distance_code))
                <td scope="row">&nbsp;&nbsp;{{ $product->distance_code }}</td>
                @else
                <td scope="row">&nbsp;&nbsp;---</td>
                @endif
                <td>&nbsp;&nbsp;{{ $product->name }}</td>
                <td>&nbsp;&nbsp;{{ $product->short_name }}</td>
                <td>&nbsp;&nbsp;{{ $product->height_mm }}</td>
                <td>&nbsp;&nbsp;{{ $product->price_net }}</td>
                <td>&nbsp;&nbsp;{{ $product->count }}</td>
                <td>&nbsp;&nbsp;{{ $product->total_price }}</td>
            </tr>
            @endforeach

            @endif

            <!-- main system -->

            <tr style="font-size: 12px; text-align: left; background-color: lightgray;">
                <td colspan="7" style="padding-bottom: 10px; padding-top: 6px;">
                    &nbsp;&nbsp; Wsporniki z wybranego, głównego systemu
                    <strong>{{$application['main_system_name'] }}</strong>
                    pod {{$application['data']['type'] === 'slab' ? 'płyty' : 'deski' }}
                </td>
            </tr>

            @foreach($application['order_for_main_system'] as $product)
            <tr>
                @if(isset($product->distance_code))
                <td scope="row">&nbsp;&nbsp;{{ $product->distance_code }}</td>
                @else
                <td scope="row">&nbsp;&nbsp;---</td>
                @endif
                <td>&nbsp;&nbsp;{{ $product->name }}</td>
                <td>&nbsp;&nbsp;{{ $product->short_name }}</td>
                <td>&nbsp;&nbsp;{{ $product->height_mm }}</td>
                <td>&nbsp;&nbsp;{{ $product->price_net }}</td>
                <td>&nbsp;&nbsp;{{ $product->count }}</td>
                <td>&nbsp;&nbsp;{{ $product->total_price }}</td>
            </tr>
            @endforeach

            <!-- over system -->

            @if ($application['over_main_system_name'] !== 'initial' &&
            !empty($application['order_for_over_main_system']))

            <tr style="font-size: 12px; text-align: left; background-color: lightgray;">
                <td colspan="7" style="padding-bottom: 10px; padding-top: 6px;">
                    &nbsp;&nbsp;System
                    <strong>{{$application['over_main_system_name'] }}</strong>
                    pod {{$application['data']['type'] === 'slab' ? 'płyty' : 'deski' }}
                </td>
            </tr>

            @foreach($application['order_for_over_main_system'] as $product)
            <tr>
                @if(isset($product->distance_code))
                <td scope="row">&nbsp;&nbsp;{{ $product->distance_code }}</td>
                @else
                <td scope="row">&nbsp;&nbsp;---</td>
                @endif
                <td>&nbsp;&nbsp;{{ $product->name }}</td>
                <td>&nbsp;&nbsp;{{ $product->short_name }}</td>
                <td>&nbsp;&nbsp;{{ $product->height_mm }}</td>
                <td>&nbsp;&nbsp;{{ $product->price_net }}</td>
                <td>&nbsp;&nbsp;{{ $product->count }}</td>
                <td>&nbsp;&nbsp;{{ $product->total_price }}</td>
            </tr>
            @endforeach

            @endif

            <!-- over system level 2 highest -->

            @if (
            !empty($application['order_for_over_main_system_level_2']))

            <tr style="font-size: 12px; text-align: left; background-color: lightgray;">
                <td colspan="7" style="padding-bottom: 10px; padding-top: 6px;">
                    &nbsp;&nbsp;System
                    <strong>{{$application['over_main_system_level_2_name'] }}</strong>
                    pod {{$application['data']['type'] === 'slab' ? 'płyty' : 'deski' }}
                </td>
            </tr>

            @foreach($application['order_for_over_main_system_level_2'] as $product)
            <tr>
                @if(isset($product->distance_code))
                <td scope="row">&nbsp;&nbsp;{{ $product->distance_code }}</td>
                @else
                <td scope="row">&nbsp;&nbsp;---</td>
                @endif
                <td>&nbsp;&nbsp;{{ $product->name }}</td>
                <td>&nbsp;&nbsp;{{ $product->short_name }}</td>
                <td>&nbsp;&nbsp;{{ $product->height_mm }}</td>
                <td>&nbsp;&nbsp;{{ $product->price_net }}</td>
                <td>&nbsp;&nbsp;{{ $product->count }}</td>
                <td>&nbsp;&nbsp;{{ $product->total_price }}</td>
            </tr>
            @endforeach

            @endif




        </tbody>

        <tfoot>

            <tr>
                <td colspan="5"></td>
                <td align="right">Łącznie</td>
                <td align="right">{{ $application->total }}</td>
            </tr>
            <tr style="display: none;">
                <td colspan="5"></td>
                <td align="right">Tax $</td>
                <td align="right">294.3</td>
            </tr>
            <tr style="display: none;">
                <td colspan="5"></td>
                <td align="right">Total $</td>
                <td align="right" class="gray">$ 1929.3</td>
            </tr>
        </tfoot>
    </table>

</body>

</html>