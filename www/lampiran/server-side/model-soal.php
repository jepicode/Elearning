<?php
header("Access-Control-Allow-Origin: *");

include("koneksi.php");

if($_GET['do'] == "lihathasil"){
    $id = $_POST['id'];
    $sql = mysqli_query($con,"SELECT * FROM ujian WHERE id_ujian='$id'");
    $r = mysqli_fetch_assoc($sql);
    $s = explode("|",$r['soal']); /* Soal */
    $s2 = explode("|",$r['jawaban']); /* Jawaban User */
    $n = count($s)-1;
    $benar = 0;
    
    $data = array();
    for($i=0;$i<$n;$i++){
        $sql1 = mysqli_query($con,"SELECT * FROM soal WHERE id_soal='$s[$i]'");
        $arr = array("jawabanUser"=>$s2[$i]);
        $fetch = mysqli_fetch_assoc($sql1);
        $data[] = array_merge($fetch,$arr);
        if($s2[$i] == $fetch['jawaban'])
            $benar++;
    }
    
    $data["hasil"] = $r['hasil'];
    $data['benar'] = "$benar";
    
    echo json_encode($data);
}

if($_GET['do'] == "getSoal"){
    $type = $_POST['tipe'];
    $sql = mysqli_query($con,"SELECT * FROM soal WHERE type='$type' ORDER BY RAND()");
    $data = array();
    while($r = mysqli_fetch_assoc($sql)){
        $data[] = $r;
    }
    echo json_encode($data);
}

if($_GET['do'] == "initUjian"){
    date_default_timezone_set("Asia/Jakarta");
    $date = date("Y:m:d H:i:s");
    $date2 = date("Y:m:d H:i:s",strtotime("+ 2 hours"));
    $sql = mysqli_query($con,"INSERT INTO ujian(waktu) VALUES('$date')");
    $data = array();
    $data['id'] = mysqli_insert_id($con);
    $data['year'] = date("Y",strtotime($date2));
    $data['month'] = date("m",strtotime($date2));
    $data['day'] = date("d",strtotime($date2));
    $data['hour'] = date("H",strtotime($date2));
    $data['minute'] = date("i",strtotime($date2));
    $data['second'] = date("s",strtotime($date2));
    echo json_encode($data);
}

if($_GET['do'] == "test"){
    date_default_timezone_set("Asia/Jakarta");
    $date = date("Y:m:d H:i:s");
    $date2 = date("Y:m:d H:i:s",strtotime("+ 2 hours"));
    echo date("H",strtotime($date2));
}

if($_GET['do'] == "saveToDatabase"){
    $soal = "";
    $jawaban = "";
    $benar = 0;

    foreach($_POST as $key=>$value){
        if($key != "tipe" && $key != "idUser"){
            $soal=$soal.$key."|";
            $jawaban=$jawaban.$value."|";
            $q = mysqli_query($con, "SELECT * FROM soal WHERE id_soal='$key' AND jawaban='$value'");
            if(mysqli_num_rows($q) != 0)
                $benar++;
        }
    }

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
    }
    
}
?>
