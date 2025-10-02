@echo off
echo Starting TradeTide Local Server...
echo.
echo Make sure PHP is installed and in your PATH
echo.
cd /d "%~dp0"
echo Current directory: %CD%
echo.
echo Starting PHP server on localhost:8000
echo.
echo Open your browser and go to:
echo http://localhost:8000/Website pages/pages/index.php
echo.
echo Press Ctrl+C to stop the server
echo.
php -S localhost:8000
pause

