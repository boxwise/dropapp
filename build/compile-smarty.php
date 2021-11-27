<?php

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../library/lib/smarty.php';
// You can’t precompile Smarty on one system and then upload to another server,
// as the compiled filenames have the absolute path of the source files encoded.

// /srv is the root path on google app engine
$deploymentRootFolder = '/workspace';

// So, this is a cheeky work-around that rewrites the hashes
// in the templates and on the file system so they will match
// the paths of the target deployment system

// The hash was reverse engineered from smarty_internal_resource_file.php
$smarty = new Zmarty();
precompileTemplates($smarty);
rewriteAllTemplateHashes($smarty, $deploymentRootFolder);

function precompileTemplates($smarty)
{
    $smarty->clearCompiledTemplate();
    $smarty->compileAllTemplates('.tpl', true, 0, null);
}

function rewriteAllTemplateHashes($smarty, $deploymentRootFolder)
{
    $rootDir = realpath(__DIR__.'/../');
    $originalTemplateDir = $smarty->getTemplateDir()[0];
    // swap out the root of originalTemplateDir for the new deployment path
    $deployedTemplateDir = $deploymentRootFolder.str_replace($rootDir, '', $originalTemplateDir);
    echo "Re-mapping templates from '{$originalTemplateDir}' to '{$deployedTemplateDir}'\n";

    $it = new FilesystemIterator($smarty->getCompileDir(), );
    foreach (glob($smarty->getCompileDir().'*.tpl.php') as $fileName) {
        rewriteTemplateHash($smarty, $fileName, $originalTemplateDir, $deployedTemplateDir);
    }
}

function rewriteTemplateHash($smarty, $templateFileName, $originalTemplateDir, $deployedTemplateDir)
{
    // example file name format:
    // 5ad5ecef0cb555f346e97a65e3cc109456de7a1a_1.file.mobile_scan.tpl.php
    echo "Processing compiled template {$templateFileName}\n";
    $fileNameParts = explode('.', basename($templateFileName));
    $originalTemplateHash = $fileNameParts[0];
    $originalTemplateName = $fileNameParts[2].'.'.$fileNameParts[3];
    $originalTemplatePath = $originalTemplateDir.$originalTemplateName;
    // work out what we expect the current hash to be, to ensure it matches
    // if not, the algorithm has somehow changed, so we'll abort
    $computedHashInput = $originalTemplatePath.$originalTemplateDir;
    $computedHash = sha1($computedHashInput);
    // the _0/_1/_3 suffix on the first component of the file name appears to
    // vary based on Smarty configuration. We just extract it from the existing
    // file name to save us working that bit out
    $computedHashWithSuffix = $computedHash.'_'.explode('_', $originalTemplateHash)[1];
    if ($computedHashWithSuffix != $originalTemplateHash) {
        throw new Exception("Failed to anticipate current hash for {$templateFileName}\nHashed input: {$computedHashInput}\nCalculated hash: {$computedHashWithSuffix}\nActual hash: {$originalTemplateHash}");
    }
    $newTemplatePath = $deployedTemplateDir.$originalTemplateName;
    // we want to
    // (a) replace the old file name and hash within the contents of the file
    //     with the new hash
    // (b) rename the template so it uses the new hash
    $newHash = sha1($newTemplatePath.$deployedTemplateDir);
    $currentTemplate = file_get_contents($templateFileName);
    $newTemplate = str_replace($computedHash, $newHash, $currentTemplate);
    $newTemplate = str_replace($originalTemplatePath, $newTemplatePath, $newTemplate);
    $newTemplateCompiledPath = str_replace($computedHash, $newHash, $templateFileName);
    echo "Saving new template to {$newTemplateCompiledPath} and deleting the original\n";
    file_put_contents($newTemplateCompiledPath, $newTemplate);
    unlink($templateFileName);
}
