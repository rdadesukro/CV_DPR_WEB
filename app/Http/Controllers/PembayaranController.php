<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use Datatables;
use Crypt;
use App\Setoran;
use Carbon\Carbon;
use PDF;
class PembayaranController extends Controller
{
    //

    function index(){
    	$pagetitle = "Pembyaran";
    	$smalltitle = "Pembyaran";
    	return view('pembayaran.pembayaran', compact('pagetitle','smalltitle'));
    }

    // function datatable(Request $r){
    //     $mobil_id= $r->mobil_id;
    //     $query = Setoran::with(['mobil'])
    //     ->where([
    //        ['mobil_id', '=', $mobil_id],
    //        ['transportir_id', '!=', null],
    //        ['status_pembayaran', '=', 'belum lunas']])
    //     ->get();

    //     //dd($query);
    //     return view('pembayaran.tabel-pembayaran', compact('query'));
    // }
    function datatable(Request $r,$id){
        
    $filter = "";;
     if (request()->has('search')) {
         $search = request('search');
         $keyword = $search['value'];
         if(strlen($keyword)>=3){
             $keyword = strtolower($keyword);
             $filter = " and (lower(a.nama_menu) like '%$keyword%' or lower(b.nama_menu) like '%$keyword%') ";
         }   
     }


     $query = Setoran::whereHas('mobil', function ($query) use ($keyword,$id){
         $query->where('nama_sopir', 'like', '%'.$keyword.'%');
         $query->where('mobil_id', $id);
         $query->where('status_pembayaran', 'belum lunas');
 
     })
     ->with(['mobil' => function($query) use ($keyword){
         $query->where('nama_sopir', 'like', '%'.$keyword.'%');
     }])->get();
  

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

    function cetak(Request $r){
        $total_kasbon = 0;
        $total_kasbon_new = 0;
        $total_kotor=0;
        $total_uang_jalan=0;
        $total_bersih=0;
        $total_bersih_new=0;
        $nama_Sopir;
        $no_polisi;
        $date =Carbon::now()->format('d-M-yy');
        $total_final_bersih=0;
        $mobil_id= $r->mobil_id;

        $query = Setoran::with(['mobil'])
        ->where([
           ['mobil_id', '=', $mobil_id],
           ['transportir_id', '!=', null],
           ['status_pembayaran', '=', 'lunas']])
        ->get();

       // dd($query);

        foreach ($query as $p) {
            $total_kotor += $p->jumlah_kotor;
            $total_bersih_new += $p->jumlah_bersih;
            $nama_Sopir = $p->mobil->nama_sopir;
            $no_polisi = $p->mobil->nopol;
          }
          $total_bersih =  "Rp " . number_format( $total_bersih_new,0,',','.');
          $total_kotor =  "Rp " . number_format( $total_kotor,0,',','.');
        $pdf = PDF::loadView('cetak.cetak-pembayaran',['setoran' => $query,
        'total_kotor'=>$total_kotor,
        'total_bersih'=>$total_bersih,
        'nama_Sopir'=>$nama_Sopir,
        'no_polisi'=>$no_polisi,
        'date'=>$date])->setPaper('legal','landscape');
        //return $pdf->stream('rekapan.pdf');
        return $pdf->stream('pdf_file.pdf');
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

          

            $record = array(
                    "mobil_id"=>(int)($r->id), 
                    "uang_jalan"=>(int)($r->uang_jalan), 
                    "tgl_ambil_uang_jalan"=>Carbon::now()->toDateString(),
                    "uang_tambahan"=>(int)($r->uang_tambahan), 
                    "uang_kurangan"=>(int)($r->uang_kurangan), 
                    "tgl_muat"=>Carbon::now()->toDateString(),
                    "status_pembayaran"=>"lunas", 
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
            $uuid = $r->mobil_id;

            $setoran = Setoran::where([
                ['mobil_id', '=', $uuid],
                ['transportir_id', '!=', null],
                ['status_pembayaran', '=', 'belum lunas']])
             ->get();
            // $menu = DB::table('setorans')->where("uuid", $uuid)->first();
            
            foreach ($setoran as $data) {
                $pembayaran =Setoran::where('id',$data->id)->first();
                $pembayaran->status_pembayaran = 'lunas';
                $pembayaran->save();
            }
          
                // DB::table('setorans')->where('uuid', $uuid)->delete();
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

       // dd($menu);

            
        if($menu){
            $respon = array('status'=>true,'data'=>$menu, 
            	'informasi'=>'Nama Menu: '. $menu->nama_sopir);
            return response()->json($respon);
        }
        $respon = array('status'=>false,'message'=>'Data Tidak Ditemukan');
        return response()->json($respon);
    }
    function get_bayar($id){
    	// $menu = DB::table('setorans')->where('uuid', $uuid)->first();

        // $menu=  DB::table("setorans,mobils")
        //     ->select("setorans.mobil_id", "mobils.nama_sopir", "setorans.uang_jalan", "setorans.tgl_ambil_uang_jalan")
        //     ->where("setorans.mobil_id", "=", "mobils.id")
        //     ->where("setorans.uuid", "=",$uuid)
        //     ->first();

        // $menu = DB::table('setorans')
        // ->leftJoin('mobils','mobils.id','=','setorans.mobil_id')
        // ->select("mobils.nama_sopir","setorans.*")    
        // ->where('setorans.uuid', $uuid)->first();
        // $mobil_id= $r->mobil_id;
        $menu = Setoran::where([
           ['mobil_id', '=', $id],
           ['transportir_id', '!=', null],
           ['status_pembayaran', '=', 'belum lunas']])
        ->first();

       // dd($menu);

            
        if($menu){
            $respon = array('status'=>true,'data'=>$menu, 
            'informasi'=>'Nama Menu: '. $menu->mobil_id);
        return response()->json($respon);
        }
        $respon = array('status'=>false,'message'=>'Data Tidak Ditemukan');
        return response()->json($respon);
    }


}
