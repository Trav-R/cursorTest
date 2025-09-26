#!/usr/bin/env python3
"""
Website Builder Startup Script
Starts both Python backend and PHP frontend servers
"""

import subprocess
import sys
import time
import webbrowser
import os
from threading import Thread

def start_python_backend():
    """Start the Python Flask backend"""
    print("🚀 Starting Python backend on http://localhost:5000")
    subprocess.run([sys.executable, "app.py"])

def start_php_frontend():
    """Start the PHP frontend server"""
    print("🌐 Starting PHP frontend on http://localhost:8000")
    subprocess.run(["php", "-S", "localhost:8000"])

def main():
    print("=" * 50)
    print("🏗️  Website Builder - Starting Services")
    print("=" * 50)
    
    # Check if Python dependencies are installed
    try:
        import flask
        import flask_cors
        print("✅ Python dependencies found")
    except ImportError:
        print("❌ Python dependencies not found. Installing...")
        subprocess.run([sys.executable, "-m", "pip", "install", "-r", "requirements.txt"])
        print("✅ Python dependencies installed")
    
    # Check if PHP is available
    try:
        result = subprocess.run(["php", "--version"], capture_output=True, text=True)
        if result.returncode == 0:
            print("✅ PHP found")
        else:
            print("❌ PHP not found. Please install PHP to run the frontend.")
            return
    except FileNotFoundError:
        print("❌ PHP not found. Please install PHP to run the frontend.")
        return
    
    print("\n🎯 Starting services...")
    print("📝 Backend API: http://localhost:5000")
    print("🌐 Frontend: http://localhost:8000")
    print("\n💡 Press Ctrl+C to stop all services")
    print("=" * 50)
    
    # Start backend in a separate thread
    backend_thread = Thread(target=start_python_backend, daemon=True)
    backend_thread.start()
    
    # Wait a moment for backend to start
    time.sleep(2)
    
    # Start frontend (this will block)
    try:
        start_php_frontend()
    except KeyboardInterrupt:
        print("\n🛑 Shutting down services...")
        sys.exit(0)

if __name__ == "__main__":
    main()
