#!/usr/bin/env python3
"""
Website Builder - Python Backend API
A Flask-based API for building websites with drag-and-drop functionality
"""

from flask import Flask, request, jsonify, render_template_string
from flask_cors import CORS
import json
import os
import uuid
from datetime import datetime
from config import TEMPLATES, COMPONENTS, BACKEND_HOST, BACKEND_PORT

app = Flask(__name__)
CORS(app)

# In-memory storage for demo purposes (use database in production)
projects = {}
templates = TEMPLATES

@app.route('/')
def index():
    """Serve the main PHP interface"""
    return render_template_string(open('index.php').read())

@app.route('/api/templates', methods=['GET'])
def get_templates():
    """Get available templates"""
    return jsonify(templates)

@app.route('/api/projects', methods=['GET'])
def get_projects():
    """Get all projects"""
    return jsonify(list(projects.values()))

@app.route('/api/projects', methods=['POST'])
def create_project():
    """Create a new project"""
    data = request.json
    project_id = str(uuid.uuid4())
    project = {
        'id': project_id,
        'name': data.get('name', 'Untitled Project'),
        'template': data.get('template', 'business'),
        'components': templates[data.get('template', 'business')]['components'].copy(),
        'created_at': datetime.now().isoformat(),
        'updated_at': datetime.now().isoformat()
    }
    projects[project_id] = project
    return jsonify(project)

@app.route('/api/projects/<project_id>', methods=['GET'])
def get_project(project_id):
    """Get a specific project"""
    if project_id not in projects:
        return jsonify({'error': 'Project not found'}), 404
    return jsonify(projects[project_id])

@app.route('/api/projects/<project_id>', methods=['PUT'])
def update_project(project_id):
    """Update a project"""
    if project_id not in projects:
        return jsonify({'error': 'Project not found'}), 404
    
    data = request.json
    projects[project_id].update({
        'name': data.get('name', projects[project_id]['name']),
        'components': data.get('components', projects[project_id]['components']),
        'updated_at': datetime.now().isoformat()
    })
    return jsonify(projects[project_id])

@app.route('/api/projects/<project_id>/export', methods=['POST'])
def export_project(project_id):
    """Export project as HTML"""
    if project_id not in projects:
        return jsonify({'error': 'Project not found'}), 404
    
    project = projects[project_id]
    html_content = generate_html(project)
    
    # Save to file
    filename = f"export_{project_id}.html"
    with open(filename, 'w', encoding='utf-8') as f:
        f.write(html_content)
    
    return jsonify({
        'filename': filename,
        'content': html_content,
        'message': 'Project exported successfully'
    })

@app.route('/api/components', methods=['GET'])
def get_components():
    """Get available components"""
    return jsonify(COMPONENTS)

def generate_html(project):
    """Generate HTML from project components"""
    html = f"""<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{project['name']}</title>
    <style>
        body {{
            margin: 0;
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }}
        .component {{
            margin: 10px 0;
        }}
        .header {{
            background-color: #333;
            color: white;
            padding: 20px;
            text-align: center;
        }}
        .hero {{
            background-color: #f4f4f4;
            padding: 40px;
            text-align: center;
        }}
        .section {{
            padding: 40px;
        }}
        .sidebar {{
            background-color: #f1f2f6;
            padding: 20px;
            float: left;
            width: 200px;
        }}
        .footer {{
            background-color: #333;
            color: white;
            padding: 20px;
            text-align: center;
            clear: both;
        }}
        .text {{
            padding: 20px;
        }}
        .image {{
            text-align: center;
            padding: 20px;
        }}
        .button {{
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px;
        }}
    </style>
</head>
<body>
"""
    
    for component in project['components']:
        style_attr = ' '.join([f"{k}: {v};" for k, v in component.get('style', {}).items()])
        html += f'    <div class="component {component["type"]}" style="{style_attr}">\n'
        html += f'        {component["content"]}\n'
        html += '    </div>\n'
    
    html += """</body>
</html>"""
    
    return html

if __name__ == '__main__':
    app.run(debug=True, host=BACKEND_HOST, port=BACKEND_PORT)
