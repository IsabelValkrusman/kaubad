<?php
  $yhendus=new mysqli("localhost", "valkrusman", "12345", "valkrusman");
  
  function kysiKaupadeAndmed($sorttulp="nimetus", $otsisona=""){
    global $yhendus;
    $lubatudtulbad=array("nimetus", "grupinimi", "hind");
    if(!in_array($sorttulp, $lubatudtulbad)){
      return "lubamatu tulp";
    }
    $otsisona=addslashes(stripslashes($otsisona));
    $kask=$yhendus->prepare("SELECT kaubad.id, nimetus, grupinimi, kaubagrupi_id, hind
       FROM kaubad, kaubagrupid
       WHERE kaubad.kaubagrupi_id=kaubagrupid.id
        AND (nimetus LIKE '%$otsisona%' OR grupinimi LIKE '%$otsisona%')
       ORDER BY $sorttulp");
    //echo $yhendus->error;
    $kask->bind_result($id, $nimetus, $grupinimi, $kaubagrupi_id, $hind);
    $kask->execute();
    $hoidla=array();
    while($kask->fetch()){
      $kaup=new stdClass();
      $kaup->id=$id;
      $kaup->nimetus=htmlspecialchars($nimetus);
      $kaup->grupinimi=htmlspecialchars($grupinimi);
      $kaup->kaubagrupi_id=$kaubagrupi_id;
      $kaup->hind=$hind;
      array_push($hoidla, $kaup);
    }
    return $hoidla;
  }
  
  /**
  * Luuakse HTML select-valik, kus v6etakse v22rtuseks sqllausest tulnud
  * esimene tulp ning n2idatakse teise tulba oma.
  */
//dropdown list tabelist kaubagrupid.grupinimi
  function looRippMenyy($sqllause, $valikunimi, $valitudid=""){
     global $yhendus;
     $kask=$yhendus->prepare($sqllause);
     $kask->bind_result($id, $sisu);
     $kask->execute();
     $tulemus="<select name='$valikunimi'>";
     while($kask->fetch()){
       $lisand="";
       if($id==$valitudid){$lisand=" selected='selected'";}
       $tulemus.="<option value='$id' $lisand >$sisu</option>";
     }
     $tulemus.="</select>";
     return $tulemus;
  }

/*
  function looRippMenyy($sqllause, $valikunimi){
     global $yhendus;
     $kask=$yhendus->prepare($sqllause);
     $kask->bind_result($id, $sisu);
     $kask->execute();
     $tulemus="<select name='$valikunimi'>";
     while($kask->fetch()){
       $tulemus.="<option value='$id'>$sisu</option>";
     }
     $tulemus.="</select>";
     return $tulemus;
  }
*/
  //lisab grupinimi tabelisse kaubagrupid
  function lisaGrupp($grupinimi){
     global $yhendus;
     $kask=$yhendus->prepare("INSERT INTO kaubagrupid (grupinimi)
                      VALUES (?)");
     $kask->bind_param("s", $grupinimi);
     $kask->execute();
  }

// lisab kaubanimi, hind, grupp tabelisse kaubad
  function lisaKaup($nimetus, $kaubagrupi_id, $hind){
     global $yhendus;
     $kask=$yhendus->prepare("INSERT INTO 
       kaubad (nimetus, kaubagrupi_id, hind)
       VALUES (?, ?, ?)");
     $kask->bind_param("sid", $nimetus, $kaubagrupi_id, $hind);
     $kask->execute();

  }

// kustutab kaup tabelist
  function kustutaKaup($kauba_id){
     global $yhendus;
     $kask=$yhendus->prepare("DELETE FROM kaubad WHERE id=?");
     $kask->bind_param("i", $kauba_id);
     $kask->execute();
  }

//uuendab kaup tabelis kaubad
  function muudaKaup($kauba_id, $nimetus, $kaubagrupi_id, $hind){
     global $yhendus;
     $kask=$yhendus->prepare("UPDATE kaubad SET nimetus=?, kaubagrupi_id=?, hind=?
                      WHERE id=?");
     $kask->bind_param("sidi", $nimetus, $kaubagrupi_id, $hind, $kauba_id);
     $kask->execute();
  }
?>
  //---------------  
  //if(array_pop(explode("/", $_SERVER["PHP_SELF"]))=="abifunktsioonid.php"):

<pre>
<?php
  //print_r(kysiKaupadeAndmed("hind", "fass\\aad"));
?>
</pre>

<?php
//endif ?>
