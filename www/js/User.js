function User() {
    this.nama;
    this.nis;
    this.pass;
    this.role;
    this.url = "http://e-learning-organ.000webhostapp.com/";
    
    this.Login = function(){
        var arr = {nis:this.nis, pass:this.pass};
        return $.post(this.url+"model-user.php?do=login",arr);
    }
    
    this.Logout = function(){
        localStorage.removeItem("login");
        localStorage.removeItem("nis");
        localStorage.removeItem("nama");
                
        /* Latihan */
        localStorage.removeItem("soalLatihan");
        localStorage.removeItem("jumlahSoalLatihan");
                
        window.location='index.php';
    }
    
    this.Register = function(){
        var arr = {};
        arr['nis'] = this.nis;
        arr['nama'] = this.nama;
        arr['pass'] = this.pass;
        arr['role'] = "siswa";
        return $.post(this.url+"model-user.php?do=register",arr);
    }
}