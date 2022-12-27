<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use Datatables;
use Crypt;
use App\Setoran;
use Carbon\Carbon;

class UangJalanController extends Controller
{
    function index(){
    	$pagetitle = "Input Setoran";
    	$smalltitle = "Pengaturan Menu Aplikasi";
    	return view('uang-jalan.uang-jalan', compact('pagetitle','smalltitle'));
    }

    function datatable(Request $r,$id){
        
           $filter = "";
        // $mobil_id =$r->id;
       // dd($mobil_id);
        if (request()->has('search')) {
            $search = request('search');
            $keyword = $search['value'];
            if(strlen($keyword)>=3){
                $keyword = strtolower($keyword);
                $filter = " and (lower(a.nama_menu) like '%$keyword%' or lower(b.nama_menu) like '%$keyword%') ";
            }   
        }

         $sql_union = "select a.nama_menu , a.url, a.uuid, a.urutan, b.nama_menu as group_menu from menu as a, menu as b where a.id_menu_induk = b.id_menu and a.id_menu_induk > 0  $filter order by a.id_menu_induk  ";

        //  $query = DB::table(DB::raw("($sql_union) as x"))
        //             ->select(['group_menu','nama_menu', 'url','uuid','urutan']);


        $query = Setoran::whereHas('mobil', function ($query) use ($keyword,$id){
            $query->where('nama_sopir', 'like', '%'.$keyword.'%');
            $query->where('mobil_id', $id);
    
        })
        ->with(['mobil' => function($query) use ($keyword){
            $query->where('nama_sopir', 'like', '%'.$keyword.'%');
        }])->get();
        // $query = Setoran::with('mobil')->get();
        // $query = Setoran::with(["mobil"])
        //                     ->WhereHas('mobil', function($query) use ($keyword){
        //                         $query->where('nama_sopir', 'like', '%'.$keyword.'%');
        //                     })
        //                     ->get();
      

         return Datatables::of($query)
            ->addColumn('action', function ($query) {
                    $edit = ""; $delete = "";
                    if($this->ucu()){
                        $edit = '<button data-bs-toggle="modal" data-uuid="'.$query->uuid.'" data-bs-target="#modal-edit" class="btn btn-outline-secondary btn-outline btn-sm" type="button"><i class="las la-pen"></i></button>';
                    }
                    if($this->ucd()){
                        $delete = '<button  data-uuid="'.$query->uuid.'" class="btn btn-outline-secondary btn-sm btn-konfirm-delete" type="button"><i class="las la-trash"></i></button>';
                    }
                    $action =  $edit." ".$delete;
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

            $rp = $r->uang_jalan;
          
                $product = new Setoran;
                $product->mobil_id =(int)($r->id);
                $product->uang_jalan =preg_replace('/[Rp. ]/','',$rp);
                $product->tgl_ambil_uang_jalan =$r->tanggal;
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
    	// if($this->ucu()){
	    	loadHelper('format');
	    	$uuid = $r->uuid;

          
            $rp = $r->uang_jalan_edit;
            $record = array(
                    "mobil_id"=>(int)($r->id), 
                    "uang_jalan"=>preg_replace('/[Rp. ]/','',$rp),
                    "tgl_ambil_uang_jalan"=>$r->date,
                    "updated_at"=>Carbon::now(), 
                    "uuid"=> $uuid);
    

	    	DB::table('setorans')->where('uuid', $uuid)->update($record);
	    	$respon = array('status'=>true,'message'=>'Data Menu Berhasil Disimpan!', 
	    		'_token'=>csrf_token());
        	return response()->json($respon);
    	// }else{
    	// 	$respon = array('status'=>false,'message'=>'Akses Ditolak!');
        // 	return response()->json($respon);
    	// }
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
        ->select("setorans.mobil_id", "mobils.nama_sopir", "setorans.uang_jalan", "setorans.tgl_ambil_uang_jalan", "setorans.uuid")    
        ->where('setorans.uuid', $uuid)->first();

            
        if($menu){
            $respon = array('status'=>true,'data'=>$menu, 
            	'informasi'=>'Nama Menu: '. $menu->nama_sopir);
            return response()->json($respon);
        }
        $respon = array('status'=>false,'message'=>'Data Tidak Ditemukan');
        return response()->json($respon);
    }

}
