<?php
require "../UnifiedDiffPatcher.php";

// Run the Patching process
try{
    $diff = new UnifiedDiffPatcher();
    $patchPath = getcwd() . '/patch.txt'; //Absolute path of the patch file.
    $p = 1; // -p parameter of the unix patch command
    //chdir('../');//The folder from where the patch path are relative to.

    $ret = $diff->validatePatch($patchPath, $p, true); //Validate the patch, and display debug informations
    if ($ret !== false) {
        $diff->processPatch($patchPath, $p); //Apply the patch without displaying debug informations.
    } else {
        echo 'Error repport:' . "\n";
        echo implode("\n", $diff->getError());
    }
}
catch(Exception $e) { //This will catch critical error witch can't be recovered.
                      //Like file access errors.
    echo "\n" . $e->getMessage();
}
exit("\n");