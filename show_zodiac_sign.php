<?php
include('layouts/header.php');

// Recebe a data de nascimento do usuário
$data_nascimento = $_POST['data_nascimento'];
$data_nascimento = DateTime::createFromFormat('Y-m-d', $data_nascimento);

// Carrega o arquivo XML de signos
$signos = simplexml_load_file("signos.xml");

// Função para converter uma string de data (dd/mm) para um objeto DateTime
function criarData($diaMes, $ano) {
    return DateTime::createFromFormat('d/m/Y', $diaMes . '/' . $ano);
}

// Itera pelos signos e verifica em qual intervalo a data se encaixa
$signoEncontrado = null;
foreach ($signos->signo as $signo) {
    $dataInicio = criarData((string)$signo->dataInicio, $data_nascimento->format('Y'));
    $dataFim = criarData((string)$signo->dataFim, $data_nascimento->format('Y'));

    // Ajusta o ano se o período do signo envolver o final de ano
    if ($dataFim < $dataInicio) {
        $dataFim->modify('+1 year');
    }

    if ($data_nascimento >= $dataInicio && $data_nascimento <= $dataFim) {
        $signoEncontrado = $signo;
        break;
    }
}

// Mapeia os ícones de signos usando Font Awesome
$iconesSignos = [
    'Áries' => 'fa-aries',
    'Touro' => 'fa-taurus',
    'Gêmeos' => 'fa-gemini',
    'Câncer' => 'fa-cancer',
    'Leão' => 'fa-leo',
    'Virgem' => 'fa-virgo',
    'Libra' => 'fa-libra',
    'Escorpião' => 'fa-scorpio',
    'Sagitário' => 'fa-sagittarius',
    'Capricórnio' => 'fa-capricorn',
    'Aquário' => 'fa-aquarius',
    'Peixes' => 'fa-pisces'
];
?>

<div class="container mt-5">
    <?php if ($signoEncontrado): ?>
        <h2>
            <!-- Adiciona o ícone do signo ao lado do nome usando Font Awesome -->
            <i class="fas <?php echo $iconesSignos[(string)$signoEncontrado->signoNome]; ?>"></i>
            Seu signo é: <?php echo $signoEncontrado->signoNome; ?>
        </h2>
        <p><?php echo $signoEncontrado->descricao; ?></p>
    <?php else: ?>
        <p>Signo não encontrado para a data inserida.</p>
    <?php endif; ?>
    <a href="index.php" class="btn btn-secondary mt-3">Voltar</a>
</div>

</body>
</html>
