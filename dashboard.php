<?php
    
  include('./php/db/conexao.php'); 
  
  // Caso o usuário não esteja logado, redireciona para o index
  include('./php/db/verificar_login.php');

  $id_user = $_SESSION['id'];
  
  // verificando se está sendo passado por parâmetro algum valor de busca
// Verifica se foi passado algum valor de busca
if (isset($_GET['busca'])) {
  // Se a busca estiver vazia, remove o parâmetro da URL e recarrega sem ele
  if (empty($_GET['busca'])) {
      header("Location: dashboard.php");
      exit();
  }

  $dataCompleta = $_GET['busca'];
  $partesData = explode("/", $dataCompleta);

  // Verifica se a data está no formato correto
  if (count($partesData) === 3) {
      $dia = $partesData[0];
      $mes = $partesData[1];
      $ano = $partesData[2];
      $busca = $ano . '-' . $mes . '-' . $dia;
  } else {
      $busca = '';
  }
} else {
  $busca = '';
}



  // SELECT DAS TAREFAS PENDENTES
  // guardando o comando em uma variável
  $queryPendentes = "SELECT id_task, title_task, description_task, date_task, time_task FROM task WHERE id_user = '{$id_user}' AND status_task = 'pendente' AND date_task LIKE '%{$busca}%' ORDER BY date_task, time_task ASC";

  // enviando o query para o banco
  $resultPendentes = mysqli_query($conexao, $queryPendentes);

  // transformando os dados em um array
  $arrayTarefasPendentes = mysqli_fetch_assoc($resultPendentes);

  // pegando o número de linhas que o result retornou, para conferir os dados
  $iPendentes = mysqli_num_rows($resultPendentes);


  // SELECT DAS TAREFAS CONCLUÍDAS
  // guardando o comando em uma variável
  $queryConcluidas = "SELECT id_task, title_task, description_task, date_task, time_task FROM task WHERE id_user = '{$id_user}' AND status_task = 'concluida' AND date_task LIKE '%{$busca}%' ORDER BY date_task, time_task ASC";

  // enviando o query para o banco
  $resultConcluidas = mysqli_query($conexao, $queryConcluidas);
 
  // transformando os dados em um array
  $arrayTarefasConcluidas = mysqli_fetch_assoc($resultConcluidas);
 
  // pegando o número de linhas que o result retornou, para conferir os dados
  $iConcluidas = mysqli_num_rows($resultConcluidas);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Task Manager - Dashboard</title>
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
  <!-- Font Awesome -->
  
  <link rel="stylesheet" href="./css/dashboard.css">
  <link rel="shortcut icon" href="./img/favicon.ico">

  <!-- jQuery para criação de máscara para data na busca -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script>
  <!-- jQuery para criação de máscara para data na busca -->

  <!-- Adicionando a classe js no <html> para aplicação de efeitos -->
  <script>
    document.documentElement.className += ' js';
  </script>
  <!-- Adicionando a classe js no <html> para aplicação de efeitos -->

