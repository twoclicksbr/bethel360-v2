<?php

/**
 * Script de teste para validação da ETAPA 1
 * Testa: Register, Login, Logout
 */

$baseUrl = 'https://beth1.bethel360-api.test/api/v2';

echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║         BETHEL360° - TESTE DE AUTENTICAÇÃO (ETAPA 1)      ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";
echo "\n";

// Função helper para fazer requisições
function makeRequest($method, $url, $data = null, $token = null) {
    $ch = curl_init($url);

    $headers = [
        'Content-Type: application/json',
        'Accept: application/json',
    ];

    if ($token) {
        $headers[] = "Authorization: Bearer $token";
    }

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Para desenvolvimento local

    if ($data) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);

    curl_close($ch);

    if ($error) {
        return [
            'success' => false,
            'error' => $error,
            'http_code' => $httpCode,
        ];
    }

    return [
        'success' => $httpCode >= 200 && $httpCode < 300,
        'http_code' => $httpCode,
        'data' => json_decode($response, true),
        'raw' => $response,
    ];
}

// Função helper para exibir resultado
function showResult($testName, $result) {
    echo "┌─ $testName\n";
    echo "│  Status: " . ($result['success'] ? '✅ SUCESSO' : '❌ FALHA') . " (HTTP {$result['http_code']})\n";

    if ($result['success']) {
        if (isset($result['data']['data'])) {
            echo "│  Dados: " . json_encode($result['data']['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
        } elseif (isset($result['data']['message'])) {
            echo "│  Mensagem: {$result['data']['message']}\n";
        }
    } else {
        if (isset($result['error'])) {
            echo "│  Erro: {$result['error']}\n";
        }
        if (isset($result['data']['message'])) {
            echo "│  Mensagem: {$result['data']['message']}\n";
        }
        if (isset($result['data']['errors'])) {
            echo "│  Erros de validação:\n";
            foreach ($result['data']['errors'] as $field => $errors) {
                echo "│    - $field: " . implode(', ', $errors) . "\n";
            }
        }
    }
    echo "└─\n\n";

    return $result;
}

// ============================================================================
// TESTE 1: Health Check
// ============================================================================
echo "🔍 TESTE 1: Health Check (rota pública)\n";
$result = makeRequest('GET', "$baseUrl/health");
showResult('Health Check', $result);

// ============================================================================
// TESTE 2: Registrar novo usuário
// ============================================================================
echo "📝 TESTE 2: Registrar novo usuário\n";

$userData = [
    'first_name' => 'Maria',
    'last_name' => 'Santos',
    'email' => 'maria.santos' . time() . '@teste.com', // Email único
    'password' => 'senha123',
    'password_confirmation' => 'senha123',
    'gender_id' => 2, // Feminino
    'birth_date' => '1985-03-20',
];

$registerResult = makeRequest('POST', "$baseUrl/auth/register", $userData);
showResult('Registro de Usuário', $registerResult);

if (!$registerResult['success']) {
    echo "❌ FALHA NO REGISTRO - Não é possível continuar com os testes\n";
    exit(1);
}

// ============================================================================
// TESTE 3: Login
// ============================================================================
echo "🔐 TESTE 3: Login\n";

$loginData = [
    'email' => $userData['email'],
    'password' => $userData['password'],
];

$loginResult = makeRequest('POST', "$baseUrl/auth/login", $loginData);
showResult('Login', $loginResult);

if (!$loginResult['success']) {
    echo "❌ FALHA NO LOGIN - Não é possível continuar com os testes\n";
    exit(1);
}

// Token pode estar em data.token ou data.data.token dependendo do response format
$token = null;
if (isset($loginResult['data']['token'])) {
    $token = $loginResult['data']['token'];
} elseif (isset($loginResult['data']['data']['token'])) {
    $token = $loginResult['data']['data']['token'];
}

if (!$token) {
    echo "❌ TOKEN NÃO RECEBIDO\n";
    echo "Debug - Estrutura da resposta:\n";
    print_r($loginResult);
    echo "\n";
    exit(1);
}

echo "🎫 Token recebido: " . substr($token, 0, 20) . "...\n\n";

// ============================================================================
// TESTE 4: Logout
// ============================================================================
echo "🚪 TESTE 4: Logout\n";

$logoutResult = makeRequest('POST', "$baseUrl/auth/logout", null, $token);
showResult('Logout', $logoutResult);

// ============================================================================
// TESTE 5: Tentar acessar com token revogado
// ============================================================================
echo "🔒 TESTE 5: Validar token revogado\n";

$invalidResult = makeRequest('POST', "$baseUrl/auth/logout", null, $token);
// Inverter o sucesso: se der 401, o teste passou (token foi realmente revogado)
$invalidResult['success'] = $invalidResult['http_code'] === 401;
showResult('Token Revogado (deve retornar 401)', $invalidResult);

// ============================================================================
// RESUMO
// ============================================================================
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║                      RESUMO DOS TESTES                     ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";
echo "\n";

$tests = [
    'Health Check' => $result,
    'Registro' => $registerResult,
    'Login' => $loginResult,
    'Logout' => $logoutResult,
    'Token Revogado' => $invalidResult,
];

$passed = 0;
$total = count($tests);

foreach ($tests as $name => $result) {
    $status = $result['success'] ? '✅' : '❌';
    echo "$status $name (HTTP {$result['http_code']})\n";
    if ($result['success']) {
        $passed++;
    }
}

echo "\n";
echo "Testes passados: $passed/$total\n";
echo "\n";

if ($passed === $total) {
    echo "🎉 TODOS OS TESTES PASSARAM! ETAPA 1 VALIDADA COM SUCESSO! 🎉\n";
    exit(0);
} else {
    echo "⚠️  ALGUNS TESTES FALHARAM - Verifique os logs acima\n";
    exit(1);
}
