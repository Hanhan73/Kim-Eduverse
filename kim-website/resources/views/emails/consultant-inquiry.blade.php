<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Inquiry Konsultasi</title>
</head>

<body style="font-family: Arial, sans-serif;">

    <h2>Inquiry Konsultasi Baru</h2>

    <table cellpadding="6">
        <tr>
            <td><strong>Nama</strong></td>
            <td>: {{ $nama }}</td>
        </tr>
        <tr>
            <td><strong>Email</strong></td>
            <td>: {{ $email }}</td>
        </tr>
        <tr>
            <td><strong>Telepon</strong></td>
            <td>: {{ $telepon ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Perusahaan</strong></td>
            <td>: {{ $perusahaan ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Kategori</strong></td>
            <td>: {{ ucfirst(str_replace('-', ' ', $kategori)) }}</td>
        </tr>
    </table>

    <p><strong>Pesan:</strong></p>
    <p>{{ $pesan ?? '-' }}</p>

    <hr>
    <p>Email ini dikirim otomatis dari website.</p>

</body>

</html>