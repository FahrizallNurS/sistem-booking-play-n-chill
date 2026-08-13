{{--
    4 card kontekstual sesuai $jenisTransaksi:
    - semua   : Total Booking | Total F&B | Total Dibatalkan (gabungan) | Total Pendapatan (gabungan)
    - booking : Total Booking | Booking Selesai | Booking Dibatalkan | Pendapatan Booking
    - fnb     : Total F&B | F&B Selesai | F&B Dibatalkan | Pendapatan F&B
--}}
<div class="row">
    <div class="col-md-3">
        <div class="small-box bg-info">
            <div class="inner">
                @if($jenisTransaksi === 'fnb')
                    <h3>{{ $totalFnb }}</h3>
                    <p>Total F&amp;B</p>
                @else
                    <h3>{{ $totalBooking }}</h3>
                    <p>Total Booking</p>
                @endif
            </div>
            <div class="icon"><i class="fas fa-calendar-check"></i></div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="small-box bg-success">
            <div class="inner">
                @if($jenisTransaksi === 'booking')
                    <h3>{{ $bookingSelesai }}</h3>
                    <p>Booking Selesai</p>
                @elseif($jenisTransaksi === 'fnb')
                    <h3>{{ $fnbSelesai }}</h3>
                    <p>F&amp;B Selesai</p>
                @else
                    <h3>{{ $totalFnb }}</h3>
                    <p>Total F&amp;B</p>
                @endif
            </div>
            <div class="icon"><i class="fas fa-check-circle"></i></div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="small-box bg-danger">
            <div class="inner">
                @if($jenisTransaksi === 'booking')
                    <h3>{{ $bookingDibatalkan }}</h3>
                    <p>Booking Dibatalkan</p>
                @elseif($jenisTransaksi === 'fnb')
                    <h3>{{ $fnbDibatalkan }}</h3>
                    <p>F&amp;B Dibatalkan</p>
                @else
                    <h3>{{ $bookingDibatalkan + $fnbDibatalkan }}</h3>
                    <p>Total Dibatalkan</p>
                @endif
            </div>
            <div class="icon"><i class="fas fa-times-circle"></i></div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="small-box bg-warning">
            <div class="inner">
                @if($jenisTransaksi === 'booking')
                    <h3>Rp {{ number_format($pendapatanBooking, 0, ',', '.') }}</h3>
                    <p>Pendapatan Booking</p>
                @elseif($jenisTransaksi === 'fnb')
                    <h3>Rp {{ number_format($pendapatanFnb, 0, ',', '.') }}</h3>
                    <p>Pendapatan F&amp;B</p>
                @else
                    <h3>Rp {{ number_format($pendapatanBooking + $pendapatanFnb, 0, ',', '.') }}</h3>
                    <p>Total Pendapatan</p>
                @endif
            </div>
            <div class="icon"><i class="fas fa-money-bill-wave"></i></div>
        </div>
    </div>
</div>