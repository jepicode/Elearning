function ClearMem(){
    this.clearMem = function(type){
        if(type == "latihan"){
            localStorage.removeItem("soalLatihan");
            localStorage.removeItem("jumlahSoalLatihan");
            localStorage.removeItem("posisiSoalLatihan");
            localStorage.removeItem("jawabanSoalLatihan");
            localStorage.removeItem("idUjianLatihan");
            return true;
        }
        if(type == "ujian"){
            localStorage.removeItem("soalUjian");
            localStorage.removeItem("jumlahSoalUjian");
            localStorage.removeItem("posisiSoalUjian");
            localStorage.removeItem("jawabanSoalUjian");
            localStorage.removeItem("idUjian");
            return true;
        }
    }
    this.clearAll = function(){
        if(localStorage.getItem("idUjian") !== null){
            localStorage.removeItem("soalUjian");
            localStorage.removeItem("jumlahSoalUjian");
            localStorage.removeItem("posisiSoalUjian");
            localStorage.removeItem("jawabanSoalUjian");
            localStorage.removeItem("idUjian");
        }
        if(localStorage.getItem("idUjianLatihan") !== null){
            localStorage.removeItem("soalLatihan");
            localStorage.removeItem("jumlahSoalLatihan");
            localStorage.removeItem("posisiSoalLatihan");
            localStorage.removeItem("jawabanSoalLatihan");
            localStorage.removeItem("idUjianLatihan");
        }
        
        localStorage.removeItem("login");
        localStorage.removeItem("nis");
        localStorage.removeItem("nama");
        localStorage.removeItem("role");
        
    }
}