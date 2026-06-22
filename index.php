<?php
/**
 * Calculadora de Hipoteca - Cuota mensual (sistema francés) e intereses totales
 */
header('Content-Type: text/html; charset=utf-8');

$capital = $tasaAnual = $plazoAnios = '';
$cuota = null; $totalPagado = null; $totalIntereses = null; $primerInteres = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $capital   = (float)($_POST['capital'] ?? 0);
    $tasaAnual = (float)($_POST['tasaAnual'] ?? 0);
    $plazoAnios = (int)($_POST['plazoAnios'] ?? 0);

    if ($capital > 0 && $tasaAnual >= 0 && $plazoAnios > 0) {
        $tasaMensual = $tasaAnual / 100 / 12;
        $n = $plazoAnios * 12;

        if ($tasaMensual == 0) {
            $cuota = $capital / $n;
        } else {
            $cuota = $capital * $tasaMensual * pow(1 + $tasaMensual, $n) / (pow(1 + $tasaMensual, $n) - 1);
        }
        $totalPagado = $cuota * $n;
        $totalIntereses = $totalPagado - $capital;
        $primerInteres = $capital * $tasaMensual;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Calculadora de Hipoteca Online | ConfiguroWeb</title>
<meta name="description" content="Calcula la cuota mensual de tu hipoteca y los intereses totales. Sistema de amortización francés. Gratis en ConfiguroWeb.">
<meta name="keywords" content="calculadora hipoteca, cuota mensual, amortizacion, prestamo, interes, mortgage calculator">
<meta property="og:type" content="website">
<meta property="og:title" content="Calculadora de Hipoteca Online">
<meta property="og:description" content="Calcula la cuota mensual de tu hipoteca y los intereses totales.">
<link rel="canonical" href="https://demoscweb.com/github/php-calculadora-hipoteca/">
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"WebApplication","name":"Calculadora de Hipoteca","applicationCategory":"FinanceApplication","operatingSystem":"Any","offers":{"@type":"Offer","price":"0","priceCurrency":"USD"},"author":{"@type":"Person","name":"ConfiguroWeb","url":"https://configuroweb.com"}}
</script>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header>
  <h1>🏠 Calculadora de Hipoteca</h1>
  <p class="subtitle">Cuota mensual e intereses totales</p>
</header>
<main>
  <form method="POST">
    <label for="capital">Importe del préstamo ($)</label>
    <input type="number" name="capital" id="capital" step="0.01" value="<?php echo htmlspecialchars($capital); ?>" placeholder="150000" required>

    <label for="tasaAnual">Tasa de interés anual (%)</label>
    <input type="number" name="tasaAnual" id="tasaAnual" step="0.01" value="<?php echo htmlspecialchars($tasaAnual); ?>" placeholder="4.5" required>

    <label for="plazoAnios">Plazo (años)</label>
    <input type="number" name="plazoAnios" id="plazoAnios" step="1" value="<?php echo htmlspecialchars($plazoAnios); ?>" placeholder="30" required>

    <button type="submit" class="btn-primary">🏠 Calcular cuota</button>
  </form>

  <?php if ($cuota !== null): ?>
  <div class="resultados">
    <h2>Resultados</h2>
    <div class="tarjeta-destacada">
      <span class="etiqueta">Cuota mensual</span>
      <span class="valor-grande">$<?php echo number_format($cuota, 2); ?></span>
    </div>
    <div class="grid-3">
      <div class="tarjeta-sm">
        <span class="etiqueta">Total a pagar</span>
        <span class="valor-sm">$<?php echo number_format($totalPagado, 2); ?></span>
      </div>
      <div class="tarjeta-sm">
        <span class="etiqueta">Intereses totales</span>
        <span class="valor-sm neg">$<?php echo number_format($totalIntereses, 2); ?></span>
      </div>
      <div class="tarjeta-sm">
        <span class="etiqueta">% en intereses</span>
        <span class="valor-sm"><?php echo round(($totalIntereses / $capital) * 100, 1); ?>%</span>
      </div>
    </div>
    <p class="interpretacion">
      🏠 Por un préstamo de <strong>$<?php echo number_format($capital, 2); ?></strong> a <?php echo (int)$plazoAnios; ?> años
      pagarás <strong>$<?php echo number_format($cuota, 2); ?></strong> al mes durante <?php echo (int)($plazoAnios*12); ?> cuotas.
      Al final habrás pagado <strong>$<?php echo number_format($totalIntereses, 2); ?></strong> solo en intereses
      (el <?php echo round(($totalIntereses/$capital)*100,1); ?>% del capital).
    </p>
    <p class="interpretacion" style="opacity:0.85">
      💡 El primer mes, <strong>$<?php echo number_format($primerInteres, 2); ?></strong> de tu cuota son intereses
      y <strong>$<?php echo number_format($cuota - $primerInteres, 2); ?></strong> es amortización del capital.
    </p>
  </div>
  <?php endif; ?>

  <section class="info">
    <h2>¿Cómo se calcula la cuota?</h2>
    <p>Usamos el sistema de amortización <strong>francés</strong> (el más común en hipotecas), donde la cuota es constante y la parte de intereses disminuye con el tiempo mientras la amortización al capital aumenta.</p>
    <p class="formula">Cuota = Capital × i × (1+i)ⁿ / ((1+i)ⁿ − 1)</p>
    <p>donde <em>i</em> es la tasa mensual (tasa anual ÷ 12 ÷ 100) y <em>n</em> es el número total de cuotas.</p>
  </section>
</main>
<footer>
  <p>Desarrollado por <a href="https://configuroweb.com" target="_blank">ConfiguroWeb</a> ·
     <a href="https://appscweb.com/citas/" target="_blank">Sistema de Citas</a> ·
     <a href="https://appscweb.com/negocios/" target="_blank">Gestión de Negocios</a></p>
  <p>&copy; <?php echo date('Y'); ?> ConfiguroWeb</p>
</footer>
<script src="assets/script.js"></script>
</body>
</html>