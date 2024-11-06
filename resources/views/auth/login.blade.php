<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Hire Now</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
  <script src="https://www.google.com/recaptcha/api.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap');

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Montserrat', sans-serif;
    }

    body, input {
      font-family: 'Montserrat', sans-serif;
      
    }

    .containers {
      position: relative;
      width: 100%;
      background-color: #fff;
      min-height: 100vh;
      overflow: hidden;
    }

    .form-row {
      justify-content: center;
    }

    .forms-container {
      position: absolute;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
    }

    .signin-signup {
      position: absolute;
      top: 50%;
      transform: translate(-50%, -50%);
      left: 75%;
      width: 50%;
      transition: 1s 0.7s ease-in-out;
      display: grid;
      grid-template-columns: 1fr;
      z-index: 5;
    }

    form {
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      padding: 0rem 5rem;
      transition: all 0.2s 0.7s;
      overflow: hidden;
      grid-column: 1 / 2;
      grid-row: 1 / 2;
    }

    form.sign-up-form {
      opacity: 0;
      z-index: 1;
    }

    form.sign-in-form {
      z-index: 2;
    }

    .title {
      font-size: 1.2rem;
      color: #444;
      margin-bottom: 10px;
    }

    .input-field {
        max-width: 380px;
        width: 100%;
        background-color: #fff;
        border: 1px solid #56196d; /* Updated border to be magenta with a clear thickness */
        border-radius: 20px; /* Single declaration for border-radius */
        margin: 10px 0;
        height: 55px;
        display: grid;
        grid-template-columns: 15% 85%;
        padding: 0 0.4rem;
        position: relative;
        border-radius: 20px; /* Single declaration for border-radius */
    }

    .input-field i {
      text-align: center;
      line-height: 55px;
      color: #acacac;
      transition: 0.5s;
      font-size: 1.1rem;
    }

    .input-field input {
      background: none;
      outline: none;
      border: none;
      line-height: 1;
      font-weight: 600;
      font-size: 1.1rem;
      color: #333;
    }

    .input-field input::placeholder {
      color: #aaa;
      font-weight: 500;
    }

    .social-text {
      padding: 0.7rem 0;
      font-size: 1rem;
      text-align: center;
      width: 100%;
    }

    .social-media {
      display: flex;
      justify-content: center;
    }

    .social-icon {
      height: 46px;
      width: 46px;
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 0 0.45rem;
      color: #333;
      border-radius: 50%;
      border: 1px solid #333;
      text-decoration: none;
      font-size: 1.1rem;
      transition: 0.3s;
    }

    .social-icon:hover {
      color: #5b1b71;
      border-color: #5b1b71;
    }

    .btn {
      width: 150px;
      background-color: #5b1b71;
      
      outline: none;
      height: 49px;
      
      color: #fff;
      text-transform: uppercase;
      font-weight: 600;
      margin: 10px 0;
      cursor: pointer;
      transition: 0.5s;
      border-radius: 25px;
    }

    .btn:hover {
      background-color: #6d248a;
    }

.business-banner {
  background-color: #fff; /* White background */
  border: 1px solid #ddd;
  border-radius: 20px;
  padding: 20px;
  text-align: center;
  margin-top: 20px;
  color: #000; /* Text color black */
  max-width: 600px; /* Set maximum width for the banner */
  margin-left: auto;
  margin-right: auto;
}

.business-banner p {
  font-size: 1rem;
  color: #000;
  margin-bottom: 10px;
}

.business-banner .heading {
  font-size: 1.5rem;
  font-weight: bold;
  margin-bottom: 10px;
  color:#56196d;
}

.business-banner .btn {
  background-color: #000;
  color: #fff; /* Text color white */
  text-decoration: none;
  padding: 12px 30px; /* Increase padding for button */
  border-radius: 5px;
  display: inline-block;
  transition: 0.3s;
  font-weight: bold;
  min-width: 250px; /* Set minimum width for button */
  
}

.business-banner .btn:hover {
  background-color: #333; /* Darken button on hover */
}

