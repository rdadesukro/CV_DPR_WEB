
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
  <script>

  </script>
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
						<div class="col-md-12">
							<table class="table table-striped table-condensed">
								
					
	
							<select class="form-control select2" name="id_mobil" id="id_mobil"  required="required" style="width: 100%;">
								@if($list_group_menu)
									@foreach($list_group_menu as $d)
										<option value="{{$d->value}}" @if($value=$d->value) selected="selected" @endif>{{$d->text}}</option>
									@endforeach
								@endif
							</select>

									
								</tr>
							</table>
						</div>
					</div>
					<div class="card-header">
						<h5 class="card-title">Pengaturan Menu</h5>
						<h6 class="card-subtitle text-muted">Fitur Ini Digunakan Untuk Manajemen Menu Pada Sistem </h6>
					</div>
					<div class="card-body">
						@if(ucc())
				   		{{Html::btnModal('<i class="la la-plus-circle"></i> Tambah Uang Jalan','modal-tambah','primary')}}
				   		<hr>
				   		@endif
						<table id="datatable" class="table table-striped table-hover table-sm" style="width:100%">
							<thead>
								<tr>
									<th>#</th>
									<th>Nama Sopir</th>
									<th>Tanggal</th>
									<th>Uang Jalan</th>
									<th>Uang Tambahan</th>
									<th>Uang Kurangan</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								 
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
	    Tanggal *
		<p><input  class="form-control" name="tanggal" type="text" id="tanggal"></p>
	
		{{ Form::bsSelect2('Nama Sopir','id',$list_group_menu,'',true,'md-8')}}
		  
		Uang Jalan (Rp) *
		
  		<div  class="group">
            <input class="form-control"  name="uang_jalan" type="text" id="uang_jalan">
        </div>
	{{Html::mCloseSubmitLG('Simpan')}}
{{ Form::bsClose()}}
@endif

@if(ucu())
<!-- MODAL FORM EDIT -->
{{ Form::bsOpen('form-edit',url($main_path."/update")) }}
	{{Html::mOpenLG('modal-edit','Edit Menu')}}
		{{ Form::bsSelect2('Nama Sopir','id',$list_group_menu,'',true,'md-8')}}
		Tanggal *
		<p><input  class="form-control" name="date" type="text" id="date"></p>
		Uang Jalan *
		<div  class="group">
                    <input class="form-control"  name="uang_jalan_edit" type="text" id="uang_jalan_edit">
        </div>
		
		{{ Form::bsHidden('uuid','') }}
	{{Html::mCloseSubmitLG('Simpan')}}
{{ Form::bsClose()}}
 @endif

@if(ucd())
 <!-- FORM DELETE -->
{{ Form::bsOpen('form-delete',url($main_path."/delete")) }}
	{{ Form::bsHidden('uuid','') }}
{{ Form::bsClose()}}
@endif

@endsection

@section("js")
<script type="text/javascript">
	$(function(){


		var $tabel1 = $('#datatable').DataTable({
		    processing: true,
		    responsive: true,
		    fixedHeader: true,
		    serverSide: true,
			
			ajax: "{{url('uang-jalan/dt/')}}"+1,
		    "iDisplayLength": 10,
		    columns: [
				{data:'DT_Row_Index' , orderable:false, searchable: false,sClass:""},
		         {data:'mobil.nama_sopir' , name:"mobil.nama_sopir" , orderable:true, searchable: true,sClass:""},
		         {data:'tgl_ambil_uang_jalan' , name:"tgl_ambil_uang_jalan" , orderable:false, searchable: false,sClass:""},
		         {data:'uang_jalan' , name:"uang_jalan" , orderable:false, searchable: false,sClass:""},
				 {data:'uang_tambahan' , name:"uang_tambahan" , orderable:false, searchable: false,sClass:""},
		         {data:'uang_kurangan' , name:"uang_kurangan" , orderable:false, searchable: false,sClass:""},
		         {data:'action' , orderable:false, searchable: false,sClass:"text-center"},
		        ],
		        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
		        $(nRow).addClass( aData["rowClass"] );
		        return nRow;
		    },
		    "drawCallback": function( settings ) {
				initKonfirmDelete();
		    }
		});
		$("#id_mobil").on('change', function(){
			var bulan = $(this).val();
			$tabel1.ajax.url("{{url('uang-jalan/dt')}}/"+bulan).load();
		})

		

		 

		@if(ucc())
		$validator_form_tambah = $("#form-tambah").validate();
		$("#modal-tambah").on('show.bs.modal', function(e){
			$validator_form_tambah.resetForm();
			$("#form-tambah").clearForm();
			$('#form-tambah #id').selectize()[0].selectize.clear();
			enableButton("#form-tambah button[type=submit]")
		});

		$('#form-tambah').ajaxForm({
			beforeSubmit:function(){disableButton("#form-tambah button[type=submit]")},
			success:function($respon){
				if ($respon.status==true){
					 $("#modal-tambah").modal('hide'); 
					 successNotify($respon.message);
					 $tabel1.ajax.reload(null, true);
				}else{
					errorNotify($respon.message);
				}
				enableButton("#form-tambah button[type=submit]")
			},
			error:function(){
				$("#form-tambah button[type=submit]").button('reset');
				$("#modal-tambah").modal('hide'); 
				errorNotify('Terjadi Kesalahan!');
			}
		}); 
		@endif

		@if(ucu())
		$validator_form_edit = $("#form-edit").validate();
		$("#modal-edit").on('show.bs.modal', function(e){
			$uuid  = $(e.relatedTarget).data('uuid');
			$validator_form_edit.resetForm();
			$("#form-edit").clearForm();
			$('#form-edit #id').selectize()[0].selectize.clear();
			disableButton("#form-edit button[type=submit]")
			$.get("{{url('uang-jalan/get-data')}}/"+$uuid, function(respon){
				if(respon.status){
					$('#form-edit #id').selectize()[0].selectize.setValue(respon.data.mobil_id,false);
					$('#form-edit #uuid').val(respon.data.uuid);
					$('#form-edit #date').val(respon.data.tgl_ambil_uang_jalan);
					
					$('#form-edit #uang_jalan_edit').val(formatRupiah(respon.data.uang_jalan));
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

		@if(ucd())
		$('#form-delete').ajaxForm({
			beforeSubmit:function(){},
			success:function($respon){
				if ($respon.status==true){
					 successNotify($respon.message);
					 $tabel1.ajax.reload(null, true);
				}else{
					errorNotify($respon.message);
				}
			},
			error:function(){errorNotify('Terjadi Kesalahan!');}
		}); 
		var initKonfirmDelete= function(){
			$('.btn-konfirm-delete').on('click', function(e){
				$uuid  = $(this).data('uuid');
				 
				$.get("{{url('uang-jalan/get-data')}}/"+$uuid, function(respon){
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
					}else{
						errorNotify(respon.message);
					}
				})
			})
		}
		@endif 
	})



	$( function() {
			$( "#date" ).datepicker({
				dateFormat: 'yy-mm-dd',
			});
		} );

	$( function() {
			$( "#tanggal" ).datepicker({
				dateFormat: 'yy-mm-dd',
			});
	});


	$(document).ready(function(){

// Format mata uang.
$( '#uang_jalan' ).mask('000.000.000', {reverse: true});

})

$(document).ready(function(){

// Format mata uang.
$( '#uang_jalan_edit' ).mask('000.000.000', {reverse: true});

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
            return "Rp. " == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
        }


</script>
@endsection