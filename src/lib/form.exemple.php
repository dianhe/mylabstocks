<?php
require_once 'form.cls.php';
// on instancie notre objet
$form = new form ();
// on cr�e un 1er formulaire

$form -> openForm (array ('action' => '?', 'id' => 'MyForm'));
$form -> openFieldset (array ('style' => 'border:1px dotted red; width: 300px;'));
$form -> addLegend ('test');
$form -> addInput ('text', array ('id' => 'MyText', 'value' => 'ok', 'test' => 'test'));
$form -> addLabel ('label', array ('for' => 'MyText', 'style' => 'margin: 5px;'));
$form -> addAnything ('<br /><br />');
$form -> addInput ('button', array ('id' => 'MyButton', 'value' => 'click!', 'test' => 'test'));
$form -> closeFieldset ();
$form -> closeForm ();

echo '<div style="border: 1px solid darkgrey; text-align: center; width: 310px;">';
// on l'affiche
echo $form;
echo '</div>';

// on compte et affiche ses �l�ments (debugging only)
echo $form -> showElems ();
echo $form -> countElems ();

// on lib�re les ressources pour pouvoir cr�er un 2d formulaire
$form -> freeForm ();

// on r�initialise un nouveau formulaire
// on ouvre effectivement le nouveau formulaire
$form -> openForm (array ('action' => '?', 'id' => 'MyForm2'));
$form -> openFieldset (array ('style' => 'border:1px dotted blue; width: 300px;'));
$form -> addLegend ('test 2');
$form -> addInput ('text', array ('id' => 'MyText2', 'value' => 'yep', 'test' => 'test'));
$form -> addInput ('checkbox', array ('id' => 'MyCheck', 'value' => '1', 'test' => 'test'));
$form -> addLabel ('Checkbox', array ('for' => 'MyCheck'));
$form -> addTextarea ('mon texte', array ('cols' => 20, 'rows' => 10));
$form -> openSelect ();
$form -> openOptgroup ('label options 1');
$form -> addOption ('1');
$form -> closeOptgroup ();
$form -> openOptgroup ('label options 2');
$form -> addOption ('2');
$form -> addOption ('2_2');
$form -> closeOptgroup ();
$form -> closeSelect ();
$form -> closeFieldset ();
$form -> closeForm ();

echo '<div style="border: 1px solid orange; text-align: center; width: 310px;">';
echo $form;
echo '</div>';

echo $form -> showElems ();
echo $form -> countElems ();

$form -> freeForm ();
?>