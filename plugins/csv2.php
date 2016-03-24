<?php
function csv_parser($up)
{
  $file = $_POST['file'];
  $csv_lines  = file($up);
  if(is_array($csv_lines))
  {
    //разбор csv
    $cnt = count($csv_lines); 				// в cnd записывается количество строк файла
    for($i = 0; $i < $cnt; $i++) 			// именно столько раз выполняем цикл
    {
      $line = $csv_lines[$i]; 				// строку заливаем в переменную lines и разбираем её
      $line = trim($line);
      $first_char = true; 				//указатель на то, что через цикл проходит первый символ столбца
      $col_num = 0; 					//номер столбца
      $length = strlen($line);				// считаем длину строки
      for($b = 0; $b < $length; $b++)
      {
        if($skip_char != true)				//переменная $skip_char определяет обрабатывать ли данный символ
        {
          
          ///print $line[$b];
          $process = true; 				//определяет обрабатывать/не обрабатывать строку
          
          if($first_char == true)			//определяем маркер окончания столбца по первому символу
          {
            if($line[$b] == '"')
            {
              $terminator = '";';			// сигнал об окончании строки
              $process = false;
            }
            else
              $terminator = ';';
            $first_char = false;
          }
          
          
          if($line[$b] == '\'') 		//просматриваем одинарные кавычки, определяем их природу
          {
              //echo "line ".$i.", char = ".$b.", value = ".$line[$b]."<br />";
          }

          if($line[$b] == '"')  		//просматриваем парные кавычки, определяем их природу
          {
            $next_char = $line[$b + 1];
            if($next_char == '"') 		//удвоенные кавычки
              $skip_char = true; 
            elseif($next_char == ';')		//маркер конца столбца
            {
              if($terminator == '";')
              {
                $first_char = true;
                $process = false;
                $skip_char = true;
              }
            }
          }
          
      
          //определяем природу точки с запятой
          if($process == true)
          {
            if($line[$b] == ';')
            {
               if($terminator == ';')
               {
                  $first_char = true;
                  $process = false;
               }
            }
          }

          if($process == true)
            $column .= $line[$b];

          if($b == ($length - 1))
          {
            $first_char = true;
          }

          if($first_char == true)
          {

            $values[$i][$col_num] = $column;
            $column = '';
            $col_num++;
          }
        }
        else
          $skip_char = false;
      }
    }
  }
   return $values;
}
?>