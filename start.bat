@echo off
echo ================================================
echo Website Builder - Starting Services
echo ================================================

REM Check if Python is installed
python --version >nul 2>&1
if errorlevel 1 (
    echo Error: Python is not installed or not in PATH
    echo Please install Python 3.7+ and try again
    pause
    exit /b 1
)

REM Check if PHP is installed
php --version >nul 2>&1
if errorlevel 1 (
    echo Error: PHP is not installed or not in PATH
    echo Please install PHP 7.4+ and try again
    pause
    exit /b 1
)

REM Install Python dependencies
echo Installing Python dependencies...
pip install -r requirements.txt

REM Start the application
echo Starting Website Builder...
python start.py

pause
