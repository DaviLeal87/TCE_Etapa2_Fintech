<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #292929;
            color: #eee;
            font-size: 24px;
        }

        h2 {
            margin-top: 70px;
            font-size: 36px;
            color: #bf80ff;
        }

        h3 {
            font-size: 20px;
            color: #bf80ff;
        }

        table {
            border-collapse: collapse;
            width: 50%;
            margin: 0 auto;
        }

        th,
        td {
            border: 1px solid #bf80ff;
            padding: 10px;
            text-align: center;
        }

        footer {
            padding: 20px;
            text-align: center;
            width: 100%;
            color: #bf80ff;
        }

        a:hover {
            background-color: #525252;
        }

        a {
            font-size: 20px;
            color: #bf80ff;
        }
    </style>
</head>

<body>
    <header>
        <h2>Trabalho Conclusão de Etapa </h2>
    </header>
    <main>
        <form>
            <fieldset>
                <legend>Histórico de simulações</legend>
                <label for="lblid">Id da Simulação</label>
                <input type="number" name="id"><br>
                <input type="submit" value="Buscar">
            </fieldset>
        </form>

        <?php
        require_once 'classes/r.class.php';

        R::setup(
            'mysql:host=127.0.0.1;dbname=fintech',
            'root',
            ''
        );

        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $tbfintech = R::load('tbfintech', $id);

            if ($tbfintech->id) {
                echo '<br><br><h3>Dados:</h3>';
                echo 'ID da Simulação: ' . $tbfintech->id . '<br>';
                echo 'Cliente: ' . $tbfintech->cliente . '<br>';
                echo 'Aporte Inicial: ' . $tbfintech->aporteinicial . '<br>';
                echo 'Aporte Mensal: ' . $tbfintech->aportemensal . '<br>';
                echo 'Rendimento: ' . $tbfintech->rendimento . '<br>';
                echo 'Periodo: ' . $tbfintech->periodo . '<br>';

                $aporteinicial = floatval($tbfintech->aporteinicial);
                $aportemensal = intval($tbfintech->aportemensal);
                $rendimentoMensal = floatval($tbfintech->rendimento);
                $periodo = floatval($tbfintech->periodo);

                function calcularValores($valorAtual, $aporte, $rendimentoMensal)
                {
                    $total = $valorAtual + $aporte;
                    $rendimento = $total * ($rendimentoMensal / 100);
                    $total += $rendimento;
                    $valores = array($rendimento, $total);
                    return $valores;
                }
                $resultados = array();
                $valorAtual = $aporteinicial;

                for ($i = 1; $i <= $periodo; $i++) {
                    if ($i == 1) {
                        $aporte = 0;
                    } else {
                        $aporte = $aportemensal;
                    }


                    list($rendimento, $total) = calcularValores($valorAtual, $aporte, $rendimentoMensal);

                    $resultados[] = array(
                        'mes' => $i,
                        'valorinicial' => $valorAtual,
                        'aporte' => $aporte,
                        'rendimento' => $rendimento,
                        'total' => $total
                    );


                    $valorAtual = $total;
                }

                echo "<h3>Resultados da Simulação:</h3>
                            <table border=1>
                              <tr>
                                <th>Mês</th>
                                <th>Valor Inicial</th>
                                <th>Rendimento</th>
                                <th>Total</th>
                              </tr>";

                foreach ($resultados as $resultado) {
                    echo "<tr>";
                    echo "<td>" . $resultado['mes'] . "</td>";
                    echo "<td>" . number_format($resultado['valorinicial'], 2, ',', '.') . "</td>";
                    echo "<td>" . number_format($resultado['rendimento'], 2, ',', '.') . "</td>";
                    echo "<td>" . number_format($resultado['total'], 2, ',', '.') . "</td>";
                    echo "</tr>";
                }
                echo '</table>';
            } else {
                echo '<div style="color:red">
                <br><br>Simulação não encontrada!
                </div>';
            }
        }
        ?>
        <br><br>
        <a href="index.html">Home</a>
        <br><br>
    </main>
    <footer>
        <p>&copy; IFNMG - 2023 Davi Antônio e Luana Lima </p>
    </footer>
</body>

</html>