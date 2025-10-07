<?php
/**
 * Simple SCORM Export Test
 * This script simulates the SCORM export process to identify issues
 */

// Include the necessary Xerte files
require_once('../config.php');
require_once('../website_code/php/user_library.php');
require_once('../website_code/php/template_status.php');
require_once('../website_code/php/scorm/archive.php');

echo "<h1>SCORM Export Test</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .success { color: green; font-weight: bold; }
    .error { color: red; font-weight: bold; }
    .info { color: blue; }
    .test { margin: 10px 0; padding: 10px; border: 1px solid #ccc; }
</style>";

// Test 1: Check if we can access the sample project
echo "<div class='test'>";
echo "<h2>Test 1: Sample Project Access</h2>";

$sample_project_id = 2; // Based on the directory structure we saw
$sample_path = "../USER-FILES/2-guest2-Nottingham";

if (file_exists($sample_path)) {
    echo "<div class='success'>✓ Sample project directory found</div>";
    
    // Check for required files
    $required_files = ['data.xml', 'preview.xml'];
    foreach ($required_files as $file) {
        $file_path = $sample_path . '/' . $file;
        if (file_exists($file_path)) {
            echo "<div class='success'>✓ $file exists</div>";
        } else {
            echo "<div class='error'>✗ $file missing</div>";
        }
    }
} else {
    echo "<div class='error'>✗ Sample project directory not found</div>";
}
echo "</div>";

// Test 2: Test ZIP creation with archive class
echo "<div class='test'>";
echo "<h2>Test 2: Archive Class Test</h2>";

try {
    // Create a temporary directory for testing
    $test_dir = 'test_export_' . time();
    if (!file_exists($test_dir)) {
        mkdir($test_dir, 0777, true);
    }
    
    // Create some test files
    $test_files = [
        'test1.txt' => 'This is a test file for SCORM export',
        'test2.txt' => 'Another test file',
        'subdir/test3.txt' => 'Test file in subdirectory'
    ];
    
    foreach ($test_files as $filename => $content) {
        $dir = dirname($filename);
        if ($dir != '.' && !file_exists($test_dir . '/' . $dir)) {
            mkdir($test_dir . '/' . $dir, 0777, true);
        }
        file_put_contents($test_dir . '/' . $filename, $content);
    }
    
    // Test the archive class
    $zipfile = new zip_file($test_dir . '/test_export.zip');
    $zipfile->set_options(array(
        'basedir' => $test_dir,
        'name' => 'test_export.zip',
        'type' => 'zip'
    ));
    
    $zipfile->add_files(array('test1.txt', 'test2.txt', 'subdir/test3.txt'));
    $zipfile->create_archive();
    
    if (file_exists($test_dir . '/test_export.zip')) {
        echo "<div class='success'>✓ Archive class ZIP creation successful</div>";
        echo "<div class='info'>ZIP file size: " . filesize($test_dir . '/test_export.zip') . " bytes</div>";
        
        // Clean up
        unlink($test_dir . '/test_export.zip');
    } else {
        echo "<div class='error'>✗ Archive class ZIP creation failed</div>";
    }
    
    // Clean up test files
    foreach ($test_files as $filename => $content) {
        $file_path = $test_dir . '/' . $filename;
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }
    
    // Remove subdirectory
    if (file_exists($test_dir . '/subdir')) {
        rmdir($test_dir . '/subdir');
    }
    
    rmdir($test_dir);
    
} catch (Exception $e) {
    echo "<div class='error'>✗ Archive class test failed: " . $e->getMessage() . "</div>";
}
echo "</div>";

// Test 3: Test SCORM manifest creation
echo "<div class='test'>";
echo "<h2>Test 3: SCORM Manifest Creation</h2>";

try {
    // Include the SCORM library
    require_once('../website_code/php/scorm/scorm_library.php');
    
    // Create a test manifest
    $test_dir = 'test_manifest_' . time();
    if (!file_exists($test_dir)) {
        mkdir($test_dir, 0777, true);
    }
    
    // Set global variables that the function expects
    global $dir_path, $delete_file_array, $zipfile;
    $dir_path = $test_dir . '/';
    $delete_file_array = array();
    
    // Create a mock zipfile object
    $zipfile = new stdClass();
    $zipfile->add_files = function($file) {
        echo "<div class='info'>Would add file to ZIP: $file</div>";
    };
    
    // Test manifest creation
    lmsmanifest_create('Test Project', false, 'Test Learning Object');
    
    if (file_exists($test_dir . '/imsmanifest.xml')) {
        echo "<div class='success'>✓ SCORM manifest created successfully</div>";
        echo "<div class='info'>Manifest file size: " . filesize($test_dir . '/imsmanifest.xml') . " bytes</div>";
        
        // Show first few lines of manifest
        $manifest_content = file_get_contents($test_dir . '/imsmanifest.xml');
        $lines = explode("\n", $manifest_content);
        echo "<div class='info'>Manifest preview:</div>";
        echo "<pre style='background: #f5f5f5; padding: 10px; font-size: 12px;'>";
        for ($i = 0; $i < min(5, count($lines)); $i++) {
            echo htmlspecialchars($lines[$i]) . "\n";
        }
        echo "</pre>";
        
        // Clean up
        unlink($test_dir . '/imsmanifest.xml');
    } else {
        echo "<div class='error'>✗ SCORM manifest creation failed</div>";
    }
    
    rmdir($test_dir);
    
} catch (Exception $e) {
    echo "<div class='error'>✗ SCORM manifest test failed: " . $e->getMessage() . "</div>";
}
echo "</div>";

// Test 4: Test file permissions
echo "<div class='test'>";
echo "<h2>Test 4: File Permissions Test</h2>";

$test_dirs = [
    '../USER-FILES' => 'User files directory',
    '../USER-FILES/2-guest2-Nottingham' => 'Sample project',
    '../error_logs' => 'Error logs directory'
];

foreach ($test_dirs as $dir => $description) {
    if (file_exists($dir)) {
        if (is_writable($dir)) {
            echo "<div class='success'>✓ $description is writable</div>";
        } else {
            echo "<div class='error'>✗ $description is NOT writable</div>";
            echo "<div class='info'>Path: $dir</div>";
            echo "<div class='info'>Permissions: " . substr(sprintf('%o', fileperms($dir)), -4) . "</div>";
        }
    } else {
        echo "<div class='error'>✗ $description does not exist</div>";
    }
}
echo "</div>";

// Test 5: Simulate export process
echo "<div class='test'>";
echo "<h2>Test 5: Export Process Simulation</h2>";

echo "<div class='info'>Simulating SCORM export process...</div>";

// Check if we can access the export script
$export_script = '../website_code/php/scorm/export.php';
if (file_exists($export_script)) {
    echo "<div class='success'>✓ Export script found</div>";
    
    // Check if we can include it (without actually running it)
    $export_content = file_get_contents($export_script);
    if (strpos($export_content, 'ZipArchive') !== false || strpos($export_content, 'zip_file') !== false) {
        echo "<div class='success'>✓ Export script uses ZIP functionality</div>";
    } else {
        echo "<div class='error'>✗ Export script may not have ZIP functionality</div>";
    }
} else {
    echo "<div class='error'>✗ Export script not found</div>";
}

echo "</div>";

echo "<hr>";
echo "<h2>Summary</h2>";
echo "<div class='info'>";
echo "<p>This test has checked the basic components needed for SCORM export:</p>";
echo "<ul>";
echo "<li>Sample project accessibility</li>";
echo "<li>ZIP creation capabilities</li>";
echo "<li>SCORM manifest generation</li>";
echo "<li>File permissions</li>";
echo "<li>Export script availability</li>";
echo "</ul>";
echo "<p>If all tests pass, the issue might be in the browser-side JavaScript or the specific export request.</p>";
echo "</div>";

echo "<p><em>Test completed at " . date('Y-m-d H:i:s') . "</em></p>";
?>
