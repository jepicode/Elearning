function Soal() {
    this.a;
    this.b;
    this.url = "http://e-learning-organ.000webhostapp.com/";
    this.jawaban;
    this.typeSoal;
    this.idUjian;
    
    this.getSoal = function(){
        var data = {tipe:this.typeSoal};
        return $.post(this.url+"model-soal.php?do=getSoal", data);
    }
    
    this.saveJawaban = function(idx,jawaban,tipeSoal){
        var listJawaban; // Buat Nampung Jawaban User
        var JumSoal; // Buat tau ada berapa soal
        var num = parseInt(idx) - 1; // Buat index
        if(tipeSoal == "latihan"){
            listJawaban = JSON.parse(localStorage.getItem("jawabanSoalLatihan")); // ambil jawaban user
            JumSoal = Object.keys(listJawaban).length; // dari jawaban user, bisa ketauan ada berapa soal
            var data = new Array(JumSoal);
            for(i=0;i<JumSoal;i++){
                if(i == num)
                    data[i] = jawaban;
                else
                    data[i] = listJawaban[i];
            }
            localStorage.setItem("jawabanSoalLatihan",JSON.stringify(data));
            //console.log(localStorage.getItem("jawabanSoalLatihan"));
            
        }
        else if(tipeSoal == "ujian"){
            listJawaban = JSON.parse(localStorage.getItem("jawabanSoalUjian"));
            JumSoal = Object.keys(listJawaban).length;
            var data = new Array(JumSoal);
            for(i=0;i<JumSoal;i++){
                if(i == num)
                    data[i] = jawaban;
                else
                    data[i] = listJawaban[i];
            }
            localStorage.setItem("jawabanSoalUjian",JSON.stringify(data));
            //console.log(localStorage.getItem("jawabanSoalUjian"));
            
        }

    }
    
    this.saveToDatabase = function(){
        if(this.typeSoal == "latihan") {
            var listJawaban = JSON.parse(localStorage.getItem("jawabanSoalLatihan"));
            var soal = JSON.parse(localStorage.getItem("soalLatihan"));
        }
        else if(this.typeSoal == "ujian") {
            var listJawaban = JSON.parse(localStorage.getItem("jawabanSoalUjian"));
            var soal = JSON.parse(localStorage.getItem("soalUjian"));
        }
        var jumSoal = Object.keys(listJawaban).length;
        var idSoal = {}
        for(i=0;i<jumSoal;i++){
            idSoal[soal[i].id_soal] = listJawaban[i];
        }
        idSoal["tipe"] = this.typeSoal;
        idSoal["idUser"] = localStorage.getItem("nis");
                
        return $.post(this.url+"model-soal.php?do=saveToDatabase",idSoal);
    }
    
    this.getHasil = function(){
        if(this.typeSoal == "latihan")
            this.idUjian = localStorage.getItem("lihatHasilLatihan");
        else
            this.idUjian = localStorage.getItem("lihatHasilUjian");
        var data = {id:this.idUjian};
        return $.post(this.url+"model-soal.php?do=lihathasil",data);
    }
    
    this.initUjian = function(){
        return $.post(this.url+"model-soal.php?do=initUjian");
    }
    
}