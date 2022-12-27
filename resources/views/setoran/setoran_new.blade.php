<!doctype html>
<html lang="en">
<head>
 
  <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
  
</head>
<body>

</body>
</html>
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

					if(!$list_group_menu){
						$list_group_menu = 1;
					}


?>
@extends('layout')
@section("pagetitle")
	BERANDALAN
@endsection

@section('content')
<div class="row">
			<div class="col-12">
				<div class="card">
			
					<div class="card-body">
					
	   			<tr>
	   				
				   <h5 class="card-title">Nama Sopir</h5>
				   <select class="form-control select2" name="id_mobil" id="id_mobil"  required="required" style="width: 100%;">
								@if($list_group_menu)
									@foreach($list_group_menu as $d)
										<option value="{{$d->value}}" @if($value=$d->value) selected="selected" @endif>{{$d->text}}</option>
									@endforeach
								@endif
					</select>

			
	   			</tr>
	   	
				   </div>		
					</div>
				</div>
				
				<div id="tabel-tpp">
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
 
@endsection

@section("modal")
@if(ucc())
<!-- MODAL FORM TAMBAH -->
{{ Form::bsOpen('form-tambah',url($main_path."/insert_uj")) }}
	{{Html::mOpenLG('modal-tambah','Tambah Menu')}}
		{{ Form::bsSelect2('Nama Sopir','id',$list_group_menu,'',true,'md-8')}}
		{{ Form::bsTextField('Tanggal','tgl_ambil_uang_jalan','',true,'md-8') }}
		{{ Form::bsTextField('Uang Jalan','uang_jalan','',true,'md-8') }}
	{{Html::mCloseSubmitLG('Simpan')}}
{{ Form::bsClose()}}
@endif

@if(ucu())
<!-- MODAL FORM EDIT -->
{{ Form::bsOpen('form-edit',url($main_path."/update")) }}
	{{Html::mOpenLG('modal-edit','Edit Menu')}}
		
	   <p>
		Uang Tambahan *
  		<div  class="group">
            <input class="form-control"  name="uang_tambahan" type="text" id="uang_tambahan">
        </div>
		</p>
		
		<p>
		Uang Kurangan *
  		<div  class="group">
            <input class="form-control"  name="uang_kurangan" type="text" id="uang_kurangan">
        </div>
		</p>
		<p>
		Tanggal Muat *
		<div  class="group">
		<input  class="form-control" name="tgl_muat" type="text" id="tgl_muat">
		</div>
	    </p>
	

		<p>
		Berat *
  		<div  class="group">
            <input class="form-control"  name="berat" type="text" id="berat">
        </div>
		<p>
		{{ Form::bsSelect2('Tujuan','tujuan_id',$tujuan,'',true,'md-8')}}
		{{ Form::bsTextField('Harga','harga','',true,'md-8') }}
		{{ Form::bsSelect2('Transportir','transportir_id',$transportir,'',true,'md-8')}}
		{{ Form::bsHidden('uuid','') }}
		{{Html::mCloseSubmitLG('Simpan')}}
{{ Form::bsClose()}}
 @endif



@endsection

@section("js")
<script type="text/javascript">
	$(function(){

		
		
		var bulan = "{{$list_group_menu}}";
		
		var $tabel1 = $('#datatable').DataTable({
		    processing: true,
		    responsive: true,
		    fixedHeader: true,
		    serverSide: true,
		    ajax: "{{url('setoran-do/dt')}}/"+bulan,
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
			$tabel1.ajax.url("{{url('setoran-do/dt')}}/"+bulan).load();
		})

		$( function() {
			$( "#tgl_muat" ).datepicker({
				dateFormat: 'yy-mm-dd',
			});
		});

		   	$(document).ready(function(){
				$('#uang_tambahan' ).mask('000.000.000', 
				{reverse: true});
			})

			$(document).ready(function(){
				$('#uang_kurangan' ).mask('000.000.000', 
				{reverse: true});
			})

			$(document).ready(function(){
				$('#berat' ).mask('000.000.000', 
				{reverse: true});
			})

			$(document).ready(function(){
				$('#berat' ).mask('000.000.000', 
				{reverse: true});
			})


			



			function formatRupiah(angka) {
            let number_string = angka.toString().replace(/[^,\d]/g, ''),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);
            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }
            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return S == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
        }


		

		$("#cetak-jadwal-pdf").on('click', function(){
	        $mobil_id = $("#id").val();
			$tabel1.ajax.url("{{url('setoran-do/dt')}}/"+$mobil_id).load();
		})


		@if(ucu())
		$validator_form_edit = $("#form-edit").validate();
		$("#modal-edit").on('show.bs.modal', function(e){
			$uuid  = $(e.relatedTarget).data('uuid');
			$validator_form_edit.resetForm();
			$("#form-edit").clearForm();
			$('#form-edit #tujuan_id').selectize()[0].selectize.clear();
			$('#form-edit #transportir_id').selectize()[0].selectize.clear();
			disableButton("#form-edit button[type=submit]")
			$.get("{{url('setoran-do/get-data')}}/"+$uuid, function(respon){
				if(respon.status){
					$('#form-edit #tujuan_id').selectize()[0].selectize.setValue(respon.data.tujuan_id,false);
					$('#form-edit #transportir_id').selectize()[0].selectize.setValue(respon.data.transportir_id,false);
					$('#form-edit #uuid').val(respon.data.uuid);
			
					$('#form-edit #uang_tambahan').val(formatRupiah(respon.data.uang_tambahan));
					$('#form-edit #uang_kurangan').val(formatRupiah(respon.data.uang_kurangan));
					$('#form-edit #tgl_muat').val(respon.data.tgl_muat);
					$('#form-edit #berat').val(respon.data.berat);
				
					$('#form-edit #harga').val(respon.data.harga);
				
					enableButton("#form-edit button[type=submit]");
				}else{
					errorNotify(respon.message);
				}
			})
		});

		$('#form-edit').ajaxForm({
			beforeSubmit:function(){disableButton("#form-edit button[type=submit]")},
			success:function($respon){
				if ($respon.status==true){
					 $("#modal-edit").modal('hide'); 
					 successNotify($respon.message);
					 $tabel1.ajax.reload(null, true);
				}else{
					errorNotify($respon.message);
				}
				enableButton("#form-edit button[type=submit]")
			},
			error:function(){
				$("#form-edit button[type=submit]").button('reset');
				$("#modal-edit").modal('hide'); 
				errorNotify('Terjadi Kesalahan!');
			}
		}); 
		@endif

		
		
			 

	})
</script>
@endsection