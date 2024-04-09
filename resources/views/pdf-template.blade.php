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

    <img src="data:image/png;base64,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"
        alt="Logo" width="200">

    <table width="100%" style="padding-top: 14px">
        <tbody>
            <tr>
                <td colspan="2" style="font-size: 14px;"><strong>Oferta indywidualna dla:
                        {{ is_array($application) ? $application['data']['name_surname'] : '' }}</strong> |
                    {{ is_array($application) ? $application['data']['email'] : '' }}</td>
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

        <!-- <tfoot style="font-size: 12px; text-align: left; background-color: lightgray;">

            <tr style="text-align: left; font-size: 14px; margin-top: 30px; ">
                <td colspan="5"></td>
                <td style="padding-top: 13px;padding-bottom: 10px;" align="left"></td>
                <td style="padding-top: 13px;padding-bottom: 10px;" align="left">Łącznie: {{ $application['total'] }}
                </td>
            </tr>

        </tfoot> -->
    </table>



    <table style="width: 100%; font-size: 12px; text-align: left; background-color: lightgray;">

        <tr style="text-align: right; font-size: 14px; margin-top: 30px; ">
            <td colspan="12"></td>

            <td style="padding-top: 6px;padding-bottom: 10px; padding-right: 20px;" align="right">Łącznie:
                {{ $application['total'] }}
            </td>
        </tr>

    </table>

    <!-- after products -->

    <table width="100%" style="padding-top: 14px">
        <tbody>
            <tr>
                <td colspan="2" style="font-size: 14px;"><strong>Dlaczego warto zamówić u nas ?
                    </strong>
                </td>
            </tr>
        </tbody>
    </table>

    <table width="100%"
        style="border-bottom: 1px solid orange; padding-bottom: 14px; border-top: 1px solid orange; padding-top: 14px; margin-top: 20px;">
        <tbody>

            <tr style="font-size: 10px;">
                <td>&nbsp;</td>
                <td>- Oferowane produkty są produkowane w Polsce.</td>
                <td>&nbsp;</td>
            </tr>
            <tr style="font-size: 10px;">
                <td>&nbsp;</td>
                <td>- Dostarczamy 1-2 dni na terenie PL.</td>
                <td>&nbsp;</td>
            </tr>
            <tr style="font-size: 10px;">
                <td>&nbsp;</td>
                <td>- Pomożemy Ci obliczyć zapotrzebownie na ilość wsporników i ich wysokość.</td>
                <td>&nbsp;</td>
            </tr>
            <tr style="font-size: 10px;">
                <td>&nbsp;</td>
                <td>- Nasze produkty posiadają Krajową Ocenę Techniczną ITB.</td>
                <td>&nbsp;</td>
            </tr>
            <tr style="font-size: 10px;">
                <td>&nbsp;</td>
                <td>- Zamawiasz dokładnie tyle sztuk ile potrzebujesz.</td>
                <td>&nbsp;</td>
            </tr>
            <tr style="font-size: 10px;">
                <td>&nbsp;</td>
                <td>- Masz możliwość zwrócenia niewykorzystanych ilości.</td>
                <td>&nbsp;</td>
            </tr>
        </tbody>
    </table>

    <table style="width: 100%; margin-left: auto; margin-right: auto; margin-top: 20px;">
        <tbody>
            <tr>
                <td valign="center" align="center"> <img
                        src="data:image/jpeg;base64,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"
                        alt=""></td>
            </tr>
        </tbody>
    </table>
    <!-- DivTable.com -->



</body>

</html>