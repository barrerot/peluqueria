<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./css/signup/vars.css">
  <link rel="stylesheet" href="./css/signup/style.css">
  
  <style>
   a,
   button,
   input,
   select,
   h1,
   h2,
   h3,
   h4,
   h5,
   * {
       box-sizing: border-box;
       margin: 0;
       padding: 0;
       border: none;
       text-decoration: none;
       background: none;
   
       -webkit-font-smoothing: antialiased;
   }
   
   menu, ol, ul {
       list-style-type: none;
       margin: 0;
       padding: 0;
   }

   .button-base, .button-base2 {
       cursor: pointer;
   }

   .button2 a {
       color: inherit;
       text-decoration: none;
   }
   
   .button2 a:hover {
       text-decoration: underline;
   }

   /* Estilos para inputs */
   input {
     width: 100%;
     padding: 10px;
     line-height: 1.5;
     font-size: 16px;
     border: 1px solid #ccc;
     border-radius: 4px;
     height: 44px;
     box-sizing: border-box;
   }

   input:focus {
     outline: none;
     border-color: #007BFF;
     box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
   }

   .input-field {
     margin-bottom: 15px;
   }

   .hint-text {
     font-size: 12px;
     color: #666;
     margin-top: 5px;
   }

   .actions {
     margin-top: 20px;
   }

   .row {
     display: flex;
     align-items: center;
     justify-content: space-between;
     margin-top: 20px;
   }

   </style>
  <title>Registro</title>
</head>
<body>
  <div class="desktop">
    <div class="log-in">
      <div class="section">
        <div class="header-navigation">
          <div class="logo">
            <div
              class="logo-reserwa"
              style="
                background: url(./img/signup/logo-reserwa0.png) center;
                background-size: cover;
                background-repeat: no-repeat;
              "
            >
              <div class="content"></div>
            </div>
          </div>
        </div>
        <div class="container">
          <div class="content2">
            <div class="text-and-supporting-text">
              <div class="text">Crea tu cuenta</div>
              <div class="supporting-text">
                <span>
                  <span class="supporting-text-span">Crea tu cuenta</span>
                  <span class="supporting-text-span2">gratis</span>
                  <span class="supporting-text-span3">ahora.</span>
                </span>
              </div>
            </div>
            <div id="messages">
              <?php session_start(); ?>
              <?php if (isset($_SESSION['error']) && !empty($_SESSION['error'])): ?>
                  <div class="alert alert-danger alert-dismissible fade show" role="alert">
                      <i class="fas fa-exclamation-circle"></i>
                      <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
              <?php endif; ?>

              <?php if (isset($_SESSION['success']) && !empty($_SESSION['success'])): ?>
                  <div class="alert alert-success alert-dismissible fade show" role="alert">
                      <i class="fas fa-check-circle"></i>
                      <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
              <?php endif; ?>
            </div>
            <div class="content3">
              <div class="form">
                <form action="registro.php" method="POST">
                  <div class="input-field">
                    <label for="nombre">Nombre*</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Introduce tu nombre">
                  </div>
                  <div class="input-field">
                    <label for="email">Email*</label>
                    <input type="email" id="email" name="email" placeholder="Correo electrónico">
                  </div>
                  <div class="input-field">
                    <label for="password">Contraseña*</label>
                    <input type="password" id="password" name="password" placeholder="Crea una contraseña">
                    <div class="hint-text">
                      Debe tener mínimo 8 caracteres con números y letras.
                    </div>
                  </div>
                  <div class="actions">
                    <button type="submit" class="button">
                      <div class="button-base">
                        <div class="text3">Crear cuenta</div>
                      </div>
                    </button>
                  </div>
                </form>
              </div>
              <div class="row">
                <div class="text5">¿Ya tienes una cuenta?</div>
                <div class="button2">
                  <div class="button-base2">
                    <a href="login-form.php">
                      <div class="text6">Aquí para acceder.</div>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="footer">
          <div class="text7">© 2024 Todos los derechos reservados a Reserwa.</div>
        </div>
      </div>
      <div class="section2">
        <img class="line-pattern" src="./img/signup/line-pattern0.svg" />
        <img class="line-pattern2" src="./img/signup/line-pattern1.svg" />
        <div class="_3-2-screen-mockup">
          <div class="mockup-shadow"></div>
          <img
            class="screen-mockup-replace-fill"
            src="./img/signup/screen-mockup-replace-fill0.png"
          />
        </div>
      </div>
    </div>
  </div>
</body>
</html>
