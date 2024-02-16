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
        <h1>Commande affectée</h1>
        <p>Adresse du client: {{ $adresseClient }}</p>
        <p>Nom du client: {{ $nomClient }}</p>
        <p>Numéro du client: {{ $numeroClient }}</p>

        <h2>Informations vendeurs:</h2>
        <ul>
            @foreach($informationsVendeurs as $informations)
                <li>{{ $informations }}</li>
            @endforeach
        </ul>
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
