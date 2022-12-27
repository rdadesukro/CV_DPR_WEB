<?php
$main_path = Request::segment(1);

//dd($main_path);
loadHelper('akses'); 
$list_group_menu = DB::table('mobils')
					->select('id as value','nama_sopir as text')->get();

					$transportir = DB::table('trasportirs')
					->select('id as value','nama_transportir as text')->get();
					$tujuan = DB::table('tujuans')
					->select('id as value','nama_tujuan as text')->get();
					$id_organisasi='';
?>
@extends('layout')
@section("pagetitle")
	BERANDALAN
@endsection

@section('content')

		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h5 class="card-title">Pengaturan Menu</h5>
						<h6 class="card-subtitle text-muted">Fitur Ini Digunakan Untuk Manajemen Menu Pada Sistem </h6>
					</div>
					<div class="card-body">
					<table class="table table-striped table-hover table-sm">
	   			<tr>
	   				
				   <h5 class="card-title">Nama Sopir</h5>
				   <select class="form-control select2" name="id_mobil" id="id_mobil"  required="required" style="width: 100%;">
								@if($list_group_menu)
									@foreach($list_group_menu as $d)
										<option value="{{$d->value}}" @if($value=$d->value) selected="selected" @endif>{{$d->text}}</option>
									@endforeach
								@endif
					</select>
					<button class="btn btn-danger" data-toggle="modal" data-target="#modal-reset-lppk">
				  		<i class="la la-refresh"></i> Tarik Ulang DPA
				  	</button> &nbsp;
					<button type="button" class="btn btn-primary bayar"><i class="la la-search-plus"></i> Bayar</button>
					&nbsp
					<button class="btn btn-primary" id="cetak-pdf">
					   		<i class="la la-file-pdf-o"></i> Cetak (PDF)
					 </button>
					  
	   		</table>
						
					</div>
				</div>
				<div >
			</div>
		</div >

		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h5 class="card-title">Data Muatan</h5>
						</div>
					<div class="card-body">
					
						<table id="datatable" class="table table-striped table-hover table-sm" style="width:100%">
							<thead>
								<tr>
									<th>#</th>
									<th>Nama Sopir</th>
									<th>Uang Jalan</th>
									<th>Uang Tambahan</th>
									<th>Uang Kurangan</th>
									<th>TTU</th>
									<th>Trasnportir</th>
									<th>Tanggal Muat</th>
									<th>Berat</th>
									<th>Tujauan</th>
									<th>Harga</th>
									<th>Total Kotor</th>
									<th>Total Bersih</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
							<tbody class="sm">
			   			
			   				
								 
							</tbody>
						</table>


					</div>
				</div>
			</div>
		</div>
		
@section("modal")

{{ Form::bsOpen('form-reset',url($main_path."/reset-realisasi")) }}
			{{Html::mOpen('modal-reset-lppk','Input Ulang (Reset) Data LPPK')}}
			<?php 
			$datacek = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			var_dump($datacek);
			
			?>
			
				<div id="panel-reset-lppk">
					<h4>Anda Yakin Ingin Menginput Ulang (Reset Data) Realisasi LPPK.</h4>
					<p class="alert alert-warning">
					
					</p>
					
				</div>
				<hr>
				<center>
					<button type="submit" class="btn btn-danger">
						<i class="la la-refresh"></i> Ya, Lanjutkan!
					</button>
					<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Batalkan</button>
				</center>
			{{Html::mClose()}}
{{ Form::bsClose()}}

	

@endsection

@endsection
@section("js")

	<script type="text/javascript">

