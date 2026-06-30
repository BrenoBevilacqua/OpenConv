<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta charset="UTF-8">
    <title>Registrar Administrador</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f6f8fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: white;
            padding: 30px 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            color: #2c3e50;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #34495e;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
        }

        .btn-login {
            width: 100%;
            padding: 10px;
            background-color: #2ecc71;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-login:hover {
            background-color: #27ae60;
        }

        .error-message {
            background: #ffe0e0;
            color: #b00020;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .back-to-login {
            text-align: center;
            margin-top: 15px;
        }

        .back-to-login a {
            color: #3498db;
            text-decoration: none;
            font-size: 0.9em;
        }

        .back-to-login a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h2>Registrar Administrador</h2>

        @if ($errors->any())
        <div class="error-message">
            @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <form action="{{ route('register.submit') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">Nome</label>
                <input type="text" id="name" name="name" required value="{{ old('name') }}">
            </div>

            <div class="form-group">
                <label for="username">Nome de Usuário</label>
                <input type="text" id="username" name="username" required value="{{ old('username') }}">
            </div>

            <div class="form-group">
                <label for="email">Email (para login)</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}">
            </div>

            <div class="form-group">
                <label for="password">Senha</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirmar Senha</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>

            <button type="submit" class="btn-login">Registrar</button>
        </form>

        <div class="back-to-login">
            <a href="{{ route('login') }}">← Já tenho conta</a>
        </div>
    </div>
</body>

</html>