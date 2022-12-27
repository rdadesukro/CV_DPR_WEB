<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setoran extends Model
{
    protected $table = 'setorans';
    protected $appends = [
    'jumlah_bersih_new',
    'jumlah_kotor_new',
    'uang_jalan_new',
    'uang_jalan',
    'berat_new',
    'jumlah_kotor',
    'totalpencairan',
    'jumlah_bersih',
    'tgl_ambil_uang_jalan',
    'transportir',
    'tujuan',
    'tambahan',
    'kurangan',
    'ttu'];

    
    public function mobil()
    {
        return $this->belongsTo('App\Mobil');
    }

    public function getTransportirAttribute(){


        $data = Trasportirs::where('id', $this->attributes['transportir_id'])->pluck('nama_transportir');
        return $data;
    }

    public function getTujuanAttribute(){


        $data = Tujuan::where('id', $this->attributes['tujuan_id'])->pluck('nama_tujuan');
        return $data;
    }

    public function getTglAmbilUangJalanAttribute()
    {

        return \Carbon\Carbon::parse($this->attributes['tgl_ambil_uang_jalan'])
        ->format('d-M-Y');
    }

    public function getUangJalanAttribute()
    {

       $hasil_rupiah = "Rp " . number_format($this->attributes['uang_jalan'],0,',','.');
        return  $hasil_rupiah;
    }
    public function getUangJalanNewAttribute()
    {

       $hasil_rupiah = "Rp " . number_format($this->attributes['uang_jalan'],0,',','.');
        return  $hasil_rupiah;
    }


    public function getBeratNewAttribute()
    {

        $hasil_rupiah = number_format($this->attributes['berat'],0,',','.');
        return $hasil_rupiah;
    }
    
    public function getTambahanAttribute()
    {

        $hasil_rupiah =  "Rp " . number_format($this->attributes['uang_tambahan'],0,',','.');
        return $hasil_rupiah;
    }

    public function getTtuAttribute()
    {

        $uang_tambahan = $this->attributes['uang_tambahan'];
        $uang_kurangan = $this->attributes['uang_kurangan'];
        $uang_jalan = $this->attributes['uang_jalan'];
        $total= 0;

       
            $total = $uang_jalan+$uang_tambahan-$uang_kurangan;

         $total = "Rp " . number_format($total,0,',','.');
        return $total;
    }
    public function getKuranganAttribute()
    {

        $hasil_rupiah =  "Rp " . number_format($this->attributes['uang_kurangan'],0,',','.');
        return $hasil_rupiah;
    }

    // public function getBeratBongkarAttribute()
    // {
    //     $hasil_rupiah =number_format($this->attributes['berat_bongkar'],0,',','.');
    //     return $hasil_rupiah;
    // }
   

    public function getJumlahKotorAttribute(){
        $berat_muat = $this->attributes['berat'];
        $harga = $this->attributes['harga'];
        $total= 0;

       
            $total = $berat_muat*$harga;

       // $total = "Rp " . number_format($total,0,',','.');
        return $total;

    }

    public function getJumlahKotorNewAttribute(){
        $berat_muat = $this->attributes['berat'];
        $harga = $this->attributes['harga'];
        $total= 0;

      
          
            $total = $berat_muat*$harga;
        $total = "Rp " . number_format($total,0,',','.');
        return $total;

    }

    public function getJumlahBersihAttribute(){
        $berat_muat = $this->attributes['berat'];
        $uang_jalan = $this->attributes['uang_jalan'];
        $harga = $this->attributes['harga'];
        $total= 0;
        $uang_tambahan = $this->attributes['uang_tambahan'];
        $uang_kurangan = $this->attributes['uang_kurangan'];
      
            $total = ($berat_muat*$harga)-($uang_jalan+$uang_tambahan-$uang_kurangan);
     
        // $total = "Rp " . number_format($total,0,',','.');
        return $total;

    }

    public function getJumlahBersihNewAttribute(){
        $berat_muat = $this->attributes['berat'];
        $uang_jalan = $this->attributes['uang_jalan'];
        $uang_tambahan = $this->attributes['uang_tambahan'];
        $uang_kurangan = $this->attributes['uang_kurangan'];
        $harga = $this->attributes['harga'];
        $total= 0;

      
            $total = ($berat_muat*$harga)-($uang_jalan+$uang_tambahan-$uang_kurangan);
        
        $total = "Rp " . number_format($total,0,',','.');
        return $total;

    }

   

    // public function getTransporirAttribute(){


    //     $data = Trasportirs::where('id', $this->attributes['transportir_id'])->get();
    //     return $data;
    // }

    public function getTotalPencairanAttribute(){

        $berat_muat = $this->attributes['berat'];
        $harga = $this->attributes['harga'];
        $total= 0;
        $total = $berat_muat*($harga+5);
        return $total;
    }



}
