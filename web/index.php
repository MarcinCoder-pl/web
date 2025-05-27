<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require_once __DIR__ . '/tools/bootstrap.php';?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <title>Przykładowa Strona</title>
  <link rel="stylesheet" href="style.css">
</head><body>
  <div class="container">
    <header>
      <h1>Witaj na Mojej Stronie</h1>
      <p>To jest przykładowy szablon strony do testowania stylów CSS.</p>
    </header>

    <nav>
      <ul>
        <li><a href="#">Strona Główna</a></li>
        <li><a href="#">O nas</a></li>
        <li><a href="#">Kontakt</a></li>
      </ul>
    </nav>

    <main>
      <section class="box">
        <h2>Formularz kontaktowy</h2>
        <form action="#" method="post">
          <label for="name">Imię:</label>
          <input type="text" id="name" name="name" placeholder="Wpisz swoje imię">

          <label for="email">Email:</label>
          <input type="email" id="email" name="email" placeholder="Twój email">

          <label for="message">Wiadomość:</label>
          <textarea id="message" name="message" rows="4" placeholder="Twoja wiadomość..."></textarea>

          <button type="submit">Wyślij</button>
        </form>
      </section>

      <section class="box">
        <h2>Inna sekcja</h2>
        <p>Możesz tutaj dodać dowolną treść, np. aktualności lub opis usługi.</p>
      </section>
    </main>

    <aside>
      <h3>Panel boczny</h3>
      <p>To jest boczny panel, np. na linki lub informacje.</p>
    </aside>

    <footer>
      <p>&copy; 2025 Moja Strona. Wszelkie prawa zastrzeżone.</p>
    </footer>
  </div>
</body>

</html>
