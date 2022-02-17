<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Exam Sections PDF</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <style media="all">
       body{margin: 0px;font-size: 14px;line-height: 21px;font-family:Arial,sans-serif;}
      .datesPdf{margin:0 auto;padding: 20px 45px 0px;box-sizing: border-box;background: #fff;}
      .datesPdf h1{font-size: 20px;line-height: 28px;}
      table.upcomming-events{width:100%;border-collapse:collapse;background:#fff;display:table;}
      table.upcomming-events tr td,table.upcomming-events tr th{padding:5px 10px;border:1px solid #b8c8d1;width:auto}
      table.upcomming-events th,table.upcomming-events th *{color:#fff;font-weight:600;}
      table.upcomming-events th{background:#003d5c;text-align:center}
      table.upcomming-events tr td:first-child{width:115px}
      table.upcomming-events tr td,table.upcomming-events tr td *{color:#000;text-align:left;background-color:#fff;}
      table.upcomming-events tr td p{margin:0}
      tr.faded{opacity: .6;}
      .wikkiContents h2 {font-size: 18px;color: #000;font-weight: 600;}
      .wikkiContents table {display: table;width: 100%;border-collapse: collapse;overflow: automargin-bottom: 15px}
      .wikkiContents table tr th {text-align: center;  background-color: #003d5c;color: #fff;font-weight: 600;padding: 4px 8px 6px;border: 1px solid #003d5c;}
      .wikkiContents table tr th h1, .wikkiContents table tr th h2, .wikkiContents table tr th h3, .wikkiContents table tr th h4, .wikkiContents table tr th h5, .wikkiContents table tr th h6, .wikkiContents table tr th p {
        margin: 6px 0px 4px;
        font-weight: 600;
      }
      .wikkiContents table tr td {color: #000;text-align: left;background-color: #fff;border: 1px solid #003d5c;padding: 2px 8px 6px;}
      .wikkiContents p {margin: 4px 0 10px;}
      .wikkiContents table tr td p, .wikkiContents table tr th p {  margin: 0;}
      .wikkiContents ul{padding:0;margin-left:18px;list-style-type:disc;margin-bottom:5px}
      .wikkiContents div,ol,ul{}
      .wikkiContents ol li,.wikkiContents ul li{margin-bottom:10px}
      .wikkiContents table a{text-decoration:none}
      .fnt-w6,.wikkiContents a{font-weight:400;color:#008489}
      .wikkiContents h1{font-size:20px;line-height:28px;margin:6px 0 10px}
      
      .pdf-disc{    color: #666;
    margin: 0 -45px;
    background: #f3f8f8;
    padding: 6px 10px;
    text-align: center;}
    .pdf-disc p{margin: 0px;}
      .pdfHeader{margin-bottom:30px}
      .pdfFooter{margin:30px 0 15px;display:flex;flex-direction:row;flex-wrap:nowrap;align-items:baseline}
      .pdfHeader img,.logo-footer img{width:120px}
      .pdf-disc{color:#666;}
      .pdfFooter div:nth-child(2){margin-left:auto;}
      .pdfFooter div:nth-child(2) a{color:#008489}
      .ambedWidget{margin-left: 1px solid!important;margin-right: 1px solid!important;}
          </style>
  </head>
  <body>
    <div class="datesPdf">
    <h1><?php echo $examName." ".$year." ".$sectionName."   "?><a class="dl-nw" style="color:#008489;display:inline-block;font-weight:600;font-size:14px;text-decoration:none" target="_blank" href="<?=$examUrl?>">Visit on Shiksha</a></h1>
    <?php 
      if ($sectionName == "Dates" && (!empty($datesData) || is_array($datesData))) {
        if (empty($upperWiki)) {
          echo '<p>Get all '.$examName.' '.$year.' important dates such as registration date, application date, admit card date, exam date, result date and counselling date. Sign up so that you don’t miss a single exam date or an update on '.$examName.' '.$year.' exam.</p>';
        }
        else{
          echo '<div class="wikkiContents">'.$upperWiki.'</div>';
        }
        echo '<table class="upcomming-events"><tbody>';
        if (!empty($datesData['future'])) {
          echo '<tr><th colspan="2">UPCOMING EVENTS</th></tr>';
          foreach ($datesData['future'] as $key => $futureDate) {
            if ($futureDate->getStartDate() == $futureDate->getEndDate()) {
              echo "<tr><td><p>".date_format(date_create($futureDate->getStartDate()),"d M' y")."</p></td><td><p>".$futureDate->getEventName()."</p></td></tr>";
            } else {

              echo "<tr><td><p>".date_format(date_create($futureDate->getStartDate()),"d M' y")."-</p><p>".date_format(date_create($futureDate->getEndDate()),"d M' y")."</p></td><td><p>".$futureDate->getEventName()."</p></td></tr>";

            }
          }
        }
        if (!empty($datesData['past'])) {
          echo '<tr class="faded"><th colspan="2">PAST EVENTS</th></tr>';
          foreach ($datesData['past'] as $key => $pastDate) {
            if ($pastDate->getStartDate() == $pastDate->getEndDate()) {
              echo "<tr><td><p>".date_format(date_create($pastDate->getStartDate()),"d M' y")."</p></td><td><p>".$pastDate->getEventName()."</p></td></tr>";
            } else {
              echo "<tr><td><p>".date_format(date_create($pastDate->getStartDate()),"d M' y")."-</p><p>".date_format(date_create($pastDate->getEndDate()),"d M' y")."</p></td><td><p>".$pastDate->getEventName()."</p></td></tr>";
            }
          }

      }
      echo "</tbody></table>";
    }
    ?>
    <div class="wikkiContents">
    <?php echo $entityValue;?>
    </div>
    </div>
  </body>
</html>
