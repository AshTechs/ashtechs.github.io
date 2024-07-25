function togglePassword() {
   var pass = document.getElementById("password");
   var cpass = document.getElementById("cpassword");
   if (pass.type === "password" && cpass.type === "password") {
      pass.type = "text";
      cpass.type = "text";
   } else {
      pass.type = "password";
      cpass.type = "password";
   }
}