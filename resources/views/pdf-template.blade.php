<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>DD Group API </title>

    <link rel="preload" href="{{ public_path('fonts/Geogrotesque-Bold.woff2') }}" as="font" type="font/woff2"
        crossorigin>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <style>
    @font-face {
        font-family: OptimusPrinceps;
        src: url('/fonts/Geogrotesque-Bold.woff2');
    }

    body {
        font-family: 'Geogrotesque Bd';
        font-weight: bold;
        font-style: normal;
    }

    .page-break {
        page-break-after: always;
    }
    </style>
    </style>
</head>

<body>

    <table width="100%">
        <tr>
            <td valign="top"><img src="https://via.placeholder.com/150" alt="" width="150" /></td>
            <td align="right">
                <h3>Deck-Dry Polska sp. z o.o.</h3>
                <pre>
                ul. Wenus 73A, 80-299 Gdańsk
                tel. +48 58 585 97 37
                www.ddgro.eu
                sales@ddgro.eu
            </pre>
            </td>
        </tr>

    </table>

    <table width="100%">
        <tr>
            <td><strong>From:</strong> {{ $application->data->lowest }} </td>
            <td><strong>To:</strong>{{$application->over_main_system_name}}</td>
        </tr>

    </table>

    <br />

    <table width="100%">
        <thead style="background-color: lightgray;">
            <tr>
                <th>#</th>
                <th>Description</th>
                <th>Quantity</th>
                <th>Unit Price $</th>
                <th>Total $</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th scope="row">1</th>
                <td>Playstation IV - Black</td>
                <td align="right">1</td>
                <td align="right">1400.00</td>
                <td align="right">1400.00</td>
            </tr>
            <tr>
                <th scope="row">1</th>
                <td>Metal Gear Solid - Phantom</td>
                <td align="right">1</td>
                <td align="right">105.00</td>
                <td align="right">105.00</td>
            </tr>
            <tr>
                <th scope="row">1</th>
                <td>Final Fantasy XV - Game</td>
                <td align="right">1</td>
                <td align="right">130.00</td>
                <td align="right">130.00</td>
            </tr>
        </tbody>

        <tfoot>
            <tr>
                <td colspan="3"></td>
                <td align="right">Subtotal $</td>
                <td align="right">1635.00</td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td align="right">Tax $</td>
                <td align="right">294.3</td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td align="right">Total $</td>
                <td align="right" class="gray">$ 1929.3</td>
            </tr>
        </tfoot>
    </table>

</body>

</html>