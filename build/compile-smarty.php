<?php

require_once __DIR__.'/../vendor/autoload.php';

require_once __DIR__.'/../library/lib/smarty.php';
// You can’t precompile Smarty on one system and then upload to another server,
// as the compiled filenames have the absolute path of the source files encoded.

// /workspace is the root path on google app engine
// not /srv as the GCP docs suggest
$deploymentRootFolder = '/workspace';

// So, this is a cheeky work-around that rewrites the hashes
// in the templates and on the file system so they will match
// the paths of the target deployment system

// v4: The hash was reverse engineered from smarty_internal_resource_file.php
// v5: The hash was reverse engineered from 
// v5: vendor/smarty/smarty/src/Resource/FilePlugin.php
// v5: $source->uid = sha1($source->name . $smarty->_joined_template_dir);
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
    echo "Original root dir: {$rootDir}\n";
    // swap out the root of originalTemplateDir for the new deployment path
    // v5: _joined_template_dir is all template dirs joined with #
    $originalJoinedDir = join('#', $smarty->getTemplateDir());
    $deployedJoinedDir = $deploymentRootFolder.str_replace($rootDir, '', $originalJoinedDir);

    echo "Re-mapping templates from '{$originalJoinedDir}' to '{$deployedJoinedDir}'\n";

    foreach (glob($smarty->getCompileDir().'*.tpl.php') as $fileName) {
        rewriteTemplateHash($fileName, $originalJoinedDir, $deployedJoinedDir);
    }
}

function rewriteTemplateHash($templateFileName, $originalJoinedDir, $deployedJoinedDir)
{
    // example file name format:
    // Smarty 4: 5ad5ecef0cb555f346e97a65e3cc109456de7a1a_1.file.mobile_scan.tpl.php
    // Smarty 5: {sha1}_{0|2}.file_{basename}.tpl.php
    echo "Processing compiled template {$templateFileName}\n";
    $baseName = basename($templateFileName);

    // Extract parts: hash_suffix.file_templatename.tpl.php
    if (!preg_match('/^([a-f0-9]+)_(\d+)\.file_(.+\.tpl)\.php$/', $baseName, $matches)) {
        echo "  Skipping - doesn't match expected pattern\n";
        return;
    }

    $originalHash = $matches[1];
    $suffix = $matches[2];
    $templateName = $matches[3];

    // work out what we expect the current hash to be, to ensure it matches
    // if not, the algorithm has somehow changed, so we'll abort
    // Smarty 4 hash: sha1($templatePath . $templateDir)
    // Smarty 5 hash: sha1(templateName . joinedTemplateDirs)
    $computedHash = sha1($templateName . $originalJoinedDir);
    if ($computedHash !== $originalHash) {
        throw new Exception("Failed to anticipate current hash for {$templateFileName}\nTemplate: {$templateName}\nJoined dirs: {$originalJoinedDir}\nCalculated hash: {$computedHash}\nActual hash: {$originalHash}");
    }

    // Calculate new hash for deployment
    $newHash = sha1($templateName . $deployedJoinedDir);
    // we want to
    // (a) replace the old file name and hash within the contents of the file
    //     with the new hash
    $currentTemplate = file_get_contents($templateFileName);
    $newTemplate = str_replace($computedHash, $newHash, $currentTemplate);
    $rootDir = realpath(__DIR__.'/../');
    $deploymentRootFolder = dirname($deployedJoinedDir);
    $newTemplate = str_replace($rootDir, $deploymentRootFolder, $newTemplate);
    // (b) rename the template so it uses the new hash
    $newTemplateCompiledPath = str_replace($computedHash, $newHash, $templateFileName);
    echo "Saving new template to {$newTemplateCompiledPath} and deleting the original\n";
    file_put_contents($newTemplateCompiledPath, $newTemplate);
    unlink($templateFileName);
}
