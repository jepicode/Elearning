function Ujian() {
    this.url = "http://e-learning-organ.000webhostapp.com/";
    this.idUser = localStorage.getItem("nis");
    this.soal = "";
    this.idUjian = "";
    this.typeSoal = "";
    
    this.initLatihan = function (soal) {
        var jawaban = "";
        var a = JSON.parse(soal);
        for(var b in a){
            this.soal = this.soal+a[b].id_soal+"|";
            jawaban = jawaban+"null|";
        }
        var data = {};
        data["nis"] = this.idUser;
        data["soal"] = this.soal;
        data["jawaban"] = jawaban;
        return $.post(this.url+"model-ujian.php?do=initlatihan",data)
    }
    
    this.initUjian = function (soal) {
        this.soal = "";
        var jawaban = "";
        var a = JSON.parse(soal);
        for(var b in a){
            this.soal = this.soal+a[b].id_soal+"|";
            jawaban = jawaban+"null|";
        }
        var data = {};
        data["nis"] = this.idUser;
        data["soal"] = this.soal;
        data["jawaban"] = jawaban;
        return $.post(this.url+"model-ujian.php?do=initujian",data)
    } 
    
    this.removeLatihan = function () {
        var data = {id:this.idUjian};
        return $.post(this.url+"model-ujian.php?do=removelatihan",data);
    }
    
    this.saveToDatabase = function(tipe, logout){
        var logout = logout || "no";
        if(tipe == "latihan") {
            var listJawaban = JSON.parse(localStorage.getItem("jawabanSoalLatihan"));
            var id = localStorage.getItem("idUjianLatihan");
        }
        else if(tipe == "ujian") {
            var listJawaban = JSON.parse(localStorage.getItem("jawabanSoalUjian"));
            var id = localStorage.getItem("idUjian");
        }
        var jumSoal = Object.keys(listJawaban).length;
        var jawabanSoal = "";
        
        for(i=0; i<jumSoal; i++){
            jawabanSoal = jawabanSoal+listJawaban[i]+"|";
        }
        
        var idSoal = {};
        idSoal['jawaban'] = jawabanSoal;
        idSoal['id'] = id;
        if(logout != "no")
            idSoal['logout'] = "yes";
        else
            idSoal['logout'] = "no";
        return $.post(this.url+"model-ujian.php?do=savetodatabase",idSoal);
    }
    
    this.checkLatihan = function(idx){
        var idx = idx || "";
        if(idx != "")
            var id = {id:localStorage.getItem("nis"),idx:idx};
        else
            var id = {id:localStorage.getItem("nis")};
        return $.post(this.url+"model-ujian.php?do=checklatihan",id);
    }
    
    this.checkUjian = function(idx){
        var idx = idx || "";
        if(idx != "")
            var id = {id:localStorage.getItem("nis"),idx:idx};
        else
            var id = {id:localStorage.getItem("nis")};
        return $.post(this.url+"model-ujian.php?do=checkujian",id);
    }
    
    this.ambilHasil = function(tipe){
        var id={id:localStorage.getItem("nis"),tipe:tipe};
        return $.post(this.url+"model-ujian.php?do=ambilhasil",id);
    }

}