<?php
/**
 * SCORM Export Diagnostic Tool for Xerte
 * This script tests all components needed for SCORM export functionality
 */

echo "<h1>SCORM Export Diagnostic Tool</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .success { color: green; font-weight: bold; }
    .error { color: red; font-weight: bold; }
    .warning { color: orange; font-weight: bold; }
    .info { color: blue; }
    .section { margin: 20px 0; padding: 15px; border: 1px solid #ccc; border-radius: 5px; }
    .test-result { margin: 10px 0; padding: 5px; }
</style>";

// Test 1: PHP ZIP Extension
echo "<div class='section'>";
echo "<h2>1. PHP ZIP Extension Test</h2>";

if (extension_loaded('zip')) {
    echo "<div class='test-result success'>✓ PHP ZIP extension is loaded</div>";
    $zip_version = phpversion('zip');
    echo "<div class='test-result info'>ZIP version: " . ($zip_version ? $zip_version : 'Unknown') . "</div>";
} else {
    echo "<div class='test-result error'>✗ PHP ZIP extension is NOT loaded</div>";
    echo "<div class='test-result warning'>You need to enable the ZIP extension in php.ini</div>";
}

// Test 2: PHP Functions
echo "<h3>Required PHP Functions</h3>";
$required_functions = ['fopen', 'fwrite', 'fclose', 'file_exists', 'is_dir', 'mkdir', 'chmod', 'unlink', 'rmdir'];
foreach ($required_functions as $func) {
    if (function_exists($func)) {
        echo "<div class='test-result success'>✓ $func() is available</div>";
    } else {
        echo "<div class='test-result error'>✗ $func() is NOT available</div>";
    }
}

echo "</div>";

// Test 3: Directory Permissions
echo "<div class='section'>";
echo "<h2>2. Directory Permissions Test</h2>";

$test_dirs = [
    '../USER-FILES' => 'User files directory',
    '../USER-FILES/2-guest2-Nottingham' => 'Sample project directory',
    '../error_logs' => 'Error logs directory',
    '.' => 'Current directory (editor)'
];

foreach ($test_dirs as $dir => $description) {
    if (file_exists($dir)) {
        if (is_writable($dir)) {
            echo "<div class='test-result success'>✓ $description is writable</div>";
        } else {
            echo "<div class='test-result error'>✗ $description is NOT writable</div>";
            echo "<div class='test-result warning'>Path: $dir</div>";
        }
    } else {
        echo "<div class='test-result error'>✗ $description does not exist</div>";
        echo "<div class='test-result warning'>Path: $dir</div>";
    }
}

echo "</div>";

// Test 4: ZIP Creation Test
echo "<div class='section'>";
echo "<h2>3. ZIP Creation Test</h2>";

$test_zip_file = 'test_export.zip';
$test_content = 'This is a test file for ZIP creation.';

try {
    // Create a test file
    $test_file = 'test_file.txt';
    file_put_contents($test_file, $test_content);
    
    // Create ZIP
    $zip = new ZipArchive();
    $result = $zip->open($test_zip_file, ZipArchive::CREATE);
    
    if ($result === TRUE) {
        $zip->addFile($test_file, 'test_file.txt');
        $zip->close();
        
        if (file_exists($test_zip_file)) {
            echo "<div class='test-result success'>✓ ZIP file created successfully</div>";
            echo "<div class='test-result info'>File size: " . filesize($test_zip_file) . " bytes</div>";
            
            // Clean up
            unlink($test_zip_file);
            unlink($test_file);
        } else {
            echo "<div class='test-result error'>✗ ZIP file was not created</div>";
        }
    } else {
        echo "<div class='test-result error'>✗ Failed to create ZIP file (Error code: $result)</div>";
    }
} catch (Exception $e) {
    echo "<div class='test-result error'>✗ ZIP creation failed: " . $e->getMessage() . "</div>";
}

echo "</div>";

// Test 5: Xerte Export Files
echo "<div class='section'>";
echo "<h2>4. Xerte Export Files Test</h2>";

$export_files = [
    '../website_code/php/scorm/export.php' => 'SCORM Export Script',
    '../website_code/php/scorm/archive.php' => 'Archive Library',
    '../website_code/php/scorm/scorm_library.php' => 'SCORM Library',
    '../website_code/php/properties/export_template.php' => 'Export Template',
    '../modules/xerte/export.php' => 'Xerte Module Export',
    '../modules/xerte/export_page.php' => 'Xerte Export Page'
];

foreach ($export_files as $file => $description) {
    if (file_exists($file)) {
        echo "<div class='test-result success'>✓ $description exists</div>";
    } else {
        echo "<div class='test-result error'>✗ $description is missing</div>";
        echo "<div class='test-result warning'>Path: $file</div>";
    }
}

echo "</div>";

// Test 6: Sample Project Analysis
echo "<div class='section'>";
echo "<h2>5. Sample Project Analysis</h2>";

$sample_project = '../USER-FILES/2-guest2-Nottingham';
if (file_exists($sample_project)) {
    echo "<div class='test-result success'>✓ Sample project found</div>";
    
    $required_files = ['data.xml', 'preview.xml'];
    foreach ($required_files as $file) {
        $file_path = $sample_project . '/' . $file;
        if (file_exists($file_path)) {
            echo "<div class='test-result success'>✓ $file exists</div>";
        } else {
            echo "<div class='test-result error'>✗ $file is missing</div>";
        }
    }
    
    // Check media directory
    $media_dir = $sample_project . '/media';
    if (file_exists($media_dir)) {
        echo "<div class='test-result success'>✓ Media directory exists</div>";
        $media_files = scandir($media_dir);
        $media_count = count($media_files) - 2; // Subtract . and ..
        echo "<div class='test-result info'>Media files count: $media_count</div>";
    } else {
        echo "<div class='test-result error'>✗ Media directory is missing</div>";
    }
} else {
    echo "<div class='test-result error'>✗ Sample project not found</div>";
}

echo "</div>";

// Test 7: PHP Configuration
echo "<div class='section'>";
echo "<h2>6. PHP Configuration</h2>";

$php_settings = [
    'max_execution_time' => ini_get('max_execution_time'),
    'memory_limit' => ini_get('memory_limit'),
    'upload_max_filesize' => ini_get('upload_max_filesize'),
    'post_max_size' => ini_get('post_max_size'),
    'file_uploads' => ini_get('file_uploads') ? 'Enabled' : 'Disabled'
];

foreach ($php_settings as $setting => $value) {
    echo "<div class='test-result info'>$setting: $value</div>";
}

echo "</div>";

// Test 8: Browser Test Instructions
echo "<div class='section'>";
echo "<h2>7. Next Steps</h2>";
echo "<div class='test-result info'>";
echo "<h3>To test SCORM export:</h3>";
echo "<ol>";
echo "<li>Open Xerte in your browser</li>";
echo "<li>Go to a project (like the sample project in USER-FILES/2-guest2-Nottingham)</li>";
echo "<li>Click on Properties/Export</li>";
echo "<li>Try to export as SCORM 1.2</li>";
echo "<li>Check browser Developer Tools (F12) → Console tab for errors</li>";
echo "<li>Check browser Developer Tools (F12) → Network tab for failed requests</li>";
echo "</ol>";
echo "</div>";

echo "<div class='test-result info'>";
echo "<h3>Common Issues and Solutions:</h3>";
echo "<ul>";
echo "<li><strong>ZIP extension not loaded:</strong> Uncomment 'extension=zip' in php.ini and restart Apache</li>";
echo "<li><strong>Permission denied:</strong> Set proper permissions on USER-FILES directory</li>";
echo "<li><strong>File not found:</strong> Check that all Xerte files are properly installed</li>";
echo "<li><strong>Memory/timeout issues:</strong> Increase max_execution_time and memory_limit in php.ini</li>";
echo "</ul>";
echo "</div>";

echo "</div>";

echo "<div class='section'>";
echo "<h2>8. Manual ZIP Test</h2>";
echo "<div class='test-result info'>";
echo "<p>If you want to test ZIP creation manually, you can use this form:</p>";
echo "<form method='post'>";
echo "<input type='submit' name='test_zip' value='Create Test ZIP File' style='padding: 10px; background: #007cba; color: white; border: none; border-radius: 3px; cursor: pointer;'>";
echo "</form>";
echo "</div>";
echo "</div>";

// Handle manual ZIP test
if (isset($_POST['test_zip'])) {
    echo "<div class='section'>";
    echo "<h2>Manual ZIP Test Results</h2>";
    
    $test_files = [
        'test1.txt' => 'This is test file 1',
        'test2.txt' => 'This is test file 2',
        'subdir/test3.txt' => 'This is test file 3 in subdirectory'
    ];
    
    $zip_file = 'manual_test.zip';
    
    try {
        $zip = new ZipArchive();
        $result = $zip->open($zip_file, ZipArchive::CREATE);
        
        if ($result === TRUE) {
            foreach ($test_files as $filename => $content) {
                // Create subdirectory if needed
                $dir = dirname($filename);
                if ($dir != '.' && !file_exists($dir)) {
                    mkdir($dir, 0777, true);
                }
                
                file_put_contents($filename, $content);
                $zip->addFile($filename, $filename);
            }
            
            $zip->close();
            
            if (file_exists($zip_file)) {
                echo "<div class='test-result success'>✓ Manual ZIP test successful!</div>";
                echo "<div class='test-result info'>ZIP file created: $zip_file (" . filesize($zip_file) . " bytes)</div>";
                echo "<div class='test-result info'><a href='$zip_file' download>Download test ZIP file</a></div>";
                
                // Clean up test files
                foreach ($test_files as $filename => $content) {
                    if (file_exists($filename)) unlink($filename);
                }
                if (file_exists('subdir')) rmdir('subdir');
            } else {
                echo "<div class='test-result error'>✗ ZIP file was not created</div>";
            }
        } else {
            echo "<div class='test-result error'>✗ Failed to create ZIP (Error code: $result)</div>";
        }
    } catch (Exception $e) {
        echo "<div class='test-result error'>✗ Manual ZIP test failed: " . $e->getMessage() . "</div>";
    }
    
    echo "</div>";
}

echo "<hr>";
echo "<p><em>Diagnostic completed at " . date('Y-m-d H:i:s') . "</em></p>";
?>
