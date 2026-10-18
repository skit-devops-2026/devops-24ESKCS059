<?php
header('Content-Type: text/plain; version=0.0.4; charset=utf-8');
header('Cache-Control: no-store');

echo "# HELP bloodbank_app_up Whether the Blood Bank metrics endpoint is responding.\n";
echo "# TYPE bloodbank_app_up gauge\n";
echo "bloodbank_app_up 1\n";