</head>
<body>
  <section id="dashboard">    
    <header>
      <div class="container">
        <a href="dashboard.php" class="logotipo">
          <div class="icone">
            <i class="fa fa-check fa-lg fa-fw" aria-hidden="true"></i>
          </div>
          <h1>Tasc.io</h1>
        </a>
        <div class="perfil">
          <div class="avatar">
          </div>
          <div class="nome">
            <h1><?php echo $_SESSION['nome'];?></h1>
            <button id="theme-toggle" class="theme-button" type="button">
              <i class="fa fa-moon-o"></i>
            </button>
            <a href="./php/db/logout.php">
              <i class="fa fa-sign-out fa-lg fa-fw icone" aria-hidden="true"></i>
            </a>
          </div>          
        </div>
      </div>
    </header>
    <main>
      <div class="container">
        <div class="topo">
          <h1>Tarefas</h1>
          <div class="filtro">
            <a href="#">Pendentes</a>
            <a href="#">Concluídas</a>
          </div>
        </div>
        <div class="hero">
          <form class="pesquisa">
             
            <input name="busca" id="busca" type="text" placeholder="Pesquise por data (DD/MM/AAAA)...">
            <button class="submit">
              <i class="fa fa-search fa-lg fa-fw" aria-hidden="true"></i>
            </button>
              
          </form>
          <a href="./adicionar_tarefas.php" class="adicionar">
              <div class="icone">
                <i class="fa fa-plus fa-lg fa-fw" aria-hidden="true"></i>
              </div>
              <h2>Nova tarefa</h2>
          </a>
        </div>
        <div class="cards">

          <div class="pendentes">
            <?php
              // se o número de linhas vindos do query for maior que zero, executa o loop
              echo '<h1 class="mobile-title" style="margin: -5px 0 20px;">Pendentes</h1>';
              if($iPendentes > 0) {
                // inicia o loop que vai mostrar todos os dados
                do {
            ?>
              <dl class="card js-card">
                <div></div>
                <dt class="header">
                  <a href="./php/db/edit_tarefas_pendentes.php?id_task=<?= $arrayTarefasPendentes['id_task']?>" class="check">
                    <i class="fa fa-check fa-lg fa-fw" aria-hidden="true"></i>
                  </a>
                  <h1><?php echo $arrayTarefasPendentes['title_task'];?></h1>
                  <a class="seta" href="#">
                    <i class="fa fa-chevron-down fa-lg fa-fw setas ativo" aria-hidden="true"></i>
                    <i class="fa fa-chevron-up fa-lg fa-fw setas" aria-hidden="true"></i>
                  </a>
                </dt>
                <dd>
                  <p><?php echo $arrayTarefasPendentes['description_task'];?></p>
                </dd>
                <footer>
                  <div class="data-hora">
                    <div class="data">
                      <i class="fa fa-calendar fa-lg fa-fw" aria-hidden="true"></i>
                      <span><?php echo $arrayTarefasPendentes['date_task'];?></span>
                    </div>
                    <div class="hora">
                    <i class="fa fa-clock-o fa-lg fa-fw" aria-hidden="true"></i>
                      <span><?php echo $arrayTarefasPendentes['time_task']; ?></span>
                    </div>
                  </div>
                  <div class="opcoes">
                    <a href="./editar_tarefas.php?id_task=<?= $arrayTarefasPendentes['id_task']?>" class="editar">
                      <i class="fa fa-edit fa-lg fa-fw" aria-hidden="true"></i>
                    </a>
                    <a href="./php/db/deletar_tarefas.php?id_task=<?php echo $arrayTarefasPendentes['id_task'];?>" class="excluir">
                      <i class="fa fa-trash fa-lg fa-fw" aria-hidden="true"></i>
                    </a>
                  </div>
                </footer>
              </dl>
            <?php
              // finaliza o loop que vai mostrar os dados
                }while($arrayTarefasPendentes = mysqli_fetch_assoc($resultPendentes));
              // fim do if 
              } else {
                if (!empty($_GET['busca'])) {
                  echo '<h1 class="no-task">Não foi encontrada nenhuma tarefa com esta data!</h1>';
                } else {
                  // Verifica se o usuário tem alguma tarefa cadastrada
                  $queryVerificaTarefas = "SELECT id_task FROM task WHERE id_user = '{$id_user}'";
                  $resultVerificaTarefas = mysqli_query($conexao, $queryVerificaTarefas);
                  $iTotalTarefas = mysqli_num_rows($resultVerificaTarefas);
                  if ($iTotalTarefas == 0) {
                    // Se não houver nenhuma tarefa cadastrada no sistema
                    echo '<h1 class="no-task">Adicione uma tarefa!</h1>';
                  } 
                }
              }
            ?>
          </div>

          <div class="concluidas">
            <?php
              // se o número de linhas vindos do query for maior que zero, executa o loop
              echo '<h1 class="mobile-title" style="margin: 40px 0 20px">Concluídas</h1>';
              if($iConcluidas > 0) {
                // inicia o loop que vai mostrar todos os dados
                do {
            ?>
            <dl class="card js-card checked">
              <dt class="header">
                <a href="./php/db/edit_tarefas_concluidas.php?id_task=<?= $arrayTarefasConcluidas['id_task']?>" class="check checked">
                  <i class="fa fa-check fa-lg fa-fw" aria-hidden="true"></i>
                </a>
                <h1 class="riscado"><?php echo $arrayTarefasConcluidas['title_task'];?></h1>
                <a class="seta" href="#">
                  <i class="fa fa-chevron-down fa-lg fa-fw setas ativo" aria-hidden="true"></i>
                  <i class="fa fa-chevron-up fa-lg fa-fw setas" aria-hidden="true"></i>
                </a>
              </dt>
              <dd>
                <p><?php echo $arrayTarefasConcluidas['description_task'];?></p>
              </dd>
              <footer>
                <div class="data-hora">
                  <div class="data">
                    <i class="fa fa-calendar fa-lg fa-fw" aria-hidden="true"></i>
                    <span><?php echo $arrayTarefasConcluidas['date_task'];?></span>
                  </div>
                  <div class="hora">
                    <i class="fa fa-clock-o fa-lg fa-fw" aria-hidden="true"></i>
                    <span><?php echo $arrayTarefasConcluidas['time_task'];?></span>
                  </div>
                </div>
                <div class="opcoes">
                  <a href="./editar_tarefas.php?id_task=<?= $arrayTarefasConcluidas['id_task']?>" class="editar">
                    <i class="fa fa-edit fa-lg fa-fw" aria-hidden="true"></i>
                  </a>
                  <a href="./php/db/deletar_tarefas.php?id_task=<?php echo $arrayTarefasConcluidas['id_task'];?>" class="excluir">
                    <i class="fa fa-trash fa-lg fa-fw" aria-hidden="true"></i>
                  </a>
                </div>
              </footer>
            </dl>

            <?php
            // finaliza o loop que vai mostrar os dados
                }while($arrayTarefasConcluidas = mysqli_fetch_assoc($resultConcluidas));
              // fim do if 
              } else {
                // Verifica se há outras tarefas no banco
                $queryVerificaTarefas = "SELECT id_task FROM task WHERE id_user = '{$id_user}'";
                $resultVerificaTarefas = mysqli_query($conexao, $queryVerificaTarefas);
                $iTotalTarefas = mysqli_num_rows($resultVerificaTarefas);

                if ($iTotalTarefas > 0) {
                  echo '<h1 class="no-task">Não foi encontrada nenhuma tarefa com esta data!</h1>';
                } else {
                  echo '<h1 class="no-task">Complete uma tarefa!</h1>';
                }
              }
            ?>
          </div>
          
        </div>
        
        
      </div>
    </main>
  </section>

  <script src="./js/scripts.js"></script>

  <!-- Criação da máscara na busca -->
  <script type="text/javascript">
    $("#busca").mask("00/00/0000");
  </script>
  <!-- Criação da máscara na busca -->

</body>
</html>