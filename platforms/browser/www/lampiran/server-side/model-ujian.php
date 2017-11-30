<?php
header("Access-Control-Allow-Origin: *");

include("koneksi.php");

if($_GET['do'] == "initlatihan"){
    date_default_timezone_set("Asia/Jakarta");
    $date = date("Y:m:d H:i:s");
    $sql = mysqli_query($con,"INSERT INTO ujian(nis, waktu_mulai, jenis, soal, jawaban) VALUES('$_POST[nis]', '$date', 'latihan', '$_POST[soal]', '$_POST[jawaban]')") or die(mysqli_error($con));
    echo mysqli_insert_id($con);
}

if($_GET['do'] == "removelatihan"){
    $id = $_POST['id'];
    $sql = mysqli_query($con,"DELETE FROM ujian WHERE id_ujian='$id'") or die(mysqli_error($con));
    echo mysqli_affected_rows($con);
}

if($_GET['do'] == "initujian"){
    date_default_timezone_set("Asia/Jakarta");
    $date = date("Y:m:d H:i:s");
    $date2 = date("Y/m/d H:i:s",strtotime("+ 2 hours"));
    $sql = mysqli_query($con,"INSERT INTO ujian(nis, waktu_mulai, jenis, soal, jawaban) VALUES('$_POST[nis]', '$date', 'ujian', '$_POST[soal]', '$_POST[jawaban]')") or die(mysqli_error($con));
    $data = array();
    $data['id'] = mysqli_insert_id($con);
    $data['waktu_selesai'] = $date2;
    echo json_encode($data);
}

if($_GET['do'] == "savetodatabase"){
    $sql = mysqli_query($con,"SELECT * FROM ujian WHERE id_ujian='$_POST[id]'") or die(mysqli_error($con));
    $r = mysqli_fetch_assoc($sql);
    
    $soal = explode("|",$r['soal']);
    $jawaban = explode("|",$_POST['jawaban']);
    $benar = 0;
    $s = count($soal)-1;
    for($i=0; $i<$s; $i++){
        $q = mysqli_query($con,"SELECT * FROM soal WHERE id_soal='$soal[$i]' AND jawaban='$jawaban[$i]'") or die(mysqli_error($con));
        if(mysqli_num_rows($q) != 0)
            $benar++;
    }
    
    date_default_timezone_set("Asia/Jakarta");
    $date = date("Y:m:d H:i:s");
    $hasil = $benar/$s * 100;
    if($r['jenis'] == "ujian"){
        $dat = new DateTime($r['waktu_mulai']);
        $dat->modify("+2 hours");
        if(new DateTime() > $dat){
            $date = $dat->format("Y:m:d H:i:s");
        }
    }
    if($_POST['logout'] == "no")
        $sql1 = mysqli_query($con, "UPDATE ujian SET waktu_selesai='$date', jawaban='$_POST[jawaban]', hasil='$hasil' WHERE id_ujian='$_POST[id]'") or die(mysqli_error($con));
    else
        $sql1 = mysqli_query($con, "UPDATE ujian SET jawaban='$_POST[jawaban]' WHERE id_ujian='$_POST[id]'") or die(mysqli_error($con));
    echo mysqli_affected_rows($con);
    
/*
    $spl = explode("|",$soal);
    $spl = sizeof($spl) - 1;
    $hasil = $benar/$spl*100;

    date_default_timezone_set("Asia/Jakarta");
    $date = date("Y:m:d H:i:s");
    if($_POST['tipe'] == "latihan") {
        $sql = "INSERT INTO ujian VALUES(NULL, '".$_POST['idUser']."', '$date', '".$_POST['tipe']."', '$soal', '$jawaban', '$hasil')";
        mysqli_query($con, $sql);
        echo mysqli_insert_id($con);
    }
    else if($_POST['tipe'] == "ujian"){
        $sql = "UPDATE ujian SET soal='$soal', jawaban='$jawaban', hasil='$hasil' WHERE id_ujian='".$_POST['idUjian']."'";
        mysqli_query($con, $sql);
        echo mysqli_affected_rows($con);
    } */
    
}

