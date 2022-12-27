
		<div class="row">
			<div class="col-12">
				<div class="card">
					
					<div class="card-body">
					
						<table id="datatable" class="table table-striped table-hover table-sm" style="width:100%">
							<thead>
								<tr>
									<th>#</th>
									<th>Nama Sopir</th>
									<th>Tanggal</th>
									<th>Uang Jalan</th>
									<th>Uang Tambahan</th>
									<th>Uang Kurangan</th>
									<th>Trasnportir</th>
									<th>Tanggal Muat</th>
									<th>Berat</th>
									<th>Tujauan</th>
									<th>Harga</th>
									<th>Total Kotor</th>
									<th>Total Bersih</th>
									
								</tr>
							</thead>
							<tbody>
							<?php $no=1;?>
				   				@foreach($query as $r)
				   					<tr @if($r->jam_kerja==1) class="byellow" @endif>
				   						<td class="tc sm1">{{$no++}}</td>
				   						<td class="sm1">{{$r->mobil->nama_sopir}}</td>
										   <td class="sm1">{{$r->tgl_ambil_uang_jalan}}</td>
										   <td class="sm1">{{$r->uang_jalan_new}}</td>
										   <td class="sm1">{{$r->tambahan}}</td>
										   <td class="sm1">{{$r->kurangan}}</td>
										   <td class="sm1">{{$r->transportir[0]}}</td>
										   <td class="sm1">{{$r->tgl_muat}}</td>
										   <td class="sm1">{{$r->berat_new}}</td>
										   <td class="sm1">{{$r->harga}}</td>
										   <td class="sm1">{{$r->tujuan[0]}}</td>
										   <td class="sm1">{{$r->jumlah_kotor_new}}</td>
										   <td class="sm1">{{$r->jumlah_bersih_new}}</td>
										
				   					</tr>
				   				@endforeach
								 @if($query->isEmpty()) 
					        	<tr >
								   <td style="text-align:center" colspan="13">
                  					 <p><b>Data tidak ada</b></p>
                                   </td>
                                </tr>
								@endif
							</tbody>
						</table>


					</div>
				</div>
			</div>
		</div>
