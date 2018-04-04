<?php
/**
 * Created by PhpStorm.
 * User: sozaki19
 * Date: 4/2/18
 * Time: 11:18 AM
 */

$dir = new PDO('sqlite:/home/local/CORNELL-COLLEGE/sozaki19/PhpstormProjects/Capstone2018/app/app.db');

/**
// example of how to grab a data query and display parts of the rows.
$result = $dir->query("SELECT * FROM ".$query);
foreach ($result as $row)
{
    echo $row[0] . "\n";
    //echo $row[1] . "\n";
    //echo $row[2] . "\n";
    //echo $row[3] . "\n";
}
*/
$count = 0;
for($suit = 0; $suit < 4; $suit++){
    for($faceVal = 1; $faceVal <= 13; $faceVal++){
        $count++;
        echo "suit = ".$suit.", face = ".$faceVal."\n";
        $dir->query("INSERT INTO cards (suit,face_value,flipped) VALUES (".$suit.",".$faceVal.",0)");
    }
}
echo "We finished with ".$count. " cards.\n";