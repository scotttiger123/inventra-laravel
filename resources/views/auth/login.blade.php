<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Inventra Login</title>
    <link rel="stylesheet" href="login.css" />
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
      rel="stylesheet"
    />
        <!-- Google Font: Source Sans Pro -->
      <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
      
      <!-- icheck bootstrap -->
      <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
      
      <!-- Theme style -->
      <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
      <script src="https://www.google.com/recaptcha/api.js"></script>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}
body {
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}
.container {
  max-width: 2000px;
  margin-left: auto;
  margin-right: auto;
}
.i-logo {
  display: flex;
  justify-content: flex-end;
  align-items: center;
  padding: 10px 40px;
}

.i-logo img {
  max-width: 100%;
  height: auto;
  max-height: 80px;
}
.page {
  display: flex;
  justify-content: space-between;
  width: 100%;
}
.inputs {
  display: flex;
  flex-direction: column;
  margin-top: 2rem;
  justify-content: center;
  width: 50%;
}
.inputs input {
  padding-block: 14px;
  border: none;
  border-radius: 6px;
  padding-left: 6px;
}
.password {
  margin-top: 1.5rem;
  display: flex;
  flex-direction: column;
}
.input-container {
  position: relative;
  display: flex;
  align-items: center;
}

#password {
  width: 100%;
  padding-right: 40px;
}

#togglePassword {
  position: absolute;
  right: 10px;
  bottom: 12px;
  cursor: pointer;
  color: #aaa;
  font-size: 1.2rem;
}

#togglePassword:hover {
  color: #000;
}
.name {
  margin-top: 1.5rem;
  display: flex;
  flex-direction: column;
}
.inputs input {
  margin-top: 10px;
}
.left {
  background-color: #00bf63;
  color: white;
  padding: 2rem;
  width: 50%;
  margin-top: -7rem;
  height: 100vh;
  display: flex;
  justify-content: center;
  flex-direction: column;
}
.center {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: 3rem;
  flex-direction: column;
}
.check {
  display: flex;
  gap: 6px;
  margin-top: 1rem;
}
.check p {
  margin-top: 7px;
}
.log-btn {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: 3rem;
}
.log-btn button {
  background-color: black;
  color: white;
  font-weight: 200;
  font-size: 18px;
  padding: 14px 60px;
  border: none;
  border-radius: 6px;
  width: 50%;
  cursor: pointer;
}
.para {
  display: flex;
  justify-content: center;
  margin-top: 10px;
  text-align: center;
}
.para span {
  font-weight: 700;
}
.right {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 50%;
}

.right-img img {
  width: 90%;
  height: auto;
  display: grid;
  place-items: center;
}
.hide {
  margin-top: 6rem;
  margin-left: 2.5rem;
  display: none;
}
.hide p {
  margin-top: 2rem;
}
@media (max-width: 1095px) {
  .hide {
    display: block;
  }
  .page {
    display: flex;
    flex-direction: column-reverse;
  }
  .left {
    width: 100%;
    margin-top: 1rem;
    height: 50vh;
  }
  .i-logo {
    position: absolute;
    padding-right: 3rem;
    top: 0;
  }
  .right-img img {
    width: 500px;
    height: auto;
  }
  .right {
    margin-left: auto;
    margin-right: auto;
  }
  .show {
    display: none;
  }
}
@media (max-width: 670px) {
  .inputs {
    display: flex;
    flex-direction: column;
    margin-top: 0rem;
    justify-content: center;
    width: 70%;
  }
  .log-btn button {
    width: 70%;
  }
  .left {
    padding: 0rem;
    margin-top: 1rem;
  }
}
@media (max-width: 599px) {
  .right-img img {
    width: 300px;
    height: auto;
  }
  .right {
    margin-left: auto;
    margin-right: auto;
  }
}

      </style>
  </head>
  <body>
    <div class="container">
      <div class="i-logo">
        
        <img src="../../dist/img/logo.png"  />
      </div>
      <div class="hide">
        <h1>
          Welcome to <br />
          Inventra!
        </h1>
        <p>Before Continue, Please Sign in First.</p>
      </div>
      <div class="page">
        <div class="left">
          <div class="center">
            <div class="inputs">
              <div class="show">
                <h1>Let's you sign in</h1>
                <p>Welcome to Inventra!</p>
                @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                      </div>
                  @endif
                  @if (session('success'))
                      <div class="alert alert-success">
                          {{ session('success') }}
                      </div>
                  @endif
                @if($errors->any())
                  <div class="alert alert-danger">
                    <ul>
                      @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                      @endforeach
                    </ul>
                  </div>
                @endif
              </div>
              <form action="{{ route('login') }}" method="post" class="sign-in-form">
                @csrf
              <div class="name">
                <label>Username</label>
                <input type="text" type="email" placeholder="Email" name="email" value="{{ old('email') }}"/>
              </div>
              <div class="password">
                <label>Password</label>
                <div class="input-container">
                  <input type="password" id="password" placeholder="Password" name="password" required />
                  <i id="togglePassword" class="fa-solid fa-eye-slash"></i>
                </div>
                <div class="check">
                  <input type="checkbox" id="keepLoggedIn" />
                  
                  <p>Keep me logged in</p>
                </div>
              </div>
            </div>
          </div>
          <div class="log-btn">
            <button type = "submit">Sign In</button>
          </div>
          </form>
          <div class="para">
            <p>
              Don't have an account? <span>Register <br />Forgot Password</span>
            </p>
          </div>
        </div>
        <div class="right">
          <div class="right-img">
                 <img src="../../dist/img/log.png"  />
          </div>
        </div>
      </div>
    </div>

    <script>
      const passwordInput = document.getElementById("password");
      const togglePassword = document.getElementById("togglePassword");

      togglePassword.addEventListener("click", () => {
        const type = passwordInput.type === "password" ? "text" : "password";
        passwordInput.type = type;
        togglePassword.classList.toggle("fa-eye");
        togglePassword.classList.toggle("fa-eye-slash");
      });
    </script>
  </body>
</html>