@media (max-width: 768px) {
  .signin-signup {
    left: 50%;
    transform: translate(-50%, -50%);
    width: 90%;
    padding: 0 20px;
  }

  form {
    padding: 0 1rem;
  }

  .btn {
    min-width: 150px;
    background-color:#56196D;
  }

  .business-banner {
    padding: 15px;
    max-width: 90%;
  }

  .business-banner .btn {
    padding: 10px 20px;
    min-width: 200px;
  }
}



    
    .business-banner .heading {
      font-size: 1.5rem;
      font-weight: bold;
      margin-bottom: 10px;
    }

    .panels-container {
      position: absolute;
      height: 100%;
      width: 100%;
      top: 0;
      left: 0;
      display: grid;
      grid-template-columns: repeat(2, 1fr);
    }

    .containers:before {
      content: "";
      position: absolute;
      height: 2000px;
      width: 2000px;
      top: -10%;
      right: 48%;
      transform: translateY(-50%);
      background-color: #56196D; /* Base color */
      background-image: linear-gradient(-45deg, #471558 0%, #C6426E 50%, #F86F03 100%);

      transition: 1.8s ease-in-out;
      border-radius: 50%;
      z-index: 6;
    }

    .image {
      width: 100%;
      transition: transform 1.1s ease-in-out;
      transition-delay: 0.4s;
    }

    .panel {
      display: flex;
      flex-direction: column;
      align-items: flex-end;
      justify-content: space-around;
      text-align: center;
      z-index: 6;
    }

    .left-panel {
      pointer-events: all;
      padding: 3rem 17% 2rem 12%;
    }

    .right-panel {
      pointer-events: none;
      padding: 3rem 12% 2rem 17%;
    }

    .panel .content {
      color: #fff;
      transition: transform 0.9s ease-in-out;
      transition-delay: 0.6s;
    }

    .panel h3 {
      font-weight: 600;
      line-height: 1;
      font-size: 1.5rem;
    }

    .panel p {
      font-size: 0.95rem;
      padding: 0.7rem 0;
    }

    .btn.transparent {
      margin: 0;
      background: none;
      border: 2px solid #fff;
      width: 130px;
      height: 41px;
      font-weight: 600;
      font-size: 0.8rem;
    }

    .right-panel .image, .right-panel .content {
      transform: translateX(800px);
    }

    .containers.sign-up-mode:before {
      transform: translate(100%, -50%);
      right: 52%;
    }

    .containers.sign-up-mode .left-panel .image, .container.sign-up-mode .left-panel .content {
      transform: translateX(-800px);
    }

    .containers.sign-up-mode .signin-signup {
      left: 25%;
    }

    .containers.sign-up-mode form.sign-up-form {
      opacity: 1;
      z-index: 2;
    }

    .containers.sign-up-mode form.sign-in-form {
      opacity: 0;
      z-index: 1;
    }

    .containers.sign-up-mode .right-panel .image, .containers.sign-up-mode .right-panel .content {
      transform: translateX(0%);
    }

    .containers.sign-up-mode .left-panel {
      pointer-events: none;
    }

    .containers.sign-up-mode .right-panel {
      pointer-events: all;
    }

    @media (max-width: 870px) {
      .containers {
        min-height: 800px;
        height: 100vh;
      }

      .signin-signup {
        width: 100%;
        top: 95%;
        transform: translate(-50%, -100%);
        transition: 1s 0.8s ease-in-out;
      }

      .containers.sign-up-mode .signin-signup {
        top: 5%;
        transform: translate(-50%, 0);
      }

      .panels-container {
        grid-template-columns: 1fr;
        grid-template-rows: 1fr 2fr 1fr;
      }

      .panel {
        flex-direction: row;
        justify-content: space-around;
        align-items: center;
        padding: 2.5rem 8%;
      }

      .left-panel {
        grid-row: 1 / 2;
      }

      .right-panel {
        grid-row: 3 / 4;
      }

      .image {
        width: 200px;
        transition: transform 0.9s ease-in-out;
        transition-delay: 0.6s;
      }

      .panel .content {
        padding-right: 15%;
        transition: transform 0.9s ease-in-out;
        transition-delay: 0.8s;
      }

      .panel h3 {
        font-size: 1.2rem;
      }

      .panel p {
        font-size: 0.7rem;
        padding: 0.5rem 0;
      }

      .btn.transparent {
        width: 110px;
        height: 35px;
        font-size: 0.7rem;
      }

      .containers:before {
        width: 1500px;
        height: 1500px;
        transform: translateX(-50%);
        left: 30%;
        bottom: 68%;
        right: initial;
        top: initial;
        transition: 2s ease-in-out;
      }

      .containers.sign-up-mode:before {
        transform: translate(-50%, 100%);
        bottom: 32%;
        right: initial;
      }

      .containers.sign-up-mode .left-panel .image, .containers.sign-up-mode .left-panel .content {
        transform: translateY(-300px);
      }

      .containers.sign-up-mode .right-panel .image, .containers.sign-up-mode .right-panel .content {
        transform: translateY(0px);
      }

      .right-panel .image, .right-panel .content {
        transform: translateY(300px);
      }

      .containers.sign-up-mode .signin-signup {
        top: 5%;
        transform: translate(-50%, 0);
      }
    }

    @media (max-width: 570px) {
      form {
        padding: 0 1.5rem;
      }

      .image {
        display: none;
      }

      .panel .content {
        padding: 0.5rem 1rem;
      }

      .containers {
        padding: 1.5rem;
      }

      .containers:before {
        top: 83%;
        left: -25%;
      }

      .signin-signup {
        top: 82%;
      }

      .containers.sign-up-mode:before {
        bottom: 28%;
        left: 50%;
      }
    }

    .new-alert {
      position: absolute;
      bottom: 0;
      right: 0;
      transition: all 0.3s ease-in-out;
      z-index: 999;
    }

    .logo-image {
      width: 60%; /* Adjust percentage based on how much space you want it to take */
      height: auto; /* Maintain aspect ratio */
      max-width: 600px; /* Set a max-width to prevent it from getting too large */
      min-width: 100px; /* Set a min-width to prevent it from getting too small */
    }
    
    
    .sort h4 {
  color: #56196d;
}
.sort {
  display: flex;
}
.bar {
  font-weight: 400;
  font-size: 14px;
}
.bar:hover {
  text-decoration: underline;
}
.sort h4:hover {
  text-decoration: underline;
}
.sort p {
  font-weight: 700;
  font-size: 14px;
  font-family: "Open Sans", sans-serif;
}
  </style>
</head>
<body class="hold-transition">
<div class="">
  <div class="containers">
    <div class="forms-container">
      <div class="signin-signup">
        <form action="{{ route('login') }}" method="post" class="sign-in-form">
          @csrf
          <div class="card-header text-center">
            <img src="../../dist/img/logo.png" alt="" style="height: 200px; width: auto;" />

          </div>
          <h2 class="title">Sign in to account</h2>
          <!-- Error Display Section -->
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
          <div class="input-field">
            <i class="fas fa-user"></i>
         
            <input type="email" placeholder="Email" name="email" value="{{ old('email') }}">
        
          </div>
          <div class="input-field">
            <i class="fas fa-lock"></i>
            <input type="password" placeholder="Password" name="password" required />
          </div>
          <div class="form-row">
            <div class="remember" style="">
              <input type="checkbox">
              <label for="">Remember me</label>
            </div>
          </div>
          <input type="submit" value="Login" class="btn solid btn-full-width" />
        </form>
        <div class="forgot form-row new-form sort" style="display: flex; gap: 15px; margin-top: 11px; margin-bottom: 11px;">
          <a style="color: rgb(125, 125, 125);">Don't have an account?</a>
          <a for="" style="text-decoration: none;color:#56196d; font-weight: bold;" href="/register" >Register</a>
        </div>
        <div class="forgot form-row another-form sort">
          <a for="" style="text-decoration: none;color:#56196d; font-weight: bold;" href="">Forgot password?</a>
        </div>
        <!-- <div class="social-text">Or Sign in with</div>
        <div class="social-media"> -->
        
        </div>
        
      </div>
    </div>
    <div class="panels-container">
      <div class="panel left-panel">
        <div class="content">
          <img src="../../dist/img/login.png" class="image" alt="" />
        </div>
      </div>
    </div>
  </div>
</div>

<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
</body>
</html>
