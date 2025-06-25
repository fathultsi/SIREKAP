<!-- resources/views/unauthorized.blade.php -->
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditolak</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"> <!-- Link ke stylesheet -->
</head>

<body>
    <div class="container">
        <h1>Akses Ditolak</h1>
        <p>Maaf, Anda tidak diizinkan mengakses halaman ini.</p>
        <a href="{{ route('login') }}">Kembali</a>
    </div>
</body>

</html>
