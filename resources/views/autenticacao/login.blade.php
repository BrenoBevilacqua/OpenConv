<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Login - Sistema de Convênios</title>
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

        input[type="user"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
        }

        .remember-me {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .remember-me input {
            margin-right: 5px;
        }

        .btn-login {
            width: 100%;
            padding: 10px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .btn-login:hover {
            background-color: #2980b9;
        }

        .forgot-password {
            text-align: center;
            margin-top: 10px;
        }

        .forgot-password a {
            color: #3498db;
            font-size: 0.9em;
            text-decoration: none;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }

        .error-message {
            background: #ffe0e0;
            color: #b00020;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h2>Entrar</h2>

        @if ($errors->any())
        <div class="error-message">
            @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <form action="{{ route('convenio.authenticate') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="username">Usuário/E-mail</label>
                <input
                    type="text" id="username" name="username" placeholder="Digite seu usuário/e-mail" required
                    value="{{ old('username') }}" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box;">
            </div>

            <div class="form-group">
                <label for="password">Senha</label>
                <input type="password" id="password" name="password" placeholder="Digite sua senha" required>
            </div>

            <div class="remember-me">
                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember">Lembrar de mim</label>
            </div>


            <!-- (mantém todo o conteúdo acima como está) -->

            <button type="submit" class="btn-login">Entrar</button>

            <!-- Novo botão para criar conta -->
            <div style="margin-top: 15px; text-align: center;">
                <a href="{{ route('register') }}" style="
            display: inline-block;
            padding: 10px 20px;
            background-color: #2ecc71;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        ">Criar Conta</a>
            </div>



        </form>
    </div>
</body>

</html>