var bulan = "{{$list_group_menu}}";
		
		var $tabel1 = $('#datatable').DataTable({
		    processing: true,
		    responsive: true,
		    fixedHeader: true,
		    serverSide: true,
		    ajax: "{{url('pembayaran/dt')}}/"+bulan,
		    "iDisplayLength": 10,
		    columns: [
				{data:'DT_Row_Index' , orderable:false, searchable: false,sClass:""},
		         {data:'mobil.nama_sopir' , name:"mobil.nama_sopir" , orderable:false, searchable: true,sClass:""},
		          {data:'uang_jalan' , name:"uang_jalan" , orderable:false, searchable: false,sClass:""},
				 {data:'tambahan' , name:"tambahan" , orderable:false, searchable: false,sClass:""},
		         {data:'kurangan' , name:"kurangan" , orderable:false, searchable: false,sClass:""},
				 {data:'ttu' , name:"ttu" , orderable:false, searchable: false,sClass:""},
				 {data:'transportir' , name:"transportir" , orderable:false, searchable: false,sClass:""},
				 {data:'tgl_muat' , name:"tgl_muat" , orderable:false, searchable: false,sClass:""},
				 {data:'berat_new' , name:"berat_new" , orderable:false, searchable: false,sClass:""},
				 {data:'tujuan' , name:"tujuan" , orderable:false, searchable: false,sClass:""},
				 {data:'harga' , name:"harga" , orderable:false, searchable: false,sClass:""},
				 {data:'jumlah_kotor_new' , name:"jumlah_kotor_new" , orderable:false, searchable: false,sClass:""},
				 {data:'jumlah_bersih_new' , name:"jumlah_bersih_new" , orderable:false, searchable: false,sClass:""},
		         {data:'action' , orderable:false, searchable: false,sClass:"text-center"},
		        ],
		        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
		        $(nRow).addClass( aData["rowClass"] );
		        return nRow;
		    },
		    "drawCallback": function( settings ) {
		      
		    }
		});

		$("#id_mobil").on('change', function(){
			var bulan = $(this).val();
			$tabel1.ajax.url("{{url('pembayaran/dt')}}/"+bulan).load();
		})


		$("#modal-input-lppk").on('show.bs.modal', function(e){
				$id_lppk  = $(e.relatedTarget).data('id_lppk');
				$("#form-lppk button[type=submit]").button('loading');
				$("#panel-input-lppk").html('<center>Loading Form Realisasi</center>');
				$.get("{{url('/input-lppk/form')}}/"+$id_lppk, function(respon){
					 $("#panel-input-lppk").html(respon);
					 $("#form-lppk button[type=submit]").button('reset');
					 $validator_form_lppk = $("#form-lppk").validate();
				})
			});

		



    $(".download-pdf").click(function(){
		$list_group_menu = $("#id").val();
        var data = '';
        $.ajax({
            type: 'GET',
            url: '/pembayaran/cetak?mobil_id='+$list_group_menu,
            data: data,
            xhrFields: {
                responseType: 'blob'
            },
            success: function(response){
                var blob = new Blob([response]);
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = "Sample.pdf";
                link.click();
            },
            error: function(blob){
                console.log(blob);
            }
        });
    });
  



	$(".bayar").click(function(){
		$list_group_menu = $("#id_mobil").val();
		$.get("{{url('pembayaran/get-bayar')}}/"+$list_group_menu, function(respon){
			
					if(respon.status){
						$("#form-delete #uuid").val(respon.data.uuid);
						$.confirm({
						    title: 'Yakin Hapus Data?',
						    content: respon.informasi,
						    buttons: {
						        
						        cancel :{
						        	text: 'Batalkan'
						        },
						        confirm: {
						        	text: 'Hapus',
						        	btnClass: 'btn-danger',
						        	action:function(){$("#form-delete").submit()}
						        },
						    }
						});
						// successNotify(respon.message);
					
					}else{
						errorNotify(respon.status);
					}
				})
    });

		$('#form-delete').ajaxForm({
			beforeSubmit:function(){},
			success:function($respon){
				if ($respon.status==true){
					 successNotify($respon.message);
				}else{
					errorNotify($respon.message);
				}
			},
			error:function(){errorNotify('Terjadi Kesalahan!');}
		}); 

		$("#cetak-pdf").on('click', function(){
			$kdbulan = $("#kdbulan").val();
			$list_group_menu = $("#id").val();
			$kdkegunit =  $("#kdkegunit").val();
			$unitkey =  $("#unitkey").val();
			if (($list_group_menu!='null'))
			{
				url_cetak_pdf = "/pembayaran/cetak?mobil_id="+$list_group_menu;
			//	url_cetak_pdf = url_cetak_pdf + '/'+$unitkey + '/'+$kdkegunit + '/'+ $kdbulan;
				window.location.href = url_cetak_pdf; 
			}else{
				swal('Pilih Bulan, SKPD dan Kegiatan Terlebih Dahulu!')
			}
		})


</script>
@endsection

