#!/bin/bash

echo "================================================"
echo "Website Builder - Starting Services"
echo "================================================"

# Check if Python is installed
if ! command -v python3 &> /dev/null; then
    echo "Error: Python 3 is not installed"
    echo "Please install Python 3.7+ and try again"
    exit 1
fi

# Check if PHP is installed
if ! command -v php &> /dev/null; then
    echo "Error: PHP is not installed"
    echo "Please install PHP 7.4+ and try again"
    exit 1
fi

# Install Python dependencies
echo "Installing Python dependencies..."
pip3 install -r requirements.txt

# Make start.py executable
chmod +x start.py

# Start the application
echo "Starting Website Builder..."
python3 start.py
