<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Hire Now Registration Employer</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <!-- Custom CSS -->
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
      background-image: url('{{ asset('storage/background.jpg') }}');
      background-size: cover;
      background-repeat: no-repeat;
      background-attachment: fixed;
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

    .register-box {
      width: 500px;
      background-color: rgba(255, 255, 255, 0.7);
      border: 1px solid #dcdcdc;
      border-radius: 4px;
      padding: 20px 30px;
      box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
      text-align: center;
    }

    .title {
      font-size: 2.2rem;
      color: #444;
      margin-bottom: 10px;
    }

    .input-field {
      max-width: 380px;
      width: 100%;
      background-color: #f0f0f0;
      margin: 10px 0;
      height: 55px;
      border-radius: 5px;
      display: grid;
      grid-template-columns: 15% 85%;
      padding: 0 0.4rem;
      position: relative;
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
      border: none;
      outline: none;
      height: 49px;
      border-radius: 4px;
      color: #fff;
      text-transform: uppercase;
      font-weight: 600;
      margin: 10px 0;
      cursor: pointer;
      transition: 0.5s;
    }

    .btn:hover {
      background-color: #6d248a;
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
      background-image: linear-gradient(-45deg, #5b1b71 0%, #C6426E 50%, #F86F03 100%);
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

      .signin-signup, .containers.sign-up-mode .signin-signup {
        left: 50%;
      }

      .panels-containers {
        grid-template-columns: 1fr;
        grid-template-rows: 1fr 2fr 1fr;
      }

      .panel {
        flex-direction: row;
        justify-content: space-around;
        align-items: center;
        padding: 2.5rem 8%;
        grid-column: 1 / 2;
      }

      .right-panel {
        grid-row: 3 / 4;
      }

      .left-panel {
        grid-row: 1 / 2;
        display: none;
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
        bottom: 83%;
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
      width: 60%;
      height: auto;
      max-width: 600px;
      min-width: 100px;
      background-color: white; /* White background for the logo */
      padding: 10px; /* Optional: Add some padding around the logo */
      border-radius: 5px; /* Optional: Rounded corners for the logo background */
    }
  </style>
</head>
<body class="hold-transition">
  <div class="">
    <div class="containers">
      <div class="forms-container">
        <div class="signin-signup">
          <form action="{{ route('register-employer-store') }}" method="post" class="sign-up-form">
            @csrf
                        <div class="card-header text-center" style="background-color: white;">
                <img src="../../dist/img/logo.png" class="logo-image" alt="Logo" />
            </div>

            <h2 class="title">Register as Employer</h2>
            <!-- Error Display Section -->
            @if(session('status'))
              <div class="alert alert-success">
                {{ session('status') }}
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
              <input 
                type="text" 
                placeholder="Full Name" 
                name="name" 
                value="{{ old('name') }}" 
                required 
              />
            </div>
            <div class="input-field">
              <i class="fas fa-envelope"></i>
              <input type="email" placeholder="Email" name="email" value="{{ old('email', $email) }}" required />
       
            </div>
            <div class="input-field">
              <i class="fas fa-phone"></i>
              <input 
                type="tel" 
                placeholder="Phone" 
                name="phone" 
                value="{{ old('phone') }}" 
                required 
              />
            </div>
            <div class="input-field">
              <i class="fas fa-lock"></i>
              <input type="password" placeholder="Password" name="password" required />
            </div>
            <div class="input-field">
              <i class="fas fa-lock"></i>
              <input type="password" placeholder="Confirm Password" name="password_confirmation" required />
            </div>
            <input type="submit" value="Register" class="btn solid" />
            <p class="social-text">Already have an account? <a href="{{ route('login-employer') }}" >Login</a></p>
          </form>
        </div>
      </div>
      <div class="panels-container">
        <div class="panel left-panel">
          <div class="content">
            <img src="../../dist/img/login.png" class="image" alt="Login Image" />
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- jQuery -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
  <!-- Custom JS -->
  <script>
    // Your custom JavaScript code
  </script>
</body>
</html>
