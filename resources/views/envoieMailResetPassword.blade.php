<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
  </head>
  <body>
    <div class="image" style="width: 90%; margin: 0 auto; text-align: center">
      <img
        src="https://falltech.site/panierlocal/assets/baniereclrok.png"
        alt=""
        class="img-fluid"
        style="width: 100%; height: auto"
      />
    </div>
    <div
      class="text-container"
      style="
        text-align: justify;
        width: 60%;
        margin: 20px auto;
        color: #263238;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
      "
    >
    <p>Salut,</p>
    <p>Vous avez demandé une réinitialisation de votre mot de passe. Cliquez sur le bouton ci-dessous pour procéder à la réinitialisation :</p>
    <a href="{{ $resetPasswordUrl }}" style="display: inline-block; background-color: #007bff; color: #ffffff; text-decoration: none; padding: 10px 20px; border-radius: 5px;">Réinitialiser mon mot de passe</a>
    <p>Si vous n'avez pas demandé cette réinitialisation, vous pouvez ignorer cet e-mail en toute sécurité.</p>
    <p>Cordialement,<br>Panier Local</p>
    </div>
    <div
      class="text-footer"
      style="
        text-align: start;
        width: 90%;
        background-color: #263238;
        border-radius: 50px;
        display: flex;
        justify-content: start;
        padding: 10px;margin: 0 auto;
      "
    >
      <img
        src="https://falltech.site/panierlocal/assets/panierLocal-logo.png"
        alt=""
        style="width: 200px; height: 170px"
      />
      <div
        class="link-text"
        style="display: flex; align-items: center"
      >
        <span style="margin-top:55%"
          ><a
            href="https://www.falltech.site/panierlocal"
            target="_blank"
            style="color: white; font-size: 20px;"
            >Visiter le site</a
          >
        </span>
      </div>
    </div>
  </body>
</html>

