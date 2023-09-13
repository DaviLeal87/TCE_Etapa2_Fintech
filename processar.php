<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=m, initial-scale=1.0">
    <title>Processamento</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #292929;
            color: #eee;
            font-size: 15px;
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

        footer {
            padding: 20px;
            text-align: center;
            width: 100%;
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
        <h2>Trabalho Conclusão de Etapa</h2>
    </header>
    <main>
        <?php
        require_once 'classes/r.class.php';
        require_once 'classes/autoloader.class.php';

        R::setup(
            'mysql:host=127.0.0.1;dbname=fintech',
            'root',
            ''
        );

        if (isset($_GET['aporteinicial']) && isset($_GET['aportemensal'])) {
            $cliente = $_GET['cliente'];
            $aporteinicial = $_GET['aporteinicial'];
            $aportemensal = $_GET['aportemensal'];
            $rendimento = $_GET['rendimento'];
            $periodo = $_GET['periodo'];

            $tbfintech = R::dispense('tbfintech');
            $tbfintech->cliente = $cliente;
            $tbfintech->aporteinicial = $aporteinicial;
            $tbfintech->aportemensal = $aportemensal;
            $tbfintech->rendimento = $rendimento;
            $tbfintech->periodo = $periodo;
            $id = R::store($tbfintech);
            R::close();
        }

        echo '

        <h3>Dados da Simulação:</h3>
        <p> ID da Simulação: ' . $tbfintech->id . ' </p>
        <p>Cliente: ' . $cliente . '</p>
        <p>Aporte Inicial: R$ ' . $aporteinicial . '</p>
        <p>Aporte Mensal: R$ ' . $aportemensal . '</p>
        <p>Rendimento: ' . $rendimento . '%</p>
        <p>Período de Investimento: ' . $periodo . ' meses</p>';

        if (isset($_GET['cliente'])  &&  isset($_GET['aporteinicial']) && isset($_GET['aportemensal'])  && isset($_GET['rendimento']) && isset($_GET['periodo'])) {

            $cliente = $_GET['cliente'];
            $aporteinicial = $_GET['aporteinicial'];
            $aportemensal = $_GET['aportemensal'];
            $rendimentoMensal = $_GET['rendimento'];
            $periodo = $_GET['periodo'];

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
        }
        echo '</table>';
        ?>
        <br><br>
        <a href="entrada.html">Fazer nova simulação</a>
        <br>
        <a href="index.html">Home</a>
        <br>
    </main>
    <hr>
    <footer>
        <p>&copy; IFNMG - 2023 Davi Antônio e Luana Lima </p>
    </footer>
</body>

</html>