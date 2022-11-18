<?php
//andmete sorteerimine
  require("abifunktsioonid.php");
  $sorttulp="nimetus";
  $otsisona="";
  if(isset($_REQUEST["sorttulp"])){
      $sorttulp=$_REQUEST["sorttulp"];
  }
  if(isSet($_REQUEST["grupilisamine"])){
    lisaGrupp($_REQUEST["uuegrupinimi"]);
    header("Location: kaubahaldus.php");
    exit();
  }
  if(isSet($_REQUEST["kaubalisamine"])){
    lisaKaup($_REQUEST["nimetus"], $_REQUEST["kaubagrupi_id"], $_REQUEST["hind"]);
    header("Location: kaubahaldus.php");
    exit();
  }
  if(isSet($_REQUEST["kustutusid"])){
     kustutaKaup($_REQUEST["kustutusid"]);
  }
  if(isSet($_REQUEST["muutmine"])){
     muudaKaup($_REQUEST["muudetudid"], $_REQUEST["nimetus"],
                              $_REQUEST["kaubagrupi_id"], $_REQUEST["hind"]);
  }
  $kaubad=kysiKaupadeAndmed();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
      <title>Kaupade leht</title>
      <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
      <link rel="stylesheet" type="text/css" href="style.css">

  </head>
  <body>    
   <form action="kaubahaldus.php">
       <div id="sisu">

     <h2>Kauba lisamine</h2>
     <dl>
       <dt>Nimetus:</dt>
       <dd><input type="text" name="nimetus" /></dd>
       <dt>Kaubagrupp:</dt>
       <dd><?php
         echo looRippMenyy("SELECT id, grupinimi FROM kaubagrupid", 
                           "kaubagrupi_id");
       ?>
       </dd>
       <dt>Hind:</dt>
       <dd><input type="text" name="hind" /></dd>
     </dl>
     <input type="submit" name="kaubalisamine" value="Lisa kaup" />
       </div>
       <div id="sisu1">
     <h2>Grupi lisamine</h2>
     <input type="text" name="uuegrupinimi" />
     <input type="submit" name="grupilisamine" value="Lisa grupp" />
   </form>
   <form action="kaubahaldus.php">
   </div>
       <div id="sisu2">
     <h2>Kaupade loetelu</h2>

       <tr>
         <th>Haldus</th>
           <th><a href="kaubahaldus.php?sorttulp=nimetus">Nimetus</a></th>
           <th><a href="kaubahaldus.php?sorttulp=grupinimi">Kaubagrupp</a></th>
           <th><a href="kaubahaldus.php?sorttulp=hind">Hind</a></th>
       </tr>

       <?php foreach($kaubad as $kaup): ?>
         <tr>
          <?php if(isSet($_REQUEST["muutmisid"]) &&
                            intval($_REQUEST["muutmisid"])==$kaup->id): ?>
           <td>
               <br>
             <input type="submit" name="muutmine" value="Muuda" />
             <input type="submit" name="katkestus" value="Katkesta" />
             <input type="hidden" name="muudetudid" value="<?=$kaup->id ?>" />
               <br>
           </td>
           <td><input type="text" name="nimetus" value="<?=$kaup->nimetus ?>" /></td>
           <td><?php
             echo looRippMenyy("SELECT id, grupinimi FROM kaubagrupid", 
                                        "kaubagrupi_id", $kaup->kaubagrupi_id);
           ?></td>
           <td><input type="text" name="hind" value="<?=$kaup->hind ?>" /></td>
          <?php else: ?>
          <br>
           <td><a href="kaubahaldus.php?kustutusid=<?=$kaup->id ?>"
                  onclick="return confirm('Kas ikka soovid kustutada?')">x</a>
               <a href="kaubahaldus.php?muutmisid=<?=$kaup->id ?>">m</a>
           </td>

           <td><?=$kaup->nimetus ?></td>
           <td><?=$kaup->grupinimi ?></td>
           <td><?=$kaup->hind ?></td>
          <br>
          <?php endif ?>
         </tr>
       <?php endforeach; ?>
     </table>
       </div>
   </form>
  </body>
</html>