if($_GET['do'] == "checklatihan"){
    $id = $_POST['id'];
    if(isset($_POST['idx'])){
        $sql = mysqli_query($con, "SELECT * FROM ujian WHERE id_ujian='$_POST[idx]'");
    }
    else
        $sql = mysqli_query($con, "SELECT * FROM ujian WHERE nis='$id' AND waktu_selesai='0000-00-00 00:00:00' AND jenis='latihan' ORDER BY id_ujian DESC LIMIT 1");
    if(mysqli_num_rows($sql) != 0){
        $data = array();
        $r = mysqli_fetch_assoc($sql);
        $data['id'] = $r['id_ujian'];
        $soal = array();
        $idsoal = explode("|",$r['soal']);
        $jawsoal = explode("|",$r['jawaban']);
        $jumSoal = count($idsoal) - 1;
        $jawaban = array();
        for($i=0; $i<$jumSoal; $i++){
            $sql1 = mysqli_query($con, "SELECT * FROM soal WHERE id_soal='$idsoal[$i]'");
            if(mysqli_num_rows($sql1) != 0){
                $soal[] = mysqli_fetch_assoc($sql1);
            }
            $jawaban[] = $jawsoal[$i];
        }
        $data['soal'] = $soal;
        $data['jumsoal'] = $jumSoal;
        $data['jawaban'] = $jawaban;
        $data['status'] = "found";
        echo json_encode($data);
    }
    else {
        $data = array();
        $data['status'] = "not found";
        echo json_encode($data);
    }
}

if($_GET['do'] == "checkujian"){
    $id = $_POST['id'];
    if(isset($_POST['idx'])){
        $sql = mysqli_query($con, "SELECT * FROM ujian WHERE id_ujian='$_POST[idx]'");
    }
    else
        $sql = mysqli_query($con, "SELECT * FROM ujian WHERE nis='$id' AND jenis='ujian'");
    $data = array();
    if(mysqli_num_rows($sql) != 0){
        $r = mysqli_fetch_assoc($sql);
        if($r['waktu_selesai'] == "0000-00-00 00:00:00" || (isset($_POST['idx']) && $_POST['idx'] != "")) {
            $data['status'] = "found";
            $data['id'] = $r['id_ujian'];
            $soal = array();
            $jawaban = array();
            $idsoal = explode("|",$r['soal']);
            $jawsoal = explode("|",$r['jawaban']);
            $jumSoal = count($idsoal) - 1;
            for($i=0; $i<$jumSoal; $i++){
                $sql1 = mysqli_query($con, "SELECT * FROM soal WHERE id_soal='$idsoal[$i]'");
                if(mysqli_num_rows($sql1) != 0){
                    $soal[] = mysqli_fetch_assoc($sql1);
                }
                $jawaban[] = $jawsoal[$i];
            }
            $data['soal'] = $soal;
            $data['jumsoal'] = $jumSoal;
            $data['jawaban'] = $jawaban;
            $dat = new DateTime($r['waktu_mulai']);
            $dat->modify("+2 hours");
            $data['waktu'] = $dat->format("Y/m/d H:i:s");
            $data['hasil'] = $r['hasil'];
            $data['waktu_selesai'] = $r['waktu_selesai'];
            echo json_encode($data);
        }
        else {
            $data['status'] = "done";
            $data['id'] = $r['id_ujian'];
            echo json_encode($data);
        }
    }
    else {
        $data['status'] = "not found";
        echo json_encode($data);
    }
}

if($_GET['do'] == "ambilhasil"){
    $sql = mysqli_query($con,"SELECT * FROM ujian where nis='$_POST[id]' AND jenis='$_POST[tipe]' ORDER BY id_ujian ASC");
    $data = array();
    $ujian = array();
    if(mysqli_num_rows($sql) != 0){
        while($r = mysqli_fetch_assoc($sql)){
            if($r['waktu_selesai'] != "0000-00-00 00:00:00")
                $ujian[] = $r;
        }
        $data['status'] = "ok";
        $data['ujian'] = $ujian;
    }
    else {
        $data['status'] = "not ok";
    }
    echo json_encode($data);
}
?>
