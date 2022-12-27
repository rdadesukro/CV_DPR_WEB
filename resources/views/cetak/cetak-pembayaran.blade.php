<!DOCTYPE html>
<html>
<head>
	<title>CV DUA PUTRA RADEN</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
	<style type="text/css">
		table tr td,
		table tr th{
			font-size: 9pt;
		}
	</style>
	<center>
		<h5>CV DUA PUTRA RADEN</h5>

	</center>
    <table style="width: 487.156px; height: 62px;">
        <tbody>
         <tr>
         <td style="width: 127px;">Nama Pemilik Mobil</td>
         <td style="width: 11px;">:</td>
         <td style="width: 435.156px;">&nbsp;{{$nama_Sopir}}</td>
         </tr>
        <tr>
        <td style="width: 127px;">No Polisi</td>
        <td style="width: 11px;">:</td>
        <td style="width: 435.156px;">&nbsp;{{$no_polisi}}</td>
        </tr>
        <tr>
        <td style="width: 127px;">Tanggal Pencairan</td>
        <td style="width: 11px;">:</td>
        <td style="width: 435.156px;">&nbsp;{{$date}}</td>
        </tr>
        </tbody>
    </table>
    <p>&nbsp;</p>
    <h6>REKAPAN</h6>
	<table class='table table-bordered'>
		<thead>
			<tr>
				<th style="width: 12px ">No</th>
                <th>Nama Sopir</th>
                <th>Tanggal Uang Jalan</th>
                <th>Tanggal Muat</th>
				<th>Uang Jalan</th>
				<th>Berat</th>
				<th>Tujuan</th>
				<th style="width: 12px " >Harga</th>
                <th>Total Kotor</th>
                <th>Total Bersih</th>
			</tr>
		</thead>
		<tbody>
           

            @php $i=1 @endphp
            @foreach ($setoran as $product)
                                          <tr >
                                            <td > {{ $i++ }} </td>
                                            <td> {{ $product->mobil->nama_sopir }} </td>
                                            <td> {{ $product->tgl_ambil_uang_jalan }} </td>
                                            <td> {{ $product->tgl_muat }} </td>
                                            <td> {{ $product->uang_jalan_new }} </td>
                                            <td> {{ $product->berat_new}} </td>
                                            <td> {{ $product->tujuan[0]}} </td>
                                            <td> {{ $product->harga }} </td>
                                            <td> {{ $product->jumlah_kotor_new }} </td>
                                            <td > {{ $product->jumlah_bersih_new }} </td>
                                          </tr>
            @endforeach
           

		</tbody>
        <tfoot>
            <tr>
              <th style="text_align:center;" colspan="8">Total</th>
              <td>{{$total_kotor}}</td>
              <td>{{$total_bersih}}</td>
            </tr>
          </tfoot>

	</table>

    <!-- <div style="page-break-before:always;"> </div> -->

   



</body>
</html>
     