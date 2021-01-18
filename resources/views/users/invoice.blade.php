<html>
<head>
    <!-- Styles -->
    <link rel="stylesheet" href="{{ public_path('css/ticket.css') }}" media="all" />
    <link rel="stylesheet" href="{{ public_path('css/bootstrap3.min.css') }}" media="all" />
</head>
<body>
<div class="container-fluid align-items-center">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="receipt-main col-xs-10 col-sm-10 col-md-6 col-xs-offset-1 col-sm-offset-1 col-md-offset-3">
                    <div class="row">
                        <div class="receipt-header receipt-header-mid">
                            <div class="col-xs-8 col-sm-8 col-md-8 text-left">
                                <div class="receipt-right">
                                    <h5>{{Auth::user()->name}}</h5>
                                    <p><b>Mobile :</b> {{Auth::user()->nomor_hp}}</p>
                                    <p><b>Email :</b> {{Auth::user()->email}}</p>
                                </div>
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-4">
                                <div class="receipt-left">
                                    <h3>INVOICE</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div style="display: none">
                            {{ $total = 0 }}
                        </div>
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Ticket</th>
                                <th>Harga</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($tickets as $ticket)
                            <tr>
                                <td class="col-md-9">{{ $ticket->concert->judul }}</td>
                                <td class="col-md-3"><i class="fa fa-inr"></i> Rp. {{ $ticket->concert->price }}</td>
                                <div style="display: none"> {{$total += $ticket->concert->price }}</div>
                            </tr>
                            @endforeach
                            <tr>

                                <td class="text-right"><h2><strong>Total: </strong></h2></td>
                                <td class="text-left text-danger"><h2><strong><i class="fa fa-inr"></i> Rp. {{ $total }}</strong></h2></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
