<?php
echo "Testing session functionality:\n";
session_start();
echo "Session ID: " . session_id() . "\n";
echo "Session save path: " . session_save_path() . "\n";
echo "Session status: " . session_status() . "\n";

// Test creating a session variable
$_SESSION['test'] = 'Hello World';
echo "Session variable set: " . $_SESSION['test'] . "\n";

// Test session files directory
$sessionPath = session_save_path();
if (empty($sessionPath)) {
    $sessionPath = sys_get_temp_dir();
}
echo "Session files directory: " . $sessionPath . "\n";

// Check if directory is writable
if (is_writable($sessionPath)) {
    echo "Session directory is writable ✓\n";
} else {
    echo "Session directory is NOT writable ✗\n";
}

echo "Test completed.\n";
