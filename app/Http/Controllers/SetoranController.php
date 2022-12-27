<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use Datatables;
use Crypt;
use App\Setoran;
use Carbon\Carbon;

class SetoranController extends Controller
{
    //

    function index(Request $r){
        // $mobil_id=$r->mobil_id;
        // $keyword=$r->nama_sopir;
        // //  var_dump($mobil_id);
        // //   $filter = "";
        // //   if (request()->has('search')) {
        // //       $search = request('search');
        // //       $keyword = $search['value'];
        // //       if(strlen($keyword)>=3){
        // //           $keyword = strtolower($keyword);
        // //           $filter = " and (lower(a.nama_menu) like '%$keyword%' or lower(b.nama_menu) like '%$keyword%') ";
        // //       }   
        // //   }
  
        //   $query = Setoran::whereHas('mobil', function ($query) use ($keyword,$mobil_id){
        //       $query->where('mobil_id', $mobil_id);
        //       $query->where('nama_sopir', 'like', '%'.$keyword.'%');
      
        //   })
        //   ->with(['mobil' => function($query) use ($keyword){
        //       $query->where('nama_sopir', 'like', '%'.$keyword.'%');
        //   }])->get();

          $list_group_menu = DB::select("
          select 
          id as value, 
          nama_sopir as text
          from mobils");
           
    	$pagetitle = "Input Setoran";
    	$smalltitle = "Pengaturan Menu Aplikasi";
    	return view('setoran.setoran', compact('pagetitle','smalltitle'));
    }

    function datatable(Request $r,$id){
        $mobil_id=$r->mobil_id;
        $keyword=$r->nama_sopir;
      //  var_dump($mobil_id);
        // $filter = "";
        // if (request()->has('search')) {
        //     $search = request('search');
        //     $keyword = $search['value'];
        //     if(strlen($keyword)>=3){
        //         $keyword = strtolower($keyword);
        //         $filter = " and (lower(a.nama_menu) like '%$keyword%' or lower(b.nama_menu) like '%$keyword%') ";
        //     }   
        // }

        $query = Setoran::whereHas('mobil', function ($query) use ($keyword,$id){
            $query->where('mobil_id', $id);
            $query->where('nama_sopir', 'like', '%'.$keyword.'%');
    
        })
        ->with(['mobil' => function($query) use ($keyword){
            $query->where('nama_sopir', 'like', '%'.$keyword.'%');
        }])->get();
      //  var_dump($mobil_id);
         

        // $pagetitle = "Input Setoran";
    	// $smalltitle = "Pengaturan Menu Aplikasi";
    	// return view('setoran.setoran', compact('pagetitle','smalltitle','query'));

         return Datatables::of($query)
            ->addColumn('action', function ($query) {
                    $edit = ""; $delete = "";
                    if($this->ucu()){
                        $edit = '<button data-bs-toggle="modal" data-uuid="'.$query->uuid.'" data-bs-target="#modal-edit" class="btn btn-outline-secondary btn-outline btn-sm" type="button"><i class="las la-pen"></i></button>';
                    }
                   
                    $action =  $edit;
                    if ($action==""){$action='<a href="#" class="act"><i class="la la-lock"></i></a>'; }
                        return $action;
            })
            ->addIndexColumn()
            ->rawColumns(['action','label'])
            ->make(true);
    }

    function submit_insert(Request $r){
        // if($this->ucc()){
            loadHelper('format');
            $uuid = $this->genUUID();

          // dd($r->id);
           try {

            // $record = array(
            //     "mobil_id"=>(int)($r->id), 
            //     "uang_jalan"=>(int)($r->id), 
            //     "tgl_ambil_uang_jalan"=>Carbon::now()->toDateString(),
            //     "created_at"=>Carbon::now(), 
            //     "uuid"=> $uuid);

                $product = new Setoran;
                $product->mobil_id =(int)($r->id);
                $product->uang_jalan =(int)($r->uang_jalan);
                $product->tgl_ambil_uang_jalan = Carbon::now()->toDateString();
                $product->created_at = Carbon::now();
                $product->uuid =  $uuid;
                $product->save();

            // DB::table('setorans')->insert($record);
            $respon = array('status'=>true,'message'=>'Hak Akses Menu Berhasil Ditambahkan!');
            return response()->json($respon);
          
          } catch (\Exception $e) {
             
          
            $respon = array('status'=>false,'message'=>$e->getMessage());
            return response()->json($respon);
          
          }



           
        // }else{
        //     $respon = array('status'=>false,'message'=>'Akses Ditolak!');
        //     return response()->json($respon);
        // }
    }

    function submit_update(Request $r){
    	if($this->ucu()){
	    	loadHelper('format');
	    	$uuid = $r->uuid;

          
            $uang_kurangan = $r->uang_kurangan;
            $uang_tambahan = $r->uang_tambahan;
            $berat = $r->berat;
            $record = array(
                    "uang_tambahan"=>preg_replace('/[Rp. ]/','',$uang_tambahan),
                    "uang_kurangan"=>preg_replace('/[Rp. ]/','',$uang_kurangan),
                    "tgl_muat"=>$r->tgl_muat,
                    "berat"=>preg_replace('/[Rp. ]/','',$berat),
                    "tujuan_id"=>(int)($r->tujuan_id), 
                    "harga"=>(int)($r->harga), 
                    "transportir_id"=>(int)($r->transportir_id), 
                    "updated_at"=>Carbon::now());
    

	    	DB::table('setorans')->where('uuid', $uuid)->update($record);
	    	$respon = array('status'=>true,'message'=>'Data Menu Berhasil Disimpan!', 
	    		'_token'=>csrf_token());
        	return response()->json($respon);
    	}else{
    		$respon = array('status'=>false,'message'=>'Akses Ditolak!');
        	return response()->json($respon);
    	}
    }
    function submit_delete(Request $r){
        if($this->ucd()){
            loadHelper('format');
            $uuid = $r->uuid;
            $menu = DB::table('setorans')->where("uuid", $uuid)->first();
            
          
                DB::table('setorans')->where('uuid', $uuid)->delete();
                $respon = array('status'=>true,'message'=>'Data Menu Berhasil Dihapus!', 
                '_token'=>csrf_token());
              
            return response()->json($respon);
        }else{
            $respon = array('status'=>false,'message'=>'Akses Ditolak!');
            return response()->json($respon);
        }
    }
    function get_data($uuid){
    	// $menu = DB::table('setorans')->where('uuid', $uuid)->first();

        // $menu=  DB::table("setorans,mobils")
        //     ->select("setorans.mobil_id", "mobils.nama_sopir", "setorans.uang_jalan", "setorans.tgl_ambil_uang_jalan")
        //     ->where("setorans.mobil_id", "=", "mobils.id")
        //     ->where("setorans.uuid", "=",$uuid)
        //     ->first();

        $menu = DB::table('setorans')
        ->leftJoin('mobils','mobils.id','=','setorans.mobil_id')
        ->select("mobils.nama_sopir","setorans.*")    
        ->where('setorans.uuid', $uuid)->first();

     // dd($$uuid);

            
        if($menu){
            $respon = array('status'=>true,'data'=>$menu, 
            	'informasi'=>'Nama Menu: '. $menu->nama_sopir);
            return response()->json($respon);
        }
        $respon = array('status'=>false,'message'=>'Data Tidak Ditemukan');
        return response()->json($respon);
    }


}
