<?php
require "../UnifiedDiffPatcher.php";
require "memorySteam.php";

//Let's suppose theses variables are received by POST.
$p = 1;
$patchContent = file_get_contents('patch.txt');


//Place the content in a stream
if (!stream_wrapper_register("memory", "MemoryStream")) {
    die("Failed to register protocol");
}

//Transfert the patch content into a stream.
$fp = fopen("memory://foo", "r+");
fwrite($fp, $patchContent);
rewind($fp);

//chdir('../'); //Move the Current Working Directory.

// Run the Patching process
try {

    $diff = new UnifiedDiffPatcher();
    $ret = $diff->validatePatch($fp, $p, true);
    if ($ret !== false) {
        rewind($fp); //Place the file pointer to the beginning of the patch.
        $hunkCount = $diff->processPatch($fp, $p);
    } else {
        echo implode("\n", $diff->getError());
        die();
    }
}
catch (Exception $e) { //This will catch critical error witch can't be recovered.
                      //Like file access errors.
    echo $e->getMessage();
    die();
}

if ($hunkCount == 0) {
    die();
} else {
    echo $hunkCount . ' hunk(s) applied.';
    die();
}
